<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Show analytics dashboard
     */
    public function index()
    {
        try {
            $analytics = [
                'overview' => $this->getOverviewData(),
                'trends' => $this->getTrendsData(),
                'performance' => $this->getPerformanceData(),
                'demographics' => $this->getDemographicsData()
            ];

            return view('admin.analytics.index', compact('analytics'));
        } catch (\Exception $e) {
            // Fallback with basic data if complex queries fail
            $analytics = [
                'overview' => $this->getBasicOverviewData(),
                'trends' => [],
                'performance' => $this->getBasicPerformanceData(),
                'demographics' => []
            ];

            return view('admin.analytics.index', compact('analytics'));
        }
    }

    /**
     * Generate reports
     */
    public function reports(Request $request)
    {
        $reportType = $request->get('type', 'overview');
        $startDate = Carbon::parse($request->get('start_date', now()->subMonth()));
        $endDate = Carbon::parse($request->get('end_date', now()));

        $report = $this->generateReport($reportType, $startDate, $endDate);

        if ($request->get('format') === 'pdf') {
            return $this->generatePDFReport($report, $reportType, $startDate, $endDate);
        }

        return view('admin.analytics.reports', compact('report', 'reportType', 'startDate', 'endDate'));
    }

    private function getOverviewData()
    {
        return [
            'total_students' => Student::count(),
            'active_students' => Student::whereHas('enrollments', function($q) {
                $q->where('status', 'active');
            })->count(),
            'total_courses' => Course::count(),
            'active_courses' => Course::where('status', 'active')->count(),
            'total_enrollments' => Enrollment::count(),
            'active_enrollments' => Enrollment::where('status', 'active')->count(),
            'completion_rate' => $this->getOverallCompletionRate(),
            'revenue' => $this->getTotalRevenue()
        ];
    }

    private function getTrendsData()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $months[] = [
                'month' => $date->format('M Y'),
                'students' => Student::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'enrollments' => Enrollment::whereBetween('enrolled_on', [$startOfMonth, $endOfMonth])->count(),
                'completions' => Enrollment::where('status', 'completed')
                    ->whereBetween('completion_date', [$startOfMonth, $endOfMonth])->count()
            ];
        }

        return $months;
    }

    private function getPerformanceData()
    {
        return [
            'top_courses' => Course::withCount('enrollments')
                ->orderBy('enrollments_count', 'desc')
                ->take(10)
                ->get(),
            'completion_rates' => Course::withCount([
                'enrollments as total_enrollments',
                'enrollments as completed_enrollments' => function($q) {
                    $q->where('status', 'completed');
                }
            ])->get()->map(function($course) {
                return [
                    'course' => $course->course_name,
                    'rate' => $course->total_enrollments > 0 
                        ? round(($course->completed_enrollments / $course->total_enrollments) * 100, 1)
                        : 0
                ];
            }),
            'average_grade' => Enrollment::whereNotNull('grade')->avg('grade'),
            'dropout_rate' => $this->getDropoutRate()
        ];
    }

    private function getBasicOverviewData()
    {
        return [
            'total_students' => Student::count(),
            'active_students' => Student::whereHas('enrollments', function($q) {
                $q->where('status', 'active');
            })->count(),
            'total_courses' => Course::count(),
            'active_courses' => Course::where('status', 'active')->count(),
            'total_enrollments' => Enrollment::count(),
            'active_enrollments' => Enrollment::where('status', 'active')->count(),
            'completion_rate' => $this->getOverallCompletionRate(),
            'revenue' => 0 // Simplified for compatibility
        ];
    }

    private function getBasicPerformanceData()
    {
        return [
            'top_courses' => Course::withCount('enrollments')
                ->orderBy('enrollments_count', 'desc')
                ->take(10)
                ->get(),
            'completion_rates' => [],
            'average_grade' => Enrollment::whereNotNull('grade')->avg('grade') ?? 0,
            'dropout_rate' => $this->getDropoutRate()
        ];
    }

    private function getDemographicsData()
    {
        return [
            'students_by_month' => collect(), // Simplified to avoid date function issues
            'enrollments_by_course_category' => Course::selectRaw('category, COUNT(enrollments.id) as enrollment_count')
                ->leftJoin('enrollments', 'courses.id', '=', 'enrollments.course_id')
                ->groupBy('category')
                ->get()
        ];
    }

    private function getOverallCompletionRate()
    {
        $total = Enrollment::count();
        if ($total === 0) return 0;
        
        $completed = Enrollment::where('status', 'completed')->count();
        return round(($completed / $total) * 100, 1);
    }

    private function getTotalRevenue()
    {
        return Enrollment::join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->where('enrollments.status', 'active')
            ->sum('courses.price');
    }

    private function getDropoutRate()
    {
        $total = Enrollment::count();
        if ($total === 0) return 0;
        
        $dropped = Enrollment::where('status', 'dropped')->count();
        return round(($dropped / $total) * 100, 1);
    }

    private function generateReport($type, $startDate, $endDate)
    {
        switch ($type) {
            case 'enrollments':
                return $this->getEnrollmentReport($startDate, $endDate);
            case 'performance':
                return $this->getPerformanceReport($startDate, $endDate);
            case 'revenue':
                return $this->getRevenueReport($startDate, $endDate);
            default:
                return $this->getOverviewReport($startDate, $endDate);
        }
    }

    private function getEnrollmentReport($startDate, $endDate)
    {
        return [
            'period' => "{$startDate->format('M j, Y')} - {$endDate->format('M j, Y')}",
            'new_enrollments' => Enrollment::whereBetween('enrolled_on', [$startDate, $endDate])->count(),
            'completions' => Enrollment::whereBetween('completion_date', [$startDate, $endDate])->count(),
            'dropouts' => Enrollment::where('status', 'dropped')
                ->whereBetween('updated_at', [$startDate, $endDate])->count(),
            'by_course' => Course::withCount([
                'enrollments as new_enrollments' => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('enrolled_on', [$startDate, $endDate]);
                }
            ])->having('new_enrollments', '>', 0)
            ->orderBy('new_enrollments', 'desc')
            ->get()
        ];
    }

    private function getOverviewReport($startDate, $endDate)
    {
        return [
            'period' => "{$startDate->format('M j, Y')} - {$endDate->format('M j, Y')}",
            'students' => Student::whereBetween('created_at', [$startDate, $endDate])->count(),
            'courses' => Course::whereBetween('created_at', [$startDate, $endDate])->count(),
            'enrollments' => Enrollment::whereBetween('enrolled_on', [$startDate, $endDate])->count(),
            'completions' => Enrollment::whereBetween('completion_date', [$startDate, $endDate])->count(),
        ];
    }

    private function getPerformanceReport($startDate, $endDate)
    {
        // Get database driver for compatibility
        $driver = config('database.default');
        $connection = config("database.connections.{$driver}.driver");
        
        // Use appropriate date difference function
        if ($connection === 'sqlite') {
            $dateDiffFunction = "AVG(julianday(completion_date) - julianday(enrolled_on))";
        } else {
            $dateDiffFunction = "AVG(DATEDIFF(completion_date, enrolled_on))";
        }
        
        try {
            $avgCompletionTime = Enrollment::whereNotNull('completion_date')
                ->whereBetween('completion_date', [$startDate, $endDate])
                ->selectRaw("{$dateDiffFunction} as avg_days")
                ->value('avg_days');
            
            $gradeDistribution = Enrollment::whereNotNull('grade')
                ->whereBetween('completion_date', [$startDate, $endDate])
                ->selectRaw('
                    COUNT(CASE WHEN grade >= 90 THEN 1 END) as grade_a,
                    COUNT(CASE WHEN grade >= 80 AND grade < 90 THEN 1 END) as grade_b,
                    COUNT(CASE WHEN grade >= 70 AND grade < 80 THEN 1 END) as grade_c,
                    COUNT(CASE WHEN grade >= 60 AND grade < 70 THEN 1 END) as grade_d,
                    COUNT(CASE WHEN grade < 60 THEN 1 END) as grade_f
                ')->first();
        } catch (\Exception $e) {
            $avgCompletionTime = 0;
            $gradeDistribution = (object) [
                'grade_a' => 0, 'grade_b' => 0, 'grade_c' => 0, 'grade_d' => 0, 'grade_f' => 0
            ];
        }

        return [
            'period' => "{$startDate->format('M j, Y')} - {$endDate->format('M j, Y')}",
            'average_completion_time' => $avgCompletionTime,
            'grade_distribution' => $gradeDistribution
        ];
    }

    private function getRevenueReport($startDate, $endDate)
    {
        return [
            'period' => "{$startDate->format('M j, Y')} - {$endDate->format('M j, Y')}",
            'total_revenue' => Enrollment::join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->whereBetween('enrollments.enrolled_on', [$startDate, $endDate])
                ->sum('courses.price'),
            'by_course' => Course::join('enrollments', 'courses.id', '=', 'enrollments.course_id')
                ->whereBetween('enrollments.enrolled_on', [$startDate, $endDate])
                ->selectRaw('courses.course_name, courses.price, COUNT(enrollments.id) as enrollment_count, (courses.price * COUNT(enrollments.id)) as revenue')
                ->groupBy('courses.id', 'courses.course_name', 'courses.price')
                ->orderBy('revenue', 'desc')
                ->get()
        ];
    }

    /**
     * Generate PDF report (simplified version without external PDF library)
     */
    private function generatePDFReport($report, $reportType, $startDate, $endDate)
    {
        // For now, return a simple CSV download instead of PDF
        // This can be enhanced later with a PDF library like DomPDF or TCPDF
        
        $filename = "report_{$reportType}_" . now()->format('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($report, $reportType) {
            $file = fopen('php://output', 'w');
            
            // Add header
            fputcsv($file, [ucfirst($reportType) . ' Report', 'Generated on: ' . now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []); // Empty line
            
            if ($reportType === 'overview') {
                fputcsv($file, ['Metric', 'Value']);
                fputcsv($file, ['Period', $report['period'] ?? 'N/A']);
                fputcsv($file, ['New Students', $report['students'] ?? 0]);
                fputcsv($file, ['New Courses', $report['courses'] ?? 0]);
                fputcsv($file, ['New Enrollments', $report['enrollments'] ?? 0]);
                fputcsv($file, ['Completions', $report['completions'] ?? 0]);
            } elseif ($reportType === 'enrollments' && isset($report['by_course'])) {
                fputcsv($file, ['Course Name', 'New Enrollments']);
                foreach ($report['by_course'] as $course) {
                    fputcsv($file, [$course->course_name, $course->new_enrollments]);
                }
            } elseif ($reportType === 'revenue' && isset($report['by_course'])) {
                fputcsv($file, ['Course Name', 'Price', 'Enrollments', 'Revenue']);
                foreach ($report['by_course'] as $course) {
                    fputcsv($file, [$course->course_name, $course->price, $course->enrollment_count, $course->revenue]);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}