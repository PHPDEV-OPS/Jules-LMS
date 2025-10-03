@extends('layouts.admin')

@section('title', 'Create Email Template')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Email Template</h1>
            <p class="mt-1 text-sm text-gray-500">Create a new email template for system communications</p>
        </div>
        <a href="{{ route('admin.email-templates.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <span class="material-icons text-sm mr-2">arrow_back</span>
            Back to List
        </a>
    </div>

    <!-- Create Form -->
    <form method="POST" action="{{ route('admin.email-templates.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Template Information</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Template Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                           placeholder="Enter template name">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type and Category -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Template Type *</label>
                        <select name="type" id="type" required onchange="updateVariablesList()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            <option value="">Select a type</option>
                            <option value="enrollment_confirmation" {{ old('type') === 'enrollment_confirmation' ? 'selected' : '' }}>Enrollment Confirmation</option>
                            <option value="course_completion" {{ old('type') === 'course_completion' ? 'selected' : '' }}>Course Completion</option>
                            <option value="certificate_issued" {{ old('type') === 'certificate_issued' ? 'selected' : '' }}>Certificate Issued</option>
                            <option value="assignment_due" {{ old('type') === 'assignment_due' ? 'selected' : '' }}>Assignment Due</option>
                            <option value="grade_notification" {{ old('type') === 'grade_notification' ? 'selected' : '' }}>Grade Notification</option>
                            <option value="welcome_email" {{ old('type') === 'welcome_email' ? 'selected' : '' }}>Welcome Email</option>
                            <option value="password_reset" {{ old('type') === 'password_reset' ? 'selected' : '' }}>Password Reset</option>
                            <option value="announcement" {{ old('type') === 'announcement' ? 'selected' : '' }}>Announcement</option>
                            <option value="reminder" {{ old('type') === 'reminder' ? 'selected' : '' }}>Reminder</option>
                            <option value="custom" {{ old('type') === 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category" id="category"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            <option value="system" {{ old('category', 'system') === 'system' ? 'selected' : '' }}>System</option>
                            <option value="course" {{ old('category') === 'course' ? 'selected' : '' }}>Course Related</option>
                            <option value="assessment" {{ old('category') === 'assessment' ? 'selected' : '' }}>Assessment</option>
                            <option value="notification" {{ old('category') === 'notification' ? 'selected' : '' }}>Notification</option>
                            <option value="marketing" {{ old('category') === 'marketing' ? 'selected' : '' }}>Marketing</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Email Subject *</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                           placeholder="Enter email subject">
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                           {{ old('is_active', '1') ? 'checked' : '' }}
                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">Active (ready to use)</label>
                </div>
            </div>
        </div>

        <!-- Email Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Content Editor -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Email Content</h2>
                    </div>
                    <div class="px-6 py-6 space-y-6">
                        <!-- HTML Body -->
                        <div>
                            <label for="body_html" class="block text-sm font-medium text-gray-700 mb-2">HTML Content *</label>
                            <textarea name="body_html" id="body_html" rows="12" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 font-mono text-sm"
                                      placeholder="Enter HTML email content...">{{ old('body_html') }}</textarea>
                            @error('body_html')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Plain Text Body -->
                        <div>
                            <label for="body_text" class="block text-sm font-medium text-gray-700 mb-2">Plain Text Content</label>
                            <textarea name="body_text" id="body_text" rows="8"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                      placeholder="Enter plain text version (optional)...">{{ old('body_text') }}</textarea>
                            @error('body_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Plain text version for email clients that don't support HTML</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Variables Sidebar -->
            <div>
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Available Variables</h2>
                    </div>
                    <div class="px-6 py-6">
                        <p class="text-sm text-gray-600 mb-4">Click to insert variables into your template:</p>
                        
                        <!-- Common Variables -->
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">Common Variables</h3>
                            <div class="space-y-1" id="common-variables">
                                <button type="button" onclick="insertVariable('{@{user_name}}')" 
                                        class="block w-full text-left px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded font-mono">
                                    {@{user_name}}
                                </button>
                                <button type="button" onclick="insertVariable('{@{user_email}}')" 
                                        class="block w-full text-left px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded font-mono">
                                    {@{user_email}}
                                </button>
                                <button type="button" onclick="insertVariable('{@{site_name}}')" 
                                        class="block w-full text-left px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded font-mono">
                                    {@{site_name}}
                                </button>
                                <button type="button" onclick="insertVariable('{@{site_url}}')" 
                                        class="block w-full text-left px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded font-mono">
                                    {@{site_url}}
                                </button>
                                <button type="button" onclick="insertVariable('{@{current_date}}')" 
                                        class="block w-full text-left px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded font-mono">
                                    {@{current_date}}
                            </div>
                        </div>

                        <!-- Type-specific Variables -->
                        <div id="type-variables">
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">Type-specific Variables</h3>
                            <p class="text-xs text-gray-500">Select a template type to see available variables</p>
                        </div>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="bg-white shadow rounded-lg mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Template Preview</h2>
                    </div>
                    <div class="px-6 py-6">
                        <button type="button" onclick="previewTemplate()" 
                                class="w-full flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <span class="material-icons text-sm mr-2">visibility</span>
                            Preview Template
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3 pt-6 border-t">
            <a href="{{ route('admin.email-templates.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" name="action" value="test"
                    class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">send</span>
                Save & Test
            </button>
            <button type="submit"
                    class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <span class="material-icons text-sm mr-2">save</span>
                Create Template
            </button>
        </div>
    </form>
</div>

<script>
const typeVariables = {
    enrollment_confirmation: ['{@{course_name}}', '{@{enrollment_date}}', '{@{course_url}}'],
    course_completion: ['{@{course_name}}', '{@{completion_date}}', '{@{certificate_url}}'],
    certificate_issued: ['{@{certificate_name}}', '{@{certificate_url}}', '{@{issue_date}}'],
    assignment_due: ['{@{assignment_name}}', '{@{due_date}}', '{@{course_name}}'],
    grade_notification: ['{@{assignment_name}}', '{@{grade}}', '{@{feedback}}', '{@{course_name}}'],
    welcome_email: ['{@{login_url}}', '{@{username}}'],
    password_reset: ['{@{reset_url}}', '{@{reset_token}}'],
    announcement: ['{@{announcement_title}}', '{@{announcement_content}}'],
    reminder: ['{@{reminder_content}}', '{@{action_url}}']
};

function updateVariablesList() {
    const type = document.getElementById('type').value;
    const container = document.getElementById('type-variables');
    
    if (type && typeVariables[type]) {
        let html = '<h3 class="text-sm font-semibold text-gray-900 mb-2">Type-specific Variables</h3>';
        html += '<div class="space-y-1">';
        
        typeVariables[type].forEach(variable => {
            html += `<button type="button" onclick="insertVariable('${variable}')" 
                            class="block w-full text-left px-2 py-1 text-xs bg-blue-100 hover:bg-blue-200 rounded font-mono">
                        ${variable}
                    </button>`;
        });
        
        html += '</div>';
        container.innerHTML = html;
    } else {
        container.innerHTML = '<h3 class="text-sm font-semibold text-gray-900 mb-2">Type-specific Variables</h3><p class="text-xs text-gray-500">Select a template type to see available variables</p>';
    }
}

function insertVariable(variable) {
    const textarea = document.getElementById('body_html');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    
    textarea.value = text.substring(0, start) + variable + text.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + variable.length, start + variable.length);
}

function previewTemplate() {
    const subject = document.getElementById('subject').value;
    const bodyHtml = document.getElementById('body_html').value;
    
    if (!subject || !bodyHtml) {
        alert('Please fill in the subject and HTML content first.');
        return;
    }
    
    const previewWindow = window.open('', '_blank', 'width=800,height=600');
    previewWindow.document.write(`
        <html>
            <head><title>Email Template Preview</title></head>
            <body style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                    <div style="background: #f8f9fa; padding: 10px 15px; border-bottom: 1px solid #ddd;">
                        <strong>Subject:</strong> ${subject}
                    </div>
                    <div style="padding: 20px;">
                        ${bodyHtml}
                    </div>
                </div>
            </body>
        </html>
    `);
}
</script>
@endsection