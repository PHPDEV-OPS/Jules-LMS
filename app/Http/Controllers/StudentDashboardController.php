<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    /**
     * Display the main student dashboard
     */
    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        
        // Get enrollments data
        $enrollments = $student->enrollments()->with('course')->get();
        $totalEnrollments = $enrollments->count();
        $activeEnrollments = $enrollments->where('status', 'active')->count();
        $completedEnrollments = $enrollments->where('status', 'completed')->count();
        
        // Get recent enrollments (last 10)
        $recentEnrollments = $student->enrollments()
            ->with('course')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Get available courses for enrollment
        $popularCourses = Course::whereDoesntHave('enrollments', function($query) use ($student) {
            $query->where('student_id', $student->id);
        })
        ->withCount('enrollments')
        ->orderBy('enrollments_count', 'desc')
        ->take(10)
        ->get();
        
        // Get unread notifications count
        $unreadNotifications = Notification::where('student_id', $student->id)
            ->where('is_read', false)
            ->count();
        
        // Get pending assessments (mock data for now)
        $pendingAssessments = 0; // TODO: Implement actual pending assessments logic
        
        return view('student.dashboard', compact(
            'student',
            'enrollments',
            'totalEnrollments',
            'activeEnrollments',
            'completedEnrollments',
            'recentEnrollments',
            'popularCourses',
            'unreadNotifications',
            'pendingAssessments'
        ));
    }

    /**
     * Display the analytics view (legacy method)
     */
    public function analytics()
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
        })->orderBy('title')->get();
        
        return view('student.analytics', compact('enrollments', 'availableCourses', 'student'));
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
