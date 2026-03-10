<?php

use App\Console\Commands\FlagInactiveStudents;
use App\Console\Commands\GenerateTaskOccurrences;
use App\Console\Commands\SendTaskReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(GenerateTaskOccurrences::class)
    ->dailyAt('01:30')
    ->description('Generate recurring task occurrences.');

Schedule::command(FlagInactiveStudents::class)
    ->dailyAt('02:00')
    ->description('Flag inactive students for admin follow-up.');

Schedule::command(SendTaskReminders::class)
    ->everyFiveMinutes()
    ->description('Send scheduled reminders.');
