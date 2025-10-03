<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PracticeTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Get enrolled courses with available assessments for practice
        $enrolledCourses = Enrollment::where('student_id', $student->id)
            ->where('status', 'active')
            ->with(['course' => function ($query) {
                $query->with(['assessments' => function ($assessmentQuery) {
                    $assessmentQuery->where('is_active', true)
                        ->orderBy('title');
                }]);
            }])
            ->get();

        return view('student.practice-tests.index', compact('enrolledCourses'));
    }

    public function show(Assessment $assessment)
    {
        $student = Auth::guard('student')->user();
        
        // Verify student is enrolled in the course
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $assessment->course_id)
            ->where('status', 'active')
            ->first();
            
        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }

        // Get assessment with questions
        $assessment = Assessment::with(['questions' => function ($query) {
            $query->inRandomOrder();
        }])->findOrFail($assessment->id);

        return view('student.practice-tests.show', compact('assessment'));
    }

    public function start(Assessment $assessment)
    {
        $student = Auth::guard('student')->user();
        
        // Verify enrollment
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $assessment->course_id)
            ->where('status', 'active')
            ->first();
            
        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }

        // Get shuffled questions for practice
        $questions = AssessmentQuestion::where('assessment_id', $assessment->id)
            ->inRandomOrder()
            ->get();

        if ($questions->isEmpty()) {
            return redirect()->route('student.practice-tests.show', $assessment)
                ->with('error', 'No questions available for this practice test.');
        }

        session([
            'practice_test' => [
                'assessment_id' => $assessment->id,
                'started_at' => now(),
                'questions' => $questions->pluck('id')->toArray(),
                'current_question' => 0,
                'answers' => []
            ]
        ]);

        return redirect()->route('student.practice-tests.question', ['assessment' => $assessment, 'question' => 1]);
    }

    public function question(Assessment $assessment, $questionNumber)
    {
        $practiceTest = session('practice_test');
        
        if (!$practiceTest || $practiceTest['assessment_id'] != $assessment->id) {
            return redirect()->route('student.practice-tests.show', $assessment)
                ->with('error', 'Practice test session expired. Please start again.');
        }

        $questionIndex = $questionNumber - 1;
        
        if ($questionIndex < 0 || $questionIndex >= count($practiceTest['questions'])) {
            return redirect()->route('student.practice-tests.show', $assessment)
                ->with('error', 'Invalid question number.');
        }

        $questionId = $practiceTest['questions'][$questionIndex];
        $question = AssessmentQuestion::findOrFail($questionId);
        
        $totalQuestions = count($practiceTest['questions']);
        $currentAnswer = $practiceTest['answers'][$questionId] ?? null;

        return view('student.practice-tests.question', compact(
            'assessment', 
            'question', 
            'questionNumber', 
            'totalQuestions',
            'currentAnswer'
        ));
    }

    public function saveAnswer(Request $request, Assessment $assessment, $questionNumber)
    {
        $practiceTest = session('practice_test');
        
        if (!$practiceTest || $practiceTest['assessment_id'] != $assessment->id) {
            return response()->json(['error' => 'Practice test session expired'], 400);
        }

        $questionIndex = $questionNumber - 1;
        $questionId = $practiceTest['questions'][$questionIndex];
        
        $practiceTest['answers'][$questionId] = $request->input('answer');
        session(['practice_test' => $practiceTest]);

        return response()->json(['success' => true]);
    }

    public function submit(Assessment $assessment)
    {
        $practiceTest = session('practice_test');
        
        if (!$practiceTest || $practiceTest['assessment_id'] != $assessment->id) {
            return redirect()->route('student.practice-tests.show', $assessment)
                ->with('error', 'Practice test session expired. Please start again.');
        }

        // Calculate results
        $questions = AssessmentQuestion::whereIn('id', $practiceTest['questions'])->get()->keyBy('id');
        $totalQuestions = count($questions);
        $correctAnswers = 0;
        $results = [];

        foreach ($practiceTest['answers'] as $questionId => $answer) {
            $question = $questions[$questionId];
            $isCorrect = $question->correct_answer === $answer;
            
            if ($isCorrect) {
                $correctAnswers++;
            }

            $results[] = [
                'question' => $question,
                'selected_answer' => $answer,
                'is_correct' => $isCorrect,
            ];
        }

        $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;
        $timeSpent = now()->diffInMinutes($practiceTest['started_at']);

        // Clear practice test session
        session()->forget('practice_test');

        return view('student.practice-tests.results', compact(
            'assessment',
            'results',
            'score',
            'correctAnswers',
            'totalQuestions',
            'timeSpent'
        ));
    }

    public function restart(Assessment $assessment)
    {
        session()->forget('practice_test');
        return redirect()->route('student.practice-tests.start', $assessment);
    }
}