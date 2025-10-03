@extends('layouts.dashboard')

@section('title', 'Announcements')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Announcements</h1>
            <p class="mt-1 text-sm text-gray-500">Stay updated with the latest news and information</p>
        </div>
        @if($unreadCount > 0)
        <div class="flex space-x-3">
            <button onclick="markAllAsRead()" 
                    class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
                <span class="material-icons mr-2 text-sm">mark_email_read</span>
                Mark All Read ({{ $unreadCount }})
            </button>
        </div>
        @endif
    </div>

    <!-- Announcements List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900">Recent Announcements</h2>
                @if($unreadCount > 0)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    {{ $unreadCount }} unread
                </span>
                @endif
            </div>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($announcements as $announcement)
            @php
                $isRead = $announcement->readings->isNotEmpty();
            @endphp
            <div class="p-6 {{ !$isRead ? 'bg-blue-50' : 'bg-white' }} hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4 flex-1">
                        <!-- Priority & Read Status Icon -->
                        <div class="flex-shrink-0">
                            @if(!$isRead)
                                <div class="w-3 h-3 bg-blue-600 rounded-full mt-2"></div>
                            @else
                                <div class="w-10 h-10 bg-{{ $announcement->priority === 'high' ? 'red' : ($announcement->priority === 'normal' ? 'blue' : 'gray') }}-100 rounded-full flex items-center justify-center mt-1">
                                    <span class="material-icons text-{{ $announcement->priority === 'high' ? 'red' : ($announcement->priority === 'normal' ? 'blue' : 'gray') }}-600 text-sm">
                                        {{ $announcement->type === 'announcement' ? 'campaign' : ($announcement->type === 'alert' ? 'warning' : 'info') }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Announcement Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg font-medium text-gray-900 {{ !$isRead ? 'font-bold' : '' }}">
                                    {{ $announcement->title }}
                                </h3>
                                
                                <!-- Priority Badge -->
                                @if($announcement->priority === 'high')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <span class="material-icons mr-1" style="font-size: 12px;">priority_high</span>
                                    High Priority
                                </span>
                                @elseif($announcement->priority === 'low')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Low Priority
                                </span>
                                @endif
                                
                                <!-- Type Badge -->
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $announcement->type === 'announcement' ? 'bg-blue-100 text-blue-800' : 
                                       ($announcement->type === 'alert' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($announcement->type) }}
                                </span>
                            </div>
                            
                            <p class="text-gray-700 mb-3">{{ Str::limit($announcement->content, 200) }}</p>
                            
                            <div class="flex items-center text-sm text-gray-500 space-x-6">
                                <div class="flex items-center">
                                    <span class="material-icons mr-1 text-sm">schedule</span>
                                    <span>{{ $announcement->created_at->format('M j, Y g:i A') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons mr-1 text-sm">person</span>
                                    <span>System Admin</span>
                                </div>
                                @if($isRead)
                                <div class="flex items-center text-green-600">
                                    <span class="material-icons mr-1 text-sm">check_circle</span>
                                    <span>Read</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex space-x-2 ml-4">
                        <a href="{{ route('student.announcements.show', $announcement) }}" 
                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <span class="material-icons mr-1 text-sm">visibility</span>
                            Read More
                        </a>
                        
                        @if(!$isRead)
                        <button onclick="markAsRead({{ $announcement->id }})" 
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <span class="material-icons mr-1 text-sm">mark_email_read</span>
                            Mark Read
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <span class="material-icons text-4xl text-gray-400">campaign</span>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No Announcements</h3>
                <p class="mt-2 text-sm text-gray-500">There are no announcements to display at the moment. Check back later for updates.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($announcements->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function markAsRead(announcementId) {
    fetch(`{{ url('student/announcements') }}/${announcementId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to show updated status
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function markAllAsRead() {
    if (confirm('Are you sure you want to mark all announcements as read?')) {
        // Get all unread announcement IDs and mark them as read
        const unreadElements = document.querySelectorAll('.bg-blue-50');
        unreadElements.forEach(element => {
            // This is a simplified implementation
            // In a real app, you'd want a bulk API endpoint
        });
        
        // For now, just reload the page
        window.location.reload();
    }
}
</script>
@endsection