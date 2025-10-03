@extends('layouts.admin')

@section('title', 'Help & Support')

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Help & Support
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Manage support tickets and help documentation
            </p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('admin.system.help', ['tab' => 'tickets']) }}" 
               class="@if(request('tab', 'tickets') === 'tickets') border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Support Tickets
            </a>
            <a href="{{ route('admin.system.help', ['tab' => 'articles']) }}" 
               class="@if(request('tab') === 'articles') border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Help Articles
            </a>
        </nav>
    </div>

    @if(request('tab', 'tickets') === 'tickets')
        <!-- Support Tickets Tab -->
        <div class="space-y-6">
            <!-- Ticket Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="material-icons text-blue-600 text-2xl">support</i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Tickets</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $ticketStats['total'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="material-icons text-yellow-600 text-2xl">pending</i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $ticketStats['pending'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="material-icons text-orange-600 text-2xl">schedule</i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">In Progress</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $ticketStats['in_progress'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="material-icons text-green-600 text-2xl">check_circle</i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Resolved</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $ticketStats['resolved'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tickets Filter -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Filter Tickets</h3>
                </div>
                <div class="px-6 py-4">
                    <form method="GET" action="{{ route('admin.system.help') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input type="hidden" name="tab" value="tickets">
                        
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search tickets..."
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" 
                                    id="status" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                            <select name="priority" 
                                    id="priority" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Priorities</option>
                                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <i class="material-icons text-sm mr-2">search</i>
                                Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tickets List -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Support Tickets</h3>
                </div>
                
                @if($tickets->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($tickets as $ticket)
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if($ticket->priority === 'urgent') bg-red-100 text-red-800
                                                    @elseif($ticket->priority === 'high') bg-orange-100 text-orange-800
                                                    @elseif($ticket->priority === 'medium') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    {{ ucfirst($ticket->priority) }}
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">
                                                    #{{ $ticket->id }} - {{ $ticket->subject }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $ticket->student ? $ticket->student->name : 'Anonymous' }} • 
                                                    Category: {{ $ticket->category }} • 
                                                    {{ $ticket->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2 ml-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($ticket->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($ticket->status === 'in_progress') bg-blue-100 text-blue-800
                                            @elseif($ticket->status === 'resolved') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                        
                                        <a href="{{ route('admin.system.help.tickets.show', $ticket->id) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                            <i class="material-icons text-sm mr-1">visibility</i>
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $tickets->withQueryString()->links() }}
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <i class="material-icons text-gray-400 text-6xl mb-4">support</i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Support Tickets</h3>
                        <p class="text-gray-500">No support tickets match your current filters.</p>
                    </div>
                @endif
            </div>
        </div>

    @else
        <!-- Help Articles Tab -->
        <div class="space-y-6">
            <!-- Articles Actions -->
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Help Articles</h3>
                    <p class="text-sm text-gray-500">Manage help documentation and knowledge base</p>
                </div>
                <button type="button" 
                        onclick="openCreateArticleModal()"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="material-icons text-sm mr-2">add</i>
                    Create Article
                </button>
            </div>

            <!-- Articles List -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if($articles->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($articles as $article)
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <i class="material-icons text-gray-400">
                                                    @if($article->is_published) article @else drafts @endif
                                                </i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $article->title }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Category: {{ $article->category }} • 
                                                    @if($article->is_published)
                                                        <span class="text-green-600">Published</span>
                                                    @else
                                                        <span class="text-yellow-600">Draft</span>
                                                    @endif
                                                     • 
                                                    {{ $article->updated_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2 ml-4">
                                        <button type="button" 
                                                onclick="editArticle({{ $article->id }})"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                            <i class="material-icons text-sm mr-1">edit</i>
                                            Edit
                                        </button>
                                        
                                        @if($article->is_published)
                                            <form action="{{ route('admin.system.help.articles.update', $article->id) }}" 
                                                  method="POST" 
                                                  class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-2 border border-yellow-300 rounded-md text-sm font-medium text-yellow-700 bg-white hover:bg-yellow-50">
                                                    <i class="material-icons text-sm mr-1">visibility_off</i>
                                                    Unpublish
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.system.help.articles.update', $article->id) }}" 
                                                  method="POST" 
                                                  class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-2 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-white hover:bg-green-50">
                                                    <i class="material-icons text-sm mr-1">visibility</i>
                                                    Publish
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('admin.system.help.articles.destroy', $article->id) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this article?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                                                <i class="material-icons text-sm mr-1">delete</i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $articles->links() }}
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <i class="material-icons text-gray-400 text-6xl mb-4">article</i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Help Articles</h3>
                        <p class="text-gray-500 mb-4">Create your first help article to get started.</p>
                        <button type="button" 
                                onclick="openCreateArticleModal()"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <i class="material-icons text-sm mr-2">add</i>
                            Create First Article
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Create/Edit Article Modal -->
<div id="articleModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg max-w-4xl w-full p-6 max-h-screen overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 id="articleModalTitle" class="text-lg font-medium text-gray-900">Create Article</h3>
                <button type="button" onclick="closeArticleModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="material-icons">close</i>
                </button>
            </div>
            
            <form id="articleForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="article_title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" 
                               name="title" 
                               id="article_title" 
                               required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="article_category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category" 
                                    id="article_category" 
                                    required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Category</option>
                                <option value="Getting Started">Getting Started</option>
                                <option value="Account Management">Account Management</option>
                                <option value="Courses">Courses</option>
                                <option value="Technical Issues">Technical Issues</option>
                                <option value="Billing">Billing</option>
                                <option value="General">General</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="article_sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                            <input type="number" 
                                   name="sort_order" 
                                   id="article_sort_order" 
                                   value="0"
                                   min="0"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="article_content" class="block text-sm font-medium text-gray-700">Content</label>
                        <textarea name="content" 
                                  id="article_content" 
                                  rows="15"
                                  required
                                  placeholder="Write your article content here..."
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="is_published" 
                               id="article_is_published"
                               value="1"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="article_is_published" class="ml-2 block text-sm text-gray-900">
                            Publish immediately
                        </label>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeArticleModal()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="material-icons text-sm mr-2">save</i>
                        Save Article
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateArticleModal() {
    document.getElementById('articleModalTitle').textContent = 'Create Article';
    document.getElementById('articleForm').action = '{{ route("admin.system.help.articles.store") }}';
    document.getElementById('articleForm').reset();
    
    // Remove method override if exists
    const methodField = document.querySelector('input[name="_method"]');
    if (methodField) {
        methodField.remove();
    }
    
    document.getElementById('articleModal').classList.remove('hidden');
}

function editArticle(articleId) {
    fetch(`{{ route('admin.system.help.articles.edit', 'PLACEHOLDER') }}`.replace('PLACEHOLDER', articleId))
        .then(response => response.json())
        .then(data => {
            document.getElementById('articleModalTitle').textContent = 'Edit Article';
            document.getElementById('articleForm').action = `{{ route('admin.system.help.articles.update', 'PLACEHOLDER') }}`.replace('PLACEHOLDER', articleId);
            
            // Add method override for PUT
            let methodField = document.querySelector('input[name="_method"]');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                document.getElementById('articleForm').appendChild(methodField);
            }
            methodField.value = 'PUT';
            
            // Populate form fields
            document.getElementById('article_title').value = data.title;
            document.getElementById('article_category').value = data.category;
            document.getElementById('article_sort_order').value = data.sort_order;
            document.getElementById('article_content').value = data.content;
            document.getElementById('article_is_published').checked = data.is_published;
            
            document.getElementById('articleModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load article data');
        });
}

function closeArticleModal() {
    document.getElementById('articleModal').classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        closeArticleModal();
    }
});
</script>
@endsection