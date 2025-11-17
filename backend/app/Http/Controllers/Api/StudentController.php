<?php

namespace App\Http\Controllers\Api;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Models\ClassModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return StudentResource::collection(Student::paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:225',
            'student_id' => 'required|string|unique:students,student_id',
            'class' => 'required|string|max:10',
            'section' => 'required|string',
            'photo' => 'nullable|string',
        ]);

        $student = Student::create($validated);
        return new StudentResource($student);
    }

    public function show(Student $student)
    {
        return new StudentResource($student);
    }


    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:225',
            'student_id' => 'sometimes|required|string|unique:students,student_id,' . $student->id,
            'class' => 'sometimes|required|string|max:10',
            'section' => 'sometimes|required|string',
            'photo' => 'nullable|string',
        ]);

        $student->update($validated);
        return new StudentResource($student);
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return response()->noContent();
    }

    // Get unique classes
    public function getClasses()
    {
        $classes = ClassModel::pluck('name');
        return response()->json(['classes' => $classes]);
    }

    // Get sections for a specific class
    public function getSections(Request $request)
    {
        $request->validate([
            'class' => 'required|string',
        ]);

        $class = ClassModel::where('name', $request->class)->first();
        $sections = $class ? $class->sections : [];
        return response()->json(['sections' => $sections]);
    }
}
