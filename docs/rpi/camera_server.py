#!/usr/bin/env python3
"""
SyncBin RPi Camera Snapshot Server (Conflict-Free Dual Support)
==============================================================
Runs on Raspberry Pi on port 5050.
- If yolo_detector.py is actively running, it instantly serves the live frame
  from /tmp/syncbin_latest.jpg (0ms delay, zero hardware conflict).
- If yolo_detector.py is stopped, it gracefully falls back to libcamera-still.
"""

import subprocess
import base64
import tempfile
import os
import time
import json
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

    # 2. Fallback: If AI detector is not running, use libcamera-still
    with tempfile.NamedTemporaryFile(suffix=".jpg", delete=False) as tmp:
        tmp_path = tmp.name

    try:
        result = subprocess.run(
            [
                "libcamera-still",
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

        if result.returncode != 0:
            error_msg = result.stderr.decode("utf-8", errors="replace").strip()
            return jsonify({
                "status":  "error",
                "message": f"Camera busy or unavailable: {error_msg}",
            }), 500

        with open(tmp_path, "rb") as f:
            raw = f.read()

        b64 = base64.b64encode(raw).decode("utf-8")
        data_uri = f"data:image/jpeg;base64,{b64}"

        return jsonify({
            "status":      "ok",
            "image":       data_uri,
            "width":       SNAP_WIDTH,
            "height":      SNAP_HEIGHT,
            "captured_at": datetime.now(timezone.utc).isoformat(),
            "source":      "libcamera_still"
        })

    except subprocess.TimeoutExpired:
        return jsonify({
            "status":  "error",
            "message": "libcamera-still timed out (>15 s).",
        }), 504

    except Exception as e:
        return jsonify({
            "status":  "error",
            "message": str(e),
        }), 500

    finally:
        if os.path.exists(tmp_path):
            os.unlink(tmp_path)


@app.route("/health", methods=["GET"])
def health():
    return jsonify({"status": "ok", "service": "syncbin-camera"})


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5050, debug=False)
