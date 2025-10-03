@extends('layouts.admin')

@section('title', 'Announcement Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $announcement->title }}</h1>
            <p class="mt-1 text-sm text-gray-500">
                Published {{ $announcement->published_at ? $announcement->published_at->format('M d, Y \\a\\t g:i A') : 'immediately' }}
                {{ $announcement->course ? ' • ' . $announcement->course->title : ' • General Announcement' }}
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.announcements.edit', $announcement) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <span class="material-icons text-sm mr-2">edit</span>
                Edit Announcement
            </a>
            <a href="{{ route('admin.announcements.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">arrow_back</span>
                Back to List
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="rounded-md p-4
        {{ $announcement->is_active ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
        <div class="flex">
            <div class="flex-shrink-0">
                <span class="material-icons text-{{ $announcement->is_active ? 'green' : 'gray' }}-400">
                    {{ $announcement->is_active ? 'check_circle' : 'pause_circle' }}
                </span>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-{{ $announcement->is_active ? 'green' : 'gray' }}-800">
                    {{ $announcement->is_active ? 'Active Announcement' : 'Inactive Announcement' }}
                </h3>
                <div class="mt-1 text-sm text-{{ $announcement->is_active ? 'green' : 'gray' }}-700">
                    @if($announcement->is_active)
                        This announcement is currently visible to students.
                    @else
                        This announcement is currently hidden from students.
                    @endif
                    @if($announcement->pin_to_top)
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Pinned to Top
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Announcement Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Content Section -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Content</h2>
                </div>
                <div class="px-6 py-6">
                    <div class="prose max-w-none">
                        <p class="text-gray-900 whitespace-pre-line">{{ $announcement->content }}</p>
                    </div>
                </div>
            </div>

            <!-- Reading Statistics -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Reading Statistics</h2>
                </div>
                <div class="px-6 py-6">
                    @if($announcement->readings->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $announcement->readings->count() }}</div>
                            <div class="text-sm text-gray-500">Total Reads</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $announcement->readings->unique('student_id')->count() }}</div>
                            <div class="text-sm text-gray-500">Unique Readers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">
                                {{ $announcement->readings->avg('read_duration') ? round($announcement->readings->avg('read_duration')) . 's' : 'N/A' }}
                            </div>
                            <div class="text-sm text-gray-500">Avg Read Time</div>
                        </div>
                    </div>

                    <!-- Recent Readers -->
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Recent Readers</h3>
                    <div class="space-y-2">
                        @foreach($announcement->readings->take(5) as $reading)
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-md">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-3"></span>
                                <span class="text-sm font-medium text-gray-900">{{ $reading->student->name ?? 'Unknown Student' }}</span>
                            </div>
                            <span class="text-sm text-gray-500">{{ $reading->read_at->diffForHumans() }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <span class="material-icons text-4xl text-gray-400 mb-4">visibility_off</span>
                        <p class="text-gray-500">No reading statistics available yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Details Card -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Details</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Course</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $announcement->course ? $announcement->course->title : 'All Courses' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Priority</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $announcement->priority === 'urgent' ? 'bg-red-100 text-red-800' :
                                   ($announcement->priority === 'high' ? 'bg-orange-100 text-orange-800' :
                                   ($announcement->priority === 'normal' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($announcement->priority ?? 'normal') }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $announcement->type ?? 'general')) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $announcement->created_at->format('M d, Y \\a\\t g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Published</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $announcement->published_at ? $announcement->published_at->format('M d, Y \\a\\t g:i A') : 'Immediately' }}
                        </dd>
                    </div>
                    @if($announcement->expires_at)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Expires</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $announcement->expires_at->format('M d, Y \\a\\t g:i A') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.announcements.edit', $announcement) }}" 
                       class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">edit</span>
                        Edit Announcement
                    </a>
                    
                    @if($announcement->is_active)
                    <button onclick="toggleStatus(false)" 
                            class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">pause</span>
                        Deactivate
                    </button>
                    @else
                    <button onclick="toggleStatus(true)" 
                            class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">play_arrow</span>
                        Activate
                    </button>
                    @endif

                    <button class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">content_copy</span>
                        Duplicate
                    </button>

                    <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this announcement?')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                            <span class="material-icons text-sm mr-3">delete</span>
                            Delete Announcement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleStatus(activate) {
    // This would typically make an AJAX call to toggle the status
    if (confirm(activate ? 'Activate this announcement?' : 'Deactivate this announcement?')) {
        // Redirect to edit page or make AJAX call
        window.location.href = '{{ route("admin.announcements.edit", $announcement) }}';
    }
}
</script>
@endsection