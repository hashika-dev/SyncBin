#!/usr/bin/env python3
"""
SyncBin Live AI Vision Engine — yolo_detector.py
- Cooldown: 5-second reset timer before accepting next item
- Dual Snapshot support: Writes to /tmp/syncbin_latest.jpg & serves port 5050
- Zero camera hardware lock conflicts
"""

import os
import sys
import time
import json
import base64
import queue
import threading
from datetime import datetime, timezone
from http.server import HTTPServer, BaseHTTPRequestHandler
import requests
import cv2

# Ensure system Picamera2 can be loaded inside virtual environment (venv)
for p in ["/usr/lib/python3/dist-packages", "/usr/local/lib/python3/dist-packages"]:
    if os.path.exists(p) and p not in sys.path:
        sys.path.append(p)

from ultralytics import YOLO

# ── 1. CONFIGURATION ──────────────────────────────────────────────────────────
DASHBOARD_URL = os.environ.get("DASHBOARD_URL", "https://ecosync.me/api/bins/camera-scan")
BASE_DIR = os.path.dirname(os.path.abspath(__file__))

def get_model_path():
    for name in ["yolov8n.onnx", "best.onnx", "best.pt", "yolov8n.pt"]:
        full = os.path.join(BASE_DIR, name)
        if os.path.exists(full):
            return full
        if os.path.exists(name):
            return name
    return "yolov8n.onnx"

MODEL_PATH = os.environ.get("MODEL_PATH", get_model_path())

CONF_THRESHOLD = float(os.environ.get("CONF_THRESHOLD", 0.45))
HAS_DISPLAY = bool(os.environ.get("DISPLAY") or os.environ.get("WAYLAND_DISPLAY"))
SNAPSHOT_PORT = int(os.environ.get("SNAPSHOT_PORT", 5050))

# ── RESET COOLDOWN: 5 FULL SECONDS OF CLEAR CAMERA BEFORE NEXT ITEM ──────────
CLEAR_VIEW_COOLDOWN = 5.0

# Shared file path so camera_server / dashboard can grab frames without conflict
SHARED_FRAME_PATH = "/tmp/syncbin_latest.jpg"

# ── 2. TRASH CATEGORY MAPPING ─────────────────────────────────────────────────
ITEM_MAPPING = {
    # Recyclables
    "bottle":       {"name": "Plastic Water Bottle", "slug": "recyclable"},
    "cup":          {"name": "Disposable Cup",       "slug": "recyclable"},
    "wine glass":   {"name": "Glass Bottle / Jar",   "slug": "recyclable"},
    "fork":         {"name": "Plastic / Metal Fork", "slug": "recyclable"},
    "knife":        {"name": "Metal Cutlery",        "slug": "recyclable"},
    "spoon":        {"name": "Plastic / Metal Spoon","slug": "recyclable"},
    "bowl":         {"name": "Disposable Bowl",      "slug": "recyclable"},
    "book":         {"name": "Paper / Cardboard",    "slug": "recyclable"},
    "scissors":     {"name": "Metal Waste",          "slug": "recyclable"},
    "recyclable":   {"name": "Recyclable Material",  "slug": "recyclable"},

    # Biodegradables (Organics)
    "banana":       {"name": "Banana Peel",          "slug": "biodegradable"},
    "apple":        {"name": "Apple Core",           "slug": "biodegradable"},
    "orange":       {"name": "Orange Peel",          "slug": "biodegradable"},
    "carrot":       {"name": "Vegetable Scrap",      "slug": "biodegradable"},
    "broccoli":     {"name": "Vegetable Scrap",      "slug": "biodegradable"},
    "sandwich":     {"name": "Food Waste",           "slug": "biodegradable"},
    "hot dog":      {"name": "Food Waste",           "slug": "biodegradable"},
    "pizza":        {"name": "Food Waste",           "slug": "biodegradable"},
    "donut":        {"name": "Food Waste",           "slug": "biodegradable"},
    "cake":         {"name": "Food Waste",           "slug": "biodegradable"},
    "potted plant": {"name": "Plant / Leaf Waste",   "slug": "biodegradable"},
    "biodegradable":{"name": "Organic Waste",        "slug": "biodegradable"},

    # Hazardous
    "cell phone":   {"name": "E-Waste (Phone)",      "slug": "hazardous"},
    "laptop":       {"name": "E-Waste (Laptop)",     "slug": "hazardous"},
    "mouse":        {"name": "E-Waste (Mouse)",      "slug": "hazardous"},
    "remote":       {"name": "E-Waste (Remote)",     "slug": "hazardous"},
    "keyboard":     {"name": "E-Waste (Keyboard)",   "slug": "hazardous"},
    "battery":      {"name": "Used Battery",         "slug": "hazardous"},
    "hazardous":    {"name": "Hazardous Waste",      "slug": "hazardous"},

    # Non-Biodegradables (Residuals)
    "toothbrush":   {"name": "Plastic Toothbrush",   "slug": "non-bio"},
    "non-biodegradable": {"name": "Non-Bio Residual","slug": "non-bio"},
    "non_biodegradable": {"name": "Non-Bio Residual","slug": "non-bio"},
}

