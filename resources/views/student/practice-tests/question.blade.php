@extends('layouts.dashboard')

@section('title', 'Practice Test Question')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Bar -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-xl font-bold text-gray-900">{{ $assessment->title }}</h1>
                <span class="text-sm text-gray-600">Question {{ $questionNumber }} of {{ $totalQuestions }}</span>
            </div>
            
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                     style="width: {{ ($questionNumber / $totalQuestions) * 100 }}%"></div>
            </div>
        </div>

        <!-- Question -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                        </span>
                        @if($question->points)
                            <span class="ml-2 text-sm text-gray-600">{{ $question->points }} points</span>
                        @endif
                    </div>
                    <button id="save-status" class="text-sm text-gray-500">
                        <i class="material-icons text-sm mr-1">cloud_done</i>
                        Auto-saved
                    </button>
                </div>
            </div>

            <div class="px-6 py-6">
                <form id="answer-form" data-question="{{ $questionNumber }}">
                    @csrf
                    
                    <div class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">{{ $question->question_text }}</h2>
                        
                        @if($question->question_type === 'multiple_choice')
                            <div class="space-y-3">
                                @php
                                    $options = is_array($question->options) ? $question->options : json_decode($question->options, true) ?? [];
                                @endphp
                                @foreach($options as $key => $option)
                                    <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                        <input type="radio" 
                                               name="answer" 
                                               value="{{ $key }}" 
                                               class="mt-1 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                               {{ $currentAnswer === $key ? 'checked' : '' }}>
                                        <span class="ml-3 text-gray-900">
                                            <span class="font-medium">{{ strtoupper($key) }}.</span> {{ $option }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @elseif($question->question_type === 'true_false')
                            <div class="space-y-3">
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" 
                                           name="answer" 
                                           value="true" 
                                           class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                           {{ $currentAnswer === 'true' ? 'checked' : '' }}>
                                    <span class="ml-3 text-gray-900 font-medium">True</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" 
                                           name="answer" 
                                           value="false" 
                                           class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                           {{ $currentAnswer === 'false' ? 'checked' : '' }}>
                                    <span class="ml-3 text-gray-900 font-medium">False</span>
                                </label>
                            </div>
                        @elseif($question->question_type === 'short_answer')
                            <textarea name="answer" 
                                      rows="4" 
                                      class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Enter your answer here...">{{ $currentAnswer }}</textarea>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Navigation -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                <div class="flex justify-between items-center">
                    <div>
                        @if($questionNumber > 1)
                            <a href="{{ route('student.practice-tests.question', ['assessment' => $assessment, 'question' => $questionNumber - 1]) }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <i class="material-icons text-sm mr-1">chevron_left</i>
                                Previous
                            </a>
                        @endif
                    </div>

                    <div class="flex space-x-3">
                        @if($questionNumber < $totalQuestions)
                            <a href="{{ route('student.practice-tests.question', ['assessment' => $assessment, 'question' => $questionNumber + 1]) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Next
                                <i class="material-icons text-sm ml-1">chevron_right</i>
                            </a>
                        @else
                            <button type="button" 
                                    onclick="submitPracticeTest()" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                <i class="material-icons text-sm mr-1">check</i>
                                Finish Practice Test
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let saveTimeout;

// Auto-save functionality
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('answer-form');
    const inputs = form.querySelectorAll('input[name="answer"], textarea[name="answer"]');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(saveAnswer, 500);
        });
    });
});

function saveAnswer() {
    const form = document.getElementById('answer-form');
    const formData = new FormData(form);
    const questionNumber = form.dataset.question;
    
    fetch(`/student/practice-tests/{{ $assessment->id }}/question/${questionNumber}/save`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const saveStatus = document.getElementById('save-status');
        saveStatus.innerHTML = '<i class="material-icons text-sm mr-1 text-green-600">cloud_done</i><span class="text-green-600">Saved</span>';
        
        setTimeout(() => {
            saveStatus.innerHTML = '<i class="material-icons text-sm mr-1">cloud_done</i>Auto-saved';
        }, 2000);
    })
    .catch(error => {
        console.error('Save error:', error);
        const saveStatus = document.getElementById('save-status');
        saveStatus.innerHTML = '<i class="material-icons text-sm mr-1 text-red-600">cloud_off</i><span class="text-red-600">Save failed</span>';
    });
}

function submitPracticeTest() {
    if (confirm('Are you ready to finish this practice test? You can review your answers afterwards.')) {
        // Save current answer before submitting
        saveAnswer();
        
        setTimeout(() => {
            window.location.href = '{{ route("student.practice-tests.submit", $assessment) }}';
        }, 1000);
    }
}
</script>
@endsection