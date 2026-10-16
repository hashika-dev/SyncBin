#!/usr/bin/env python3
"""
SyncBin RPi Camera Snapshot Server
====================================
Runs on the Raspberry Pi 4B (Vision Engine) and serves single-frame JPEG
snapshots on demand. The Laravel dashboard POSTs to /snapshot and this
script fires libcamera-still, encodes the JPEG as base64, then returns it.

Usage:
    pip install flask
    python3 camera_server.py

Runs on: http://0.0.0.0:5050
Endpoint: POST /snapshot

Deploy tip:
    sudo nano /etc/systemd/system/syncbin-camera.service
    (See the systemd unit block at the bottom of this file)
"""

import subprocess
import base64
import tempfile
import os
import json
from datetime import datetime, timezone
from flask import Flask, jsonify

app = Flask(__name__)

# ── Config ────────────────────────────────────────────────────────────────────
SNAP_WIDTH   = 1280
SNAP_HEIGHT  = 720
SNAP_QUALITY = 85        # JPEG quality (1–100)
SNAP_TIMEOUT = 1000      # libcamera-still shutter timeout in ms (lower = faster)
# ─────────────────────────────────────────────────────────────────────────────


@app.route("/snapshot", methods=["POST"])
def snapshot():
    """Capture a single JPEG frame and return it as a base64 data URI."""
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
                "message": f"libcamera-still failed: {error_msg}",
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


# ── systemd unit (save as /etc/systemd/system/syncbin-camera.service) ─────────
# [Unit]
# Description=SyncBin RPi Camera Snapshot Server
# After=network.target
#
# [Service]
# ExecStart=/usr/bin/python3 /home/raspberrypi4/syncbin/camera_server.py
# WorkingDirectory=/home/raspberrypi4/syncbin
# Restart=always
# User=raspberrypi4
# Environment=PYTHONUNBUFFERED=1
#
# [Install]
# WantedBy=multi-user.target
#
# Enable:
#   sudo systemctl daemon-reload
#   sudo systemctl enable syncbin-camera
#   sudo systemctl start  syncbin-camera
