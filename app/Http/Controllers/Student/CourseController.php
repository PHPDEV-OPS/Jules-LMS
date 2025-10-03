<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display all courses available for the student
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Get enrolled course IDs
        $enrolledCourseIds = $student->enrollments()->pluck('course_id')->toArray();
        
        // Get available courses
        $availableCourses = Course::whereNotIn('id', $enrolledCourseIds)
            ->where('is_published', true)
            ->withCount('enrollments')
            ->paginate(12);
        
        // Get enrolled courses
        $enrolledCourses = Course::whereIn('id', $enrolledCourseIds)
            ->withCount('enrollments')
            ->paginate(12, ['*'], 'enrolled');
        
        return view('student.courses.index', compact('availableCourses', 'enrolledCourses', 'student'));
    }

    /**
     * Display course details
     */
    public function show(Course $course)
    {
        $student = Auth::guard('student')->user();
        
        // Check if student is enrolled
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();
        
        $course->loadCount('enrollments');
        
        return view('student.courses.show', compact('course', 'enrollment', 'student'));
    }

    /**
     * Enroll in a course
     */
    public function enroll(Request $request, Course $course)
    {
        $student = Auth::guard('student')->user();
        
        // Check if already enrolled
        $existingEnrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()->with('error', 'You are already enrolled in this course.');
        }

        // Create enrollment
        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Successfully enrolled in ' . $course->title . '!');
    }

    /**
     * Drop from a course
     */
    public function drop(Request $request, Course $course)
    {
        $student = Auth::guard('student')->user();
        
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        $enrollment->update(['status' => 'dropped']);

        return redirect()->back()->with('success', 'Successfully dropped from ' . $course->title . '.');
    }
}