STREAM_COLORS = {
    "recyclable": (255, 165, 0),
    "biodegradable": (46, 204, 113),
    "non-bio": (128, 128, 128),
    "hazardous": (50, 50, 240)
}

LATEST_FRAME_LOCK = threading.Lock()
LATEST_FRAME = None
UPLOAD_QUEUE = queue.Queue(maxsize=1)


def resolve_item_info(raw_label: str) -> tuple:
    clean = raw_label.lower().strip()
    if clean in ITEM_MAPPING:
        info = ITEM_MAPPING[clean]
        return (info["name"], info["slug"])

    for key, info in ITEM_MAPPING.items():
        if key in clean or clean in key:
            return (info["name"], info["slug"])

    return (raw_label.replace("_", " ").title(), "recyclable")


# ── 3. ASYNC UPLOAD WORKER (ZERO VIDEO LAG) ───────────────────────────────────
def upload_worker():
    while True:
        task = UPLOAD_QUEUE.get()
        if task is None:
            break
        payload, img_bytes, friendly_name = task
        try:
            files = {"image": ("scan.jpg", img_bytes, "image/jpeg")}
            resp = requests.post(DASHBOARD_URL, data=payload, files=files, timeout=7)
            if resp.status_code == 200:
                print(f"  [SYNCED] Registered 1x '{friendly_name}' on https://ecosync.me")
            else:
                print(f"  [Server Response {resp.status_code}]: {resp.text[:80]}")
        except Exception as err:
            print(f"  [Network Error]: {err}")
        finally:
            UPLOAD_QUEUE.task_done()


# ── 4. EMBEDDED SNAPSHOT SERVER (PORT 5050) ───────────────────────────────────
class SnapshotRequestHandler(BaseHTTPRequestHandler):
    def log_message(self, format, *args):
        return

    def do_POST(self):
        self.handle_snapshot()

    def do_GET(self):
        if self.path == "/health":
            self.send_response(200)
            self.send_header("Content-Type", "application/json")
            self.end_headers()
            self.wfile.write(json.dumps({"status": "ok", "service": "syncbin-ai"}).encode())
        else:
            self.handle_snapshot()

    def handle_snapshot(self):
        global LATEST_FRAME
        frame_copy = None
        with LATEST_FRAME_LOCK:
            if LATEST_FRAME is not None:
                frame_copy = LATEST_FRAME.copy()

        if frame_copy is None:
            self.send_response(503)
            self.send_header("Content-Type", "application/json")
            self.end_headers()
            self.wfile.write(json.dumps({
                "status": "error",
                "message": "Camera warming up..."
            }).encode())
            return

        _, img_buf = cv2.imencode(".jpg", frame_copy, [int(cv2.IMWRITE_JPEG_QUALITY), 85])
        b64 = base64.b64encode(img_buf.tobytes()).decode("utf-8")
        data_uri = f"data:image/jpeg;base64,{b64}"
        h, w, _ = frame_copy.shape

        response_body = json.dumps({
            "status": "ok",
            "image": data_uri,
            "width": w,
            "height": h,
            "captured_at": datetime.now(timezone.utc).isoformat()
        }).encode("utf-8")

        self.send_response(200)
        self.send_header("Content-Type", "application/json")
        self.send_header("Content-Length", str(len(response_body)))
        self.send_header("Access-Control-Allow-Origin", "*")
        self.end_headers()
        self.wfile.write(response_body)


