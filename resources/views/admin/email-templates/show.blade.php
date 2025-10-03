@extends('layouts.admin')

@section('title', 'Email Template Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $emailTemplate->name }}</h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ ucfirst(str_replace('_', ' ', $emailTemplate->type)) }} • 
                {{ ucfirst($emailTemplate->category) }} Category
            </p>
        </div>
        <div class="flex space-x-3">
            <button onclick="sendTestEmail()" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">send</span>
                Send Test Email
            </button>
            <a href="{{ route('admin.email-templates.edit', $emailTemplate) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <span class="material-icons text-sm mr-2">edit</span>
                Edit Template
            </a>
            <a href="{{ route('admin.email-templates.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">arrow_back</span>
                Back to List
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="rounded-md p-4 {{ $emailTemplate->is_active ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
        <div class="flex">
            <div class="flex-shrink-0">
                <span class="material-icons text-{{ $emailTemplate->is_active ? 'green' : 'gray' }}-400">
                    {{ $emailTemplate->is_active ? 'check_circle' : 'pause_circle' }}
                </span>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-{{ $emailTemplate->is_active ? 'green' : 'gray' }}-800">
                    {{ $emailTemplate->is_active ? 'Active Template' : 'Inactive Template' }}
                </h3>
                <div class="mt-1 text-sm text-{{ $emailTemplate->is_active ? 'green' : 'gray' }}-700">
                    {{ $emailTemplate->is_active ? 'This template is ready to use for email sending.' : 'This template is currently disabled and will not be used.' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Template Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Template Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Template Information</h2>
                </div>
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Template Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $emailTemplate->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $emailTemplate->type)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Category</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($emailTemplate->category) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $emailTemplate->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $emailTemplate->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $emailTemplate->created_at->format('M d, Y \\a\\t g:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Modified</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $emailTemplate->updated_at->format('M d, Y \\a\\t g:i A') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Email Subject -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Email Subject</h2>
                </div>
                <div class="px-6 py-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-lg font-medium text-gray-900">{{ $emailTemplate->subject }}</p>
                    </div>
                </div>
            </div>

            <!-- HTML Content -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900">HTML Content</h2>
                    <button onclick="previewTemplate()" 
                            class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-1">visibility</span>
                        Preview
                    </button>
                </div>
                <div class="px-6 py-6">
                    <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                        <pre class="text-green-400 text-sm font-mono">{{ $emailTemplate->body_html }}</pre>
                    </div>
                </div>
            </div>

            <!-- Plain Text Content -->
            @if($emailTemplate->body_text)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Plain Text Content</h2>
                </div>
                <div class="px-6 py-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="text-gray-900 text-sm whitespace-pre-line">{{ $emailTemplate->body_text }}</pre>
                    </div>
                </div>
            </div>
            @endif

            <!-- Usage Statistics -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Usage Statistics</h2>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $emailTemplate->usage_count ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Times Used</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $emailTemplate->success_rate ?? 0 }}%</div>
                            <div class="text-sm text-gray-500">Success Rate</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $emailTemplate->open_rate ?? 0 }}%</div>
                            <div class="text-sm text-gray-500">Open Rate</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ $emailTemplate->click_rate ?? 0 }}%</div>
                            <div class="text-sm text-gray-500">Click Rate</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.email-templates.edit', $emailTemplate) }}" 
                       class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">edit</span>
                        Edit Template
                    </a>
                    
                    <button onclick="sendTestEmail()" 
                            class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">send</span>
                        Send Test Email
                    </button>

                    <button onclick="duplicateTemplate()" 
                            class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">content_copy</span>
                        Duplicate Template
                    </button>

                    @if($emailTemplate->is_active)
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

                    <form method="POST" action="{{ route('admin.email-templates.destroy', $emailTemplate) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this template?')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                            <span class="material-icons text-sm mr-3">delete</span>
                            Delete Template
                        </button>
                    </form>
                </div>
            </div>

            <!-- Template Variables -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Available Variables</h3>
                <div class="space-y-3">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Common Variables</h4>
                        <div class="space-y-1">
                            <code class="block text-xs bg-gray-100 px-2 py-1 rounded">@{{ '{' }}@{{ '{' }}user_name@{{ '}' }}@{{ '}' }}</code>
                            <code class="block text-xs bg-gray-100 px-2 py-1 rounded">@{{ '{' }}@{{ '{' }}user_email@{{ '}' }}@{{ '}' }}</code>
                            <code class="block text-xs bg-gray-100 px-2 py-1 rounded">@{{ '{' }}@{{ '{' }}site_name@{{ '}' }}@{{ '}' }}</code>
                            <code class="block text-xs bg-gray-100 px-2 py-1 rounded">@{{ '{' }}@{{ '{' }}site_url@{{ '}' }}@{{ '}' }}</code>
                            <code class="block text-xs bg-gray-100 px-2 py-1 rounded">@{{ '{' }}@{{ '{' }}current_date@{{ '}' }}@{{ '}' }}</code>
                        </div>
                    </div>
                    
                    @php
                        $typeVariables = [
                            'enrollment_confirmation' => ['{{course_name}}', '{{enrollment_date}}', '{{course_url}}'],
                            'course_completion' => ['{{course_name}}', '{{completion_date}}', '{{certificate_url}}'],
                            'certificate_issued' => ['{{certificate_name}}', '{{certificate_url}}', '{{issue_date}}'],
                            'assignment_due' => ['{{assignment_name}}', '{{due_date}}', '{{course_name}}'],
                            'grade_notification' => ['{{assignment_name}}', '{{grade}}', '{{feedback}}', '{{course_name}}'],
                            'welcome_email' => ['{{login_url}}', '{{username}}'],
                            'password_reset' => ['{{reset_url}}', '{{reset_token}}'],
                            'announcement' => ['{{announcement_title}}', '{{announcement_content}}'],
                            'reminder' => ['{{reminder_content}}', '{{action_url}}']
                        ];
                    @endphp

                    @if(isset($typeVariables[$emailTemplate->type]))
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Type-specific Variables</h4>
                        <div class="space-y-1">
                            @foreach($typeVariables[$emailTemplate->type] as $variable)
                            <code class="block text-xs bg-blue-100 px-2 py-1 rounded">@{{ $variable }}</code>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Email Modal -->
