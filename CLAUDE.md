# EcoSync / SyncBin: project context for Claude Code

EcoSync is a thesis/capstone smart waste system: an AI camera classifies trash,
a robotic arm on a linear gantry drops it into one of four bins, and this
Laravel app (live at https://ecosync.me) monitors bins, scans, hardware and
reports.

## This repository (the web dashboard)

- Laravel 11, Tailwind CSS, Alpine.js, Vite. Hosted on Railway via `nixpacks.toml`
  (starts with `php artisan serve`; creates `database/database.sqlite` at boot).
- Auth: Laravel Breeze + custom 2FA (laragear/two-factor), roles `superadmin`
  and `admin` via `EnsureUserHasRole` (`role:superadmin` middleware). Public
  sign-up is disabled. Login uses Google reCAPTCHA v2 or a math CAPTCHA
  (`app/Http/Requests/Auth/LoginRequest.php`, `app/Services/CaptchaService.php`).
- Main controller: `app/Http/Controllers/BinController.php`. Routes in
  `routes/web.php` and `routes/auth.php`. Middleware aliases in `bootstrap/app.php`.
- Bin slugs used everywhere: `biodegradable`, `non-bio`, `recyclable`, `hazardous`.
- Follow `AGENT_RULES.md`: PSR-12, Form Requests, reversible migrations, Blade
  views reuse `x-app-layout` / `x-guest-layout`, logo is `public/favicon.svg`.
- Explain what you will change before editing. Work on a branch and open a
  pull request; the repo owner (hashika-dev) merges.

## Endpoints the hardware calls

| Endpoint | Caller | Auth today |
| --- | --- | --- |
| `POST /api/bins/camera-scan` | Raspberry Pi classifier (image + item + bin_slug) | none (`hardware.crypto` only checks signatures if headers are sent) |
| `POST /api/hardware/rpi-telemetry` | Pi stats | none |
| `POST /api/sensor/levels`, `POST /api/update_bins` | ESP32 ultrasonic sensors | none |
| `POST /api/hardware/rpi-snapshot` | Dashboard (superadmin) pulls a frame from the Pi's `camera_server.py` at `RPI_CAMERA_URL` | camera server itself has no auth |

## The hardware side (not in this repo, runs on the Raspberry Pi)

Folder on the Pi: `/home/raspberrypi4/pi-offline-ai/GEMINI-AI/`, venv at
`/home/raspberrypi4/pi-offline-ai/venv/`, auto-start service `syncbin-detector`.

- `trash_classifier.py`: Pi Camera Module 3, motion-triggered scans, HUD,
  uploads results to `/api/bins/camera-scan`, then calls the sorter.
- `ai_cascade.py`: vision AI cascade across Gemini, Groq, Mistral, OpenRouter,
  xAI (keys in the Pi `.env`, `.env` wins over shell variables). Fastest
  measured model goes first (`ai_speeds.json`); `python ai_cascade.py --test`
  times every model.
- `sorter_link.py`: finds two Arduino Unos by the ID they print
  (`ID:ARM`, `ID:GANTRY`) and runs each item as
  arm `PICK` -> gantry `B<n>` -> arm `DROP<n>` -> gantry `P` (back to start),
  waiting for each `DONE` before the next step.
- Arm Uno (`Arm_6DOF.ino`): 6 MG996R servos via PCA9685; commands PICK,
  DROP1-4, HOME, OPEN, J<joint> <value>, POS.
- Gantry Uno (`Gantry_NEMA17.ino`): NEMA 17 + TB6600 (common-anode, PUL- D12,
  DIR- D9, 3200 pulses/rev, 80 steps/mm), no limit switch: 0 mm is wherever the
  carriage sits at power-on (the bin 1 spot). Commands B1-B4, P, G<mm>, J<mm>,
  Z, S, I.

## Plans in progress

- Security hardening for the thesis rubric: see `docs/SECURITY_PLAN.md`.
- AI orchestration on the server (move the AI cascade from the Pi into this
  app with a superadmin AI Providers page): planned, not started.
