# Database backup and restore

EcoSync keeps all its data (users, bins, scans, clearance logs) in one SQLite
file. On Railway that file is `/data/database.sqlite` on a persistent volume.
This document covers how it is backed up and how to bring it back.

## How backups are made

| When | Type shown on the page | Started by |
| --- | --- | --- |
| Every day at 02:00 (`BACKUP_DAILY_AT`, `BACKUP_TIMEZONE`) | Daily | `php artisan schedule:work`, started in `nixpacks.toml` |
| Every deploy, before migrations run | Before deploy | start command in `nixpacks.toml` |
| A SuperAdmin presses **Back up now** | Manual | Backups page |
| Right before a restore | Before restore | `php artisan db:restore` |

- Backups are written with SQLite `VACUUM INTO`, which makes a consistent copy
  while the app keeps running, then checked with `PRAGMA integrity_check`.
- Files go to `/data/backups/` (a `backups` folder next to the database, or
  `BACKUP_PATH` if set), named `ecosync-YYYY-MM-DD_HHMMSS-<type>.sqlite`.
- Retention: backups older than 7 days are deleted (`BACKUP_KEEP_DAYS`), but the
  newest 3 are always kept (`BACKUP_KEEP_MIN`).
- Every backup, download, prune and restore is written to the application log.

## Who can access backups

- Only `superadmin` accounts see **Backups** in the navigation
  (`/dashboard/backups`). Admins get a 403.
- Downloading asks for the password again (`password.confirm`), because a backup
  contains every account's password hash and 2FA secret.
- File names are checked against a strict pattern, so requests such as
  `../.env` are refused with a 404.

## Limits to know about

The backups live on the same Railway volume as the database. They protect
against bad data, mistakes and failed deploys, but not against losing the
volume itself. For an off-site copy, a SuperAdmin should download the latest
backup every week and store it somewhere safe (for example the team's Google
Drive, in a private folder).

## How to restore

### On Railway (live site)

1. Open a shell inside the running EcoSync service with the Railway CLI:
   `railway link` (once, pick the project), then `railway ssh`.
2. List the backups:

   ```bash
   php artisan db:backups
   ```

3. Restore the one you want:

   ```bash
   php artisan db:restore ecosync-2026-11-06_020000-daily.sqlite
   ```

   Type `yes` to confirm. The command first saves the current database as a
   "Before restore" backup, so the restore itself can be undone the same way.
   Afterwards it runs `php artisan migrate --force`, so an older backup is
   brought up to the current database structure (for example, backups made
   before 2026-11-06 store times in UTC and are moved to Philippine time).
4. Open https://ecosync.me and check that the data is back. Users who were
   logged in may need to log in again.

### From a downloaded backup file

If the volume itself was lost, upload the downloaded `.sqlite` file into
`/data/backups/` on the new volume (keep its original name), then run the
restore command above.

## Restore test (evidence)

Run this once per term and save screenshots of each step.

1. Note the current numbers: total scans on the Audit Trail page, and the user count.
2. On the Backups page press **Back up now**. Screenshot the new row.
3. Make a visible change: for example, clear history or simulate a few scans.
   Screenshot the changed numbers.
4. In the Railway shell run `php artisan db:restore <file from step 2>`.
   Screenshot the output.
5. Reload the dashboard. The numbers match step 1 again. Screenshot this.
6. The Backups page now also shows a "Before restore" row: the data from step 3
   was not lost either.

| Date | Backup restored | Before | After change | After restore | Done by |
| --- | --- | --- | --- | --- | --- |
| | | | | | |
