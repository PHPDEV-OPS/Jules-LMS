<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of announcements.
     */
    public function index(Request $request)
    {
        $query = Announcement::with(['course', 'createdBy'])->withCount('readings');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
        }

        // Filter by course
        if ($request->has('course_id')) {
            if ($request->course_id === 'global') {
                $query->whereNull('course_id');
            } elseif ($request->course_id) {
                $query->where('course_id', $request->course_id);
            }
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'published') {
                $query->published();
            } elseif ($request->status === 'unpublished') {
                $query->where('is_published', false);
            } elseif ($request->status === 'expired') {
                $query->where('expires_at', '<', now());
            }
        }

        $announcements = $query->orderBy('is_pinned', 'desc')
                              ->orderBy('created_at', 'desc')
                              ->paginate(15);

        $courses = Course::orderBy('title')->get();
        $types = ['general', 'important', 'update', 'maintenance', 'event'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $audiences = ['all', 'students', 'tutors', 'admins'];

        $stats = [
            'total_announcements' => Announcement::count(),
            'published_announcements' => Announcement::where('is_published', true)->count(),
            'pinned_announcements' => Announcement::where('is_pinned', true)->count(),
            'expired_announcements' => Announcement::where('expires_at', '<', now())->count()
        ];

        return view('admin.announcements.index', compact(
            'announcements', 'courses', 'types', 'priorities', 'audiences', 'stats'
        ));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        $courses = Course::active()->orderBy('title')->get();
        $types = [
            'general' => 'General',
            'important' => 'Important',
            'update' => 'Update',
            'maintenance' => 'Maintenance',
            'event' => 'Event'
        ];
        $priorities = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent'
        ];
        $audiences = [
            'all' => 'All Users',
            'students' => 'Students Only',
            'tutors' => 'Tutors Only',
            'admins' => 'Admins Only'
        ];

        return view('admin.announcements.create', compact('courses', 'types', 'priorities', 'audiences'));
    }

    /**
     * Store a newly created announcement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,important,update,maintenance,event',
            'priority' => 'required|in:low,medium,high,urgent',
            'target_audience' => 'required|in:all,students,tutors,admins',
            'course_id' => 'nullable|exists:courses,id',
            'expires_at' => 'nullable|date|after:now',
            'is_published' => 'boolean',
            'is_pinned' => 'boolean',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240'
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_published'] = $request->has('is_published');
        $validated['is_pinned'] = $request->has('is_pinned');
        
        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('announcements', 'public');
            $validated['attachment_url'] = $path;
        }

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        $announcement->load(['course', 'createdBy', 'readings.user']);

        $readingStats = [
            'total_readers' => $announcement->readings()->count(),
            'recent_readers' => $announcement->readings()
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
        ];

        return view('admin.announcements.show', compact('announcement', 'readingStats'));
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        $courses = Course::active()->orderBy('title')->get();
        $types = [
            'general' => 'General',
            'important' => 'Important',
            'update' => 'Update',
            'maintenance' => 'Maintenance',
            'event' => 'Event'
        ];
        $priorities = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent'
        ];
        $audiences = [
            'all' => 'All Users',
            'students' => 'Students Only',
            'tutors' => 'Tutors Only',
            'admins' => 'Admins Only'
        ];

        return view('admin.announcements.edit', compact('announcement', 'courses', 'types', 'priorities', 'audiences'));
    }

    /**
     * Update the specified announcement.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,important,update,maintenance,event',
            'priority' => 'required|in:low,medium,high,urgent',
            'target_audience' => 'required|in:all,students,tutors,admins',
            'course_id' => 'nullable|exists:courses,id',
            'expires_at' => 'nullable|date|after:now',
            'is_published' => 'boolean',
            'is_pinned' => 'boolean',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240'
        ]);

        $validated['is_published'] = $request->has('is_published');
        $validated['is_pinned'] = $request->has('is_pinned');

        // Set published_at if publishing for the first time
        if ($validated['is_published'] && !$announcement->published_at) {
            $validated['published_at'] = now();
        }

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment
            if ($announcement->attachment_url) {
                Storage::disk('public')->delete($announcement->attachment_url);
            }
            
            $file = $request->file('attachment');
            $path = $file->store('announcements', 'public');
            $validated['attachment_url'] = $path;
        }

        $announcement->update($validated);

        return redirect()->route('admin.announcements.show', $announcement)
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Remove the specified announcement.
     */
    public function destroy(Announcement $announcement)
    {
        // Delete attachment file
        if ($announcement->attachment_url) {
            Storage::disk('public')->delete($announcement->attachment_url);
        }

        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }

    /**
     * Toggle announcement published status
     */
    public function togglePublished(Announcement $announcement)
    {
        $isPublished = !$announcement->is_published;
        
        $announcement->update([
            'is_published' => $isPublished,
            'published_at' => $isPublished ? ($announcement->published_at ?? now()) : $announcement->published_at
        ]);

        $status = $isPublished ? 'published' : 'unpublished';
        return redirect()->back()->with('success', "Announcement {$status} successfully!");
    }

    /**
     * Toggle announcement pinned status
     */
    public function togglePinned(Announcement $announcement)
    {
        $announcement->update(['is_pinned' => !$announcement->is_pinned]);
        
        $status = $announcement->is_pinned ? 'pinned' : 'unpinned';
        return redirect()->back()->with('success', "Announcement {$status} successfully!");
    }

    /**
     * Bulk actions for announcements
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,unpublish,pin,unpin,delete',
            'announcements' => 'required|array',
            'announcements.*' => 'exists:announcements,id'
        ]);

        $announcements = Announcement::whereIn('id', $request->announcements)->get();
        $count = $announcements->count();

        switch ($request->action) {
            case 'publish':
                $announcements->each(function($announcement) {
                    $announcement->update([
                        'is_published' => true,
                        'published_at' => $announcement->published_at ?? now()
                    ]);
                });
                $message = "Published {$count} announcements successfully!";
                break;

            case 'unpublish':
                $announcements->each->update(['is_published' => false]);
                $message = "Unpublished {$count} announcements successfully!";
                break;

            case 'pin':
                $announcements->each->update(['is_pinned' => true]);
                $message = "Pinned {$count} announcements successfully!";
                break;

            case 'unpin':
                $announcements->each->update(['is_pinned' => false]);
                $message = "Unpinned {$count} announcements successfully!";
                break;

            case 'delete':
                $announcements->each(function($announcement) {
                    if ($announcement->attachment_url) {
                        Storage::disk('public')->delete($announcement->attachment_url);
                    }
                    $announcement->delete();
                });
                $message = "Deleted {$count} announcements successfully!";
                break;
        }

        return redirect()->route('admin.announcements.index')
            ->with('success', $message);
    }
}