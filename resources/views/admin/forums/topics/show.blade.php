@extends('layouts.admin')

@section('title', 'Topic: ' . $topic->title)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <div>
                            <a href="{{ route('admin.forums.index') }}" class="text-gray-400 hover:text-gray-500">
                                <i class="material-icons text-sm">forum</i>
                                <span class="sr-only">Forums</span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="material-icons text-gray-300 text-sm">chevron_right</i>
                            <a href="{{ route('admin.forums.topics.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Topics
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="material-icons text-gray-300 text-sm">chevron_right</i>
                            <span class="ml-4 text-sm font-medium text-gray-500 truncate">
                                {{ Str::limit($topic->title, 30) }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
            <form action="{{ route('admin.forums.topics.pin', $topic) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium {{ $topic->is_pinned ? 'text-yellow-700 bg-yellow-50 border-yellow-300' : 'text-gray-700 bg-white' }} hover:bg-gray-50">
                    <i class="material-icons text-sm mr-2">push_pin</i>
                    {{ $topic->is_pinned ? 'Unpin' : 'Pin' }}
                </button>
            </form>
            
            <form action="{{ route('admin.forums.topics.lock', $topic) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium {{ $topic->is_locked ? 'text-red-700 bg-red-50 border-red-300' : 'text-gray-700 bg-white' }} hover:bg-gray-50">
                    <i class="material-icons text-sm mr-2">{{ $topic->is_locked ? 'lock_open' : 'lock' }}</i>
                    {{ $topic->is_locked ? 'Unlock' : 'Lock' }}
                </button>
            </form>
            
            <form action="{{ route('admin.forums.topics.destroy', $topic) }}" 
                  method="POST" 
                  class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this topic and all its replies? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                    <i class="material-icons text-sm mr-2">delete</i>
                    Delete Topic
                </button>
            </form>
        </div>
    </div>

    <!-- Topic Information -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-3">
                <!-- Status Indicators -->
                @if($topic->is_pinned)
                    <i class="material-icons text-yellow-500">push_pin</i>
                @endif
                @if($topic->is_locked)
                    <i class="material-icons text-red-500">lock</i>
                @endif
                
                <!-- Category -->
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                      style="background-color: {{ $topic->category->color }}20; color: {{ $topic->category->color }}">
                    {{ $topic->category->name }}
                </span>
                
                <!-- Course -->
                @if($topic->course)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ $topic->course->title }}
                    </span>
                @endif
            </div>

            <div class="text-sm text-gray-500">
                Created {{ $topic->created_at->format('M j, Y \a\t g:i A') }}
            </div>
        </div>

        <!-- Topic Title -->
        <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $topic->title }}</h1>

        <!-- Author Info -->
        <div class="flex items-center space-x-3 mb-6">
            <div class="h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                {{ strtoupper(substr($topic->student->first_name, 0, 1)) }}{{ strtoupper(substr($topic->student->last_name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900">
                    {{ $topic->student->first_name }} {{ $topic->student->last_name }}
                </p>
                <p class="text-xs text-gray-500">{{ $topic->student->email }}</p>
            </div>
        </div>

        <!-- Topic Content -->
        <div class="prose max-w-none mb-6">
            {!! $topic->content !!}
        </div>

        <!-- Topic Stats -->
        <div class="flex items-center space-x-6 pt-4 border-t border-gray-200">
            <div class="flex items-center text-sm text-gray-500">
                <i class="material-icons text-sm mr-1">chat</i>
                <span>{{ $topic->posts_count }} {{ Str::plural('reply', $topic->posts_count) }}</span>
            </div>
            <div class="flex items-center text-sm text-gray-500">
                <i class="material-icons text-sm mr-1">visibility</i>
                <span>{{ $topic->views_count ?? 0 }} {{ Str::plural('view', $topic->views_count ?? 0) }}</span>
            </div>
            <div class="flex items-center text-sm text-gray-500">
                <i class="material-icons text-sm mr-1">update</i>
                <span>Last activity {{ $topic->last_activity_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>

    <!-- Replies Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900">
                Replies ({{ $topic->posts_count }})
            </h2>
            
            @if($topic->posts_count > 0)
                <div class="flex items-center space-x-3">
                    <select class="text-sm border border-gray-300 rounded-md px-3 py-1" onchange="sortReplies(this.value)">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                    </select>
                    
                    <button type="button" 
                            onclick="toggleBulkReplyActions()"
                            class="text-sm text-blue-600 hover:text-blue-900">
                        Bulk Actions
                    </button>
                </div>
            @endif
        </div>

        @if($topic->posts_count > 0)
            <!-- Bulk Actions for Replies -->
            <div id="bulk-reply-actions" class="hidden bg-white shadow rounded-lg p-4">
                <form action="{{ route('admin.forums.posts.bulk-action') }}" method="POST" class="flex items-center space-x-3">
                    @csrf
                    <input type="hidden" name="topic_id" value="{{ $topic->id }}">
                    <select name="action" class="border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm" required>
                        <option value="">Select Action</option>
                        <option value="delete">Delete Replies</option>
                        <option value="mark_helpful">Mark as Helpful</option>
                        <option value="unmark_helpful">Unmark as Helpful</option>
                    </select>
                    <button type="submit" 
                            class="inline-flex items-center px-3 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Apply
                    </button>
                    <button type="button" 
                            onclick="selectAllReplies()"
                            class="text-sm text-gray-600 hover:text-gray-900">
                        Select All
                    </button>
                    <button type="button" 
                            onclick="deselectAllReplies()"
                            class="text-sm text-gray-600 hover:text-gray-900">
                        Deselect All
                    </button>
                </form>
            </div>

            <div class="space-y-4" id="replies-container">
                @foreach($posts as $post)
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4 flex-1">
                                <!-- Bulk Select Checkbox -->
                                <div class="bulk-reply-checkbox hidden">
                                    <input type="checkbox" 
                                           name="post_ids[]" 
                                           value="{{ $post->id }}"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                                </div>

                                <!-- Author Avatar -->
                                <div class="h-8 w-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                    {{ strtoupper(substr($post->student->first_name, 0, 1)) }}{{ strtoupper(substr($post->student->last_name, 0, 1)) }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <!-- Author Info -->
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $post->student->first_name }} {{ $post->student->last_name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $post->created_at->format('M j, Y \a\t g:i A') }}
                                            </p>
                                        </div>

                                        @if($post->is_solution)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="material-icons text-xs mr-1">check_circle</i>
                                                Helpful
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Post Content -->
                                    <div class="prose max-w-none text-sm">
                                        {!! $post->content !!}
                                    </div>

                                    <!-- Post Stats -->
                                    <div class="flex items-center space-x-4 mt-4 pt-3 border-t border-gray-100">
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="material-icons text-xs mr-1">thumb_up</i>
                                            <span>{{ $post->likes_count }} {{ Str::plural('like', $post->likes_count) }}</span>
                                        </div>
                                        @if($post->updated_at != $post->created_at)
                                            <div class="flex items-center text-xs text-gray-500">
                                                <i class="material-icons text-xs mr-1">edit</i>
                                                <span>Edited {{ $post->updated_at->diffForHumans() }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-2 ml-4">
                                <form action="{{ route('admin.forums.posts.toggle-helpful', $post) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-xs font-medium {{ $post->is_solution ? 'text-green-700 bg-green-50 border-green-300' : 'text-gray-700 bg-white' }} hover:bg-gray-50">
                                        <i class="material-icons text-xs mr-1">{{ $post->is_solution ? 'check_circle' : 'check_circle_outline' }}</i>
                                        {{ $post->is_solution ? 'Helpful' : 'Mark Helpful' }}
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.forums.posts.destroy', $post) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this reply?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-xs font-medium text-red-700 bg-white hover:bg-red-50">
                                        <i class="material-icons text-xs mr-1">delete</i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination for Replies -->
            @if($posts->hasPages())
                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            @endif
        @else
            <div class="bg-white shadow rounded-lg p-12 text-center">
                <i class="material-icons text-gray-400 text-6xl mb-4">chat_bubble_outline</i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Replies Yet</h3>
                <p class="text-gray-500">This topic doesn't have any replies yet.</p>
            </div>
        @endif
    </div>
</div>

<script>
function toggleBulkReplyActions() {
    const bulkActions = document.getElementById('bulk-reply-actions');
    const checkboxes = document.querySelectorAll('.bulk-reply-checkbox');
    
    bulkActions.classList.toggle('hidden');
    checkboxes.forEach(checkbox => {
        checkbox.classList.toggle('hidden');
    });
}

function selectAllReplies() {
    document.querySelectorAll('input[name="post_ids[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAllReplies() {
    document.querySelectorAll('input[name="post_ids[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}

function sortReplies(sortBy) {
    const url = new URL(window.location);
    url.searchParams.set('sort_replies', sortBy);
    window.location.href = url.toString();
}
</script>
@endsection