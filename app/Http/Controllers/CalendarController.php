<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Spatie\Browsershot\Browsershot;

class CalendarController
{
    public function __invoke(Request $request)
    {
        $now = now();
        $year = $now->year;

        $calendar = collect(range(1, 12))->map(function ($monthNum) use ($year, $now) {
            $month = Carbon::create($year, $monthNum, 1);

            $blankDays = $month->dayOfWeekIso - 1;

            return [
                'name' => $month->shortMonthName,
                'blank_days' => $blankDays,
                'days' => collect(range(1, $month->daysInMonth))->map(function ($day) use ($month, $now) {
                    $date = $month->copy()->day($day);
                    return [
                        'is_today' => $date->isToday(),
                        'is_past' => $date->isPast() && !$date->isToday(),
                    ];
                })
            ];
        });

        $html = view('calendar', compact('calendar'))->render();

        $image = Browsershot::html($html);

        if (config('services.browsershot.node') && config('services.browsershot.npm')) {
            $image = $image
                ->setNodeBinary(config('services.browsershot.node'))
                ->setNpmBinary(config('services.browsershot.npm'));
        }
        $image = $image
            ->windowSize(1290, 2796) // iPhone resolution example
            ->deviceScaleFactor(3) // Important: emulates the @3x pixel density of iPhone
            ->screenshot();

        return response($image)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-cache, private');
    }
}
