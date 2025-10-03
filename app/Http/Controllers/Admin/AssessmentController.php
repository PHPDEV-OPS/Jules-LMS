<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Course;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of assessments.
     */
    public function index(Request $request)
    {
        $query = Assessment::with('course')->withCount('submissions');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('title', 'LIKE', "%{$search}%")
                  ->orWhereHas('course', function($q) use ($search) {
                      $q->where('title', 'LIKE', "%{$search}%");
                  });
        }

        // Filter by course
        if ($request->has('course_id') && $request->course_id) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $assessments = $query->orderBy('created_at', 'desc')->paginate(15);

        $courses = Course::active()->orderBy('title')->get();
        $types = ['quiz', 'assignment', 'exam', 'project'];

        $stats = [
            'total_assessments' => Assessment::count(),
            'active_assessments' => Assessment::where('is_active', true)->count(),
            'total_submissions' => \DB::table('assessment_submissions')->count(),
            'avg_completion_rate' => $this->calculateAverageCompletionRate()
        ];

        return view('admin.assessments.index', compact('assessments', 'courses', 'types', 'stats'));
    }

    /**
     * Show the form for creating a new assessment.
     */
    public function create()
    {
        $courses = Course::active()->orderBy('title')->get();
        $types = [
            'quiz' => 'Quiz',
            'assignment' => 'Assignment',
            'exam' => 'Exam',
            'project' => 'Project'
        ];

        return view('admin.assessments.create', compact('courses', 'types'));
    }

    /**
     * Store a newly created assessment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:quiz,assignment,exam,project',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:1|lte:total_marks',
            'duration_minutes' => 'nullable|integer|min:1',
            'attempts_allowed' => 'required|integer|min:1|max:10',
            'instructions' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        Assessment::create($validated);

        return redirect()->route('admin.assessments.index')
            ->with('success', 'Assessment created successfully!');
    }

    /**
     * Display the specified assessment.
     */
    public function show(Assessment $assessment)
    {
        $assessment->load(['course', 'submissions.student', 'questions']);

        $submissionStats = [
            'total_submissions' => $assessment->submissions()->count(),
            'passed_submissions' => $assessment->submissions()->where('marks', '>=', $assessment->passing_marks)->count(),
            'average_marks' => round($assessment->submissions()->avg('marks'), 1),
            'completion_rate' => $this->getCompletionRate($assessment)
        ];

        return view('admin.assessments.show', compact('assessment', 'submissionStats'));
    }

    /**
     * Show the form for editing the specified assessment.
     */
    public function edit(Assessment $assessment)
    {
        $courses = Course::active()->orderBy('title')->get();
        $types = [
            'quiz' => 'Quiz',
            'assignment' => 'Assignment',
            'exam' => 'Exam',
            'project' => 'Project'
        ];

        return view('admin.assessments.edit', compact('assessment', 'courses', 'types'));
    }

    /**
     * Update the specified assessment.
     */
    public function update(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:quiz,assignment,exam,project',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:1|lte:total_marks',
            'duration_minutes' => 'nullable|integer|min:1',
            'attempts_allowed' => 'required|integer|min:1|max:10',
            'instructions' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $assessment->update($validated);

        return redirect()->route('admin.assessments.show', $assessment)
            ->with('success', 'Assessment updated successfully!');
    }

    /**
     * Remove the specified assessment.
     */
    public function destroy(Assessment $assessment)
    {
        if ($assessment->submissions()->count() > 0) {
            return redirect()->route('admin.assessments.index')
                ->with('error', 'Cannot delete assessment with existing submissions.');
        }

        $assessment->delete();

        return redirect()->route('admin.assessments.index')
            ->with('success', 'Assessment deleted successfully!');
    }

    /**
     * Toggle assessment status
     */
    public function toggleStatus(Assessment $assessment)
    {
        $assessment->update(['is_active' => !$assessment->is_active]);
        
        $status = $assessment->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Assessment {$status} successfully!");
    }

    /**
     * Duplicate an assessment
     */
    public function duplicate(Assessment $assessment)
    {
        $newAssessment = $assessment->replicate();
        $newAssessment->title = $assessment->title . ' (Copy)';
        $newAssessment->is_active = false;
        $newAssessment->save();

        return redirect()->route('admin.assessments.edit', $newAssessment)
            ->with('success', 'Assessment duplicated successfully! Please review and activate when ready.');
    }

    /**
     * Calculate average completion rate
     */
    private function calculateAverageCompletionRate()
    {
        $assessments = Assessment::withCount(['submissions', 'course.enrollments'])->get();
        
        if ($assessments->isEmpty()) return 0;

        $totalRate = 0;
        $count = 0;

        foreach ($assessments as $assessment) {
            if ($assessment->course && $assessment->course->enrollments_count > 0) {
                $rate = ($assessment->submissions_count / $assessment->course->enrollments_count) * 100;
                $totalRate += $rate;
                $count++;
            }
        }

        return $count > 0 ? round($totalRate / $count, 1) : 0;
    }

    /**
     * Get completion rate for specific assessment
     */
    private function getCompletionRate(Assessment $assessment)
    {
        $enrolledStudents = $assessment->course->enrollments()->count();
        $submissions = $assessment->submissions()->count();

        if ($enrolledStudents == 0) return 0;

        return round(($submissions / $enrolledStudents) * 100, 1);
    }
}