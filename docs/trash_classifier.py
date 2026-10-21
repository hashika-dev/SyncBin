#!/usr/bin/env python3
"""
SyncBin Gemini Vision AI — trash_classifier.py
=============================================
Runs Google Gemini Multimodal Vision on Raspberry Pi 4/5.
- Real-time smooth camera feed with HUD overlays (CSI or USB).
- Motorized Continuous Autofocus enabled for Camera Module 3.
- On-Demand scanning with [SPACEBAR] to avoid free-tier quota exhaustion (429/503).
- Optional Auto-Scan toggle ([A] key) with safe rate-limiting.
- Smart model fallback (gemini-2.0-flash -> gemini-1.5-flash -> gemini-3.8-flash).
- Color-coded bin classification readout and 1-sentence explanation.
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
def load_api_key():
    """Loads Gemini API key from environment or local .env file."""
    key = os.environ.get("GEMINI_API_KEY", "").strip()
    if key:
        return key

    script_dir = os.path.dirname(os.path.abspath(__file__))
    search_paths = [
        os.path.join(script_dir, ".env"),
        ".env",
        os.path.expanduser("~/.gemini_key")
    ]
    for env_path in search_paths:
        if os.path.exists(env_path):
            try:
                with open(env_path, "r") as f:
                    for line in f:
                        line = line.strip()
                        if line.startswith("GEMINI_API_KEY="):
                            return line.split("=", 1)[1].strip().strip('"').strip("'")
            except Exception:
                pass
    return ""

GEMINI_API_KEY = load_api_key()

# Default vision model (can be overridden via GEMINI_MODEL in .env)
# 'gemini-flash-lite-latest' is ultra-fast (~1.9s) and has fresh daily quota on Google's free tier
DEFAULT_MODEL = os.environ.get("GEMINI_MODEL", "gemini-flash-lite-latest")

# Live SyncBin Dashboard endpoint
DASHBOARD_URL  = os.environ.get("DASHBOARD_URL", "https://ecosync.me/api/bins/camera-scan")

# Auto-scan cooldown in seconds (minimum 13s to stay within 5 requests/min free tier)
AUTO_SCAN_INTERVAL = float(os.environ.get("AUTO_SCAN_INTERVAL", 14.0))

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
    "unknown":       "NO WASTE DETECTED",
}


# ── 2. CAMERA INITIALIZATION (CSI OR USB) ────────────────────────────────────
def init_camera():
    """Initializes Raspberry Pi Camera Module 3 (CSI via Picamera2) or fallback USB Webcam."""
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

        # Enable Continuous Hardware Autofocus for Camera Module 3
        try:
            from libcamera import controls
            cam.set_controls({
                "AfMode": controls.AfModeEnum.Continuous,
                "AfRange": controls.AfRangeEnum.Full,
                "AfSpeed": controls.AfSpeedEnum.Fast
            })
            print("[Camera] Continuous Hardware Autofocus enabled on Camera Module 3!")
        except Exception:
            try:
                cam.set_controls({"AfMode": 2})
                print("[Camera] Continuous Autofocus active (code 2)!")
            except Exception as afe:
                print(f"[Camera] Autofocus notice: {afe}")

        print("[Camera] Pi Camera Module 3 ready!")
        return ("csi", cam)
    except Exception as e:
        print(f"[Camera] Picamera2 notice: {e}")
        print("[Camera] Checking for USB webcam (/dev/video0)...")

    try:
        cap = cv2.VideoCapture(0, cv2.CAP_V4L2)
        if cap.isOpened():
            cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
            cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)
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
    """Handles background API calls to Gemini with automatic model fallback."""

    def __init__(self, api_key: str):
        if not api_key:
            print("\n[WARNING] No GEMINI_API_KEY detected in .env!")
            print("Please set your key in /home/raspberrypi4/pi-offline-ai/GEMINI-AI/.env\n")

        self.client = genai.Client(api_key=api_key) if api_key else None
        self.request_queue = queue.Queue(maxsize=1)
        self.is_busy = False
        self.last_result = None
        self.last_scan_time = 0
        self.cooldown_until = 0

        # Discover available models for this specific API key
        self.models_to_try = [DEFAULT_MODEL]
        self.active_model = DEFAULT_MODEL
        if self.client:
            self._discover_models()

        self.worker_thread = threading.Thread(target=self._worker_loop, daemon=True)
        self.worker_thread.start()

    def _discover_models(self):
        """Discovers valid active multimodal Flash models available on your Google account."""
        found = []
        try:
            for m in self.client.models.list():
                clean = getattr(m, "name", "").replace("models/", "").strip()
                # Must be a flash model
                if "flash" not in clean.lower():
                    continue
                # Exclude audio, tts, live, and embed models
                if any(k in clean.lower() for k in ["tts", "audio", "embed", "aqa", "imagen", "live"]):
                    continue
                # Exclude image generation models (not image input comprehension)
                if clean.endswith("-image") or clean.endswith("-image-preview"):
                    continue
                # Exclude retired models known to return 404
                if clean == "gemini-2.5-flash":
                    continue
                found.append(clean)
        except Exception as e:
            print(f"[Model Discovery Notice]: {e}")

        # Stable priority order:
        # Prioritize flash-lite models which have proven fastest (~1.9s) and have fresh quota
        PREFERRED_ORDER = [
            os.environ.get("GEMINI_MODEL", "").strip(),
            "gemini-flash-lite-latest",
            "gemini-3.1-flash-lite",
            "gemini-2.5-flash-lite",
            "gemini-3.5-flash-lite",
            "gemini-3.5-flash",
            "gemini-3.6-flash",
            "gemini-3.7-flash",
            "gemini-flash-latest"
        ]

        ordered_models = []
        for p in PREFERRED_ORDER:
            if p and p in found and p not in ordered_models:
                ordered_models.append(p)

        # Append any remaining discovered multimodal flash models
        for f in found:
            if f not in ordered_models:
                ordered_models.append(f)

        if not ordered_models:
            ordered_models = ["gemini-3.7-flash", "gemini-3.5-flash", "gemini-flash-latest"]

        self.models_to_try = ordered_models
        self.active_model = self.models_to_try[0]
        print(f"[Gemini] Vision Models Priority: {' -> '.join(self.models_to_try[:4])}")

    def submit_request(self, frame_bgr, trigger_mode="manual"):
        now = time.time()
        if now < self.cooldown_until:
            wait_s = int(self.cooldown_until - now)
            print(f"[Rate Limit] Rate limit active. Ready in {wait_s}s...")
            return False

        if self.is_busy:
            return False

        if not self.client:
            print("[Gemini] Cannot scan: No API key configured. Check .env")
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
                    "Analyze this camera frame and identify the primary object held in the center reticle.\n"
                    "Assume any object held towards the camera is being presented for disposal / sorting.\n"
                    "Classify it strictly into ONE of these 4 bin streams:\n"
                    "  1. 'recyclable' (plastic bottles, clean paper, soda cans, cardboard, glass)\n"
                    "  2. 'biodegradable' (fruit peels, food scraps, leaves, vegetables, organic food)\n"
                    "  3. 'non-bio' (ceramics, mugs, eyeglasses, chip bags, wrappers, styrofoam, masks)\n"
                    "  4. 'hazardous' (batteries, medicine, bulbs, electronics, phones, chemicals)\n\n"
                    "Rules:\n"
                    "- If an object is being held in view (even a reusable item like a mug or eyeglasses), set 'is_trash': true and classify it (e.g. ceramics/mugs go to 'non-bio').\n"
                    "- ONLY set 'is_trash': false if there is NO object presented at all (e.g. empty background, room ceiling, or a bare face/hand with nothing in it).\n\n"
                    "Output ONLY a raw valid JSON object with these exact keys:\n"
                    "{\n"
                    '  "is_trash": true,\n'
                    '  "item_name": "Ceramic Mug",\n'
                    '  "bin_slug": "non-bio",\n'
                    '  "confidence": 95,\n'
                    '  "explanation": "Ceramic material cannot be recycled in standard facilities and belongs in non-biodegradable waste."\n'
                    "}"
                )

                # Try primary model with automatic backoff retry on temporary 503 spikes
                response = None
                last_err = None

                for model_candidate in self.models_to_try:
                    for attempt in range(1, 4):
                        try:
                            print(f"[Gemini] Requesting analysis via {model_candidate} (attempt {attempt}/3)...")
                            response = self.client.models.generate_content(
                                model=model_candidate,
                                contents=[
                                    prompt,
                                    types.Part.from_bytes(
                                        data=img_bytes,
                                        mime_type="image/jpeg",
                                    )
                                ]
                            )
                            self.active_model = model_candidate
                            # Keep the working model at index 0 to avoid trying exhausted models on next scan
                            if model_candidate in self.models_to_try and model_candidate != self.models_to_try[0]:
                                self.models_to_try.remove(model_candidate)
                                self.models_to_try.insert(0, model_candidate)
                            break
                        except Exception as err:
                            err_str = str(err)
                            last_err = err_str

                            # If 503 High Demand spike, wait and retry this model up to 3 times
                            if ("503" in err_str or "UNAVAILABLE" in err_str) and attempt < 3:
                                wait_sec = attempt * 1.5
                                print(f"  [Demand Spike 503] {model_candidate} busy. Retrying in {wait_sec}s (attempt {attempt+1}/3)...")
                                time.sleep(wait_sec)
                                continue

                            # For 429 (per-model quota limit), 400 (unsupported modality), 404, or 503:
                            # If there are other model candidates available, cascade to the next model
                            if model_candidate != self.models_to_try[-1]:
                                if "429" in err_str or "RESOURCE_EXHAUSTED" in err_str:
                                    print(f"  [Quota 429] {model_candidate} daily quota reached. Pruning from active models...")
                                    if model_candidate in self.models_to_try:
                                        self.models_to_try.remove(model_candidate)
                                else:
                                    print(f"  [Model Fallback] {model_candidate} unavailable ({err_str[:60]}...). Cascading to next candidate...")
                                break

                            raise err

                    if response:
                        break

                if not response:
                    raise Exception(last_err or "No response from Gemini.")

                raw_text = (response.text or "").strip()
                if "```json" in raw_text:
                    raw_text = raw_text.split("```json", 1)[1].split("```", 1)[0].strip()
                elif "```" in raw_text:
                    raw_text = raw_text.split("```", 1)[1].split("```", 1)[0].strip()
                elif "{" in raw_text and "}" in raw_text:
                    raw_text = raw_text[raw_text.find("{"):raw_text.rfind("}")+1]

                parsed = json.loads(raw_text)
                is_trash = parsed.get("is_trash", True)
                item_name = parsed.get("item_name", "Unknown Item")
                bin_slug = parsed.get("bin_slug", "recyclable").lower().strip()

                if not is_trash or bin_slug not in BIN_TITLES:
                    if not is_trash:
                        bin_slug = "unknown"
                    else:
                        bin_slug = "recyclable"

                result_data = {
                    "is_trash": is_trash,
                    "item_name": item_name,
                    "bin_slug": bin_slug,
                    "confidence": int(parsed.get("confidence", 85)),
                    "explanation": parsed.get("explanation", ""),
                    "elapsed_sec": round(time.time() - start_t, 2),
                    "trigger_mode": trigger_mode,
                }

                self.last_result = result_data
                self.last_scan_time = time.time()

                print(f"\n[GEMINI SUCCESS] {result_data['item_name']} ({result_data['confidence']}%) -> [{bin_slug.upper()}] in {result_data['elapsed_sec']}s")
                print(f"                 Reason: {result_data['explanation']}\n")

                if result_data["is_trash"] and bin_slug != "unknown":
                    self._sync_to_dashboard(frame_bgr, result_data)
                else:
                    print(f"  [Notice] Non-waste frame (not synced to dashboard).\n")

            except Exception as err:
                err_msg = str(err)
                print(f"\n[Gemini Error]: {err_msg}")

                # If rate limited (429) or high demand (503), set a 15-second cooldown
                if "429" in err_msg or "RESOURCE_EXHAUSTED" in err_msg or "503" in err_msg:
                    self.cooldown_until = time.time() + 15.0
                    explanation = "Rate limit reached. Cooldown 15s (Tap Space when ready)."
                else:
                    explanation = err_msg[:60]

                self.last_result = {
                    "is_trash": False,
                    "item_name": "API Service Busy",
                    "bin_slug": "unknown",
                    "confidence": 0,
                    "explanation": explanation,
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
                    print(f"  [CLOUD SYNC] Updated live bin: https://ecosync.me")
                else:
                    print(f"  [CLOUD SYNC] Server response ({resp.status_code}): {resp.text[:80]}")
            except Exception as e:
                print(f"  [CLOUD SYNC] Network Notice: {e}")

        threading.Thread(target=upload_task, daemon=True).start()


# ── 4. SMART HANDS-FREE PRESENCE DETECTOR ─────────────────────────────────────
class SmartWasteDetector:
    """
    Watches the reticle area for newly presented trash items using robust frame differencing.
    Enforces a smooth 'One Scan Per Item' policy with an active 3.0s disposal pause.
    Detects hand/item movements during the pause so you don't need to shake or re-move the item!
    """
    def __init__(self, settle_seconds=0.75, cooldown_seconds=3.0, motion_threshold=0.05):
        self.prev_roi = None
        self.motion_detected = False
        self.motion_settled_start = None
        self.settle_seconds = settle_seconds
        self.cooldown_seconds = cooldown_seconds
        self.motion_threshold = motion_threshold
        self.cooldown_until = 0
        self.state = "IDLE"  # "IDLE", "MOTION", "STABILIZING", "BUSY", "PAUSE"
        self.status_msg = "HANDS-FREE: PRESENT ITEM IN RETICLE"
        self.stabilize_pct = 0
        self._was_busy = False

    def notify_scan_finished(self):
        """Called as soon as AI inference completes to start the 3.0s post-scan pause."""
        self.cooldown_until = time.time() + self.cooldown_seconds
        self.motion_detected = False
        self.motion_settled_start = None
        self.state = "PAUSE"

    def update(self, frame, x1, y1, x2, y2, classifier_busy, auto_enabled=True):
        now = time.time()

        # Always maintain a rolling ROI reference every frame to prevent comparing against stale frames
        roi = frame[y1:y2, x1:x2]
        gray = cv2.cvtColor(roi, cv2.COLOR_BGR2GRAY)
        gray = cv2.GaussianBlur(gray, (21, 21), 0)

        if self.prev_roi is None:
            self.prev_roi = gray
            return False

        diff = cv2.absdiff(self.prev_roi, gray)
        self.prev_roi = gray

        thresh = cv2.threshold(diff, 28, 255, cv2.THRESH_BINARY)[1]
        motion_score = cv2.countNonZero(thresh)
        total_pixels = (x2 - x1) * (y2 - y1)
        motion_ratio = motion_score / float(total_pixels) if total_pixels > 0 else 0

        # Detect completion of AI inference and activate post-scan pause
        if self._was_busy and not classifier_busy:
            self.notify_scan_finished()
        self._was_busy = classifier_busy

        if not auto_enabled:
            self.state = "MANUAL"
            self.status_msg = "MANUAL MODE [TAP SPACE TO SCAN]"
            return False

        if classifier_busy:
            self.state = "BUSY"
            self.status_msg = "ANALYZING ITEM..."
            return False

        # Post-scan pause (3.0 seconds to view result and drop in bin)
        if now < self.cooldown_until:
            self.state = "PAUSE"
            remaining = int(self.cooldown_until - now) + 1
            self.status_msg = f"PAUSE ({remaining}s) — DISPOSE IN BIN"
            # If the user moves their hand or presents the next item during the pause, capture that motion!
            if motion_ratio >= self.motion_threshold:
                self.motion_detected = True
            return False

        # Clear intentional movement (user bringing a new item into view)
        if motion_ratio >= self.motion_threshold:
            self.motion_detected = True
            self.motion_settled_start = None
            self.state = "MOTION"
            self.status_msg = "ITEM DETECTED — HOLD STEADY..."
            self.stabilize_pct = 0
            return False

        # Once motion was seen, wait for the user to hold it steady
        if self.motion_detected:
            if self.motion_settled_start is None:
                self.motion_settled_start = now
                self.state = "STABILIZING"
                self.status_msg = "HOLDING STEADY..."
                self.stabilize_pct = 15
                return False

            elapsed = now - self.motion_settled_start
            self.stabilize_pct = min(100, int((elapsed / self.settle_seconds) * 100))

            if elapsed >= self.settle_seconds:
                # Trigger exactly ONE scan
                self.motion_detected = False
                self.motion_settled_start = None
                self.state = "BUSY"
                self.status_msg = "SCAN TRIGGERED!"
                return True
            else:
                self.state = "STABILIZING"
                self.status_msg = f"HOLD STEADY... {self.stabilize_pct}%"
                return False

        self.state = "IDLE"
        self.status_msg = "HANDS-FREE: PRESENT ITEM IN RETICLE"
        self.stabilize_pct = 0
        return False


# ── 5. HUD OVERLAY RENDERING ─────────────────────────────────────────────────
def draw_hud(frame, classifier, detector, auto_detect_enabled):
    h, w, _ = frame.shape
    overlay = frame.copy()

    # Top Header Banner
    cv2.rectangle(overlay, (0, 0), (w, 42), (15, 23, 20), -1)
    cv2.putText(overlay, f"SYNCBIN GEMINI AI [{classifier.active_model}]", (14, 26),
                cv2.FONT_HERSHEY_DUPLEX, 0.52, (52, 211, 153), 1, cv2.LINE_AA)

    now = time.time()
    if classifier.is_busy:
        status_text, status_color = "ANALYZING...", (0, 215, 255)
    elif now < classifier.cooldown_until:
        remaining = int(classifier.cooldown_until - now)
        status_text, status_color = f"RATE LIMIT COOLDOWN: {remaining}s", (0, 165, 255)
    elif detector.state in ("MOTION", "PRESENT"):
        status_text, status_color = detector.status_msg, (11, 158, 245)  # Orange
    elif detector.state == "STABILIZING":
        status_text, status_color = detector.status_msg, (0, 240, 255)   # Bright Yellow
    elif detector.state in ("COOLDOWN", "PAUSE"):
        status_text, status_color = detector.status_msg, (200, 200, 100) # Soft Gold
    elif not auto_detect_enabled:
        status_text, status_color = "MANUAL [SPACE = SCAN | A = AUTO]", (180, 180, 180)
    else:
        status_text, status_color = "AUTO-DETECT READY [HOLD ITEM IN BOX]", (52, 211, 153)

    cv2.putText(overlay, status_text, (w - 365, 26),
                cv2.FONT_HERSHEY_SIMPLEX, 0.44, status_color, 1, cv2.LINE_AA)

    # Central Target Reticle Coordinates
    box_w, box_h = int(w * 0.55), int(h * 0.55)
    x1, y1 = (w - box_w) // 2, (h - box_h) // 2 + 12
    x2, y2 = x1 + box_w, y1 + box_h

    # Color code reticle dynamically based on state
    res = classifier.last_result
    if classifier.is_busy:
        reticle_color = (0, 215, 255)
    elif detector.state == "STABILIZING":
        reticle_color = (0, 240, 255)
    elif detector.state in ("MOTION", "PRESENT"):
        reticle_color = (11, 158, 245)
    elif res and res.get("is_trash") and (now - classifier.last_scan_time < 6.0):
        reticle_color = BIN_COLORS.get(res.get("bin_slug"), (52, 211, 153))
    else:
        reticle_color = (52, 211, 153) if auto_detect_enabled else (150, 150, 150)

    corner_len, t = 25, 2
    cv2.line(overlay, (x1, y1), (x1 + corner_len, y1), reticle_color, t)
    cv2.line(overlay, (x1, y1), (x1, y1 + corner_len), reticle_color, t)
    cv2.line(overlay, (x2, y1), (x2 - corner_len, y1), reticle_color, t)
    cv2.line(overlay, (x2, y1), (x2, y1 + corner_len), reticle_color, t)
    cv2.line(overlay, (x1, y2), (x1 + corner_len, y2), reticle_color, t)
    cv2.line(overlay, (x1, y2), (x1, y2 - corner_len), reticle_color, t)
    cv2.line(overlay, (x2, y2), (x2 - corner_len, y2), reticle_color, t)
    cv2.line(overlay, (x2, y2), (x2, y2 - corner_len), reticle_color, t)

    # If stabilizing, draw a progress indicator bar on top of the reticle
    if detector.state == "STABILIZING" and detector.stabilize_pct > 0:
        bar_w = int((x2 - x1) * (detector.stabilize_pct / 100.0))
        cv2.rectangle(overlay, (x1, y1 - 8), (x1 + bar_w, y1 - 4), (0, 240, 255), -1)

    # Bottom Classification Readout
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
        notice_text = res.get("explanation") or "No item recognized. Place trash in center reticle."
        cv2.putText(overlay, notice_text[:65], (30, h - 28),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.45, (160, 160, 160), 1, cv2.LINE_AA)

    cv2.addWeighted(overlay, 0.88, frame, 0.12, 0, frame)
    return frame, (x1, y1, x2, y2)


# ── 6. MAIN APPLICATION LOOP ─────────────────────────────────────────────────
def main():
    cam_type, cam_obj = init_camera()
    classifier = GeminiClassifier(api_key=GEMINI_API_KEY)
    detector = SmartWasteDetector(settle_seconds=0.75, cooldown_seconds=3.0, motion_threshold=0.05)

    WINDOW_NAME = "SyncBin Gemini AI — Hands-Free Trash Classifier"
    # WINDOW_GUI_NORMAL disables Qt status bar and toolbar to prevent QFont crashes
    cv2.namedWindow(WINDOW_NAME, cv2.WINDOW_GUI_NORMAL)
    cv2.resizeWindow(WINDOW_NAME, 640, 480)

    print("=" * 65)
    print("       SyncBin GEMINI AI Vision — Hands-Free Classifier")
    print("=" * 65)
    print(f"Target Server   : {DASHBOARD_URL}")
    print(f"Active Model    : {classifier.active_model}")
    print(f"Autofocus       : Continuous Hardware AF Active (Tap [F] to refocus)")
    print("Auto-Trigger    : ACTIVE — Hold trash item steady in box to scan")
    print("Controls        : [SPACE] = Manual Scan | [A] = Toggle Auto | [F] = Refocus | [Q] = Quit")
    print("-" * 65)

    auto_detect_enabled = True
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

            # Pre-calculate reticle box coordinates for detection
            h, w, _ = frame.shape
            box_w, box_h = int(w * 0.55), int(h * 0.55)
            x1, y1 = (w - box_w) // 2, (h - box_h) // 2 + 12
            x2, y2 = x1 + box_w, y1 + box_h

            # Hands-Free Presence & Stability Detection
            should_scan = detector.update(frame, x1, y1, x2, y2, classifier.is_busy, auto_detect_enabled)
            if should_scan and not classifier.is_busy:
                print(f"\n[AUTO-TRIGGER] Trash item held steady! Auto-scanning with {classifier.active_model}...")
                classifier.submit_request(frame, trigger_mode="auto_hands_free")

            # Draw HUD & show live preview
            display = frame.copy()
            draw_hud(display, classifier, detector, auto_detect_enabled)
            cv2.imshow(WINDOW_NAME, display)

            key = cv2.waitKey(1) & 0xFF

            # [SPACEBAR] -> Instant Manual Scan
            if key == ord(' '):
                print("\n[SPACEBAR] Manual scan requested!")
                if not classifier.is_busy:
                    classifier.submit_request(frame, trigger_mode="manual")
                else:
                    print("Gemini is currently analyzing a frame, please wait...")

            # [F] -> Force Autofocus Cycle on Camera Module 3
            elif key in (ord('f'), ord('F')):
                if cam_type == "csi":
                    print("\n[AUTOFOCUS] Triggering hardware autofocus cycle...")
                    try:
                        cam_obj.autofocus_cycle()
                    except Exception:
                        try:
                            from libcamera import controls
                            cam_obj.set_controls({"AfTrigger": controls.AfTriggerEnum.Start})
                        except Exception:
                            pass

            # [A] -> Toggle Auto-Detect Mode
            elif key in (ord('a'), ord('A')):
                auto_detect_enabled = not auto_detect_enabled
                state_str = "ENABLED (Hands-Free)" if auto_detect_enabled else "DISABLED (Manual Spacebar only)"
                print(f"\n[AUTO-DETECT] Mode toggled: {state_str}")

            # [Q] or [ESC] -> Quit
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
