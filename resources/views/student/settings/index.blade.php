@extends('layouts.dashboard')

@section('title', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="mt-2 text-gray-600">Manage your account preferences and privacy settings</p>
    </div>

    <!-- Success Message -->
    <div id="success-message" class="hidden mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        Settings updated successfully!
    </div>

    <!-- Settings Tabs -->
    <div class="bg-white shadow rounded-lg">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button type="button" class="tab-button py-4 px-1 border-b-2 border-blue-500 text-blue-600 font-medium text-sm whitespace-nowrap active" data-tab="notifications">
                    <i class="material-icons text-base mr-2">notifications</i>
                    Notifications
                </button>
                <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm whitespace-nowrap" data-tab="privacy">
                    <i class="material-icons text-base mr-2">security</i>
                    Privacy
                </button>
                <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm whitespace-nowrap" data-tab="account">
                    <i class="material-icons text-base mr-2">account_circle</i>
                    Account
                </button>
            </nav>
        </div>

        <!-- Notifications Tab -->
        <div id="notifications-tab" class="tab-content p-6">
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Email Notifications</h3>
                <p class="text-sm text-gray-600 mb-4">Choose what notifications you want to receive via email.</p>
            </div>

            <form id="notifications-form" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">Course Updates</h4>
                            <p class="text-sm text-gray-500">New lessons, assignments, and course announcements</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="course_notifications" class="sr-only peer" 
                                   {{ json_decode($student->notification_preferences ?? '{}', true)['course_notifications'] ?? true ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">Assessment Reminders</h4>
                            <p class="text-sm text-gray-500">Upcoming exams, quizzes, and assignment deadlines</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="assessment_notifications" class="sr-only peer"
                                   {{ json_decode($student->notification_preferences ?? '{}', true)['assessment_notifications'] ?? true ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">Certificates</h4>
                            <p class="text-sm text-gray-500">Certificate awards and achievement notifications</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="certificate_notifications" class="sr-only peer"
                                   {{ json_decode($student->notification_preferences ?? '{}', true)['certificate_notifications'] ?? true ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">Announcements</h4>
                            <p class="text-sm text-gray-500">Important system updates and announcements</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="announcement_notifications" class="sr-only peer"
                                   {{ json_decode($student->notification_preferences ?? '{}', true)['announcement_notifications'] ?? true ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">General Notifications</h4>
                            <p class="text-sm text-gray-500">Forum replies, messages, and other platform notifications</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="email_notifications" class="sr-only peer"
                                   {{ json_decode($student->notification_preferences ?? '{}', true)['email_notifications'] ?? true ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        <i class="material-icons text-sm mr-2">save</i>
                        Save Notification Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Privacy Tab -->
        <div id="privacy-tab" class="tab-content p-6 hidden">
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Privacy Settings</h3>
                <p class="text-sm text-gray-600 mb-4">Control who can see your profile and learning progress.</p>
            </div>

            <form id="privacy-form" class="space-y-6">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Profile Visibility</label>
                        <div class="space-y-3">
                            @php $privacy = json_decode($student->privacy_settings ?? '{}', true); @endphp
                            <label class="flex items-center">
                                <input type="radio" name="profile_visibility" value="public" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                       {{ ($privacy['profile_visibility'] ?? 'enrolled') === 'public' ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">Public</div>
                                    <div class="text-sm text-gray-500">Anyone can view your profile and learning progress</div>
                                </div>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="profile_visibility" value="enrolled" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                       {{ ($privacy['profile_visibility'] ?? 'enrolled') === 'enrolled' ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">Enrolled Students Only</div>
                                    <div class="text-sm text-gray-500">Only students in the same courses can see your profile</div>
                                </div>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="profile_visibility" value="private" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                       {{ ($privacy['profile_visibility'] ?? 'enrolled') === 'private' ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">Private</div>
                                    <div class="text-sm text-gray-500">Only you and instructors can view your profile</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">Show Learning Progress</h4>
                                <p class="text-sm text-gray-500">Display your course progress and completion statistics</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="show_progress" class="sr-only peer"
                                       {{ $privacy['show_progress'] ?? true ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">Show Certificates</h4>
                                <p class="text-sm text-gray-500">Display earned certificates on your profile</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="show_certificates" class="sr-only peer"
                                       {{ $privacy['show_certificates'] ?? true ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        <i class="material-icons text-sm mr-2">save</i>
                        Save Privacy Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Account Tab -->
        <div id="account-tab" class="tab-content p-6 hidden">
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Account Information</h3>
                <p class="text-sm text-gray-600 mb-4">View and manage your account details.</p>
            </div>

            <div class="space-y-6">
                <!-- Account Summary -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Student ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->student_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->created_at ? $student->created_at->format('F j, Y') : 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Actions -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Account Actions</h4>
                            <p class="text-sm text-gray-500">Manage your account settings and data</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('student.profile.show') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="material-icons text-sm mr-2">edit</i>
                            Edit Profile
                        </a>
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 ml-3">
                            <i class="material-icons text-sm mr-2">download</i>
                            Download My Data
                        </button>
                    </div>
                </div>

                <!-- Data Usage -->
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Data & Storage</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Profile Storage Used</span>
                            <span class="text-sm font-medium text-gray-900">2.3 MB</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 15%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">15% of 15 MB storage limit used</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');

            // Update button states
            tabButtons.forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600', 'active');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('border-blue-500', 'text-blue-600', 'active');

            // Update content visibility
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(targetTab + '-tab').classList.remove('hidden');
        });
    });

    // Notifications form
    document.getElementById('notifications-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {};
        
        // Handle checkboxes
        const checkboxes = this.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            data[checkbox.name] = checkbox.checked;
        });

        fetch('{{ route("student.settings.notifications") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Privacy form
    document.getElementById('privacy-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {};
        
        // Handle radio buttons and checkboxes
        const radioButtons = this.querySelectorAll('input[type="radio"]:checked');
        radioButtons.forEach(radio => {
            data[radio.name] = radio.value;
        });
        
        const checkboxes = this.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            data[checkbox.name] = checkbox.checked;
        });

        fetch('{{ route("student.settings.privacy") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    function showSuccessMessage() {
        const message = document.getElementById('success-message');
        message.classList.remove('hidden');
        setTimeout(() => {
            message.classList.add('hidden');
        }, 3000);
    }
});
</script>
@endpush
@endsection