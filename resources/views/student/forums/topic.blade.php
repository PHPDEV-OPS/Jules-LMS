@extends('layouts.dashboard')

@section('title', $topic->title . ' - Forums')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('student.forums.index') }}" class="text-gray-400 hover:text-gray-500">
                        <i class="material-icons text-sm">forum</i>
                        <span class="sr-only">Forums</span>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="material-icons text-gray-400 text-sm mx-2">chevron_right</i>
                        <a href="{{ route('student.forums.category', $topic->category) }}" class="text-gray-400 hover:text-gray-500">
                            {{ $topic->category->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="material-icons text-gray-400 text-sm mx-2">chevron_right</i>
                        <span class="text-sm font-medium text-gray-500 truncate">{{ $topic->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Topic Header -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-3">
                        @if($topic->is_pinned)
                            <i class="material-icons text-yellow-500 text-sm">push_pin</i>
                        @endif
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                              style="background-color: {{ $topic->category->color }}20; color: {{ $topic->category->color }}">
                            {{ $topic->category->name }}
                        </span>
                        @if($topic->course)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="material-icons text-xs mr-1">school</i>
                                {{ $topic->course->title }}
                            </span>
                        @endif
                    </div>
                    
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $topic->title }}</h1>
                    
                    <div class="prose prose-sm max-w-none">
                        {!! nl2br(e($topic->content)) !!}
                    </div>
                    
                    <div class="mt-4 flex items-center text-sm text-gray-500 space-x-4">
                        <div class="flex items-center">
                            <i class="material-icons text-xs mr-1">person</i>
                            {{ $topic->student->first_name }} {{ $topic->student->last_name }}
                        </div>
                        <div class="flex items-center">
                            <i class="material-icons text-xs mr-1">schedule</i>
                            {{ $topic->created_at->format('M j, Y g:i A') }}
                        </div>
                        <div class="flex items-center">
                            <i class="material-icons text-xs mr-1">chat</i>
                            {{ $topic->replies_count }} {{ Str::plural('reply', $topic->replies_count) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Replies -->
    @if($posts->count() > 0)
        <div class="space-y-6">
            @foreach($posts as $post)
                <div class="bg-white shadow rounded-lg" id="post-{{ $post->id }}">
                    <div class="px-6 py-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ strtoupper(substr($post->student->first_name, 0, 1)) }}{{ strtoupper(substr($post->student->last_name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $post->student->first_name }} {{ $post->student->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $post->created_at->format('M j, Y g:i A') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button onclick="likePost({{ $post->id }})" 
                                                class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600">
                                            <i class="material-icons text-sm mr-1">thumb_up</i>
                                            <span id="likes-count-{{ $post->id }}">{{ $post->likes_count ?? 0 }}</span>
                                        </button>
                                        <span class="text-gray-300">•</span>
                                        <span class="text-xs text-gray-500">#{{ $loop->iteration }}</span>
                                    </div>
                                </div>
                                
                                <div class="prose prose-sm max-w-none">
                                    {!! nl2br(e($post->content)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        @endif
    @endif

    <!-- Reply Form -->
    <div class="mt-8 bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Reply to this topic</h3>
        </div>
        <form action="{{ route('student.forums.reply', $topic) }}" method="POST" class="px-6 py-6">
            @csrf
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Your Reply
                </label>
                <textarea id="content" 
                          name="content" 
                          rows="6" 
                          class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Write your reply here..."
                          required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    <i class="material-icons text-xs mr-1">info</i>
                    Please keep discussions respectful and on-topic
                </div>
                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="window.history.back()" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="material-icons text-sm mr-2">send</i>
                        Post Reply
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function likePost(postId) {
    fetch(`{{ url('student/forums/post') }}/${postId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`likes-count-${postId}`).textContent = data.likes_count;
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endsection