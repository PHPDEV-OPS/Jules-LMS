<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\HelpArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HelpSupportController extends Controller
{
    public function index(Request $request)
    {
        // Ticket statistics for dashboard
        $ticketStats = [
            'total' => SupportTicket::count(),
            'pending' => SupportTicket::where('status', 'pending')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
        ];

        // Tickets query with filters
        $ticketsQuery = SupportTicket::with(['student']);
        
        // Apply filters if they exist
        if ($request->filled('search')) {
            $search = $request->search;
            $ticketsQuery->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $ticketsQuery->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $ticketsQuery->where('priority', $request->priority);
        }

        $tickets = $ticketsQuery->orderBy('created_at', 'desc')->paginate(10);

        // Articles query
        $articlesQuery = HelpArticle::query();
        $articles = $articlesQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.system.help-support', compact('ticketStats', 'tickets', 'articles'));
    }

    // Support Tickets Management
    public function tickets(Request $request)
    {
        $query = SupportTicket::with(['student', 'user']);

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%");
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $categories = SupportTicket::distinct('category')->pluck('category')->filter();
        $statuses = ['open', 'pending', 'in_progress', 'resolved', 'closed'];
        $priorities = ['low', 'medium', 'high', 'urgent'];

        return view('admin.system.support-tickets', compact(
            'tickets', 
            'categories', 
            'statuses', 
            'priorities'
        ));
    }

    public function showTicket(SupportTicket $ticket)
    {
        $ticket->load(['student', 'user', 'responses.user', 'responses.student']);
        return view('admin.system.support-ticket-detail', compact('ticket'));
    }

    public function updateTicketStatus(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,pending,in_progress,resolved,closed',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'internal_notes' => 'nullable|string',
        ]);

        $ticket->update([
            'status' => $request->status,
            'priority' => $request->priority ?? $ticket->priority,
            'assigned_to' => $request->assigned_to,
            'internal_notes' => $request->internal_notes,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Ticket updated successfully.');
    }

    public function respondToTicket(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'response' => 'required|string',
            'is_internal' => 'boolean',
        ]);

        $ticket->responses()->create([
            'user_id' => auth()->id(),
            'response' => $request->response,
            'is_internal' => $request->boolean('is_internal'),
        ]);

        // Update ticket status if not internal note
        if (!$request->boolean('is_internal')) {
            $ticket->update([
                'status' => 'pending',
                'last_response_at' => now(),
            ]);
        }

        return back()->with('success', 'Response added successfully.');
    }

    // Help Articles Management
    public function articles(Request $request)
    {
        $query = HelpArticle::query();

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        $articles = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $categories = HelpArticle::distinct('category')->pluck('category')->filter();

        return view('admin.system.help-articles', compact('articles', 'categories'));
    }

    public function createArticle()
    {
        $categories = HelpArticle::distinct('category')->pluck('category')->filter();
        return view('admin.system.help-article-create', compact('categories'));
    }

    public function storeArticle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|string',
            'is_published' => 'boolean',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        $article = new HelpArticle([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'tags' => $request->tags,
            'is_published' => $request->boolean('is_published'),
            'author_id' => auth()->id(),
            'slug' => \Str::slug($request->title),
        ]);

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('help-articles', 'public');
            $article->featured_image = $path;
        }

        $article->save();

        return redirect()->route('admin.help.articles')
            ->with('success', 'Help article created successfully.');
    }

    public function editArticle(HelpArticle $article)
    {
        $categories = HelpArticle::distinct('category')->pluck('category')->filter();
        return view('admin.system.help-article-edit', compact('article', 'categories'));
    }

    public function updateArticle(Request $request, HelpArticle $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|string',
            'is_published' => 'boolean',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        $article->update([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'tags' => $request->tags,
            'is_published' => $request->boolean('is_published'),
            'slug' => \Str::slug($request->title),
        ]);

        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            
            $path = $request->file('featured_image')->store('help-articles', 'public');
            $article->featured_image = $path;
            $article->save();
        }

        return back()->with('success', 'Help article updated successfully.');
    }

    public function destroyArticle(HelpArticle $article)
    {
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        return back()->with('success', 'Help article deleted successfully.');
    }

    // System Information
    public function systemInfo()
    {
        $systemInfo = [
            'application' => [
                'Name' => config('app.name'),
                'Environment' => config('app.env'),
                'Debug Mode' => config('app.debug') ? 'Enabled' : 'Disabled',
                'URL' => config('app.url'),
                'Timezone' => config('app.timezone'),
                'Locale' => config('app.locale'),
                'Laravel Version' => app()->version(),
            ],
            'server' => [
                'PHP Version' => phpversion(),
                'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'Memory Limit' => ini_get('memory_limit'),
                'Max Execution Time' => ini_get('max_execution_time') . ' seconds',
                'Max Upload Size' => ini_get('upload_max_filesize'),
                'Max Post Size' => ini_get('post_max_size'),
            ],
            'database' => [
                'Driver' => config('database.default'),
                'Host' => config('database.connections.' . config('database.default') . '.host'),
                'Database' => config('database.connections.' . config('database.default') . '.database'),
                'Connection Status' => $this->checkDatabaseConnection(),
            ],
            'storage' => [
                'Default Disk' => config('filesystems.default'),
                'Storage Path' => storage_path(),
                'Public Path' => public_path(),
                'Disk Space (Free)' => $this->formatBytes($this->getSafeDiskSpace('/', 'free')),
                'Disk Space (Total)' => $this->formatBytes($this->getSafeDiskSpace('/', 'total')),
            ],
            'cache' => [
                'Driver' => config('cache.default'),
                'Session Driver' => config('session.driver'),
                'Queue Driver' => config('queue.default'),
            ],
        ];

        return view('admin.system.system-info', compact('systemInfo'));
    }

    public function documentation()
    {
        $sections = [
            'getting_started' => 'Getting Started',
            'user_management' => 'User Management', 
            'course_management' => 'Course Management',
            'assessment_system' => 'Assessment System',
            'forum_management' => 'Forum Management',
            'system_settings' => 'System Settings',
            'troubleshooting' => 'Troubleshooting',
        ];

        return view('admin.system.documentation', compact('sections'));
    }

    private function checkDatabaseConnection()
    {
        try {
            \DB::connection()->getPdo();
            return 'Connected';
        } catch (\Exception $e) {
            return 'Connection Failed: ' . $e->getMessage();
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    private function getSafeDiskSpace($path, $type = 'free')
    {
        try {
            // For Windows, we need to get the drive root
            if (PHP_OS_FAMILY === 'Windows') {
                // Use the current drive (usually C:)
                $drive = 'C:';
                $targetPath = $drive . '\\';
            } else {
                $targetPath = $path;
            }

            if ($type === 'total') {
                return disk_total_space($targetPath);
            } else {
                return disk_free_space($targetPath);
            }
        } catch (\Exception $e) {
            // Return 0 if we can't determine disk space
            return 0;
        }
    }
}