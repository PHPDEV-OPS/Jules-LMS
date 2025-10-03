<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of students with pagination (10 per page).
     */
    public function index()
    {
        $students = Student::with('courses')->paginate(10);

        return StudentResource::collection($students);
    }

    /**
     * Display the specified student with their enrolled courses.
     */
    public function show($id)
    {
        $student = Student::with('courses')->findOrFail($id);

        return new StudentResource($student);
    }

    /**
     * Get courses for the authenticated student.
     */
    public function myCourses(Request $request)
    {
        $student = $request->user(); // Assumes authenticated student
        $student->load('courses');

        return new StudentResource($student);
    }
}