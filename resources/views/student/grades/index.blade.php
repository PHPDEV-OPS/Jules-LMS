@extends('layouts.dashboard')

@section('title', 'My Grades')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Grades</h1>
            <p class="mt-1 text-sm text-gray-500">Track your academic performance across all courses</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('student.grades.report') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons mr-2 text-sm">assessment</span>
                Detailed Report
            </a>
            <button onclick="exportGrades('pdf')" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <span class="material-icons mr-2 text-sm">download</span>
                Export PDF
            </button>
        </div>
    </div>

    <!-- Grade Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Overall GPA -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white">grade</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Average Grade</dt>
                            <dd class="text-2xl font-semibold {{ $stats['averageGrade'] >= 80 ? 'text-green-600' : ($stats['averageGrade'] >= 70 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $stats['averageGrade'] > 0 ? number_format($stats['averageGrade'], 1) . '%' : 'N/A' }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Courses -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white">school</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Courses</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['totalCourses'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Courses -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white">verified</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Completed</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['completedCourses'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Assessments -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white">assignment</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Assessments</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['totalAssessments'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Grades -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Course Grades</h2>
        </div>
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Course
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Assessments
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Average Grade
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Progress
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($enrollments as $enrollment)
                    @php
                        $submissions = $enrollment->assessmentSubmissions->where('status', 'graded');
                        $averageGrade = $submissions->avg('score') ?? 0;
                        $totalAssessments = $enrollment->course->assessments()->count();
                        $completedAssessments = $submissions->count();
                        $progress = $totalAssessments > 0 ? ($completedAssessments / $totalAssessments) * 100 : 0;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <span class="material-icons text-blue-600">school</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $enrollment->course->title }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Enrolled {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('M Y') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $enrollment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($enrollment->status === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $completedAssessments }} / {{ $totalAssessments }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($averageGrade > 0)
                                <span class="text-sm font-medium {{ $averageGrade >= 80 ? 'text-green-600' : ($averageGrade >= 70 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($averageGrade, 1) }}%
                                </span>
                                <div class="text-xs text-gray-500">
                                    {{ $averageGrade >= 80 ? 'Excellent' : ($averageGrade >= 70 ? 'Good' : 'Needs Improvement') }}
                                </div>
                            @else
                                <span class="text-sm text-gray-500">No grades yet</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">{{ number_format($progress, 0) }}%</span>
                                    </div>
                                    <div class="mt-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('student.grades.course', $enrollment->course_id) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <span class="material-icons text-4xl text-gray-400 mb-2">grade</span>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Grades Yet</h3>
                                <p class="text-sm text-gray-500">Enroll in courses and complete assessments to see your grades here.</p>
                                <div class="mt-4">
                                    <a href="{{ route('student.courses.index') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <span class="material-icons mr-2 text-sm">school</span>
                                        Browse Courses
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Grades -->
    @if($recentGrades->count() > 0)
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Recent Grades</h2>
        </div>
        <div class="px-6 py-6">
            <div class="space-y-4">
                @foreach($recentGrades as $submission)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center 
                                {{ $submission->score >= 80 ? 'bg-green-100' : ($submission->score >= 70 ? 'bg-yellow-100' : 'bg-red-100') }}">
                                <span class="material-icons text-sm 
                                    {{ $submission->score >= 80 ? 'text-green-600' : ($submission->score >= 70 ? 'text-yellow-600' : 'text-red-600') }}">
                                    grade
                                </span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">{{ $submission->assessment->title }}</h4>
                            <p class="text-xs text-gray-500">{{ $submission->assessment->course->title }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm font-medium {{ $submission->score >= 80 ? 'text-green-600' : ($submission->score >= 70 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ number_format($submission->score, 1) }}%
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $submission->graded_at->format('M j, Y') }}
                            </div>
                        </div>
                        <a href="{{ route('student.assessments.result', $submission) }}" 
                           class="text-blue-600 hover:text-blue-500 text-sm">
                            View
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function exportGrades(format) {
    const url = `{{ url('student/grades/export') }}/${format}`;
    
    // Show loading state
    const button = event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<span class="material-icons mr-2 text-sm animate-spin">refresh</span>Exporting...';
    button.disabled = true;
    
    fetch(url)
        .then(response => {
            if (response.ok) {
                // Handle download
                return response.blob();
            }
            throw new Error('Export failed');
        })
        .then(blob => {
            // Create download link
            const downloadUrl = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = downloadUrl;
            a.download = `grades-report.${format}`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(downloadUrl);
        })
        .catch(error => {
            console.error('Export error:', error);
            alert('Failed to export grades. Please try again.');
        })
        .finally(() => {
            // Restore button state
            button.innerHTML = originalContent;
            button.disabled = false;
        });
}
</script>
@endsection