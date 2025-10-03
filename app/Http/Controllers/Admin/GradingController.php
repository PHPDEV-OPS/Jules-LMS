<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grading;
use App\Models\Assessment;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;

class GradingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of gradings.
     */
    public function index(Request $request)
    {
        $query = Grading::with(['assessment.course', 'student.user', 'gradedBy']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('student.user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            })->orWhereHas('assessment', function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }

        // Filter by course
        if ($request->has('course_id') && $request->course_id) {
            $query->whereHas('assessment', function($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }

        // Filter by assessment
        if ($request->has('assessment_id') && $request->assessment_id) {
            $query->where('assessment_id', $request->assessment_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by grade
        if ($request->has('grade') && $request->grade) {
            $query->where('grade', $request->grade);
        }

        $gradings = $query->orderBy('submission_date', 'desc')->paginate(15);

        $courses = Course::orderBy('title')->get();
        $assessments = Assessment::with('course')->orderBy('title')->get();
        $statuses = ['pending', 'passed', 'failed', 'in_review'];
        $grades = ['A+', 'A', 'B+', 'B', 'C+', 'C', 'F'];

        $stats = [
            'total_submissions' => Grading::count(),
            'pending_grading' => Grading::where('status', 'pending')->count(),
            'passed_submissions' => Grading::where('status', 'passed')->count(),
            'average_grade' => $this->getAverageGrade()
        ];

        return view('admin.gradings.index', compact(
            'gradings', 'courses', 'assessments', 'statuses', 'grades', 'stats'
        ));
    }

    /**
     * Show the form for creating a new grading.
     */
    public function create()
    {
        $assessments = Assessment::with('course')->active()->orderBy('title')->get();
        $students = Student::with('user')->get();

        return view('admin.gradings.create', compact('assessments', 'students'));
    }

    /**
     * Store a newly created grading.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'student_id' => 'required|exists:students,id',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'feedback' => 'nullable|string',
            'submission_date' => 'required|date'
        ]);

        // Check if grading already exists for this student-assessment combination
        $existing = Grading::where('assessment_id', $validated['assessment_id'])
                          ->where('student_id', $validated['student_id'])
                          ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'Grading already exists for this student and assessment.')
                ->withInput();
        }

        $validated['graded_by'] = auth()->id();
        $validated['graded_at'] = now();

        Grading::create($validated);

        return redirect()->route('admin.gradings.index')
            ->with('success', 'Grading created successfully!');
    }

    /**
     * Display the specified grading.
     */
    public function show(Grading $grading)
    {
        $grading->load(['assessment.course', 'student.user', 'gradedBy']);

        return view('admin.gradings.show', compact('grading'));
    }

    /**
     * Show the form for editing the specified grading.
     */
    public function edit(Grading $grading)
    {
        $assessments = Assessment::with('course')->active()->orderBy('title')->get();
        $students = Student::with('user')->get();

        return view('admin.gradings.edit', compact('grading', 'assessments', 'students'));
    }

    /**
     * Update the specified grading.
     */
    public function update(Request $request, Grading $grading)
    {
        $validated = $request->validate([
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'feedback' => 'nullable|string',
            'submission_date' => 'required|date'
        ]);

        $validated['graded_by'] = auth()->id();
        $validated['graded_at'] = now();

        $grading->update($validated);

        return redirect()->route('admin.gradings.show', $grading)
            ->with('success', 'Grading updated successfully!');
    }

    /**
     * Remove the specified grading.
     */
    public function destroy(Grading $grading)
    {
        $grading->delete();

        return redirect()->route('admin.gradings.index')
            ->with('success', 'Grading deleted successfully!');
    }

    /**
     * Bulk grade assessments
     */
    public function bulkGrade(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.marks_obtained' => 'required|numeric|min:0',
            'grades.*.feedback' => 'nullable|string'
        ]);

        $assessment = Assessment::findOrFail($request->assessment_id);
        $graded = 0;

        foreach ($request->grades as $gradeData) {
            // Check if grading already exists
            $existing = Grading::where('assessment_id', $assessment->id)
                              ->where('student_id', $gradeData['student_id'])
                              ->first();

            if (!$existing) {
                Grading::create([
                    'assessment_id' => $assessment->id,
                    'student_id' => $gradeData['student_id'],
                    'marks_obtained' => $gradeData['marks_obtained'],
                    'total_marks' => $assessment->total_marks,
                    'feedback' => $gradeData['feedback'] ?? null,
                    'submission_date' => now(),
                    'graded_by' => auth()->id(),
                    'graded_at' => now()
                ]);
                $graded++;
            }
        }

        return redirect()->route('admin.gradings.index')
            ->with('success', "Graded {$graded} submissions successfully!");
    }

    /**
     * Grade statistics for a specific assessment
     */
    public function assessmentStats(Assessment $assessment)
    {
        $gradings = $assessment->gradings()->with(['student.user'])->get();

        $stats = [
            'total_submissions' => $gradings->count(),
            'passed' => $gradings->where('status', 'passed')->count(),
            'failed' => $gradings->where('status', 'failed')->count(),
            'pending' => $gradings->where('status', 'pending')->count(),
            'average_marks' => $gradings->avg('marks_obtained'),
            'highest_marks' => $gradings->max('marks_obtained'),
            'lowest_marks' => $gradings->min('marks_obtained'),
            'grade_distribution' => $gradings->groupBy('grade')->map->count()
        ];

        return view('admin.gradings.assessment-stats', compact('assessment', 'gradings', 'stats'));
    }

    /**
     * Export grades for an assessment
     */
    public function exportGrades(Assessment $assessment)
    {
        $gradings = $assessment->gradings()
            ->with(['student.user'])
            ->orderBy('marks_obtained', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $assessment->title . '_grades.csv"'
        ];

        $callback = function() use ($gradings) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Student Name',
                'Email',
                'Marks Obtained',
                'Total Marks',
                'Percentage',
                'Grade',
                'Status',
                'Submission Date',
                'Feedback'
            ]);

            // CSV data
            foreach ($gradings as $grading) {
                fputcsv($file, [
                    $grading->student->user->name,
                    $grading->student->user->email,
                    $grading->marks_obtained,
                    $grading->total_marks,
                    number_format($grading->percentage, 2) . '%',
                    $grading->grade,
                    $grading->status,
                    $grading->submission_date->format('Y-m-d H:i'),
                    $grading->feedback
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get average grade across all assessments
     */
    private function getAverageGrade()
    {
        $averagePercentage = Grading::avg('percentage');
        
        if (!$averagePercentage) return 'N/A';

        return Grading::calculateGrade($averagePercentage);
    }
}