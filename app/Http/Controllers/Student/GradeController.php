<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AssessmentSubmission;
use App\Models\Grading;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    /**
     * Display student grades overview
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Get all enrollments with courses
        $enrollments = $student->enrollments()
            ->with(['course', 'assessmentSubmissions.assessment'])
            ->get();
        
        // Calculate overall statistics
        $stats = [
            'totalCourses' => $enrollments->count(),
            'completedCourses' => $enrollments->where('status', 'completed')->count(),
            'averageGrade' => 0,
            'totalAssessments' => 0,
        ];
        
        // Get all assessment submissions for grade calculation
        $allSubmissions = AssessmentSubmission::where('student_id', $student->id)
            ->where('status', 'graded')
            ->get();
        
        if ($allSubmissions->count() > 0) {
            $stats['averageGrade'] = $allSubmissions->avg('score');
            $stats['totalAssessments'] = $allSubmissions->count();
        }
        
        // Get recent grades
        $recentGrades = AssessmentSubmission::where('student_id', $student->id)
            ->where('status', 'graded')
            ->with(['assessment.course'])
            ->orderBy('graded_at', 'desc')
            ->take(10)
            ->get();
        
        return view('student.grades.index', compact('enrollments', 'stats', 'recentGrades', 'student'));
    }

    /**
     * Show grades for a specific course
     */
    public function course($courseId)
    {
        $student = Auth::guard('student')->user();
        
        // Verify enrollment
        $enrollment = $student->enrollments()
            ->where('course_id', $courseId)
            ->with(['course'])
            ->firstOrFail();
        
        // Get all assessment submissions for this course
        $assessmentSubmissions = AssessmentSubmission::where('student_id', $student->id)
            ->whereHas('assessment', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->with(['assessment'])
            ->orderBy('submitted_at', 'desc')
            ->get();
        
        // Calculate course statistics
        $courseStats = [
            'totalAssessments' => $assessmentSubmissions->count(),
            'completedAssessments' => $assessmentSubmissions->where('status', 'graded')->count(),
            'averageScore' => 0,
            'highestScore' => 0,
            'lowestScore' => 0,
        ];
        
        $gradedSubmissions = $assessmentSubmissions->where('status', 'graded');
        if ($gradedSubmissions->count() > 0) {
            $courseStats['averageScore'] = $gradedSubmissions->avg('score');
            $courseStats['highestScore'] = $gradedSubmissions->max('score');
            $courseStats['lowestScore'] = $gradedSubmissions->min('score');
        }
        
        return view('student.grades.course', compact('enrollment', 'assessmentSubmissions', 'courseStats', 'student'));
    }

    /**
     * Show detailed grade report
     */
    public function report()
    {
        $student = Auth::guard('student')->user();
        
        // Get all enrollments with detailed grade information
        $enrollments = $student->enrollments()
            ->with(['course'])
            ->get();
        
        $gradeReport = [];
        $overallStats = [
            'totalPoints' => 0,
            'earnedPoints' => 0,
            'totalAssessments' => 0,
            'passedAssessments' => 0,
        ];
        
        foreach ($enrollments as $enrollment) {
            $submissions = AssessmentSubmission::where('student_id', $student->id)
                ->whereHas('assessment', function($query) use ($enrollment) {
                    $query->where('course_id', $enrollment->course_id);
                })
                ->with(['assessment'])
                ->get();
            
            $courseData = [
                'enrollment' => $enrollment,
                'submissions' => $submissions,
                'stats' => [
                    'totalAssessments' => $submissions->count(),
                    'gradedAssessments' => $submissions->where('status', 'graded')->count(),
                    'averageScore' => $submissions->where('status', 'graded')->avg('score') ?? 0,
                    'totalPoints' => $submissions->sum(function($sub) { return $sub->assessment->max_points ?? 100; }),
                    'earnedPoints' => $submissions->where('status', 'graded')->sum('score'),
                ]
            ];
            
            $gradeReport[] = $courseData;
            
            // Add to overall stats
            $overallStats['totalAssessments'] += $courseData['stats']['totalAssessments'];
            $overallStats['totalPoints'] += $courseData['stats']['totalPoints'];
            $overallStats['earnedPoints'] += $courseData['stats']['earnedPoints'];
            $overallStats['passedAssessments'] += $submissions->where('score', '>=', 70)->count();
        }
        
        return view('student.grades.report', compact('gradeReport', 'overallStats', 'student'));
    }

    /**
     * Export grades as PDF or CSV
     */
    public function export($format = 'pdf')
    {
        $student = Auth::guard('student')->user();
        
        // This would implement actual export functionality
        // For now, return a simple response
        return response()->json([
            'message' => 'Grade export functionality would be implemented here',
            'format' => $format,
            'student' => $student->name
        ]);
    }
}