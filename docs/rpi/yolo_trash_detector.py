#!/usr/bin/env python3
"""
SyncBin YOLOv8 Waste Classification & Detection Client
======================================================
Runs on the Raspberry Pi 4 / 5.
Supports:
  - Both Pi Camera Module (CSI ribbon via Picamera2/libcamera) and USB Webcams (OpenCV)
  - Models: best.pt, best.onnx, or best_ncnn_model
  - Dynamic class name detection directly from your trained weights
  - Automatic category routing (recyclable, biodegradable, non-bio, hazardous)
  - Debounce logic so an item isn't re-submitted continuously
  - Direct HTTP sync with the SyncBin dashboard API

Installation on Raspberry Pi:
  sudo apt update
  sudo apt install -y python3-pip python3-opencv python3-libcamera
  pip3 install ultralytics requests

Performance Optimization (Recommended for Pi 4):
  Export your model to NCNN for 3x–5x faster inference:
    yolo export model=best.pt format=ncnn
  Then point MODEL_PATH to 'best_ncnn_model'
"""

import os
import sys
import time
import json
import requests
import cv2
from pathlib import Path
from ultralytics import YOLO

# ── 1. Configuration ──────────────────────────────────────────────────────────
# Automatically detect fastest available model format (NCNN > ONNX > PyTorch .pt)
def detect_best_model_path() -> str:
    candidates = ["best_ncnn_model", "best.onnx", "best.pt"]
    for candidate in candidates:
        if os.path.exists(candidate):
            return candidate
    return "best.onnx" if os.path.exists("best.onnx") else "best.pt"

MODEL_PATH = os.environ.get("MODEL_PATH", detect_best_model_path())

# SyncBin Server URL (use your server's domain or local IP e.g., http://192.168.1.100:8000)
DASHBOARD_URL = os.environ.get("DASHBOARD_URL", "https://ecosync.me/api/bins/camera-scan")

# Camera Mode: 'auto', 'csi' (Raspberry Pi Camera Module), or 'usb' (USB Webcam)
CAMERA_MODE = os.environ.get("CAMERA_MODE", "auto")
USB_CAMERA_INDEX = int(os.environ.get("USB_CAMERA_INDEX", 0))

# Detection Sensitivity
CONF_THRESHOLD = float(os.environ.get("CONF_THRESHOLD", 0.50))

# Cooldown between detections (seconds) to prevent re-triggering on the same item
DEBOUNCE_SECONDS = float(os.environ.get("DEBOUNCE_SECONDS", 3.5))

# Optional class mapping file
MAPPING_FILE = Path(__file__).resolve().parent / "class_mapping.json"
# ─────────────────────────────────────────────────────────────────────────────


def load_mappings() -> dict:
    """Load user class mappings or fallback to defaults."""
    default_mappings = {
        "bottle": "recyclable", "plastic_bottle": "recyclable", "can": "recyclable",
        "aluminum_can": "recyclable", "paper": "recyclable", "cardboard": "recyclable",
        "glass": "recyclable", "banana": "biodegradable", "banana_peel": "biodegradable",
        "apple": "biodegradable", "food": "biodegradable", "leaves": "biodegradable",
        "plastic_bag": "non-bio", "wrapper": "non-bio", "styrofoam": "non-bio",
        "battery": "hazardous", "light_bulb": "hazardous", "medicine": "hazardous"
    }

    if MAPPING_FILE.exists():
        try:
            with open(MAPPING_FILE, "r") as f:
                data = json.load(f)
                return data.get("mappings", default_mappings)
        except Exception as e:
            print(f"[Warning] Could not parse {MAPPING_FILE}: {e}")
    return default_mappings


def resolve_bin_slug(class_name: str, mapping: dict) -> str:
    """Match a detected class name to one of the 4 SyncBin categories."""
    normalized = class_name.lower().replace("-", "_").strip()

    # 1. Exact match
    if normalized in mapping:
        return mapping[normalized]

    # 2. Substring / partial match
    for key, slug in mapping.items():
        if key in normalized or normalized in key:
            return slug

    # 3. Default fallback
    return "recyclable"


class CameraStream:
    """Dual-mode camera reader supporting CSI (Picamera2) and USB (OpenCV)."""

    def __init__(self, mode="auto", usb_index=0, width=640, height=480):
        self.mode = mode
        self.width = width
        self.height = height
        self.picam2 = None
        self.cap = None

        if self.mode in ("auto", "csi"):
            try:
                from picam2 import Picamera2
                print("[Camera] Initializing Raspberry Pi Camera Module (Picamera2)...")
                self.picam2 = Picamera2()
                config = self.picam2.create_preview_configuration(main={"size": (width, height), "format": "BGR888"})
                self.picam2.configure(config)
                self.picam2.start()
                self.mode = "csi"
                print("[Camera] Pi Camera Module (CSI) active!")
                return
            except Exception as e:
                print(f"[Camera] Picamera2 unavailable or failed ({e}). Falling back to USB/OpenCV...")

        print(f"[Camera] Initializing USB Webcam on /dev/video{usb_index}...")
        self.cap = cv2.VideoCapture(usb_index)
        self.cap.set(cv2.CAP_PROP_FRAME_WIDTH, width)
        self.cap.set(cv2.CAP_PROP_FRAME_HEIGHT, height)
        self.mode = "usb"
        if not self.cap.isOpened():
            raise RuntimeError(f"Could not open camera on index {usb_index}.")
        print("[Camera] USB Webcam active!")

    def read(self):
        if self.mode == "csi" and self.picam2:
            frame = self.picam2.capture_array()
            return True, frame
        elif self.cap:
            return self.cap.read()
        return False, None

    def release(self):
        if self.picam2:
            try:
                self.picam2.stop()
            except Exception:
                pass
        if self.cap:
            self.cap.release()


