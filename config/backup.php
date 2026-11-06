<?php

return [

    /*
    | Folder for database backups. Empty means "a backups folder next to the
    | database file", which on Railway is /data/backups on the volume.
    */
    'path' => env('BACKUP_PATH'),

    /* Backups older than this many days are deleted by the daily job. */
    'keep_days' => (int) env('BACKUP_KEEP_DAYS', 7),

    /* The newest N backups are never deleted, whatever their age. */
    'keep_min' => (int) env('BACKUP_KEEP_MIN', 3),

    /* Time of day (server timezone) the automatic backup runs. */
    'daily_at' => env('BACKUP_DAILY_AT', '02:00'),

    /* Timezone for daily_at, e.g. Asia/Manila. Defaults to the app timezone (UTC). */
    'timezone' => env('BACKUP_TIMEZONE', env('APP_TIMEZONE', 'UTC')),

    'log_channel' => env('BACKUP_LOG_CHANNEL', env('LOG_CHANNEL', 'stack')),

];
