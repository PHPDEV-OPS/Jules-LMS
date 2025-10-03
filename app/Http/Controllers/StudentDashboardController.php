<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    /**
     * Display the student dashboard with their enrollments
     */
    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        
        // Get student's enrollments with courses
        $enrollments = $student->enrollments()
            ->with('course')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get available courses for enrollment
        $availableCourses = Course::whereDoesntHave('enrollments', function($query) use ($student) {
            $query->where('student_id', $student->id);
        })->orderBy('course_name')->get();
        
        return view('student.dashboard', compact('enrollments', 'availableCourses', 'student'));
    }
    
    /**
     * Enroll in a course
     */
    public function enroll(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $student = Auth::guard('student')->user();
        
        // Check if already enrolled
        $existingEnrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $validated['course_id'])
            ->first();

        if ($existingEnrollment) {
            return back()->withErrors([
                'enrollment' => 'You are already enrolled in this course.'
            ]);
        }

        // Create enrollment
        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $validated['course_id'],
            'enrolled_on' => now(),
            'status' => 'active',
        ]);

        return back()->with('success', 'Successfully enrolled in the course!');
    }
    
    /**
     * Drop from a course
     */
    public function drop(Enrollment $enrollment)
    {
        $student = Auth::guard('student')->user();
        
        // Ensure the enrollment belongs to the authenticated student
        if ($enrollment->student_id !== $student->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Update status to dropped instead of deleting
        $enrollment->update(['status' => 'dropped']);
        
        return back()->with('success', 'Successfully dropped from the course.');
    }
    
    /**
     * View enrollment details
     */
    public function viewEnrollment(Enrollment $enrollment)
    {
        $student = Auth::guard('student')->user();
        
        // Ensure the enrollment belongs to the authenticated student
        if ($enrollment->student_id !== $student->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $enrollment->load('course');
        
        return view('student.enrollment-details', compact('enrollment', 'student'));
    }
}
