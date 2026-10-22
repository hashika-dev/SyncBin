#!/usr/bin/env python3
"""
SyncBin RPi Camera Snapshot Server (Conflict-Free Multi-Backend Support)
========================================================================
Runs on Raspberry Pi on port 5050.
- Priority 1: If AI vision detector (gemini_trash_classifier.py or yolo_detector.py)
  is actively running, serves the live frame from /tmp/syncbin_latest.jpg (0ms delay).
- Priority 2: If stopped, falls back to native CSI camera tool:
  * Automatically detects `rpicam-still` (Raspberry Pi OS Bookworm / Debian 12)
  * Automatically detects `libcamera-still` (Raspberry Pi OS Bullseye / Debian 11)
- Priority 3: Falls back to OpenCV / USB webcam (V4L2 device) if CSI camera isn't present.
"""

import subprocess
import base64
import tempfile
import os
import time
import json
import shutil
from datetime import datetime, timezone
from flask import Flask, jsonify

app = Flask(__name__)

# ── Config ────────────────────────────────────────────────────────────────────
SNAP_WIDTH   = 1280
SNAP_HEIGHT  = 720
SNAP_QUALITY = 85
SNAP_TIMEOUT = 1000
SHARED_FRAME_PATH = "/tmp/syncbin_latest.jpg"
# ─────────────────────────────────────────────────────────────────────────────


def get_csi_command():
    """Detect available CSI camera capture utility across RPi OS versions."""
    for cmd in ["rpicam-still", "libcamera-still"]:
        if shutil.which(cmd):
            return cmd
    return None


def capture_opencv():
    """Fallback capture using OpenCV (useful for USB webcams or V4L2 devices)."""
    try:
        import cv2
        cap = cv2.VideoCapture(0)
        if not cap.isOpened():
            return None

        # Warm up exposure / white balance
        frame = None
        for _ in range(5):
            ret, frame = cap.read()
            if not ret:
                break
            time.sleep(0.05)

        cap.release()

        if frame is not None:
            if frame.shape[1] != SNAP_WIDTH or frame.shape[0] != SNAP_HEIGHT:
                frame = cv2.resize(frame, (SNAP_WIDTH, SNAP_HEIGHT))
            success, buffer = cv2.imencode(".jpg", frame, [int(cv2.IMWRITE_JPEG_QUALITY), SNAP_QUALITY])
            if success:
                return buffer.tobytes()
    except Exception:
        pass
    return None


@app.route("/snapshot", methods=["POST", "GET"])
def snapshot():
    """Return latest camera frame without hardware lock conflicts."""

    # 1. First priority: Check if AI vision detector is actively producing frames
    if os.path.exists(SHARED_FRAME_PATH):
        try:
            mtime = os.path.getmtime(SHARED_FRAME_PATH)
            # If the shared frame is fresh (updated within the last 6 seconds)
            if (time.time() - mtime) < 6.0:
                with open(SHARED_FRAME_PATH, "rb") as f:
                    raw = f.read()

                b64 = base64.b64encode(raw).decode("utf-8")
                return jsonify({
                    "status":      "ok",
                    "image":       f"data:image/jpeg;base64,{b64}",
                    "width":       SNAP_WIDTH,
                    "height":      SNAP_HEIGHT,
                    "captured_at": datetime.now(timezone.utc).isoformat(),
                    "source":      "ai_detector_live_stream"
                })
        except Exception:
            pass

    # 2. Second priority: Try native CSI camera tool (rpicam-still or libcamera-still)
    csi_cmd = get_csi_command()
    if csi_cmd:
        with tempfile.NamedTemporaryFile(suffix=".jpg", delete=False) as tmp:
            tmp_path = tmp.name

        try:
            result = subprocess.run(
                [
                    csi_cmd,
                    "--output",    tmp_path,
                    "--width",     str(SNAP_WIDTH),
                    "--height",    str(SNAP_HEIGHT),
                    "--quality",   str(SNAP_QUALITY),
                    "--timeout",   str(SNAP_TIMEOUT),
                    "--nopreview",
                ],
                capture_output=True,
                timeout=15,
            )

            if result.returncode == 0 and os.path.exists(tmp_path) and os.path.getsize(tmp_path) > 0:
                with open(tmp_path, "rb") as f:
                    raw = f.read()

                b64 = base64.b64encode(raw).decode("utf-8")
                return jsonify({
                    "status":      "ok",
                    "image":       f"data:image/jpeg;base64,{b64}",
                    "width":       SNAP_WIDTH,
                    "height":      SNAP_HEIGHT,
                    "captured_at": datetime.now(timezone.utc).isoformat(),
                    "source":      csi_cmd
                })
        except subprocess.TimeoutExpired:
            pass
        except Exception:
            pass
        finally:
            if os.path.exists(tmp_path):
                os.unlink(tmp_path)

    # 3. Third priority: Try USB / V4L2 capture via OpenCV
    raw_cv = capture_opencv()
    if raw_cv:
        b64 = base64.b64encode(raw_cv).decode("utf-8")
        return jsonify({
            "status":      "ok",
            "image":       f"data:image/jpeg;base64,{b64}",
            "width":       SNAP_WIDTH,
            "height":      SNAP_HEIGHT,
            "captured_at": datetime.now(timezone.utc).isoformat(),
            "source":      "opencv_v4l2"
        })

    # If all capture paths failed:
    return jsonify({
        "status":  "error",
        "message": "Camera capture failed. No CSI utility (rpicam-still/libcamera-still) succeeded, and no USB camera stream was detected. Please check hardware connection.",
    }), 500


@app.route("/health", methods=["GET"])
def health():
    return jsonify({
        "status": "ok",
        "service": "syncbin-camera",
        "csi_command": get_csi_command(),
    })


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5050, debug=False)
