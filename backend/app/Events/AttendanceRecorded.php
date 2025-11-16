<?php

namespace App\Events;

use App\Models\Attendance;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttendanceRecorded
{
    use Dispatchable, SerializesModels;

    public $attendance;

    public function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance;
    }
}
