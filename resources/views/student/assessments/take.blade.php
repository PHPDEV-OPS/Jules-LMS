@extends('layouts.dashboard')

@section('title', 'Take Assessment: ' . $assessment->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Assessment Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $assessment->title }}</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $assessment->course->title }}</p>
                <p class="mt-2 text-gray-700">{{ $assessment->description }}</p>
            </div>
            @if($assessment->time_limit)
            <div class="text-right">
                <div class="inline-flex items-center px-3 py-2 rounded-md bg-yellow-100 text-yellow-800">
                    <span class="material-icons mr-2 text-sm">schedule</span>
                    <span class="font-medium">{{ $assessment->time_limit }} minutes</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Time limit</p>
            </div>
            @endif
        </div>
        
        <!-- Assessment Info -->
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg">
            <div class="text-center">
                <div class="text-lg font-semibold text-gray-900">{{ $assessment->questions->count() }}</div>
                <div class="text-sm text-gray-500">Questions</div>
            </div>
            <div class="text-center">
                <div class="text-lg font-semibold text-gray-900">{{ $assessment->passing_score ?? 70 }}%</div>
                <div class="text-sm text-gray-500">Passing Score</div>
            </div>
            <div class="text-center">
                <div class="text-lg font-semibold text-gray-900">{{ $assessment->allow_multiple_attempts ? 'Yes' : 'No' }}</div>
                <div class="text-sm text-gray-500">Multiple Attempts</div>
            </div>
        </div>
    </div>

    <!-- Assessment Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <span class="material-icons text-blue-600 mr-3">info</span>
            <div>
                <h3 class="text-lg font-medium text-blue-900 mb-2">Assessment Instructions</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Read each question carefully before selecting your answer</li>
                    <li>• You can navigate between questions using the navigation buttons</li>
                    <li>• Make sure to answer all questions before submitting</li>
                    @if($assessment->time_limit)
                    <li>• You have {{ $assessment->time_limit }} minutes to complete this assessment</li>
                    @endif
                    @if(!$assessment->allow_multiple_attempts)
                    <li>• <strong>Important:</strong> You can only take this assessment once, so review your answers carefully</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Assessment Form -->
    <form id="assessment-form" method="POST" action="{{ route('student.assessments.submit', $assessment) }}">
        @csrf
        
        <!-- Questions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Assessment Questions</h2>
                <div class="mt-2">
                    <div class="flex items-center text-sm text-gray-500">
                        <span class="material-icons mr-1 text-sm">help_outline</span>
                        <span>Question <span id="current-question">1</span> of {{ $assessment->questions->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @foreach($assessment->questions as $index => $question)
                <div class="question-container {{ $index === 0 ? 'block' : 'hidden' }}" data-question="{{ $index + 1 }}">
                    <div class="mb-6">
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Question {{ $index + 1 }}
                            </h3>
                            <span class="text-sm text-gray-500">{{ $question->points ?? 1 }} point{{ ($question->points ?? 1) !== 1 ? 's' : '' }}</span>
                        </div>
                        
                        <p class="text-gray-700 mb-6">{{ $question->question_text }}</p>
                        
                        <!-- Answer Options -->
                        <div class="space-y-3">
                            @php
                                $options = is_string($question->options) ? json_decode($question->options, true) : $question->options;
                                $optionLabels = ['A', 'B', 'C', 'D', 'E'];
                            @endphp
                            
                            @if(is_array($options))
                                @foreach($options as $optionIndex => $option)
                                <label class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" 
                                           name="answers[{{ $question->id }}]" 
                                           value="{{ $optionLabels[$optionIndex] ?? $optionIndex }}"
                                           class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $optionLabels[$optionIndex] ?? $optionIndex }}. {{ $option }}
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            @else
                                <!-- Text Answer -->
                                <textarea name="answers[{{ $question->id }}]" 
                                          rows="4" 
                                          class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Enter your answer here..."></textarea>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
                
                <!-- Navigation -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <button type="button" 
                            id="prev-btn" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                        <span class="material-icons mr-2 text-sm">chevron_left</span>
                        Previous
                    </button>
                    
                    <!-- Question Navigation -->
                    <div class="flex space-x-2">
                        @for($i = 1; $i <= $assessment->questions->count(); $i++)
                        <button type="button" 
                                class="question-nav-btn w-8 h-8 rounded-full text-sm font-medium {{ $i === 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                                data-question="{{ $i }}">
                            {{ $i }}
                        </button>
                        @endfor
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="button" 
                                id="next-btn" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                            <span class="material-icons ml-2 text-sm">chevron_right</span>
                        </button>
                        
                        <button type="button" 
                                id="submit-btn" 
                                class="hidden inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <span class="material-icons mr-2 text-sm">send</span>
                            Submit Assessment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Timer (if time limit exists) -->
    @if($assessment->time_limit)
    <div class="fixed top-4 right-4 bg-white shadow-lg rounded-lg p-4 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <span class="material-icons text-yellow-600 mr-2">schedule</span>
            <div>
                <div class="text-sm font-medium text-gray-900">Time Remaining</div>
                <div id="timer" class="text-lg font-bold text-yellow-600">{{ $assessment->time_limit }}:00</div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
