<?php

namespace App\Services;

use App\Models\Attendance;
use Illuminate\Support\Facades\Cache;

class AttendanceService
{
    // Monthly report by class
    public function monthlyReport(string $class, string $month)
    {
        $cacheKey = "attendance_report_{$class}_{$month}";
        
        return Cache::remember($cacheKey, 3600, function () use ($class, $month) {
            return Attendance::with('student')
                ->whereHas('student', fn($q) => $q->where('class', $class))
                ->whereMonth('date', $month)
                ->orderBy('date', 'asc')
                ->get()
                ->groupBy(fn($att) => $att->student->name);
        });
    }

    public function todaySummary()
    {
        return Cache::remember('attendance_summary_today', 60, function () {
         return Attendance::with('student')
            ->whereDate('date', now())
            ->get()
            ->groupBy('status')
            ->map(fn($group) => $group->count());
        });
    }

}
