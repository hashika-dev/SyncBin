# EcoSync asset and access inventory (rubric A: System and Assets)

What EcoSync is made of, what each part holds or does, who may use it, and how
it is protected. Used as the base for the risk work in the other rubric areas
(B, D, F, G, H).

- System: EcoSync smart waste sorter + web dashboard https://ecosync.me
- Owner: EcoSync student team (repository owner: hashika-dev)
- Last reviewed: 2026-11-07
- Review again: after any new hardware, endpoint, external service or role

Ratings use **High / Medium / Low** for how bad it would be if the asset lost
its **C**onfidentiality (leaked), **I**ntegrity (was changed or faked) or
**A**vailability (stopped working).

## 1. How the parts connect

```
                     Internet (HTTPS)                         Railway (Southeast Asia)
 Admin / SuperAdmin ─────────────────────────────►  ┌──────────────────────────────────┐
 (browser)                                          │ Laravel web app  (ecosync.me)     │
                                                    │  └ volume /data                   │
 Raspberry Pi 4B ──── POST /api/bins/camera-scan ─► │     ├ database.sqlite             │
  + Camera Module 3   POST /api/hardware/rpi-telemetry  └ backups/ (7 days)           │
   │  ▲                                             └──────────────────────────────────┘
   │  └── GET /snapshot (camera_server.py) ◄──────── superadmin "snapshot" button
   │
   ├─ USB serial ─► Arduino Uno (Arm)    ─► PCA9685 ─► 6-DOF arm (6 × MG996R)
   ├─ USB serial ─► Arduino Uno (Gantry) ─► TB6600  ─► NEMA 17 stepper
   └─ HTTPS ──────► AI vision APIs (Gemini, Groq, Mistral, OpenRouter, xAI)

 ESP32 DevKitC ─── POST /api/update_bins, /api/sensor/levels, /api/hardware/esp32-telemetry ─► web app
  └ 4 × HC-SR04 ultrasonic sensors (one per bin)

 Web app ─► Brevo (alert e-mail), Semaphore (alert SMS), Google reCAPTCHA (login)
```

## 2. Asset register

### 2.1 Software and data

| ID | Asset | Where | What it holds / does | C | I | A | Main threats | Current protection |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| S1 | Web application (Laravel 12) | Railway service `SyncBin` | Dashboard, reports, device endpoints | Med | High | High | Account takeover, fake device data, outdated packages | HTTPS, login + CAPTCHA + 2FA, roles, CSRF, security headers, rate limits, 0 known package advisories (`composer audit`, `npm audit`) |
| S2 | Database | `/data/database.sqlite` on the Railway volume | User accounts (name, e-mail, bcrypt password hash, 2FA secret), bins, scans, clearance logs, telemetry, sessions | High | High | High | Leak of hashes/2FA secrets, tampering, loss | Only reachable through the app; passwords hashed; encrypted session cookies; daily + pre-deploy backups |
| S3 | Database backups | `/data/backups/` | Full copies of S2 (7 days kept) | High | Med | High | Leak through download, loss with the volume | SuperAdmin only, download needs password re-entry, file-name validation, actions logged ([BACKUP_RESTORE.md](BACKUP_RESTORE.md)) |
| S4 | Application secrets | Railway Variables | `APP_KEY`, `BREVO_API_KEY`, `RECAPTCHA_SECRET_KEY`, Semaphore key, `SEED_*_PASSWORD`, `RPI_CAMERA_URL` | High | High | Med | Leak via repo, screenshots, debug pages | Not in Git (`.env` ignored), production runs with debug mode OFF (checked 2026-11-07 with `php artisan about`), branded error pages hide details |
| S5 | AI API keys | Pi `.env` in `/home/raspberrypi4/pi-offline-ai/GEMINI-AI/` | Keys for Gemini, Groq, Mistral, OpenRouter, xAI | High | Low | Med | Theft from the Pi (physical or network), quota abuse | File on the Pi only, not in Git |
| S6 | Scan photos | `storage/app/public/scans` (app container) | Images uploaded with each camera scan | Low | Low | Low | Lost on redeploy (not on the volume) | Public URL only reachable if the path is known |
| S7 | Application log | `storage/logs/laravel.log` (app container) + Railway logs | Errors, backup/restore events, device signature warnings | Med | Med | Low | Leak of details, lost on redeploy | Not public; Railway keeps deploy logs |
| S8 | Source code | GitHub `hashika-dev/SyncBin` | Code, docs, Pi scripts in `docs/rpi` | Med | High | Med | Secret committed, unreviewed change | `main` protected (PR required), GitGuardian secret scan on PRs |
| S9 | Domain + TLS | `ecosync.me` → Railway | Name users and devices connect to | Low | High | High | Domain expiry, hijack | HTTPS only, HTTPS forced in the app |
| S10 | Pi software | Raspberry Pi, service `syncbin-detector` | `trash_classifier.py`, `ai_cascade.py`, `sorter_link.py`, `camera_server.py` | Med | High | High | Tampering on the Pi, crash | Runs as a service with auto-restart |