<div id="testEmailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeTestEmailModal()"></div>
        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Send Test Email</h3>
                    <div class="mt-2">
                        <label for="test_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="test_email" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                               placeholder="Enter email address">
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button onclick="submitTestEmail()" 
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                    Send Test
                </button>
                <button onclick="closeTestEmailModal()" 
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function previewTemplate() {
    const subject = '{{ $emailTemplate->subject }}';
    const bodyHtml = `{!! str_replace(["\r", "\n"], ['', '\\n'], addslashes($emailTemplate->body_html)) !!}`;
    
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

function sendTestEmail() {
    document.getElementById('testEmailModal').classList.remove('hidden');
}

function closeTestEmailModal() {
    document.getElementById('testEmailModal').classList.add('hidden');
}

function submitTestEmail() {
    const email = document.getElementById('test_email').value;
    if (!email) {
        alert('Please enter an email address.');
        return;
    }
    
    // Here you would make an AJAX call to send the test email
    alert('Test email would be sent to: ' + email);
    closeTestEmailModal();
}

function duplicateTemplate() {
    if (confirm('Create a copy of this template?')) {
        // Redirect to create page with template data
        window.location.href = '{{ route("admin.email-templates.create") }}?duplicate={{ $emailTemplate->id }}';
    }
}

function toggleStatus(activate) {
    if (confirm(activate ? 'Activate this template?' : 'Deactivate this template?')) {
        // Redirect to edit page or make AJAX call
        window.location.href = '{{ route("admin.email-templates.edit", $emailTemplate) }}';
    }
}
</script>
@endsection