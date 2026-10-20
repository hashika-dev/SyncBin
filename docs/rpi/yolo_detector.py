#!/usr/bin/env python3
"""
SyncBin Live AI Vision Engine — yolo_detector.py
Runs YOLOv8 ONNX inference on Raspberry Pi 4/5.
Detects trash in front of the camera and syncs live to https://ecosync.me.
"""

import os
import sys
import time
import json
import cv2
import requests
from ultralytics import YOLO

# ── 1. CONFIGURATION ──────────────────────────────────────────────────────────
# LIVE WEBSITE ENDPOINT:
DASHBOARD_URL = os.environ.get("DASHBOARD_URL", "https://ecosync.me/api/bins/camera-scan")

# Automatically picks best.onnx (or falls back to best.pt)
MODEL_PATH = os.environ.get("MODEL_PATH", "best.onnx" if os.path.exists("best.onnx") else "best.pt")

# Minimum confidence required to accept a detection (0.50 = 50%)
CONF_THRESHOLD = float(os.environ.get("CONF_THRESHOLD", 0.50))

# Cooldown delay in seconds (prevents sending 30 duplicates while holding an item)
DEBOUNCE_SECONDS = float(os.environ.get("DEBOUNCE_SECONDS", 3.5))

# ── 2. TRASH CLASSIFICATION MAPPING ───────────────────────────────────────────
# Maps your model's detected labels to SyncBin's 4 streams:
# 'recyclable', 'biodegradable', 'non-bio', or 'hazardous'
CATEGORY_MAPPING = {
    # Recyclable
    "bottle": "recyclable",
    "plastic_bottle": "recyclable",
    "plastic bottle": "recyclable",
    "water_bottle": "recyclable",
    "can": "recyclable",
    "aluminum_can": "recyclable",
    "soda_can": "recyclable",
    "paper": "recyclable",
    "cardboard": "recyclable",
    "glass": "recyclable",
    "glass_bottle": "recyclable",
    "cup": "recyclable",

    # Biodegradable (Organics)
    "banana": "biodegradable",
    "banana_peel": "biodegradable",
    "banana peel": "biodegradable",
    "apple": "biodegradable",
    "apple_core": "biodegradable",
    "food": "biodegradable",
    "food_waste": "biodegradable",
    "fruit": "biodegradable",
    "vegetable": "biodegradable",
    "leaves": "biodegradable",
    "orange": "biodegradable",

    # Non-Biodegradable (Residuals)
    "plastic_bag": "non-bio",
    "plastic bag": "non-bio",
    "wrapper": "non-bio",
    "chip_bag": "non-bio",
    "styrofoam": "non-bio",
    "straw": "non-bio",
    "face_mask": "non-bio",

    # Hazardous
    "battery": "hazardous",
    "used_battery": "hazardous",
    "light_bulb": "hazardous",
    "bulb": "hazardous",
    "e-waste": "hazardous",
    "electronics": "hazardous",
    "medicine": "hazardous",
}


def resolve_bin_slug(label: str) -> str:
    """Finds the correct bin category for a given trash label."""
    norm = label.lower().strip()
    
    # 1. Exact match
    if norm in CATEGORY_MAPPING:
        return CATEGORY_MAPPING[norm]
    
    # 2. Substring match
    for key, slug in CATEGORY_MAPPING.items():
        if key in norm or norm in key:
            return slug
            
    # Default fallback if unknown
    return "recyclable"


def init_camera():
    """Initializes Raspberry Pi Camera Module (CSI) or USB Webcam."""
    # Try official Pi Camera Module first via Picamera2
    try:
        from picam2 import Picamera2
        print("[Camera] Initializing Raspberry Pi Camera Module (CSI)...")
        cam = Picamera2()
        config = cam.create_preview_configuration(main={"size": (640, 480), "format": "BGR888"})
        cam.configure(config)
        cam.start()
        print("[Camera] Pi Camera Module ready.")
        return ("csi", cam)
    except Exception:
        pass

    # Fallback to USB Webcam via OpenCV
    print("[Camera] Falling back to USB Webcam (/dev/video0)...")
    cap = cv2.VideoCapture(0)
    cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
    cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)
    if not cap.isOpened():
        print("[Error] No camera detected! Check USB or ribbon cable connection.")
        sys.exit(1)
    print("[Camera] USB Webcam ready.")
    return ("usb", cap)


def get_frame(cam_type, cam_obj):
    if cam_type == "csi":
        return True, cam_obj.capture_array()
    return cam_obj.read()