def main():
    print("=" * 65)
    print("       SyncBin YOLOv8n Smart Trash Classifier (RPi Vision)")
    print("=" * 65)
    print(f"Server Target : {DASHBOARD_URL}")
    print(f"Model Path    : {MODEL_PATH}")
    print(f"Confidence    : {CONF_THRESHOLD * 100:.0f}%")
    print(f"Debounce Time : {DEBOUNCE_SECONDS}s")

    mapping = load_mappings()

    # Load YOLO Model
    if not os.path.exists(MODEL_PATH):
        print(f"[Error] Model weights file not found at '{MODEL_PATH}'!")
        print("Please copy your trained best.pt or best_ncnn_model here.")
        sys.exit(1)

    print(f"Loading YOLO weights ({MODEL_PATH})...")
    model = YOLO(MODEL_PATH)

    # Inspect model classes
    names = getattr(model, "names", {})
    if isinstance(names, dict):
        print(f"\n[Model Classes Detected ({len(names)})]:")
        for idx, cname in names.items():
            assigned_slug = resolve_bin_slug(str(cname), mapping)
            print(f"  • ID {idx:02d}: '{cname}' -> Bin [{assigned_slug}]")
    print("-" * 65)

    # Initialize Camera
    cam = CameraStream(mode=CAMERA_MODE, usb_index=USB_CAMERA_INDEX)

    last_detection_time = 0
    last_detected_label = ""

    print("\n[Status] Scanning for waste items... Press Ctrl+C to quit.\n")

    try:
        while True:
            ret, frame = cam.read()
            if not ret or frame is None:
                time.sleep(0.1)
                continue

            # Run YOLO inference
            results = model.predict(source=frame, conf=CONF_THRESHOLD, verbose=False)
            boxes = results[0].boxes

            if len(boxes) > 0:
                # Select the detected object with highest confidence
                best_box = max(boxes, key=lambda b: float(b.conf[0]))
                cls_id = int(best_box.cls[0])
                label_raw = model.names[cls_id] if hasattr(model, "names") else f"item_{cls_id}"
                conf_score = round(float(best_box.conf[0]) * 100, 2)

                now = time.time()
                # Check debounce timeout
                if (label_raw != last_detected_label) or (now - last_detection_time > DEBOUNCE_SECONDS):
                    bin_slug = resolve_bin_slug(str(label_raw), mapping)

                    # Calculate relative bounding box percentages
                    x1, y1, x2, y2 = best_box.xyxy[0].tolist()
                    h, w, _ = frame.shape
                    bbox = {
                        "x": round((x1 / w) * 100, 1),
                        "y": round((y1 / h) * 100, 1),
                        "width": round(((x2 - x1) / w) * 100, 1),
                        "height": round(((y2 - y1) / h) * 100, 1)
                    }

                    clean_name = str(label_raw).replace("_", " ").title()
                    print(f"🔍 [DETECTED] {clean_name} ({conf_score}%) -> Target Bin: [{bin_slug}]")

                    # Draw visualization on snapshot
                    annotated = frame.copy()
                    cv2.rectangle(annotated, (int(x1), int(y1)), (int(x2), int(y2)), (0, 255, 0), 2)
                    cv2.putText(annotated, f"{clean_name} {conf_score:.1f}%", (int(x1), max(20, int(y1) - 10)),
                                cv2.FONT_HERSHEY_SIMPLEX, 0.6, (0, 255, 0), 2)

                    # Encode snapshot as JPEG
                    _, img_bytes = cv2.imencode(".jpg", annotated, [int(cv2.IMWRITE_JPEG_QUALITY), 85])

                    # Dispatch POST request to SyncBin
                    payload = {
                        "item_name": clean_name,
                        "bin_slug": bin_slug,
                        "confidence": conf_score,
                        "bounding_box": json.dumps(bbox)
                    }
                    files = {
                        "image": ("scan.jpg", img_bytes.tobytes(), "image/jpeg")
                    }

                    try:
                        print(f"📡 Syncing to {DASHBOARD_URL}...")
                        resp = requests.post(DASHBOARD_URL, data=payload, files=files, timeout=6)
                        if resp.status_code == 200:
                            print(f"✅ Synced! Item recorded in {bin_slug.upper()} stream.")
                        else:
                            print(f"⚠️ Server returned HTTP {resp.status_code}: {resp.text[:120]}")
                    except Exception as err:
                        print(f"❌ Connection error: {err}")

                    last_detection_time = now
                    last_detected_label = label_raw

            time.sleep(0.05)

    except KeyboardInterrupt:
        print("\nStopping detector gracefully...")
    finally:
        cam.release()
        cv2.destroyAllWindows()


if __name__ == "__main__":
    main()
