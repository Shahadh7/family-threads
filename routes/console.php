<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('check:timecapsules', function () {
    $currentTime = Carbon::now('Asia/Colombo')->subSecond();
    DB::table('time_capsules')
        ->where('open_date', '<=', $currentTime)  // Ensure the open_date is less than or equal to the current time
        ->where('status', '<>', 'Opened')  // Optional: Update only capsules that are not already opened
        ->update(['status' => 'Opened']);
})->purpose('Check and update timecapsules with matching open_date_time');

app(Schedule::class)->command('check:timecapsules')->everyMinute();
