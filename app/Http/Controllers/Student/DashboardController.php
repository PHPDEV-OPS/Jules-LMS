<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Show the student analytics dashboard.
     */
    public function analytics()
    {
        $student = Auth::guard('student')->user();
        
        // Get student statistics
        $totalEnrollments = $student->enrollments()->count();
        $activeEnrollments = $student->enrollments()->where('status', 'active')->count();
        $completedEnrollments = $student->enrollments()->where('status', 'completed')->count();
        $availableCourses = Course::whereNotIn('id', 
            $student->courses()->pluck('courses.id')
        )->count();

        // Get recent enrollments with course details
        $recentEnrollments = $student->enrollments()
            ->with('course')
            ->latest('enrolled_at')
            ->take(5)
            ->get();

        // Get popular courses (not enrolled)
        $popularCourses = Course::whereNotIn('id', 
            $student->courses()->pluck('courses.id')
        )
        ->withCount('enrollments')
        ->orderBy('enrollments_count', 'desc')
        ->take(6)
        ->get();

        // Calculate progress statistics
        $progressStats = [
            'this_week' => $student->enrollments()->where('enrolled_at', '>=', now()->startOfWeek())->count(),
            'this_month' => $student->enrollments()->where('enrolled_at', '>=', now()->startOfMonth())->count(),
            'completion_rate' => $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 1) : 0,
        ];

        // Mock some analytics data for demonstration
        $analyticsData = [
            'sales_by_country' => [
                ['country' => 'Completed Courses', 'sales' => $completedEnrollments, 'value' => '$0', 'bounce' => $progressStats['completion_rate'] . '%'],
                ['country' => 'Active Enrollments', 'sales' => $activeEnrollments, 'value' => '$0', 'bounce' => '85.2%'],
                ['country' => 'Available Courses', 'sales' => $availableCourses, 'value' => '$0', 'bounce' => '12.4%'],
                ['country' => 'Total Progress', 'sales' => $totalEnrollments, 'value' => '$0', 'bounce' => '98.1%'],
            ],
            'weekly_stats' => [
                'enrollments' => $progressStats['this_week'],
                'users' => 2300,
                'revenue' => 34000,
                'followers' => 91,
            ]
        ];

        return view('student.analytics', compact(
            'student',
            'totalEnrollments',
            'activeEnrollments', 
            'completedEnrollments',
            'availableCourses',
            'recentEnrollments',
            'popularCourses',
            'progressStats',
            'analyticsData'
        ));
    }

    /**
     * Handle course enrollment.
     */
    public function enroll(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $student = Auth::guard('student')->user();
        $course = Course::findOrFail($request->course_id);

        // Check if already enrolled
        $existingEnrollment = $student->enrollments()
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            if ($existingEnrollment->status === 'active') {
                return redirect()->back()->with('error', 'You are already enrolled in this course.');
            } elseif ($existingEnrollment->status === 'completed') {
                return redirect()->back()->with('error', 'You have already completed this course.');
            } else {
                // Reactivate dropped/suspended enrollment
                $existingEnrollment->update([
                    'status' => 'active',
                    'enrolled_at' => now()
                ]);
                return redirect()->back()->with('success', 'Successfully re-enrolled in ' . $course->title . '!');
            }
        }

        // Check course capacity
        if ($course->max_students && $course->enrollments()->where('status', 'active')->count() >= $course->max_students) {
            return redirect()->back()->with('error', 'This course is at maximum capacity.');
        }

        // Create enrollment
        $enrollment = $student->enrollments()->create([
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Successfully enrolled in ' . $course->title . '!');
    }

    /**
     * Handle dropping a course.
     */
    public function drop(Request $request, Enrollment $enrollment)
    {
        $student = Auth::guard('student')->user();

        // Verify ownership
        if ($enrollment->student_id !== $student->id) {
            abort(403, 'Unauthorized');
        }

        $courseName = $enrollment->course->title;
        $enrollment->update([
            'status' => 'dropped',
            'dropped_at' => now()
        ]);

        return redirect()->back()->with('success', 'You have successfully dropped from ' . $courseName . '.');
    }

    /**
     * Show enrollment details.
     */
    public function viewEnrollment(Enrollment $enrollment)
    {
        $student = Auth::guard('student')->user();

        // Verify ownership
        if ($enrollment->student_id !== $student->id) {
            abort(403, 'Unauthorized');
        }

        return view('student.enrollment-details', compact('enrollment'));
    }
}