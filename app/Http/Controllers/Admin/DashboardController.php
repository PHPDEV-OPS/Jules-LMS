<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin() && !Auth::user()->isTutor()) {
                abort(403, 'Access denied. Admin or tutor access required.');
            }
            return $next($request);
        });
    }

    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get comprehensive dashboard statistics
        $stats = [
            'total_students' => Student::count(),
            'total_courses' => Course::count(),
            'total_enrollments' => Enrollment::count(),
            'active_enrollments' => Enrollment::where('status', 'active')->count(),
            'completed_enrollments' => Enrollment::where('status', 'completed')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_tutors' => User::where('role', 'tutor')->count(),
        ];

        // Recent enrollments
        $recentEnrollments = Enrollment::with(['student', 'course'])
            ->latest('enrolled_on')
            ->take(10)
            ->get();

        // Popular courses
        $popularCourses = Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get();

        // Recent students
        $recentStudents = Student::latest('created_at')
            ->take(5)
            ->get();

        // Monthly statistics for charts
        $monthlyStats = [
            'enrollments' => $this->getMonthlyEnrollments(),
            'students' => $this->getMonthlyStudents(),
            'courses' => $this->getMonthlyCourses(),
        ];

        // Course completion rates
        $completionRates = $this->getCourseCompletionRates();

        return view('admin.dashboard', compact(
            'user',
            'stats',
            'recentEnrollments',
            'popularCourses',
            'recentStudents',
            'monthlyStats',
            'completionRates'
        ));
    }

    /**
     * Get monthly enrollment statistics.
     */
    private function getMonthlyEnrollments()
    {
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Enrollment::whereYear('enrolled_on', $date->year)
                ->whereMonth('enrolled_on', $date->month)
                ->count();
            
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        return $monthlyData;
    }

    /**
     * Get monthly student registration statistics.
     */
    private function getMonthlyStudents()
    {
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Student::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        return $monthlyData;
    }

    /**
     * Get monthly course creation statistics.
     */
    private function getMonthlyCourses()
    {
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Course::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        return $monthlyData;
    }

    /**
     * Get course completion rates.
     */
    private function getCourseCompletionRates()
    {
        $courses = Course::withCount([
            'enrollments as total_enrollments',
            'enrollments as completed_enrollments' => function($query) {
                $query->where('status', 'completed');
            }
        ])->get();

        return $courses->map(function($course) {
            $completionRate = $course->total_enrollments > 0 
                ? round(($course->completed_enrollments / $course->total_enrollments) * 100, 1)
                : 0;
            
            return [
                'course_name' => $course->title,
                'total_enrollments' => $course->total_enrollments,
                'completed_enrollments' => $course->completed_enrollments,
                'completion_rate' => $completionRate
            ];
        })->sortByDesc('completion_rate')->take(10);
    }
}