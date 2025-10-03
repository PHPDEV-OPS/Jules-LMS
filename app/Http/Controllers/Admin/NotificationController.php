<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of notifications.
     */
    public function index(Request $request)
    {
        $query = Notification::with('user');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
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
            if ($request->status === 'read') {
                $query->read();
            } elseif ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'expired') {
                $query->where('expires_at', '<', now());
            }
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        $users = User::orderBy('name')->get();
        $types = ['info', 'success', 'warning', 'error', 'enrollment', 'course', 'assessment', 'certificate', 'announcement', 'payment', 'system'];
        $priorities = ['low', 'medium', 'high', 'urgent'];

        $stats = [
            'total_notifications' => Notification::count(),
            'unread_notifications' => Notification::unread()->count(),
            'expired_notifications' => Notification::where('expires_at', '<', now())->count(),
            'high_priority_unread' => Notification::unread()->where('priority', 'high')->count()
        ];

        return view('admin.notifications.index', compact(
            'notifications', 'users', 'types', 'priorities', 'stats'
        ));
    }

    /**
     * Show the form for creating a new notification.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        $types = [
            'info' => 'Information',
            'success' => 'Success',
            'warning' => 'Warning',
            'error' => 'Error',
            'enrollment' => 'Enrollment',
            'course' => 'Course',
            'assessment' => 'Assessment',
            'certificate' => 'Certificate',
            'announcement' => 'Announcement',
            'payment' => 'Payment',
            'system' => 'System'
        ];
        $priorities = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent'
        ];

        return view('admin.notifications.create', compact('users', 'types', 'priorities'));
    }

    /**
     * Store a newly created notification.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_type' => 'required|in:single,all,role',
            'user_id' => 'required_if:recipient_type,single|exists:users,id',
            'role' => 'required_if:recipient_type,role|in:admin,tutor,learner',
            'type' => 'required|in:info,success,warning,error,enrollment,course,assessment,certificate,announcement,payment,system',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'action_url' => 'nullable|url',
            'priority' => 'required|in:low,medium,high,urgent',
            'expires_at' => 'nullable|date|after:now'
        ]);

        $created = 0;

        switch ($validated['recipient_type']) {
            case 'single':
                Notification::createForUser(
                    $validated['user_id'],
                    $validated['type'],
                    $validated['title'],
                    $validated['message'],
                    [],
                    $validated['action_url'] ?? null,
                    $validated['priority'],
                    $validated['expires_at'] ?? null
                );
                $created = 1;
                break;

            case 'all':
                Notification::createForAllUsers(
                    $validated['type'],
                    $validated['title'],
                    $validated['message'],
                    [],
                    $validated['action_url'] ?? null,
                    $validated['priority'],
                    $validated['expires_at'] ?? null
                );
                $created = User::count();
                break;

            case 'role':
                $users = User::where('role', $validated['role'])->get();
                foreach ($users as $user) {
                    Notification::createForUser(
                        $user->id,
                        $validated['type'],
                        $validated['title'],
                        $validated['message'],
                        [],
                        $validated['action_url'] ?? null,
                        $validated['priority'],
                        $validated['expires_at'] ?? null
                    );
                }
                $created = $users->count();
                break;
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', "Created {$created} notifications successfully!");
    }

    /**
     * Display the specified notification.
     */
    public function show(Notification $notification)
    {
        $notification->load('user');

        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Show the form for editing the specified notification.
     */
    public function edit(Notification $notification)
    {
        $users = User::orderBy('name')->get();
        $types = [
            'info' => 'Information',
            'success' => 'Success',
            'warning' => 'Warning',
            'error' => 'Error',
            'enrollment' => 'Enrollment',
            'course' => 'Course',
            'assessment' => 'Assessment',
            'certificate' => 'Certificate',
            'announcement' => 'Announcement',
            'payment' => 'Payment',
            'system' => 'System'
        ];
        $priorities = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent'
        ];

        return view('admin.notifications.edit', compact('notification', 'users', 'types', 'priorities'));
    }

    /**
     * Update the specified notification.
     */
    public function update(Request $request, Notification $notification)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'action_url' => 'nullable|url',
            'priority' => 'required|in:low,medium,high,urgent',
            'expires_at' => 'nullable|date|after:now'
        ]);

        $notification->update($validated);

        return redirect()->route('admin.notifications.show', $notification)
            ->with('success', 'Notification updated successfully!');
    }

    /**
     * Remove the specified notification.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification deleted successfully!');
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        $notification->markAsRead();

        return redirect()->back()
            ->with('success', 'Notification marked as read!');
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(Notification $notification)
    {
        $notification->markAsUnread();

        return redirect()->back()
            ->with('success', 'Notification marked as unread!');
    }

    /**
     * Bulk actions for notifications
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:mark_read,mark_unread,delete',
            'notifications' => 'required|array',
            'notifications.*' => 'exists:notifications,id'
        ]);

        $notifications = Notification::whereIn('id', $request->notifications)->get();
        $count = $notifications->count();

        switch ($request->action) {
            case 'mark_read':
                $notifications->each->markAsRead();
                $message = "Marked {$count} notifications as read!";
                break;

            case 'mark_unread':
                $notifications->each->markAsUnread();
                $message = "Marked {$count} notifications as unread!";
                break;

            case 'delete':
                $notifications->each->delete();
                $message = "Deleted {$count} notifications successfully!";
                break;
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', $message);
    }

    /**
     * Send system notification to all users
     */
    public function sendSystemNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,error,system',
            'priority' => 'required|in:low,medium,high,urgent',
            'expires_at' => 'nullable|date|after:now'
        ]);

        $count = User::count();

        Notification::createForAllUsers(
            $request->type,
            $request->title,
            $request->message,
            ['system' => true],
            null,
            $request->priority,
            $request->expires_at
        );

        return redirect()->route('admin.notifications.index')
            ->with('success', "System notification sent to {$count} users successfully!");
    }

    /**
     * Clean up expired notifications
     */
    public function cleanupExpired()
    {
        $count = Notification::where('expires_at', '<', now())->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', "Cleaned up {$count} expired notifications!");
    }

    /**
     * Get notification statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => Notification::count(),
            'unread' => Notification::unread()->count(),
            'by_type' => Notification::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type'),
            'by_priority' => Notification::selectRaw('priority, COUNT(*) as count')
                ->groupBy('priority')
                ->pluck('count', 'priority'),
            'recent_activity' => Notification::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        ];

        return view('admin.notifications.statistics', compact('stats'));
    }
}