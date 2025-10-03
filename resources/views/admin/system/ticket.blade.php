@extends('layouts.admin')

@section('title', 'Support Ticket #' . $ticket->id)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-                           href="{{ route('admin.system.help') }}" min-w-0">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.system.help') }}" class="text-gray-700 hover:text-gray-900 inline-flex items-center">
                            <i class="material-icons text-sm mr-1">support</i>
                            Help & Support
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="material-icons text-gray-400 text-sm">chevron_right</i>
                            <span class="ml-1 text-sm font-medium text-gray-500">Ticket #{{ $ticket->id }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                {{ $ticket->subject }}
            </h2>
        </div>
        
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
            <form action="{{ route('admin.system.help.tickets.update', $ticket->id) }}" method="POST" class="inline">
                @csrf
                <select name="status" 
                        onchange="this.form.submit()"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Ticket Info -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($ticket->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($ticket->status === 'in_progress') bg-blue-100 text-blue-800
                            @elseif($ticket->status === 'resolved') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Priority</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($ticket->priority === 'urgent') bg-red-100 text-red-800
                            @elseif($ticket->priority === 'high') bg-orange-100 text-orange-800
                            @elseif($ticket->priority === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $ticket->category }}</dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $ticket->created_at->format('M j, Y g:i A') }}</dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Info -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Student Information</h3>
        </div>
        <div class="px-6 py-4">
            @if($ticket->student)
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="material-icons text-blue-600">person</i>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ $ticket->student->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $ticket->student->email }}</p>
                        <p class="text-sm text-gray-500">Student ID: {{ $ticket->student->student_id }}</p>
                    </div>
                </div>
            @else
                <div class="text-sm text-gray-500">
                    <p>Submitted by: {{ $ticket->email ?? 'Anonymous' }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Conversation -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Conversation</h3>
        </div>
        
        <div class="px-6 py-4">
            <!-- Original Message -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center space-x-2 mb-2">
                    <span class="text-sm font-medium text-gray-900">
                        {{ $ticket->student ? $ticket->student->name : ($ticket->email ?? 'Anonymous') }}
                    </span>
                    <span class="text-xs text-gray-500">{{ $ticket->created_at->format('M j, Y g:i A') }}</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                        Original Message
                    </span>
                </div>
                <div class="prose prose-sm max-w-none">
                    {!! nl2br(e($ticket->message)) !!}
                </div>
                
                @if($ticket->attachment_path)
                    <div class="mt-3">
                        <a href="{{ Storage::url($ticket->attachment_path) }}" 
                           target="_blank"
                           class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50">
                            <i class="material-icons text-sm mr-1">attachment</i>
                            View Attachment
                        </a>
                    </div>
                @endif
            </div>

            <!-- Responses -->
            @foreach($ticket->responses as $response)
                <div class="border-l-4 {{ $response->is_admin_response ? 'border-green-500' : 'border-gray-300' }} pl-4 mb-6">
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="text-sm font-medium text-gray-900">
                            {{ $response->is_admin_response ? $response->admin_name : ($ticket->student ? $ticket->student->name : 'Student') }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $response->created_at->format('M j, Y g:i A') }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $response->is_admin_response ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $response->is_admin_response ? 'Admin Response' : 'Student Response' }}
                        </span>
                    </div>
                    <div class="prose prose-sm max-w-none">
                        {!! nl2br(e($response->message)) !!}
                    </div>
                    
                    @if($response->attachment_path)
                        <div class="mt-3">
                            <a href="{{ Storage::url($response->attachment_path) }}" 
                               target="_blank"
                               class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50">
                                <i class="material-icons text-sm mr-1">attachment</i>
                                View Attachment
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Add Response -->
    @if(!in_array($ticket->status, ['resolved', 'closed']))
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Add Response</h3>
            </div>
            <div class="px-6 py-4">
                <form action="{{ route('admin.system.help.tickets.respond', $ticket->id) }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Response</label>
                            <textarea name="message" 
                                      id="message" 
                                      rows="6"
                                      required
                                      placeholder="Type your response here..."
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        
                        <div>
                            <label for="attachment" class="block text-sm font-medium text-gray-700">Attachment (Optional)</label>
                            <input type="file" 
                                   name="attachment" 
                                   id="attachment"
                                   accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-xs text-gray-500">
                                Max file size: 10MB. Allowed types: images, PDF, Word documents, text files.
                            </p>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="change_status" 
                                       id="change_status"
                                       value="1"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="change_status" class="ml-2 block text-sm text-gray-900">
                                    Change status after response
                                </label>
                            </div>
                            
                            <div id="statusSelect" class="hidden">
                                <select name="new_status" 
                                        class="border border-gray-300 rounded-md shadow-sm py-1 px-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="in_progress">In Progress</option>
                                    <option value="resolved">Resolved</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('admin.system.help-support') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Back to Tickets
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <i class="material-icons text-sm mr-2">send</i>
                            Send Response
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
            <i class="material-icons text-gray-400 text-4xl mb-2">
                @if($ticket->status === 'resolved') check_circle @else block @endif
            </i>
            <p class="text-sm text-gray-600">
                This ticket is {{ $ticket->status }} and cannot receive new responses.
            </p>
        </div>
    @endif
</div>

<script>
document.getElementById('change_status').addEventListener('change', function() {
    const statusSelect = document.getElementById('statusSelect');
    if (this.checked) {
        statusSelect.classList.remove('hidden');
    } else {
        statusSelect.classList.add('hidden');
    }
});
</script>
@endsection