def main():
    print("=" * 65)
    print("     SyncBin LIVE AI Vision — Raspberry Pi Trash Detector")
    print("=" * 65)
    print(f"Live Web Target : {DASHBOARD_URL}")
    print(f"Active Model    : {MODEL_PATH}")

    if not os.path.exists(MODEL_PATH):
        print(f"[Error] Model file '{MODEL_PATH}' was not found.")
        print("Please place 'best.onnx' (or 'best.pt') in this folder.")
        sys.exit(1)

    print(f"Loading weights from {MODEL_PATH}...")
    model = YOLO(MODEL_PATH)

    # Print classes defined inside your model
    classes = getattr(model, "names", {})
    print(f"\nModel Classes Loaded ({len(classes)} classes):")
    for cid, cname in classes.items():
        assigned = resolve_bin_slug(str(cname))
        print(f"  [{cid:02d}] {cname}  -->  Live Bin: [{assigned.upper()}]")
    print("-" * 65)

    cam_type, cam_obj = init_camera()

    last_detection_time = 0
    last_detected_label = ""

    print(f"\n[LIVE] Point the camera at trash (confidence >= {int(CONF_THRESHOLD*100)}%)...")
    print("Press Ctrl+C to stop.\n")

    try:
        while True:
            ret, frame = get_frame(cam_type, cam_obj)
            if not ret or frame is None:
                time.sleep(0.05)
                continue

            # Run YOLO inference
            results = model.predict(source=frame, conf=CONF_THRESHOLD, verbose=False)
            boxes = results[0].boxes

            if len(boxes) > 0:
                # Get the highest-confidence detection
                best_box = max(boxes, key=lambda b: float(b.conf[0]))
                cls_id = int(best_box.cls[0])
                raw_label = model.names[cls_id] if hasattr(model, "names") else f"item_{cls_id}"
                confidence = round(float(best_box.conf[0]) * 100, 1)

                now = time.time()

                # Debounce: only send if item changed or cooldown period passed
                if (raw_label != last_detected_label) or (now - last_detection_time > DEBOUNCE_SECONDS):
                    bin_slug = resolve_bin_slug(str(raw_label))
                    clean_name = str(raw_label).replace("_", " ").title()

                    # Calculate bounding box coordinates
                    x1, y1, x2, y2 = best_box.xyxy[0].tolist()
                    h, w, _ = frame.shape
                    bbox = {
                        "x": round((x1 / w) * 100, 1),
                        "y": round((y1 / h) * 100, 1),
                        "width": round(((x2 - x1) / w) * 100, 1),
                        "height": round(((y2 - y1) / h) * 100, 1)
                    }

                    print(f"🎯 DETECTED: {clean_name} ({confidence}%)  -->  LIVE BIN: [{bin_slug.upper()}]")

                    # Draw bounding box and label overlay on captured frame
                    annotated = frame.copy()
                    cv2.rectangle(annotated, (int(x1), int(y1)), (int(x2), int(y2)), (0, 255, 0), 2)
                    cv2.putText(annotated, f"{clean_name} {confidence}%", (int(x1), max(25, int(y1) - 10)),
                                cv2.FONT_HERSHEY_SIMPLEX, 0.6, (0, 255, 0), 2)

                    # Encode snapshot as JPEG
                    _, img_encoded = cv2.imencode(".jpg", annotated, [int(cv2.IMWRITE_JPEG_QUALITY), 85])

                    # Prepare POST request to live SyncBin server
                    payload = {
                        "item_name": clean_name,
                        "bin_slug": bin_slug,
                        "confidence": confidence,
                        "bounding_box": json.dumps(bbox)
                    }
                    files = {
                        "image": ("scan.jpg", img_encoded.tobytes(), "image/jpeg")
                    }

                    try:
                        print(f"  📡 Posting to {DASHBOARD_URL}...")
                        resp = requests.post(DASHBOARD_URL, data=payload, files=files, timeout=8)
                        if resp.status_code == 200:
                            print(f"  ✅ SUCCESS: Registered on https://ecosync.me! Fill level updated.\n")
                        else:
                            print(f"  ⚠️ Live Server Response ({resp.status_code}): {resp.text[:120]}\n")
                    except Exception as err:
                        print(f"  ❌ Network Connection Error: {err}\n")

                    last_detection_time = now
                    last_detected_label = raw_label

            time.sleep(0.04)

    except KeyboardInterrupt:
        print("\nStopping yolo_detector.py...")
    finally:
        if cam_type == "usb":
            cam_obj.release()
        elif cam_type == "csi":
            cam_obj.stop()
        cv2.destroyAllWindows()


if __name__ == "__main__":
    main()
