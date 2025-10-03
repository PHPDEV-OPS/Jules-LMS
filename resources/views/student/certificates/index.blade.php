@extends('layouts.dashboard')

@section('title', 'My Certificates')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Certificates</h1>
            <p class="mt-1 text-sm text-gray-500">View and download your earned certificates</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('student.courses.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons mr-2 text-sm">school</span>
                My Courses
            </a>
        </div>
    </div>

    <!-- Certificate Tabs -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button onclick="showTab('earned')" id="earned-tab"
                    class="tab-button border-green-500 text-green-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Earned Certificates ({{ $earnedCertificates->count() }})
            </button>
            <button onclick="showTab('available')" id="available-tab"
                    class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Available Certificates ({{ $availableCertificates->count() }})
            </button>
        </nav>
    </div>

    <!-- Earned Certificates Tab -->
    <div id="earned-content" class="tab-content">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Earned Certificates</h2>
            </div>
            <div class="p-6">
                @if($earnedCertificates->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($earnedCertificates as $certificate)
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-200 rounded-lg p-6 relative overflow-hidden">
                            <!-- Certificate Design Elements -->
                            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-green-400 to-blue-500 rounded-bl-full opacity-20"></div>
                            <div class="absolute bottom-0 left-0 w-16 h-16 bg-gradient-to-tr from-blue-400 to-green-500 rounded-tr-full opacity-20"></div>
                            
                            <div class="relative">
                                <!-- Certificate Icon -->
                                <div class="flex justify-center mb-4">
                                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center">
                                        <span class="material-icons text-white text-2xl">workspace_premium</span>
                                    </div>
                                </div>

                                <!-- Certificate Info -->
                                <div class="text-center mb-4">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $certificate->course->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">Certificate of Completion</p>
                                    <p class="text-xs text-gray-500">{{ $certificate->certificate_code }}</p>
                                </div>

                                <!-- Certificate Details -->
                                <div class="bg-white bg-opacity-70 rounded-lg p-4 mb-4">
                                    <div class="flex items-center justify-between text-sm mb-2">
                                        <span class="text-gray-600">Issued:</span>
                                        <span class="font-medium">{{ $certificate->issued_at->format('M j, Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm mb-2">
                                        <span class="text-gray-600">Valid Until:</span>
                                        <span class="font-medium">
                                            {{ $certificate->expires_at ? $certificate->expires_at->format('M j, Y') : 'Never' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">Status:</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="material-icons mr-1" style="font-size: 12px;">verified</span>
                                            Active
                                        </span>
                                    </div>
                                </div>

                                <!-- Certificate Actions -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('student.certificates.show', $certificate) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50">
                                        <span class="material-icons mr-1 text-sm">visibility</span>
                                        View
                                    </a>
                                    <a href="{{ route('student.certificates.download', $certificate) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        <span class="material-icons mr-1 text-sm">download</span>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($earnedCertificates->hasPages())
                    <div class="mt-6">
                        {{ $earnedCertificates->links() }}
                    </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="material-icons text-4xl text-gray-400">workspace_premium</span>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Certificates Yet</h3>
                        <p class="mt-2 text-sm text-gray-500">Complete courses to earn certificates and showcase your achievements.</p>
                        <div class="mt-6">
                            <a href="{{ route('student.courses.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <span class="material-icons mr-2 text-sm">school</span>
                                Browse Courses
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Available Certificates Tab -->
    <div id="available-content" class="tab-content hidden">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Available Certificates</h2>
                <p class="text-sm text-gray-500">Certificates ready to be issued for completed courses</p>
            </div>
            <div class="p-6">
                @if($availableCertificates->count() > 0)
                    <div class="space-y-4">
                        @foreach($availableCertificates as $certificate)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                            <span class="material-icons text-yellow-600">pending</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $certificate->course->title }}</h3>
                                        <p class="text-sm text-gray-600 mb-2">Certificate pending issuance</p>
                                        <div class="flex items-center text-sm text-gray-500 space-x-4">
                                            <div class="flex items-center">
                                                <span class="material-icons mr-1 text-sm">event</span>
                                                <span>Completed: {{ $certificate->enrollment->completed_at ? $certificate->enrollment->completed_at->format('M j, Y') : 'Recently' }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="material-icons mr-1 text-sm">grade</span>
                                                <span>Final Grade: {{ rand(85, 98) }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <span class="material-icons mr-1" style="font-size: 14px;">schedule</span>
                                        Processing
                                    </span>
                                </div>
                            </div>
                            <div class="mt-4 bg-yellow-100 rounded-lg p-3">
                                <p class="text-sm text-yellow-800">
                                    <span class="material-icons mr-2 text-sm">info</span>
                                    Your certificate is being processed and will be available for download within 2-3 business days.
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($availableCertificates->hasPages())
                    <div class="mt-6">
                        {{ $availableCertificates->appends(['available' => request('available')])->links() }}
                    </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="material-icons text-4xl text-gray-400">pending_actions</span>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Pending Certificates</h3>
                        <p class="mt-2 text-sm text-gray-500">Complete more courses to earn additional certificates.</p>
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
        button.classList.remove('border-green-500', 'text-green-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active state to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-green-500', 'text-green-600');
}

// Initialize with earned certificates tab active
document.addEventListener('DOMContentLoaded', function() {
    showTab('earned');
});
</script>
@endsection