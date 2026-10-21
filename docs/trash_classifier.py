#!/usr/bin/env python3
"""
SyncBin Gemini Vision AI — trash_classifier.py
=============================================
Runs Google Gemini Multimodal Vision on Raspberry Pi 4/5.
- Real-time smooth camera feed with HUD overlays (CSI or USB).
- Non-blocking async background thread for Gemini API inference.
- Color-coded bin classification readout and 1-sentence explanation.
- Auto-scan with debounce + manual [SPACEBAR] trigger.
- Live dashboard sync to https://ecosync.me.
"""

import os
import sys
import time
import json
import queue
import threading
import cv2
import requests

# Ensure system Picamera2 can be loaded inside virtual environment (venv)
for p in ["/usr/lib/python3/dist-packages", "/usr/local/lib/python3/dist-packages"]:
    if os.path.exists(p) and p not in sys.path:
        sys.path.append(p)

from google import genai
from google.genai import types

# ── 1. CONFIGURATION ──────────────────────────────────────────────────────────
GEMINI_API_KEY = os.environ.get("GEMINI_API_KEY", "AIzaSyC-CsPBpthyvuLKGt7gKkmU06QUuGB2c74")
GEMINI_MODEL   = os.environ.get("GEMINI_MODEL", "gemini-2.5-flash")

# Live SyncBin Dashboard endpoint
DASHBOARD_URL  = os.environ.get("DASHBOARD_URL", "https://ecosync.me/api/bins/camera-scan")

# Auto-scan cooldown in seconds
AUTO_SCAN_INTERVAL = float(os.environ.get("AUTO_SCAN_INTERVAL", 4.0))

# Path for conflict-free web snapshot server
SHARED_FRAME_PATH = "/tmp/syncbin_latest.jpg"

# Stream Colors (BGR for OpenCV)
BIN_COLORS = {
    "recyclable":    (235, 165, 14),   # Sky Blue / Cyan
    "biodegradable": (46, 185, 16),    # Emerald Green
    "non-bio":       (11, 158, 245),   # Amber / Orange
    "hazardous":     (94, 63, 244),    # Rose / Red
    "unknown":       (160, 160, 160),  # Grey
}

BIN_TITLES = {
    "recyclable":    "RECYCLABLE BIN",
    "biodegradable": "BIODEGRADABLE BIN",
    "non-bio":       "NON-BIODEGRADABLE BIN",
    "hazardous":     "HAZARDOUS BIN",
}


# ── 2. CAMERA INITIALIZATION (CSI OR USB) ────────────────────────────────────
def init_camera():
    """Initializes Raspberry Pi Camera Module 3 (CSI via Picamera2) or fallback USB Webcam."""
    # 1. Try Official Pi Camera Module via Picamera2
    try:
        try:
            from picamera2 import Picamera2
        except ImportError:
            from picam2 import Picamera2

        print("[Camera] Initializing Raspberry Pi Camera Module 3 (Picamera2)...")
        cam = Picamera2()
        config = cam.create_preview_configuration(main={"size": (640, 480), "format": "BGR888"})
        cam.configure(config)
        cam.start()
        print("[Camera] Pi Camera Module 3 ready!")
        return ("csi", cam)
    except Exception as e:
        print(f"[Camera] Picamera2 notice: {e}")
        print("[Camera] Checking for USB webcam (/dev/video0)...")

    # 2. Try USB Webcam with validation to prevent V4L2 timeout hangs
    try:
        cap = cv2.VideoCapture(0, cv2.CAP_V4L2)
        if cap.isOpened():
            cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
            cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)
            # Quick check if camera actually returns frames
            ret, test_frame = cap.read()
            if ret and test_frame is not None:
                print("[Camera] USB Webcam ready!")
                return ("usb", cap)
            cap.release()
    except Exception:
        pass

    print("\n[Error] Could not initialize any camera feed!")
    print("  Troubleshooting steps:")
    print("  1. Stop background camera services that may hold the device:")
    print("     sudo systemctl stop syncbin-detector")
    print("     pkill -f yolo_detector")
    print("  2. If using Camera Module 3 ribbon, verify with:")
    print("     rpicam-hello -t 2000")
    sys.exit(1)


