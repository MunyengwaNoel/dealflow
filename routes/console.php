<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('dealflow:documents:check-expiry')->dailyAt('08:15');
Schedule::command('dealflow:invoices:mark-overdue')->dailyAt('09:00');
Schedule::command('dealflow:deals:follow-up-reminders')->dailyAt('08:45');
