@extends('layouts.admin')

@section('title', 'Forum Posts')

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Forum Posts
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Moderate and manage all forum posts
            </p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search posts..."
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

                <!-- Topic Filter -->
                <div>
                    <label for="topic_id" class="block text-sm font-medium text-gray-700 mb-1">Topic</label>
                    <select id="topic_id" 
                            name="topic_id" 
                            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Topics</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                                {{ Str::limit($topic->title, 40) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" 
                            name="status" 
                            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Posts</option>
                        <option value="helpful" {{ request('status') == 'helpful' ? 'selected' : '' }}>Helpful Posts</option>
                        <option value="popular" {{ request('status') == 'popular' ? 'selected' : '' }}>Popular Posts</option>
                        <option value="recent" {{ request('status') == 'recent' ? 'selected' : '' }}>Recent Posts</option>
                        <option value="flagged" {{ request('status') == 'flagged' ? 'selected' : '' }}>Flagged Posts</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select id="sort_by" 
                            name="sort_by" 
                            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="likes_count" {{ request('sort_by') == 'likes_count' ? 'selected' : '' }}>Most Liked</option>
                        <option value="updated_at" {{ request('sort_by') == 'updated_at' ? 'selected' : '' }}>Last Updated</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="material-icons text-sm mr-2">search</i>
                    Filter
                </button>
                <a href="{{ route('admin.forums.posts.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="material-icons text-sm mr-2">clear</i>
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Posts List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">All Posts</h3>
                <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-500">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}</span>
                    
                    @if($posts->count() > 0)
                        <button type="button" 
                                onclick="toggleBulkActions()"
                                class="text-sm text-blue-600 hover:text-blue-900">
                            Bulk Actions
                        </button>
                    @endif
                </div>
            </div>
        </div>

        @if($posts->count() > 0)
            <!-- Bulk Actions (hidden by default) -->
            <div id="bulk-actions" class="hidden px-6 py-4 bg-gray-50 border-b border-gray-200">
                <form action="{{ route('admin.forums.posts.bulk-action') }}" method="POST" class="flex items-center space-x-3">
                    @csrf
                    <select name="action" class="border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm" required>
                        <option value="">Select Action</option>
                        <option value="mark_helpful">Mark as Helpful</option>
                        <option value="unmark_helpful">Unmark as Helpful</option>
                        <option value="delete">Delete Posts</option>
                    </select>
                    <button type="submit" 
                            class="inline-flex items-center px-3 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Apply
                    </button>
                    <button type="button" 
                            onclick="selectAllPosts()"
                            class="text-sm text-gray-600 hover:text-gray-900">
                        Select All
                    </button>
                    <button type="button" 
                            onclick="deselectAllPosts()"
                            class="text-sm text-gray-600 hover:text-gray-900">
                        Deselect All
                    </button>
                </form>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($posts as $post)
                    <div class="px-6 py-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <!-- Bulk Select Checkbox -->
                                <div class="bulk-checkbox hidden">
                                    <input type="checkbox" 
                                           name="post_ids[]" 
                                           value="{{ $post->id }}"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                </div>

                                <!-- Author Avatar -->
                                <div class="h-10 w-10 bg-green-500 rounded-full flex items-center justify-center text-white font-medium">
                                    {{ strtoupper(substr($post->student->first_name, 0, 1)) }}{{ strtoupper(substr($post->student->last_name, 0, 1)) }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <!-- Post Header -->
                                    <div class="flex items-center space-x-3 mb-2">
                                        <!-- Author Name -->
                                        <h4 class="text-sm font-medium text-gray-900">
                                            {{ $post->student->first_name }} {{ $post->student->last_name }}
                                        </h4>

                                        <!-- Status Indicators -->
                                        @if($post->is_solution)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="material-icons text-xs mr-1">check_circle</i>
                                                Helpful
                                            </span>
                                        @endif

                                        <!-- Category -->
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" 
                                              style="background-color: {{ $post->topic->category->color }}20; color: {{ $post->topic->category->color }}">
                                            {{ $post->topic->category->name }}
                                        </span>

                                        <!-- Course -->
                                        @if($post->topic->course)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $post->topic->course->title }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Topic Link -->
                                    <p class="text-sm text-blue-600 hover:text-blue-800 mb-3">
                                        <a href="{{ route('admin.forums.topics.show', $post->topic) }}">
                                            <i class="material-icons text-xs mr-1">topic</i>
                                            {{ $post->topic->title }}
                                        </a>
                                    </p>

                                    <!-- Post Content -->
                                    <div class="prose max-w-none text-sm text-gray-700 mb-3">
                                        {!! Str::limit(strip_tags($post->content), 200) !!}
                                    </div>

                                    <!-- Post Meta -->
                                    <div class="flex items-center text-xs text-gray-500 space-x-4">
                                        <div class="flex items-center">
                                            <i class="material-icons text-xs mr-1">thumb_up</i>
                                            {{ $post->likes_count }} {{ Str::plural('like', $post->likes_count) }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="material-icons text-xs mr-1">schedule</i>
                                            {{ $post->created_at->diffForHumans() }}
                                        </div>
                                        @if($post->updated_at != $post->created_at)
                                            <div class="flex items-center">
                                                <i class="material-icons text-xs mr-1">edit</i>
                                                Edited {{ $post->updated_at->diffForHumans() }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-2 ml-4">
                                <a href="{{ route('admin.forums.topics.show', $post->topic) }}#post-{{ $post->id }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="material-icons text-sm mr-1">visibility</i>
                                    View
                                </a>
                                
                                <form action="{{ route('admin.forums.posts.toggle-helpful', $post) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium {{ $post->is_solution ? 'text-green-700 bg-green-50 border-green-300' : 'text-gray-700 bg-white' }} hover:bg-gray-50">
                                        <i class="material-icons text-sm mr-1">{{ $post->is_solution ? 'check_circle' : 'check_circle_outline' }}</i>
                                        {{ $post->is_solution ? 'Helpful' : 'Mark Helpful' }}
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.forums.posts.destroy', $post) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this post?')">
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
            @if($posts->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $posts->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-12 text-center">
                <i class="material-icons text-gray-400 text-6xl mb-4">chat_bubble_outline</i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Posts Found</h3>
                <p class="text-gray-500">No forum posts match your current filters.</p>
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

function selectAllPosts() {
    document.querySelectorAll('input[name="post_ids[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAllPosts() {
    document.querySelectorAll('input[name="post_ids[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}
</script>
@endsection