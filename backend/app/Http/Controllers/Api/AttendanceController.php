<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendance;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use App\Events\AttendanceRecorded;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    // List all attendances with pagination
    public function index()
    {
        return AttendanceResource::collection(
            Attendance::with('student')->paginate(10)
        );
    }

    // Single attendance create
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:Present,Absent,Late',
            'note' => 'nullable|string',
        ]);

        // Get student's class
        $student = \App\Models\Student::find($validated['student_id']);
        $validated['class'] = $student->class;

        // Set recorded_by to authenticated user's name
        $validated['recorded_by'] = auth()->user()->name;

        $attendance = Attendance::create($validated);

        // Fire event
        event(new AttendanceRecorded($attendance));

        return new AttendanceResource($attendance);
    }

    // Bulk attendance recording
    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:Present,Absent,Late',
            'attendances.*.note' => 'nullable|string',
        ]);

        $createdRecords = [];
        foreach ($validated['attendances'] as $attendanceData) {
            $attendanceData['date'] = $validated['date'];

            // Get student's class
            $student = \App\Models\Student::find($attendanceData['student_id']);
            $attendanceData['class'] = $student->class;

            // Set recorded_by to authenticated user's name
            $attendanceData['recorded_by'] = auth()->user()->name;

            $record = Attendance::updateOrCreate(
                ['student_id' => $attendanceData['student_id'], 'date' => $attendanceData['date']],
                $attendanceData
            );

            $createdRecords[] = $record;

            // Fire event for each attendance
            event(new AttendanceRecorded($record));
        }

        return AttendanceResource::collection($createdRecords);
    }

    // Monthly attendance report by class
    public function monthlyReport(Request $request)
    {
        $validated = $request->validate([
            'class' => 'required|string',
            'month' => 'required|date_format:m', // 01-12
        ]);

        $report = $this->attendanceService->monthlyReport($validated['class'], $validated['month']);

        return response()->json(['data' => $report]);
    }

    // Today’s attendance summary
    public function todaySummary()
    {
        $summary = $this->attendanceService->todaySummary();
        return response()->json(['data' => $summary]);
    }

    // Dashboard data
    public function dashboardData()
    {
        $data = [
            'total_students' => $this->attendanceService->getTotalStudents(),
            'overall_attendance_rate' => $this->attendanceService->getOverallAttendanceRate(),
            'attendance_rate_by_class' => $this->attendanceService->getAttendanceRateByClass(),
            'attendance_rate_by_class_section' => $this->attendanceService->getAttendanceRateByClassSection(),
        ];

        return response()->json(['data' => $data]);
    }
}