def read_frame(cam_type, cam_obj):
    if cam_type == "csi":
        return True, cam_obj.capture_array()
    return cam_obj.read()


# ── 3. ASYNC CLASSIFICATION WORKER ───────────────────────────────────────────
class GeminiClassifier:
    """Handles background API calls to Gemini so the video preview never freezes."""

    def __init__(self, api_key: str, model_name: str):
        self.client = genai.Client(api_key=api_key)
        self.model_name = model_name
        self.request_queue = queue.Queue(maxsize=1)
        self.is_busy = False
        self.last_result = None
        self.last_scan_time = 0

        self.worker_thread = threading.Thread(target=self._worker_loop, daemon=True)
        self.worker_thread.start()

    def submit_request(self, frame_bgr, trigger_mode="auto"):
        if self.is_busy:
            return False

        try:
            if not self.request_queue.empty():
                try:
                    self.request_queue.get_nowait()
                except queue.Empty:
                    pass

            self.request_queue.put_nowait((frame_bgr.copy(), trigger_mode))
            self.is_busy = True
            return True
        except queue.Full:
            return False

    def _worker_loop(self):
        while True:
            item = self.request_queue.get()
            if item is None:
                break

            frame_bgr, trigger_mode = item
            start_t = time.time()

            try:
                rgb = cv2.cvtColor(frame_bgr, cv2.COLOR_BGR2RGB)
                success, encoded = cv2.imencode(".jpg", rgb, [int(cv2.IMWRITE_JPEG_QUALITY), 85])
                if not success:
                    self.is_busy = False
                    continue

                img_bytes = encoded.tobytes()

                prompt = (
                    "You are the real-time AI Vision Engine for the SyncBin Smart Waste System.\n"
                    "Analyze this camera frame and identify the primary trash / waste item in view.\n"
                    "Classify it strictly into ONE of these 4 bin streams:\n"
                    "  1. 'recyclable' (plastic bottles, clean paper, soda cans, cardboard, glass)\n"
                    "  2. 'biodegradable' (fruit peels, food scraps, leaves, vegetables, coffee grounds)\n"
                    "  3. 'non-bio' (chip bags, plastic wrappers, styrofoam, dirty plastics, masks)\n"
                    "  4. 'hazardous' (batteries, medicine, bulbs, electronics, chemicals)\n\n"
                    "If there is NO trash item in view (e.g. empty background, room ceiling, only a hand),\n"
                    "set 'is_trash': false, 'item_name': 'No Waste Detected', 'bin_slug': 'unknown'.\n\n"
                    "Output ONLY a raw valid JSON object with these exact keys:\n"
                    "{\n"
                    '  "is_trash": true,\n'
                    '  "item_name": "Plastic Bottle",\n'
                    '  "bin_slug": "recyclable",\n'
                    '  "confidence": 92,\n'
                    '  "explanation": "Clear PET plastic bottle suitable for container recycling."\n'
                    "}"
                )

                response = self.client.models.generate_content(
                    model=self.model_name,
                    contents=[
                        prompt,
                        types.Part.from_bytes(
                            data=img_bytes,
                            mime_type="image/jpeg",
                        )
                    ]
                )

                raw_text = (response.text or "").strip()
                if raw_text.startswith("```"):
                    raw_text = raw_text.split("\n", 1)[-1].rsplit("```", 1)[0].strip()

                parsed = json.loads(raw_text)
                bin_slug = parsed.get("bin_slug", "recyclable").lower().strip()
                if bin_slug not in BIN_TITLES:
                    bin_slug = "recyclable"

                result_data = {
                    "is_trash": parsed.get("is_trash", True),
                    "item_name": parsed.get("item_name", "Unknown Item"),
                    "bin_slug": bin_slug,
                    "confidence": int(parsed.get("confidence", 85)),
                    "explanation": parsed.get("explanation", ""),
                    "elapsed_sec": round(time.time() - start_t, 2),
                    "trigger_mode": trigger_mode,
                }

                self.last_result = result_data
                self.last_scan_time = time.time()

                print(f"\n[GEMINI] {result_data['item_name']} ({result_data['confidence']}%) -> [{bin_slug.upper()}] in {result_data['elapsed_sec']}s")
                print(f"         Reason: {result_data['explanation']}")

                if result_data["is_trash"] and bin_slug != "unknown":
                    self._sync_to_dashboard(frame_bgr, result_data)

            except Exception as err:
                print(f"[Gemini Error]: {err}")
                self.last_result = {
                    "is_trash": False,
                    "item_name": "API Connection Error",
                    "bin_slug": "unknown",
                    "confidence": 0,
                    "explanation": str(err)[:60],
                    "elapsed_sec": round(time.time() - start_t, 2),
                    "trigger_mode": trigger_mode,
                }
            finally:
                self.is_busy = False
                self.request_queue.task_done()

    def _sync_to_dashboard(self, frame_bgr, result):
        def upload_task():
            try:
                _, img_encoded = cv2.imencode(".jpg", frame_bgr, [int(cv2.IMWRITE_JPEG_QUALITY), 85])
                payload = {
                    "item_name": result["item_name"],
                    "bin_slug": result["bin_slug"],
                    "confidence": result["confidence"],
                    "bounding_box": json.dumps({"x": 20, "y": 20, "width": 60, "height": 60}),
                }
                files = {"image": ("gemini_scan.jpg", img_encoded.tobytes(), "image/jpeg")}
                resp = requests.post(DASHBOARD_URL, data=payload, files=files, timeout=6)
                if resp.status_code == 200:
                    print(f"  [CLOUD SYNC] Successfully updated live bins on {DASHBOARD_URL}")
                else:
                    print(f"  [CLOUD SYNC] Server response ({resp.status_code}): {resp.text[:80]}")
            except Exception as e:
                print(f"  [CLOUD SYNC] Network Error: {e}")

        threading.Thread(target=upload_task, daemon=True).start()


