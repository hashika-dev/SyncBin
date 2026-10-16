#!/usr/bin/env python3
"""
SyncBin RPi Telemetry Reporter
--------------------------------
Run this on your Raspberry Pi 4B.
It reads real hardware stats and POSTs them to your Laravel dashboard every 5 seconds.

Requirements:
    pip3 install requests psutil

Usage:
    python3 rpi_telemetry.py

Configuration:
    Edit DASHBOARD_URL below to match your PC's IP address.
"""

import time
import subprocess
import requests
import psutil
import socket
import os

# ── Configuration ────────────────────────────────────────────────────────────
DASHBOARD_URL  = "https://ecosync.me/api/hardware/rpi-telemetry"
DEVICE_ID      = "rpi-primary"
POLL_INTERVAL  = 5   # seconds between each POST
# ─────────────────────────────────────────────────────────────────────────────


def get_cpu_temp() -> float:
    """Read CPU temperature from the Pi's thermal zone (°C)."""
    try:
        with open("/sys/class/thermal/thermal_zone0/temp") as f:
            return round(int(f.read().strip()) / 1000.0, 1)
    except Exception:
        return 0.0


def get_cpu_load() -> float:
    """CPU usage percentage over 1-second interval."""
    return round(psutil.cpu_percent(interval=1), 1)


def get_supply_voltage() -> float:
    """
    Read throttled/voltage status via vcgencmd.
    Returns measured core voltage (V). Falls back to 5.0 if unavailable.
    """
    try:
        result = subprocess.run(
            ["vcgencmd", "measure_volts", "core"],
            capture_output=True, text=True, timeout=2
        )
        # Output: "volt=1.2500V"
        volt_str = result.stdout.strip().replace("volt=", "").replace("V", "")
        return round(float(volt_str), 3)
    except Exception:
        return 5.0


def get_memory() -> dict:
    """RAM usage in MB."""
    mem = psutil.virtual_memory()
    return {
        "used_mb":  round(mem.used / 1024 / 1024, 1),
        "total_mb": round(mem.total / 1024 / 1024, 1),
    }


def get_disk() -> dict:
    """Disk usage for root partition in GB."""
    disk = psutil.disk_usage("/")
    return {
        "used_gb":  round(disk.used / 1024 / 1024 / 1024, 2),
        "total_gb": round(disk.total / 1024 / 1024 / 1024, 2),
    }


def get_ip_address() -> str:
    """Local IP address of the Pi."""
    try:
        s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        s.connect(("8.8.8.8", 80))
        ip = s.getsockname()[0]
        s.close()
        return ip
    except Exception:
        return "unknown"


def get_uptime() -> str:
    """Human-readable system uptime."""
    try:
        boot_time = psutil.boot_time()
        uptime_seconds = int(time.time() - boot_time)
        days    = uptime_seconds // 86400
        hours   = (uptime_seconds % 86400) // 3600
        minutes = (uptime_seconds % 3600) // 60
        if days > 0:
            return f"{days}d {hours}h {minutes}m"
        elif hours > 0:
            return f"{hours}h {minutes}m"
        else:
            return f"{minutes}m"
    except Exception:
        return "unknown"


def send_telemetry():
    """Collect and POST all stats to the dashboard."""
    memory = get_memory()
    disk   = get_disk()

    payload = {
        "device_id":       DEVICE_ID,
        "cpu_temp":        get_cpu_temp(),
        "cpu_load":        get_cpu_load(),
        "supply_voltage":  get_supply_voltage(),
        "memory_used_mb":  memory["used_mb"],
        "memory_total_mb": memory["total_mb"],
        "disk_used_gb":    disk["used_gb"],
        "disk_total_gb":   disk["total_gb"],
        "ip_address":      get_ip_address(),
        "uptime":          get_uptime(),
    }

    try:
        response = requests.post(DASHBOARD_URL, data=payload, timeout=5)
        if response.status_code == 200:
            data = response.json()
            print(f"[OK] {data.get('message', 'Telemetry sent')} — "
                  f"Temp: {payload['cpu_temp']}°C | "
                  f"Load: {payload['cpu_load']}% | "
                  f"Volt: {payload['supply_voltage']}V | "
                  f"RAM: {payload['memory_used_mb']}/{payload['memory_total_mb']} MB")
        else:
            print(f"[WARN] Server responded with HTTP {response.status_code}: {response.text[:120]}")
    except requests.exceptions.ConnectionError:
        print(f"[ERROR] Could not connect to {DASHBOARD_URL} — is the Laravel server running?")
    except requests.exceptions.Timeout:
        print("[ERROR] Request timed out.")
    except Exception as e:
        print(f"[ERROR] {e}")


if __name__ == "__main__":
    print(f"SyncBin RPi Telemetry Reporter")
    print(f"Posting to: {DASHBOARD_URL}")
    print(f"Device ID:  {DEVICE_ID}")
    print(f"Interval:   every {POLL_INTERVAL}s")
    print(f"─" * 50)

    while True:
        send_telemetry()
        time.sleep(POLL_INTERVAL)