def start_snapshot_server():
    try:
        server = HTTPServer(("0.0.0.0", SNAPSHOT_PORT), SnapshotRequestHandler)
        t = threading.Thread(target=server.serve_forever, daemon=True)
        t.start()
        print(f"[Snapshot Server] Active on port {SNAPSHOT_PORT}")
    except Exception as e:
        print(f"[Snapshot Server] Notice on port {SNAPSHOT_PORT}: {e}")


def init_camera_module_3():
    print("[Camera] Connecting to Raspberry Pi Camera Module 3...")
    try:
        from picamera2 import Picamera2
        picam2 = Picamera2()
        config = picam2.create_preview_configuration(
            main={"size": (640, 480), "format": "BGR888"}
        )
        picam2.configure(config)
        picam2.start()
        print("[Camera] Camera Module 3 active!")
        return picam2
    except Exception as e:
        print(f"[Error] Failed to initialize Picamera2: {e}")
        sys.exit(1)


def main():
    global LATEST_FRAME
    print("=" * 65)
    print("  SyncBin Live Vision Engine — 5s Cooldown & Instant Snapshots")
    print("=" * 65)
    print(f"Server Target   : {DASHBOARD_URL}")
    print(f"Model Path      : {MODEL_PATH}")
    print(f"Reset Delay     : {CLEAR_VIEW_COOLDOWN}s")

    start_snapshot_server()
    worker_thread = threading.Thread(target=upload_worker, daemon=True)
    worker_thread.start()

    if not os.path.exists(MODEL_PATH):
        print(f"[Fatal] Model '{MODEL_PATH}' was not found!")
        sys.exit(1)

    print(f"Loading YOLO model from {MODEL_PATH}...")
    model = YOLO(MODEL_PATH)

    cam = init_camera_module_3()

    bin_state = "READY_FOR_ITEM"
    item_last_seen_time = 0
    registered_item_name = ""
    frame_counter = 0
    cached_valid_boxes = []
    last_file_save_time = 0

    print("\n[READY] Present 1 item at a time. The system will register it ONCE.\n")

    try:
        while True:
            frame = cam.capture_array()
            if frame is None:
                time.sleep(0.01)
                continue

            with LATEST_FRAME_LOCK:
                LATEST_FRAME = frame.copy()

            display_frame = frame.copy()
            frame_counter += 1
            now = time.time()

            # Keep /tmp/syncbin_latest.jpg fresh so camera_server can grab snapshots
            if (now - last_file_save_time) > 0.3:
                try:
                    cv2.imwrite(SHARED_FRAME_PATH, frame)
                    last_file_save_time = now
                except Exception:
                    pass

            # Run inference every 2 frames
            if frame_counter % 2 == 0:
                results = model.predict(source=frame, conf=CONF_THRESHOLD, verbose=False)
                boxes = results[0].boxes

                valid_boxes = []
                for b in boxes:
                    cls_id = int(b.cls[0])
                    label = model.names[cls_id] if hasattr(model, "names") else f"item_{cls_id}"
                    label_lower = str(label).lower()
                    for key in ITEM_MAPPING.keys():
                        if key in label_lower or label_lower in key:
                            valid_boxes.append((b, label))
                            break
                cached_valid_boxes = valid_boxes

            # ── 1. ITEM IS VISIBLE ───────────────────────────────────────────
            if len(cached_valid_boxes) > 0:
                item_last_seen_time = now

                for box, raw_label in cached_valid_boxes:
                    conf = round(float(box.conf[0]) * 100, 1)
                    friendly_name, bin_slug = resolve_item_info(raw_label)
                    color = STREAM_COLORS.get(bin_slug, (0, 255, 0))
                    x1, y1, x2, y2 = [int(v) for v in box.xyxy[0].tolist()]

                    cv2.rectangle(display_frame, (x1, y1), (x2, y2), color, 2)
                    label_text = f"{friendly_name} {conf}% [{bin_slug.upper()}]"
                    (w, h), _ = cv2.getTextSize(label_text, cv2.FONT_HERSHEY_SIMPLEX, 0.45, 1)
                    cv2.rectangle(display_frame, (x1, max(0, y1 - 20)), (x1 + w + 6, max(20, y1)), color, -1)
                    cv2.putText(display_frame, label_text, (x1 + 3, max(15, y1 - 5)),
                                cv2.FONT_HERSHEY_SIMPLEX, 0.45, (255, 255, 255), 1, cv2.LINE_AA)

                if bin_state == "READY_FOR_ITEM":
                    best_box, best_raw = max(cached_valid_boxes, key=lambda pair: float(pair[0].conf[0]))
                    best_conf = round(float(best_box.conf[0]) * 100, 1)
                    best_friendly, target_slug = resolve_item_info(best_raw)

                    bx1, by1, bx2, by2 = best_box.xyxy[0].tolist()
                    fh, fw, _ = frame.shape
                    bbox_data = {
                        "x": round((bx1 / fw) * 100, 1),
                        "y": round((by1 / fh) * 100, 1),
                        "width": round(((bx2 - bx1) / fw) * 100, 1),
                        "height": round(((by2 - by1) / fh) * 100, 1)
                    }

                    print(f"🎯 [REGISTERED]: {best_friendly} ({best_conf}%) -> [{target_slug.upper()}]")

                    _, img_encoded = cv2.imencode(".jpg", display_frame, [int(cv2.IMWRITE_JPEG_QUALITY), 85])

                    payload = {
                        "item_name": best_friendly,
                        "bin_slug": target_slug,
                        "confidence": best_conf,
                        "bounding_box": json.dumps(bbox_data)
                    }

                    try:
                        if UPLOAD_QUEUE.full():
                            try:
                                UPLOAD_QUEUE.get_nowait()
                            except queue.Empty:
                                pass
                        UPLOAD_QUEUE.put_nowait((payload, img_encoded.tobytes(), best_friendly))
                    except Exception:
                        pass

                    bin_state = "ITEM_IN_VIEW"
                    registered_item_name = best_friendly

                else:
                    cv2.putText(display_frame, f"LOCKED: {registered_item_name} (Drop item to reset)", (10, 30),
                                cv2.FONT_HERSHEY_SIMPLEX, 0.55, (0, 255, 255), 2, cv2.LINE_AA)

            # ── 2. VIEW IS CLEAR (COUNTDOWN 5 SECONDS TO RESET) ──────────────
            else:
                if bin_state == "ITEM_IN_VIEW":
                    elapsed_clear = now - item_last_seen_time
                    if elapsed_clear >= CLEAR_VIEW_COOLDOWN:
                        bin_state = "READY_FOR_ITEM"
                        registered_item_name = ""
                        print(f"[RESET] View was clear for {CLEAR_VIEW_COOLDOWN}s. Ready for next item!\n")
                    else:
                        remaining = round(CLEAR_VIEW_COOLDOWN - elapsed_clear, 1)
                        cv2.putText(display_frame, f"Empty view: Resetting in {remaining}s...", (10, 30),
                                    cv2.FONT_HERSHEY_SIMPLEX, 0.55, (180, 180, 180), 2, cv2.LINE_AA)
                else:
                    cv2.putText(display_frame, "READY: Place trash in view", (10, 30),
                                cv2.FONT_HERSHEY_SIMPLEX, 0.55, (0, 255, 0), 2, cv2.LINE_AA)

            if HAS_DISPLAY:
                try:
                    cv2.imshow("SyncBin Live AI Vision — Camera Module 3", display_frame)
                    key = cv2.waitKey(1) & 0xFF
                    if key == ord('q'):
                        print("\nUser pressed 'q' — exiting preview.")
                        break
                except Exception:
                    pass

    except KeyboardInterrupt:
        print("\nStopping detector...")
    finally:
        cam.stop()
        if os.path.exists(SHARED_FRAME_PATH):
            try:
                os.remove(SHARED_FRAME_PATH)
            except Exception:
                pass
        if HAS_DISPLAY:
            cv2.destroyAllWindows()


if __name__ == "__main__":
    main()
