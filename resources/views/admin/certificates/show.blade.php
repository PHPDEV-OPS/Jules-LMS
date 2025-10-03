@extends('layouts.admin')

@section('title', 'Certificate Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Certificate Details</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $certificate->certificate_number }}</p>
        </div>
        <div class="flex space-x-3">
            <!-- Status Badge -->
            <span class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium
                {{ $certificate->status === 'issued' ? 'bg-green-100 text-green-800' : 
                   ($certificate->status === 'downloaded' ? 'bg-blue-100 text-blue-800' : 
                   ($certificate->status === 'revoked' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                <span class="w-2 h-2 rounded-full mr-2
                    {{ $certificate->status === 'issued' ? 'bg-green-600' : 
                       ($certificate->status === 'downloaded' ? 'bg-blue-600' : 
                       ($certificate->status === 'revoked' ? 'bg-red-600' : 'bg-gray-600')) }}"></span>
                {{ ucfirst($certificate->status ?? 'issued') }}
            </span>
            
            <!-- Action Buttons -->
            <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                <span class="material-icons text-sm mr-2">download</span>
                Download PDF
            </a>
            <a href="{{ route('admin.certificates.edit', $certificate) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <span class="material-icons text-sm mr-2">edit</span>
                Edit Certificate
            </a>
            <a href="{{ route('admin.certificates.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">arrow_back</span>
                Back to List
            </a>
        </div>
    </div>

    <!-- Certificate Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Certificate Information</h2>
                </div>
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Student Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->student->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Student Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->student->email ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Course</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->course->title ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Certificate Title</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->title ?? 'Certificate of Completion' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Final Grade</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($certificate->grade)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $certificate->grade >= 80 ? 'bg-green-100 text-green-800' : 
                                           ($certificate->grade >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $certificate->grade }}%
                                    </span>
                                @else
                                    <span class="text-gray-500">Not specified</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Issue Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $certificate->issue_date ? $certificate->issue_date->format('M d, Y') : 'N/A' }}
                            </dd>
                        </div>
                        @if($certificate->completion_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Completion Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->completion_date->format('M d, Y') }}</dd>
                        </div>
                        @endif
                        @if($certificate->expiry_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Expiry Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $certificate->expiry_date->format('M d, Y') }}
                                @if($certificate->expiry_date->isPast())
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Expired
                                    </span>
                                @endif
                            </dd>
                        </div>
                        @endif
                    </dl>

                    @if($certificate->custom_message)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <dt class="text-sm font-medium text-gray-500 mb-2">Custom Message</dt>
                        <dd class="text-sm text-gray-900 bg-gray-50 p-4 rounded-md whitespace-pre-line">{{ $certificate->custom_message }}</dd>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Stats -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Downloads</span>
                        <span class="text-sm font-medium text-gray-900">{{ $certificate->download_count ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Last Downloaded</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $certificate->last_downloaded_at ? $certificate->last_downloaded_at->format('M d, Y') : 'Never' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Created</span>
                        <span class="text-sm font-medium text-gray-900">{{ $certificate->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Public Verification</span>
                        <span class="text-sm font-medium text-gray-900">
                            @if($certificate->is_public)
                                <span class="text-green-600">Enabled</span>
                            @else
                                <span class="text-gray-500">Disabled</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="#" class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">download</span>
                        Download Certificate
                    </a>
                    <a href="#" class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">email</span>
                        Resend to Student
                    </a>
                    @if($certificate->is_public)
                    <a href="#" class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">link</span>
                        Copy Verification Link
                    </a>
                    @endif
                    <a href="#" class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">content_copy</span>
                        Duplicate Certificate
                    </a>
                </div>
            </div>

            <!-- Template Info -->
            @if($certificate->template)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Template</h3>
                <div class="space-y-2">
                    <p class="text-sm font-medium text-gray-900">{{ $certificate->template->name }}</p>
                    @if($certificate->template->description)
                    <p class="text-sm text-gray-500">{{ $certificate->template->description }}</p>
                    @endif
                    <div class="flex items-center text-xs text-gray-500">
                        <span class="material-icons text-xs mr-1">description</span>
                        {{ ucfirst($certificate->template->orientation ?? 'landscape') }} • {{ $certificate->template->size ?? 'A4' }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Certificate Preview -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Certificate Preview</h2>
            <div class="flex space-x-2">
                <button class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <span class="material-icons text-sm mr-2">fullscreen</span>
                    Full Screen
                </button>
                <button class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <span class="material-icons text-sm mr-2">print</span>
                    Print
                </button>
            </div>
        </div>
        <div class="px-6 py-6">
            <!-- Certificate Preview Area -->
            <div class="border border-gray-300 rounded-lg p-8 bg-gradient-to-br from-blue-50 to-indigo-100 text-center min-h-96">
                <div class="max-w-4xl mx-auto space-y-6">
                    <!-- Header -->
                    <div class="space-y-2">
                        <h1 class="text-4xl font-bold text-gray-800">{{ $certificate->title ?? 'Certificate of Completion' }}</h1>
                        <p class="text-lg text-gray-600">This certifies that</p>
                    </div>
                    
                    <!-- Student Name -->
                    <div class="py-4">
                        <h2 class="text-3xl font-bold text-blue-800 border-b-2 border-blue-300 inline-block pb-2">
                            {{ $certificate->student->name ?? 'Student Name' }}
                        </h2>
                    </div>
                    
                    <!-- Course Details -->
                    <div class="space-y-2">
                        <p class="text-lg text-gray-600">has successfully completed the course</p>
                        <h3 class="text-2xl font-semibold text-gray-800">{{ $certificate->course->title ?? 'Course Title' }}</h3>
                        @if($certificate->grade)
                        <p class="text-lg text-gray-600">with a grade of <span class="font-bold text-green-600">{{ $certificate->grade }}%</span></p>
                        @endif
                    </div>
                    
                    <!-- Custom Message -->
                    @if($certificate->custom_message)
                    <div class="bg-white/50 rounded-lg p-4 max-w-2xl mx-auto">
                        <p class="text-sm text-gray-700 italic">{{ $certificate->custom_message }}</p>
                    </div>
                    @endif
                    
                    <!-- Footer -->
                    <div class="pt-8 flex justify-between items-end">
                        <div class="text-left">
                            <p class="text-sm text-gray-600">Certificate Number:</p>
                            <p class="font-mono text-sm text-gray-800">{{ $certificate->certificate_number }}</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="border-t border-gray-400 w-32 mb-2"></div>
                            <p class="text-sm text-gray-600">Authorized Signature</p>
                            @if($certificate->issue_date)
                            <p class="text-xs text-gray-500 mt-2">{{ $certificate->issue_date->format('M d, Y') }}</p>
                            @endif
                        </div>
                        
                        <div class="text-right">
                            @if($certificate->expiry_date)
                            <p class="text-sm text-gray-600">Valid until:</p>
                            <p class="text-sm text-gray-800">{{ $certificate->expiry_date->format('M d, Y') }}</p>
                            @else
                            <p class="text-sm text-gray-600">No Expiration</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-center text-xs text-gray-500 mt-4">This is a preview. The actual certificate may appear differently based on the selected template.</p>
        </div>
    </div>

    <!-- Activity Log -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Activity Log</h2>
        </div>
        <div class="px-6 py-6">
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="material-icons text-green-600 text-sm">add_circle</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Certificate issued</p>
                        <p class="text-sm text-gray-500">{{ $certificate->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>

                @if($certificate->last_downloaded_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="material-icons text-blue-600 text-sm">download</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Certificate downloaded</p>
                        <p class="text-sm text-gray-500">{{ $certificate->last_downloaded_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
                @endif

                @if($certificate->updated_at != $certificate->created_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <span class="material-icons text-yellow-600 text-sm">edit</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Certificate updated</p>
                        <p class="text-sm text-gray-500">{{ $certificate->updated_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection