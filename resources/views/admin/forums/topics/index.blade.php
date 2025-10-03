@extends('layouts.admin')

@section('title', 'Forum Topics')

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Forum Topics
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Moderate and manage forum discussions
            </p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search topics..."
                           class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="category_id" 
                            name="category_id" 
                            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Course Filter -->
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                    <select id="course_id" 
                            name="course_id" 
                            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select id="sort_by" 
                            name="sort_by" 
                            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="last_activity_at" {{ request('sort_by') == 'last_activity_at' ? 'selected' : '' }}>Last Activity</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Title</option>
                        <option value="replies_count" {{ request('sort_by') == 'replies_count' ? 'selected' : '' }}>Replies</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="material-icons text-sm mr-2">search</i>
                    Filter
                </button>
                <a href="{{ route('admin.forums.topics.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="material-icons text-sm mr-2">clear</i>
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Topics List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">All Topics</h3>
                <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-500">{{ $topics->total() }} {{ Str::plural('topic', $topics->total()) }}</span>
                    
                    @if($topics->count() > 0)
                        <button type="button" 
                                onclick="toggleBulkActions()"
                                class="text-sm text-blue-600 hover:text-blue-900">
                            Bulk Actions
                        </button>
                    @endif
                </div>
            </div>
        </div>

        @if($topics->count() > 0)
            <!-- Bulk Actions (hidden by default) -->
            <div id="bulk-actions" class="hidden px-6 py-4 bg-gray-50 border-b border-gray-200">
                <form action="{{ route('admin.forums.topics.bulk-action') }}" method="POST" class="flex items-center space-x-3">
                    @csrf
                    <select name="action" class="border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm" required>
                        <option value="">Select Action</option>
                        <option value="pin">Pin Topics</option>
                        <option value="unpin">Unpin Topics</option>
                        <option value="lock">Lock Topics</option>
                        <option value="unlock">Unlock Topics</option>
                        <option value="delete">Delete Topics</option>
                    </select>
                    <button type="submit" 
                            class="inline-flex items-center px-3 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Apply
                    </button>
                    <button type="button" 
                            onclick="selectAllTopics()"
                            class="text-sm text-gray-600 hover:text-gray-900">
                        Select All
                    </button>
                    <button type="button" 
                            onclick="deselectAllTopics()"
                            class="text-sm text-gray-600 hover:text-gray-900">
                        Deselect All
                    </button>
                </form>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($topics as $topic)
                    <div class="px-6 py-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <!-- Bulk Select Checkbox -->
                                <div class="bulk-checkbox hidden">
                                    <input type="checkbox" 
                                           name="topic_ids[]" 
                                           value="{{ $topic->id }}"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                </div>

                                <div class="flex-1 min-w-0">
                                    <!-- Status Indicators -->
                                    <div class="flex items-center space-x-2 mb-2">
                                        @if($topic->is_pinned)
                                            <i class="material-icons text-yellow-500 text-sm">push_pin</i>
                                        @endif
                                        @if($topic->is_locked)
                                            <i class="material-icons text-red-500 text-sm">lock</i>
                                        @endif
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                              style="background-color: {{ $topic->category->color }}20; color: {{ $topic->category->color }}">
                                            {{ $topic->category->name }}
                                        </span>
                                        @if($topic->course)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $topic->course->title }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Topic Title -->
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">
                                        <a href="{{ route('admin.forums.topics.show', $topic) }}" class="hover:text-blue-600">
                                            {{ $topic->title }}
                                        </a>
                                    </h4>

                                    <!-- Topic Excerpt -->
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                        {{ Str::limit(strip_tags($topic->content), 150) }}
                                    </p>

                                    <!-- Topic Meta -->
                                    <div class="flex items-center text-sm text-gray-500 space-x-4">
                                        <div class="flex items-center">
                                            <i class="material-icons text-xs mr-1">person</i>
                                            {{ $topic->student->first_name }} {{ $topic->student->last_name }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="material-icons text-xs mr-1">chat</i>
                                            {{ $topic->replies_count }} {{ Str::plural('reply', $topic->replies_count) }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="material-icons text-xs mr-1">schedule</i>
                                            {{ $topic->created_at->diffForHumans() }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="material-icons text-xs mr-1">update</i>
                                            {{ $topic->last_activity_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-2 ml-4">
                                <a href="{{ route('admin.forums.topics.show', $topic) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="material-icons text-sm mr-1">visibility</i>
                                    View
                                </a>
                                
                                <form action="{{ route('admin.forums.topics.pin', $topic) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium {{ $topic->is_pinned ? 'text-yellow-700 bg-yellow-50 border-yellow-300' : 'text-gray-700 bg-white' }} hover:bg-gray-50">
                                        <i class="material-icons text-sm mr-1">{{ $topic->is_pinned ? 'push_pin' : 'push_pin' }}</i>
                                        {{ $topic->is_pinned ? 'Unpin' : 'Pin' }}
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.forums.topics.lock', $topic) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium {{ $topic->is_locked ? 'text-red-700 bg-red-50 border-red-300' : 'text-gray-700 bg-white' }} hover:bg-gray-50">
                                        <i class="material-icons text-sm mr-1">{{ $topic->is_locked ? 'lock_open' : 'lock' }}</i>
                                        {{ $topic->is_locked ? 'Unlock' : 'Lock' }}
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.forums.topics.destroy', $topic) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this topic and all its replies?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                                        <i class="material-icons text-sm mr-1">delete</i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($topics->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $topics->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-12 text-center">
                <i class="material-icons text-gray-400 text-6xl mb-4">topic</i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Topics Found</h3>
                <p class="text-gray-500">No forum topics match your current filters.</p>
            </div>
        @endif
    </div>
</div>

<script>
function toggleBulkActions() {
    const bulkActions = document.getElementById('bulk-actions');
    const checkboxes = document.querySelectorAll('.bulk-checkbox');
    
    bulkActions.classList.toggle('hidden');
    checkboxes.forEach(checkbox => {
        checkbox.classList.toggle('hidden');
    });
}

function selectAllTopics() {
    document.querySelectorAll('input[name="topic_ids[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAllTopics() {
    document.querySelectorAll('input[name="topic_ids[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}
</script>
@endsection