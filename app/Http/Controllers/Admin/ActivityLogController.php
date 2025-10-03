<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'student']);

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Activity type filter
        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        // User type filter
        if ($request->filled('user_type')) {
            if ($request->user_type === 'admin') {
                $query->whereNotNull('user_id');
            } elseif ($request->user_type === 'student') {
                $query->whereNotNull('student_id');
            }
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $logs = $query->paginate(25)->withQueryString();

        // Statistics
        $statistics = $this->getActivityStats($request);

        // Activity types for filter
        $activityTypes = ActivityLog::distinct('activity_type')
            ->pluck('activity_type')
            ->filter()
            ->sort();

        return view('admin.system.activity-logs', compact(
            'logs', 
            'statistics', 
            'activityTypes'
        ));
    }

    public function show(ActivityLog $log)
    {
        $log->load(['user', 'student']);
        return view('admin.system.activity-log-detail', compact('log'));
    }

    public function destroy(ActivityLog $log)
    {
        $log->delete();
        return back()->with('success', 'Activity log deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'log_ids' => 'required|array',
            'log_ids.*' => 'exists:activity_logs,id'
        ]);

        ActivityLog::whereIn('id', $request->log_ids)->delete();
        
        return back()->with('success', 'Selected activity logs deleted successfully.');
    }

    public function clearOld(Request $request)
    {
        $request->validate([
            'older_than' => 'required|integer|min:1|max:365',
        ]);

        $cutoffDate = Carbon::now()->subDays($request->older_than);
        $deletedCount = ActivityLog::where('created_at', '<', $cutoffDate)->delete();
        
        return back()->with('success', "Deleted {$deletedCount} old activity logs (older than {$request->older_than} days).");
    }

    public function export(Request $request)
    {
        $query = ActivityLog::with(['user', 'student']);

        // Apply same filters as index
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        if ($request->filled('user_type')) {
            if ($request->user_type === 'admin') {
                $query->whereNotNull('user_id');
            } elseif ($request->user_type === 'student') {
                $query->whereNotNull('student_id');
            }
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $csvData = [];
        $csvData[] = [
            'Date/Time',
            'User Type',
            'User',
            'Activity Type',
            'Description',
            'IP Address',
            'User Agent'
        ];

        foreach ($logs as $log) {
            $userType = $log->user_id ? 'Admin' : 'Student';
            $userName = $log->user_id ? ($log->user->name ?? 'Unknown') : ($log->student ? $log->student->full_name : 'Unknown');
            
            $csvData[] = [
                $log->created_at->format('Y-m-d H:i:s'),
                $userType,
                $userName,
                $log->activity_type,
                $log->description,
                $log->ip_address,
                $log->user_agent
            ];
        }

        $filename = 'activity_logs_' . date('Y-m-d_H-i-s') . '.csv';
        
        $output = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function getActivityStats($request)
    {
        $baseQuery = ActivityLog::query();

        // Apply date filters if present
        if ($request->filled('date_from')) {
            $baseQuery->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $baseQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $stats = [
            'total_logs' => (clone $baseQuery)->count(),
            'today_logs' => (clone $baseQuery)->whereDate('created_at', Carbon::today())->count(),
            'unique_users_today' => (clone $baseQuery)->whereDate('created_at', Carbon::today())->distinct('user_id')->whereNotNull('user_id')->count() +
                                   (clone $baseQuery)->whereDate('created_at', Carbon::today())->distinct('student_id')->whereNotNull('student_id')->count(),
            'critical_logs' => (clone $baseQuery)->whereIn('activity_type', ['error', 'delete', 'security'])->count(),
            'admin_activities' => (clone $baseQuery)->whereNotNull('user_id')->count(),
            'student_activities' => (clone $baseQuery)->whereNotNull('student_id')->count(),
            'unique_users' => (clone $baseQuery)->distinct('user_id')->whereNotNull('user_id')->count(),
            'unique_students' => (clone $baseQuery)->distinct('student_id')->whereNotNull('student_id')->count(),
        ];

        // Activity by type
        $stats['by_type'] = (clone $baseQuery)
            ->select('activity_type', DB::raw('count(*) as count'))
            ->groupBy('activity_type')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Activity by day (last 7 days)
        $stats['by_day'] = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = (clone $baseQuery)->whereDate('created_at', $date->format('Y-m-d'))->count();
            $stats['by_day'][$date->format('M j')] = $count;
        }

        // Activity by hour (last 24 hours)
        $stats['by_hour'] = [];
        for ($i = 23; $i >= 0; $i--) {
            $hour = Carbon::now()->subHours($i);
            $count = (clone $baseQuery)
                ->where('created_at', '>=', $hour->format('Y-m-d H:00:00'))
                ->where('created_at', '<', $hour->addHour()->format('Y-m-d H:00:00'))
                ->count();
            $stats['by_hour'][$hour->format('H:00')] = $count;
        }

        // Top IP addresses
        $stats['top_ips'] = (clone $baseQuery)
            ->select('ip_address', DB::raw('count(*) as count'))
            ->groupBy('ip_address')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return $stats;
    }
}