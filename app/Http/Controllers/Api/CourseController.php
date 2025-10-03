<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of courses for API.
     */
    public function index(Request $request)
    {
        $query = Course::with(['students', 'enrollments']);
        
        // Handle search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('course_name', 'like', "%{$search}%")
                  ->orWhere('course_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Handle credits filter
        if ($request->filled('credits')) {
            $credits = $request->credits;
            if ($credits >= 5) {
                $query->where('credits', '>=', 5);
            } else {
                $query->where('credits', $credits);
            }
        }
        
        $courses = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return response()->json($courses);
    }

    /**
     * Store a newly created course via API.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:10|unique:courses',
            'credits' => 'required|integer|min:1|max:6',
            'description' => 'nullable|string'
        ]);

        $course = Course::create($validated);
        
        return response()->json([
            'message' => 'Course created successfully',
            'course' => $course
        ], 201);
    }

    /**
     * Display the specified course for API.
     */
    public function show(Course $course)
    {
        $course->load(['students.enrollments', 'enrollments.student']);
        return response()->json($course);
    }

    /**
     * Update the specified course via API.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:10|unique:courses,course_code,' . $course->id,
            'credits' => 'required|integer|min:1|max:6',
            'description' => 'nullable|string'
        ]);

        $course->update($validated);
        
        return response()->json([
            'message' => 'Course updated successfully',
            'course' => $course
        ]);
    }

    /**
     * Remove the specified course via API.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        
        return response()->json([
            'message' => 'Course deleted successfully'
        ]);
    }
}