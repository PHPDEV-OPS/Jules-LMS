<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentSubmission;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    /**
     * Display student's available assessments
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Get student's enrolled courses
        $enrolledCourseIds = $student->enrollments()
            ->where('status', 'active')
            ->pluck('course_id')
            ->toArray();
        
        // Get assessments for enrolled courses
        $availableAssessments = Assessment::whereIn('course_id', $enrolledCourseIds)
            ->where('is_published', true)
            ->with(['course', 'submissions' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get completed assessments
        $completedAssessments = AssessmentSubmission::where('student_id', $student->id)
            ->with(['assessment.course'])
            ->orderBy('submitted_at', 'desc')
            ->paginate(10, ['*'], 'completed');
        
        return view('student.assessments.index', compact('availableAssessments', 'completedAssessments', 'student'));
    }

    /**
     * Show assessment details
     */
    public function show(Assessment $assessment)
    {
        $student = Auth::guard('student')->user();
        
        // Check if student is enrolled in the course
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $assessment->course_id)
            ->where('status', 'active')
            ->first();
        
        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }
        
        // Check if already submitted
        $submission = AssessmentSubmission::where('student_id', $student->id)
            ->where('assessment_id', $assessment->id)
            ->first();
        
        $assessment->load(['course', 'questions']);
        
        return view('student.assessments.show', compact('assessment', 'submission', 'student'));
    }

    /**
     * Take an assessment
     */
    public function take(Assessment $assessment)
    {
        $student = Auth::guard('student')->user();
        
        // Check if student is enrolled
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $assessment->course_id)
            ->where('status', 'active')
            ->first();
        
        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }
        
        // Check if already submitted
        $existingSubmission = AssessmentSubmission::where('student_id', $student->id)
            ->where('assessment_id', $assessment->id)
            ->first();
        
        if ($existingSubmission && !$assessment->allow_multiple_attempts) {
            return redirect()->route('student.assessments.show', $assessment)
                ->with('error', 'You have already submitted this assessment.');
        }
        
        $assessment->load(['questions']);
        
        return view('student.assessments.take', compact('assessment', 'student'));
    }

    /**
     * Submit an assessment
     */
    public function submit(Request $request, Assessment $assessment)
    {
        $student = Auth::guard('student')->user();
        
        // Check if student is enrolled
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $assessment->course_id)
            ->where('status', 'active')
            ->first();
        
        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }
        
        // Validate answers
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string',
        ]);
        
        // Calculate score (simplified scoring)
        $totalQuestions = $assessment->questions->count();
        $correctAnswers = 0;
        
        foreach ($request->answers as $questionId => $answer) {
            $question = $assessment->questions->find($questionId);
            if ($question && $question->correct_answer === $answer) {
                $correctAnswers++;
            }
        }
        
        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
        
        // Create submission
        AssessmentSubmission::create([
            'student_id' => $student->id,
            'assessment_id' => $assessment->id,
            'answers' => json_encode($request->answers),
            'score' => $score,
            'submitted_at' => now(),
            'graded_at' => now(), // Auto-graded
            'status' => 'graded'
        ]);
        
        return redirect()->route('student.assessments.show', $assessment)
            ->with('success', "Assessment submitted successfully! Your score: {$score}%");
    }

    /**
     * View assessment result
     */
    public function result(AssessmentSubmission $submission)
    {
        $student = Auth::guard('student')->user();
        
        if ($submission->student_id !== $student->id) {
            abort(403, 'This submission does not belong to you.');
        }
        
        $submission->load(['assessment.course', 'assessment.questions']);
        
        return view('student.assessments.result', compact('submission', 'student'));
    }
}