# ── 4. HUD OVERLAY RENDERING ─────────────────────────────────────────────────
def draw_hud(frame, classifier):
    h, w, _ = frame.shape
    overlay = frame.copy()

    # Top Header Banner
    cv2.rectangle(overlay, (0, 0), (w, 42), (15, 23, 20), -1)
    cv2.putText(overlay, "SYNCBIN GEMINI VISION AI", (14, 26),
                cv2.FONT_HERSHEY_DUPLEX, 0.65, (52, 211, 153), 1, cv2.LINE_AA)

    # Status readout
    if classifier.is_busy:
        status_text, status_color = "ANALYZING...", (0, 215, 255)
    else:
        status_text, status_color = "READY [SPACE=SCAN]", (52, 211, 153)

    cv2.putText(overlay, status_text, (w - 230, 26),
                cv2.FONT_HERSHEY_SIMPLEX, 0.55, status_color, 2, cv2.LINE_AA)

    # Central Target Reticle
    box_w, box_h = int(w * 0.55), int(h * 0.55)
    x1, y1 = (w - box_w) // 2, (h - box_h) // 2 + 15
    x2, y2 = x1 + box_w, y1 + box_h
    reticle_color = (0, 215, 255) if classifier.is_busy else (180, 180, 180)
    corner_len, t = 25, 2
    cv2.line(overlay, (x1, y1), (x1 + corner_len, y1), reticle_color, t)
    cv2.line(overlay, (x1, y1), (x1, y1 + corner_len), reticle_color, t)
    cv2.line(overlay, (x2, y1), (x2 - corner_len, y1), reticle_color, t)
    cv2.line(overlay, (x2, y1), (x2, y1 + corner_len), reticle_color, t)
    cv2.line(overlay, (x1, y2), (x1 + corner_len, y2), reticle_color, t)
    cv2.line(overlay, (x1, y2), (x1, y2 - corner_len), reticle_color, t)
    cv2.line(overlay, (x2, y2), (x2 - corner_len, y2), reticle_color, t)
    cv2.line(overlay, (x2, y2), (x2, y2 - corner_len), reticle_color, t)

    # Bottom Classification Readout
    res = classifier.last_result
    if res and res.get("is_trash"):
        slug = res.get("bin_slug", "recyclable")
        color = BIN_COLORS.get(slug, (160, 160, 160))
        bin_label = BIN_TITLES.get(slug, slug.upper())

        cv2.rectangle(overlay, (10, h - 90), (w - 10, h - 10), (10, 15, 12), -1)
        cv2.rectangle(overlay, (10, h - 90), (w - 10, h - 10), color, 2)
        cv2.rectangle(overlay, (10, h - 90), (20, h - 10), color, -1)

        title_str = f"{res['item_name']} ({res['confidence']}%) --> {bin_label}"
        cv2.putText(overlay, title_str, (30, h - 62),
                    cv2.FONT_HERSHEY_DUPLEX, 0.60, (255, 255, 255), 1, cv2.LINE_AA)

        expl_str = res.get("explanation", "")
        if len(expl_str) > 65:
            expl_str = expl_str[:62] + "..."
        cv2.putText(overlay, expl_str, (30, h - 38),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.42, (180, 200, 190), 1, cv2.LINE_AA)

        time_badge = f"{res.get('elapsed_sec', 0)}s"
        cv2.putText(overlay, time_badge, (w - 75, h - 62),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.45, (140, 140, 140), 1, cv2.LINE_AA)

    elif res and not res.get("is_trash"):
        cv2.rectangle(overlay, (10, h - 55), (w - 10, h - 10), (10, 15, 12), -1)
        cv2.putText(overlay, "No item recognized. Place trash in the center reticle.", (30, h - 28),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.50, (160, 160, 160), 1, cv2.LINE_AA)

    cv2.addWeighted(overlay, 0.88, frame, 0.12, 0, frame)
    return frame


