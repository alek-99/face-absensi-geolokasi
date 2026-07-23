<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\KirimPengingatJendelaAbsen;
use Illuminate\Support\Facades\Schedule;

Schedule::command(KirimPengingatJendelaAbsen::class)->everyMinute();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('absensi:auto-alpha')->dailyAt('09:00');
