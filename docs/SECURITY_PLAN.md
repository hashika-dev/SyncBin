# EcoSync security plan (thesis rubric A-H)

Current self-rating: 0 Yes, 7 Partly, 1 No (F). Work the steps in order,
one batch per branch and pull request. After each step, collect the evidence
listed and tick it off.

| # | Fix | Rubric | Difficulty |
| --- | --- | --- | --- |
| 1 | Production settings + real reCAPTCHA keys | C, E | Easy |
| 2 | Asset and role inventory document | A, G | Easy |
| 3 | Logout by POST only | C | Easy |
| 4 | Security headers middleware | E | Easy |
| 5 | Access-control feature tests | B, H | Easy-Medium |
| 6 | CSRF back on dashboard actions under `/api/*` | C, D | Medium |
| 7 | Login audit log (`security_logs`) + superadmin page | F, H | Medium |
| 8 | Token on the Pi camera `/snapshot` | B, G | Medium |
| 9 | Device token on hardware endpoints | B, D | Medium |
| 10 | Persistent database + daily backups | E, F | Medium-Hard |
| 11 | Incident response plan + failed-login alert | F | Medium-Hard |
| 12 | OWASP Top 10 / ASVS mapping + RA 10173 privacy notice | G | Medium-Hard |
| 13 | OWASP ZAP scan + evidence pack | H | Hard |

## Batch 1 (steps 1-4), branch `security-easy-fixes`

### Step 1: production settings (Railway Variables, not code)

- Set `APP_ENV=production`, `APP_DEBUG=false`, `SESSION_ENCRYPT=true`,
  `SESSION_SECURE_COOKIE=true`.
- `config/services.php` falls back to Google's public TEST reCAPTCHA keys, and
  `LoginRequest.php` skips verification entirely when the test site key is in
  use. Create real v2 checkbox keys for `ecosync.me` at
  google.com/recaptcha/admin and set `RECAPTCHA_SITE_KEY` / `RECAPTCHA_SECRET_KEY`.
- Code change in `app/Http/Requests/Auth/LoginRequest.php`: the `catch` around
  the siteverify call only logs and lets the login through. Add
  `$fail('Could not verify reCAPTCHA. Please try again.');` inside the catch.
- Test: real checkbox appears; login without it fails. Everyone is logged out
  once after enabling `SESSION_ENCRYPT` (expected).

### Step 2: asset and role inventory

A short document (e.g. `docs/SECURITY_INVENTORY.md`) with: roles and what each
can do (Guest, Admin, SuperAdmin); protected resources and which role may use
them (`/dashboard/hardware`, `/dashboard/history/clear`, `/api/system/seed-mock-data`
superadmin only; empty bin, reports, exports need login); assets with location,
owner and why they matter (Railway app, database, Raspberry Pi + camera, arm
and gantry Unos, ESP32 sensors, AI API keys).

### Step 3: logout by POST only

In `routes/auth.php` replace
`Route::match(['get', 'post'], 'logout', ...)` with
`Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');`.
Every logout link in `resources/views` is already a POST form (checked).
Test: Logout button works; visiting `/logout` in the address bar returns 405.

### Step 4: security headers

Create `app/Http/Middleware/SecurityHeaders.php` that sets on every response:
`X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`,
`Referrer-Policy: strict-origin-when-cross-origin`,
`Permissions-Policy: camera=(), microphone=(), geolocation=()`, and
`Strict-Transport-Security: max-age=31536000` only when
`app()->environment('production') && $request->isSecure()`.
Register it in `bootstrap/app.php` inside `withMiddleware` with
`$middleware->append(\App\Http\Middleware\SecurityHeaders::class);` right after
`trustProxies`. No view uses the browser camera or iframes (checked), so these
headers break nothing.
Test: DevTools > Network > response headers; securityheaders.com screenshot.

## Batch 2 notes (steps 5-7)

- Step 5: feature tests: guest redirected from `/dashboard`; admin gets 403 on
  `/dashboard/hardware`, `POST /api/system/seed-mock-data`,
  `DELETE /dashboard/history/clear`; superadmin allowed. Users need the 2FA
  session confirmation the `2fa` middleware expects; check how existing tests
  log in.
- Step 6: `bootstrap/app.php` exempts `api/*` from CSRF, which also exempts
  session-authenticated dashboard actions (`/api/bins/{slug}/empty`,
  `/api/bins/{slug}/scan`, `/api/system/seed-mock-data`,
  `/api/hardware/crypto-demo`, `/api/bins/test-alert-email`). Narrow the
  exemption to the device URLs only, and make sure the dashboard's fetch/axios
  calls send the CSRF token.
- Step 7: listen to `Illuminate\Auth\Events\Login`, `Failed`, `Logout`,
  `Lockout`; store user id, email tried, IP, user agent, time in a new
  `security_logs` table (reversible migration); superadmin page listing the
  latest 100.

## Evidence checklist

- [ ] Railway Variables screenshot (values hidden)
- [ ] Response headers screenshot / securityheaders.com grade
- [ ] `php artisan test` output with access-control tests passing
- [ ] Login audit page showing a failed login
- [ ] `curl` without token getting 401 from a device URL and from `/snapshot`
- [ ] Backup restore with before/after row counts
- [ ] OWASP ZAP report before and after