let currentQuestion = 1;
const totalQuestions = {{ $assessment->questions->count() }};
@if($assessment->time_limit)
let timeLeft = {{ $assessment->time_limit * 60 }}; // Convert to seconds
@endif

// Navigation functions
function showQuestion(questionNumber) {
    // Hide all questions
    document.querySelectorAll('.question-container').forEach(container => {
        container.classList.add('hidden');
    });
    
    // Show current question
    const currentContainer = document.querySelector(`[data-question="${questionNumber}"]`);
    if (currentContainer) {
        currentContainer.classList.remove('hidden');
    }
    
    // Update navigation buttons
    updateNavigation();
    
    // Update question counter
    document.getElementById('current-question').textContent = questionNumber;
    
    // Update question navigation buttons
    document.querySelectorAll('.question-nav-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    
    const activeBtn = document.querySelector(`[data-question="${questionNumber}"]`);
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-200', 'text-gray-700');
        activeBtn.classList.add('bg-blue-600', 'text-white');
    }
}

function updateNavigation() {
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');
    
    // Previous button
    prevBtn.disabled = currentQuestion === 1;
    
    // Next/Submit button
    if (currentQuestion === totalQuestions) {
        nextBtn.classList.add('hidden');
        submitBtn.classList.remove('hidden');
    } else {
        nextBtn.classList.remove('hidden');
        submitBtn.classList.add('hidden');
    }
}

// Event listeners
document.getElementById('prev-btn').addEventListener('click', () => {
    if (currentQuestion > 1) {
        currentQuestion--;
        showQuestion(currentQuestion);
    }
});

document.getElementById('next-btn').addEventListener('click', () => {
    if (currentQuestion < totalQuestions) {
        currentQuestion++;
        showQuestion(currentQuestion);
    }
});

// Question navigation buttons
document.querySelectorAll('.question-nav-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        currentQuestion = parseInt(btn.getAttribute('data-question'));
        showQuestion(currentQuestion);
    });
});

// Submit button
document.getElementById('submit-btn').addEventListener('click', () => {
    if (confirm('Are you sure you want to submit your assessment? You cannot change your answers after submission.')) {
        document.getElementById('assessment-form').submit();
    }
});

@if($assessment->time_limit)
// Timer functionality
function updateTimer() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    const display = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    
    document.getElementById('timer').textContent = display;
    
    // Change color when time is running out
    const timerElement = document.getElementById('timer');
    if (timeLeft <= 300) { // 5 minutes
        timerElement.classList.remove('text-yellow-600');
        timerElement.classList.add('text-red-600');
    }
    
    if (timeLeft <= 0) {
        alert('Time is up! Submitting assessment automatically.');
        document.getElementById('assessment-form').submit();
        return;
    }
    
    timeLeft--;
}

// Start timer
setInterval(updateTimer, 1000);
@endif

// Initialize
showQuestion(1);

// Prevent accidental navigation away
window.addEventListener('beforeunload', function (e) {
    e.preventDefault();
    e.returnValue = '';
});
</script>
@endsection