### 2.2 Hardware (13 parts, 4 controllers)

| ID | Controller | Attached parts | Talks to | C | I | A | Main threats | Current protection |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| H1 | Raspberry Pi 4B | Pi Camera Module 3 | Web app (HTTPS), AI APIs, both Unos (USB) | Med | High | High | Fake scans sent in its name, camera feed viewed by others, SD card theft | Hardware Monitor shows live/offline status |
| H2 | Arduino Uno (Arm) | PCA9685 servo driver, 6-DOF arm (6 × MG996R) | Pi only (USB serial, `ID:ARM`) | Low | Med | High | Wrong commands if someone plugs into USB; physical injury | No network connection; commands only over USB from the Pi |
| H3 | Arduino Uno (Gantry) | TB6600 driver, NEMA 17 stepper | Pi only (USB serial, `ID:GANTRY`) | Low | Med | High | Same as H2; carriage loses position (no limit switch) | No network connection |
| H4 | ESP32 DevKitC | 4 × HC-SR04 ultrasonic sensors | Web app (Wi-Fi, HTTPS) | Low | High | High | Fake fill levels sent in its name, Wi-Fi outage | Live/stale/offline status on the dashboard |

## 3. Roles and what each may do

| Action / page | Guest | Device (no login) | Admin | SuperAdmin |
| --- | --- | --- | --- | --- |
| Login page, forgot password | ✅ | | ✅ | ✅ |
| Live Monitor, Analytics, Audit Trail | | | ✅ | ✅ |
| Empty a bin, simulate a scan, export PDF/CSV, test alert e-mail | | | ✅ | ✅ |
| Own profile, password, 2FA | | | ✅ | ✅ |
| Hardware Monitor, Pi camera snapshot | | | ❌ 403 | ✅ |
| Clear history, seed demo data, crypto demo | | | ❌ 403 | ✅ |
| Backups page, back up now, download (password re-entry) | | | ❌ 403 | ✅ |
| Post camera scans, telemetry, sensor levels (`/api/bins/camera-scan`, `/api/update_bins`, `/api/sensor/levels`, `/api/hardware/*-telemetry`) | ⚠️ anyone | ✅ | | |
| Restore a backup (`php artisan db:restore`) | | | | Team members with Railway access only |

- Public sign-up is disabled; accounts are created by the team.
- Roles are enforced on the server by `EnsureUserHasRole` (`role:superadmin`), not
  only by hiding menu items. Covered by feature tests (admin gets 403).

## 4. Personal data held

| Data | Whose | Where | Why | Kept |
| --- | --- | --- | --- | --- |
| Name, e-mail | Admin and SuperAdmin users | `users` | Login, alerts | Until the account is deleted |
| Password (bcrypt hash), 2FA secret, recovery codes | Same | `users`, `two_factor_authentications` | Authentication | Until changed/deleted |
| Operator e-mail on bin clearances | Same | `bin_clearance_logs` | Audit trail of who emptied a bin | Until history is cleared |
| IP address, browser, last activity | Same | `sessions` | Keep users logged in, log out other devices | Session lifetime (10 min idle) |
| Alert phone number | Whoever is set to receive SMS alerts | Railway Variable | SMS alerts | Until changed |
| Camera images | Waste items (may catch hands or faces in frame) | S6 | Classification record | Until redeploy |

All of the above is also inside every backup (S3). This list is the starting
point for the Data Privacy Act (RA 10173) section in rubric G.

## 5. Known gaps (to fix in the rubric area shown)

| # | Gap | Assets | Area | Plan |
| --- | --- | --- | --- | --- |
| 1 | Device endpoints accept data from anyone (no device key); a stranger can post fake scans or fill levels | S1, S2, H1, H4 | B, D | Device token on every hardware endpoint, Pi and ESP32 updated |
| 2 | 2FA code is only checked on `/dashboard`; with a password alone, other pages (e.g. `/dashboard/reports`, `/dashboard/backups`) open directly | S1, S2, S3 | B | Apply the `2fa` check to every logged-in page and action |
| 3 | Pi `camera_server.py` `/snapshot` has no authentication | H1 | B | Token between the web app and the Pi |
| 4 | Device input checks are loose (e.g. `image_url` accepted as given) | S1, S2 | D | Strict validation on all device endpoints |
| 5 | Scan photos and the app log live in the container, not on the volume, so a redeploy deletes them | S6, S7 | F | Move to `/data` or accept and document |
| 6 | Backups are on the same volume as the database | S3 | F | Weekly download to a private off-site folder ([BACKUP_RESTORE.md](BACKUP_RESTORE.md)) |
| 7 | `APP_URL` is still `syncbin.up.railway.app`, so links the app generates (password reset, e-mail verification) use the Railway address instead of `ecosync.me` | S9 | E | Set `APP_URL=https://ecosync.me` in Railway Variables |
| 8 | AI keys on the Pi are readable by anyone with the SD card or a shell on the Pi | S5 | A (accepted) | Physical security of the bin; rotate keys if the Pi is lost |
