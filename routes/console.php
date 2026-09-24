<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('notices:notify-due', function (App\Services\StudentNotifier $notifier) {
    $count = $notifier->dispatchDueNotices();
    $this->info("Notified students about {$count} notice(s).");
})->purpose('Send student notifications for scheduled notices whose publish time has arrived');

Schedule::command('notices:notify-due')->everyFiveMinutes()->withoutOverlapping();
