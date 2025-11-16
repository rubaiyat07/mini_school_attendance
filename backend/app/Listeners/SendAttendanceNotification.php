<?php

namespace App\Listeners;

use App\Events\AttendanceRecorded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendAttendanceNotification implements ShouldQueue
{
    public function handle(AttendanceRecorded $event)
    {
        
        Log::info("Attendance recorded for student ID: " . $event->attendance->student_id);
    }
}
