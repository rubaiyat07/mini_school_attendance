<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AttendanceService;

class GenerateAttendanceReport extends Command
{
    protected $signature = 'attendance:generate-report {month} {class}';
    protected $description = 'Generate monthly attendance report for a class';
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        parent::__construct();
        $this->attendanceService = $attendanceService;
    }

    public function handle()
    {
        $month = (int) $this->argument('month');
        $class = $this->argument('class');

        $report = $this->attendanceService->monthlyReport($class, $month);

        if ($report->isEmpty()) {
            $this->info("No attendance records found for Class {$class}, Month {$month}.");
            return;
        }

        foreach ($report as $student => $attendances) {
            $present = $attendances->where('status', 'Present')->count();
            $total = $attendances->count();
            $this->info("{$student}: {$present}/{$total} days present");
        }

        $this->info("Attendance report generated for Class {$class}, Month {$month} successfully.");
    }
}
