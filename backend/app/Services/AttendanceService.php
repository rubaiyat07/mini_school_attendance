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

    // Get total students count
    public function getTotalStudents()
    {
        return Cache::remember('total_students', 3600, function () {
            return \App\Models\Student::count();
        });
    }

    // Get overall attendance rate
    public function getOverallAttendanceRate()
    {
        return Cache::remember('overall_attendance_rate', 3600, function () {
            $totalAttendances = Attendance::count();
            if ($totalAttendances === 0) return 0;

            $presentCount = Attendance::where('status', 'Present')->count();
            return round(($presentCount / $totalAttendances) * 100, 2);
        });
    }

    // Get attendance rate by class
    public function getAttendanceRateByClass()
    {
        return Cache::remember('attendance_rate_by_class', 3600, function () {
            return \App\Models\Student::select('class')
                ->distinct()
                ->get()
                ->map(function ($class) {
                    $students = \App\Models\Student::where('class', $class->class)->pluck('id');
                    $totalAttendances = Attendance::whereIn('student_id', $students)->count();
                    if ($totalAttendances === 0) {
                        return [
                            'class' => $class->class,
                            'rate' => 0
                        ];
                    }
                    $presentCount = Attendance::whereIn('student_id', $students)
                        ->where('status', 'Present')
                        ->count();
                    return [
                        'class' => $class->class,
                        'rate' => round(($presentCount / $totalAttendances) * 100, 2)
                    ];
                });
        });
    }

    // Get attendance rate by class and section
    public function getAttendanceRateByClassSection()
    {
        return Cache::remember('attendance_rate_by_class_section', 3600, function () {
            return \App\Models\Student::select('class', 'section')
                ->distinct()
                ->get()
                ->map(function ($item) {
                    $students = \App\Models\Student::where('class', $item->class)
                        ->where('section', $item->section)
                        ->pluck('id');
                    $totalAttendances = Attendance::whereIn('student_id', $students)->count();
                    if ($totalAttendances === 0) {
                        return [
                            'class' => $item->class,
                            'section' => $item->section,
                            'rate' => 0
                        ];
                    }
                    $presentCount = Attendance::whereIn('student_id', $students)
                        ->where('status', 'Present')
                        ->count();
                    return [
                        'class' => $item->class,
                        'section' => $item->section,
                        'rate' => round(($presentCount / $totalAttendances) * 100, 2)
                    ];
                });
        });
    }

}
