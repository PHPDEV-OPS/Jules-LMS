@extends('layouts.dashboard')

@section('title', 'My Courses')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Courses</h1>
            <p class="mt-1 text-sm text-gray-500">Manage your enrolled courses and discover new ones</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('courses.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons mr-2 text-sm">explore</span>
                Browse All Courses
            </a>
        </div>
    </div>

    <!-- Course Tabs -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button onclick="showTab('enrolled')" id="enrolled-tab"
                    class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                My Enrolled Courses ({{ $enrolledCourses->count() }})
            </button>
            <button onclick="showTab('available')" id="available-tab"
                    class="tab-button border-blue-500 text-blue-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Available Courses ({{ $availableCourses->count() }})
            </button>
        </nav>
    </div>

    <!-- Enrolled Courses Tab -->
    <div id="enrolled-content" class="tab-content hidden">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Enrolled Courses</h2>
            </div>
            <div class="p-6">
                @if($enrolledCourses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($enrolledCourses as $course)
                        <div class="bg-gray-50 rounded-lg overflow-hidden border border-gray-200">
                            <!-- Course Image Header -->
                            <div class="h-32 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center relative">
                                @if($course->image)
                                    <img src="{{ $course->image }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                                @else
                                    <span class="material-icons text-white text-4xl">{{ $course->fallback_icon }}</span>
                                @endif
                            </div>
                            
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $course->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-4">{{ Str::limit($course->description, 100) }}</p>
                                    
                                    <!-- Course Stats -->
                                    <div class="flex items-center text-sm text-gray-500 mb-4">
                                        <span class="material-icons mr-1 text-sm">people</span>
                                        <span>{{ $course->enrollments_count }} student{{ $course->enrollments_count !== 1 ? 's' : '' }}</span>
                                    </div>

                                    <!-- Progress Bar -->
                                    @php $progress = rand(20, 90); @endphp
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                                            <span>Progress</span>
                                            <span>{{ $progress }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('student.courses.show', $course) }}" 
                                           class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            <span class="material-icons mr-1 text-sm">play_circle_filled</span>
                                            Continue
                                        </a>
                                        <form method="POST" action="{{ route('student.courses.drop', $course) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure you want to drop this course?')"
                                                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                <span class="material-icons text-sm">close</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($enrolledCourses->hasPages())
                    <div class="mt-6">
                        {{ $enrolledCourses->appends(['enrolled' => request('enrolled')])->links() }}
                    </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <span class="material-icons text-6xl text-gray-400">school</span>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Enrolled Courses</h3>
                        <p class="mt-2 text-sm text-gray-500">You haven't enrolled in any courses yet. Browse available courses to get started.</p>
                        <div class="mt-6">
                            <button onclick="showTab('available')" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <span class="material-icons mr-2 text-sm">explore</span>
                                Browse Courses
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Available Courses Tab -->
    <div id="available-content" class="tab-content">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900">Available Courses</h2>
                    <div class="flex items-center space-x-2">
                        <input type="text" placeholder="Search courses..." 
                               class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        <button class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <span class="material-icons text-sm">search</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @if($availableCourses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($availableCourses as $course)
                        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                            <!-- Course Image -->
                            <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600 relative">
                                @if($course->image)
                                    <img src="{{ $course->image }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="material-icons text-white text-4xl">{{ $course->fallback_icon }}</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                                <div class="absolute top-3 right-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white text-gray-800">
                                        @if($course->price && $course->price > 0)
                                            ${{ number_format($course->price) }}
                                        @else
                                            Free
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $course->title }}</h3>
                                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($course->description, 120) }}</p>
                                
                                <!-- Course Stats -->
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <div class="flex items-center">
                                        <span class="material-icons mr-1 text-sm">people</span>
                                        <span>{{ $course->enrollments_count }} students</span>
                                    </div>
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="material-icons text-xs text-yellow-400">star</span>
                                        @endfor
                                        <span class="ml-1">4.8</span>
                                    </div>
                                </div>

                                <!-- Enroll Button -->
                                <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        <span class="material-icons mr-2 text-sm">add</span>
                                        Enroll Now
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($availableCourses->hasPages())
                    <div class="mt-6">
                        {{ $availableCourses->links() }}
                    </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <span class="material-icons text-6xl text-gray-400">library_books</span>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Available Courses</h3>
                        <p class="mt-2 text-sm text-gray-500">There are no courses available for enrollment at the moment.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active state to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-blue-500', 'text-blue-600');
}

// Initialize with available courses tab active
document.addEventListener('DOMContentLoaded', function() {
    showTab('available');
});
</script>
@endsection