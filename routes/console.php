<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Example: Clear old activity logs (older than 90 days) — runs daily
Schedule::call(function () {
    \App\Models\ActivityLog::where('created_at', '<', now()->subDays(90))->delete();
})->daily()->description('Purge old activity logs');
