<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Attendance;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        // Add attendance counts
        $totalAttendances = Attendance::where('student_id', $this->id)->count();
        $presentCount = Attendance::where('student_id', $this->id)->where('status', 'Present')->count();
        $absentCount = $totalAttendances - $presentCount;

        $data['present_days'] = $presentCount;
        $data['absent_days'] = $absentCount;

        return $data;
    }
}