# ── 5. MAIN APPLICATION LOOP ─────────────────────────────────────────────────
def main():
    print("=" * 65)
    print("       SyncBin GEMINI AI Vision — Live Trash Classifier")
    print("=" * 65)
    print(f"Target Server   : {DASHBOARD_URL}")
    print(f"Gemini Model    : {GEMINI_MODEL}")
    print(f"Auto-Scan Delay : {AUTO_SCAN_INTERVAL}s (or tap SPACEBAR anytime)")
    print("Controls        : [SPACE] = Instant Scan | [Q] or [ESC] = Quit")
    print("-" * 65)

    cam_type, cam_obj = init_camera()
    classifier = GeminiClassifier(api_key=GEMINI_API_KEY, model_name=GEMINI_MODEL)

    last_auto_scan = time.time()
    last_snapshot_write = 0

    try:
        while True:
            ret, frame = read_frame(cam_type, cam_obj)
            if not ret or frame is None:
                time.sleep(0.02)
                continue

            now = time.time()

            # Share frame for web snapshot server
            if now - last_snapshot_write >= 1.0:
                try:
                    cv2.imwrite(SHARED_FRAME_PATH, frame, [int(cv2.IMWRITE_JPEG_QUALITY), 80])
                    last_snapshot_write = now
                except Exception:
                    pass

            # Auto-Scan Trigger
            if (now - last_auto_scan >= AUTO_SCAN_INTERVAL) and not classifier.is_busy:
                classifier.submit_request(frame, trigger_mode="auto")
                last_auto_scan = now

            # Draw HUD & show live preview
            display = frame.copy()
            draw_hud(display, classifier)
            cv2.imshow("SyncBin Gemini Vision — Live Trash Classifier", display)

            key = cv2.waitKey(1) & 0xFF
            if key == ord(' '):
                print("\n[SPACEBAR] Instant scan triggered by user!")
                if not classifier.is_busy:
                    classifier.submit_request(frame, trigger_mode="manual")
                    last_auto_scan = now
            elif key in (ord('q'), ord('Q'), 27):
                print("\nExiting program...")
                break

    except KeyboardInterrupt:
        print("\nStopping classifier (Ctrl+C)...")
    finally:
        if cam_type == "usb":
            cam_obj.release()
        elif cam_type == "csi":
            cam_obj.stop()
        cv2.destroyAllWindows()
        print("Camera cleanly released. Goodbye!")


if __name__ == "__main__":
    main()
