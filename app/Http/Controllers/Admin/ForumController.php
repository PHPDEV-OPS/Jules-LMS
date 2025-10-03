<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index()
    {
        $categories = ForumCategory::withCount(['topics', 'posts'])->orderBy('sort_order')->get();
        $recentTopics = ForumTopic::with(['student', 'category', 'course'])
            ->orderBy('last_activity_at', 'desc')
            ->limit(10)
            ->get();
        
        $totalTopics = ForumTopic::count();
        $totalPosts = ForumPost::count();
        $totalCategories = ForumCategory::count();
        
        return view('admin.forums.index', compact(
            'categories', 
            'recentTopics', 
            'totalTopics', 
            'totalPosts', 
            'totalCategories'
        ));
    }

    // Forum Categories Management
    public function categories()
    {
        $categories = ForumCategory::withCount(['topics', 'posts'])->orderBy('sort_order')->get();
        return view('admin.forums.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.forums.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        ForumCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
            'sort_order' => $request->sort_order,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('admin.forums.categories.index')
            ->with('success', 'Forum category created successfully.');
    }

    public function editCategory(ForumCategory $category)
    {
        return view('admin.forums.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, ForumCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
            'sort_order' => $request->sort_order,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('admin.forums.categories.index')
            ->with('success', 'Forum category updated successfully.');
    }

    public function destroyCategory(ForumCategory $category)
    {
        // Check if category has topics
        if ($category->topics()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing topics.');
        }

        $category->delete();
        
        return redirect()->route('admin.forums.categories.index')
            ->with('success', 'Forum category deleted successfully.');
    }

    // Topic Management
    public function topics(Request $request)
    {
        $query = ForumTopic::with(['student', 'category', 'course']);

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'last_activity_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $topics = $query->paginate(20)->withQueryString();
        $categories = ForumCategory::orderBy('name')->get();
        $courses = Course::orderBy('title')->get();

        return view('admin.forums.topics.index', compact('topics', 'categories', 'courses'));
    }

    public function showTopic(ForumTopic $topic)
    {
        $topic->load(['student', 'category', 'course', 'posts.student']);
        return view('admin.forums.topics.show', compact('topic'));
    }

    public function pinTopic(ForumTopic $topic)
    {
        $topic->update(['is_pinned' => !$topic->is_pinned]);
        
        $status = $topic->is_pinned ? 'pinned' : 'unpinned';
        return back()->with('success', "Topic {$status} successfully.");
    }

    public function lockTopic(ForumTopic $topic)
    {
        $topic->update(['is_locked' => !$topic->is_locked]);
        
        $status = $topic->is_locked ? 'locked' : 'unlocked';
        return back()->with('success', "Topic {$status} successfully.");
    }

    public function destroyTopic(ForumTopic $topic)
    {
        $topic->posts()->delete();
        $topic->delete();
        
        return redirect()->route('admin.forums.topics.index')
            ->with('success', 'Topic and all replies deleted successfully.');
    }

    // Posts Management
    public function posts(Request $request)
    {
        $query = ForumPost::with(['student', 'topic.category']);

        // Filter by topic
        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $posts = $query->paginate(20)->withQueryString();

        return view('admin.forums.posts.index', compact('posts'));
    }

    public function showPost(ForumPost $post)
    {
        $post->load(['student', 'topic.category', 'topic.course']);
        return view('admin.forums.posts.show', compact('post'));
    }

    public function toggleHelpfulPost(ForumPost $post)
    {
        $post->update(['is_solution' => !$post->is_solution]);
        
        $status = $post->is_solution ? 'marked as helpful' : 'unmarked as helpful';
        return back()->with('success', "Post {$status} successfully.");
    }

    public function destroyPost(ForumPost $post)
    {
        $topic = $post->topic;
        $post->delete();

        // Update topic reply count and last activity
        $topic->decrement('replies_count');
        
        $lastPost = $topic->posts()->orderBy('created_at', 'desc')->first();
        if ($lastPost) {
            $topic->update([
                'last_post_id' => $lastPost->id,
                'last_activity_at' => $lastPost->created_at
            ]);
        } else {
            $topic->update([
                'last_post_id' => null,
                'last_activity_at' => $topic->created_at
            ]);
        }
        
        return back()->with('success', 'Post deleted successfully.');
    }

    // Bulk Actions
    public function bulkActionTopics(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,pin,unpin,lock,unlock',
            'topic_ids' => 'required|array',
            'topic_ids.*' => 'exists:forum_topics,id'
        ]);

        $topics = ForumTopic::whereIn('id', $request->topic_ids);

        switch ($request->action) {
            case 'delete':
                ForumPost::whereIn('topic_id', $request->topic_ids)->delete();
                $topics->delete();
                $message = 'Selected topics deleted successfully.';
                break;
            case 'pin':
                $topics->update(['is_pinned' => true]);
                $message = 'Selected topics pinned successfully.';
                break;
            case 'unpin':
                $topics->update(['is_pinned' => false]);
                $message = 'Selected topics unpinned successfully.';
                break;
            case 'lock':
                $topics->update(['is_locked' => true]);
                $message = 'Selected topics locked successfully.';
                break;
            case 'unlock':
                $topics->update(['is_locked' => false]);
                $message = 'Selected topics unlocked successfully.';
                break;
        }

        return back()->with('success', $message);
    }

    public function bulkActionPosts(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,mark_helpful,unmark_helpful',
            'post_ids' => 'required|array',
            'post_ids.*' => 'exists:forum_posts,id'
        ]);

        $posts = ForumPost::whereIn('id', $request->post_ids);

        switch ($request->action) {
            case 'delete':
                // Update topic counts and last activity
                $topicIds = $posts->pluck('topic_id')->unique();
                $posts->delete();
                
                foreach ($topicIds as $topicId) {
                    $topic = ForumTopic::find($topicId);
                    if ($topic) {
                        $topic->replies_count = $topic->posts()->count();
                        $lastPost = $topic->posts()->orderBy('created_at', 'desc')->first();
                        
                        $topic->last_post_id = $lastPost ? $lastPost->id : null;
                        $topic->last_activity_at = $lastPost ? $lastPost->created_at : $topic->created_at;
                        $topic->save();
                    }
                }
                
                return back()->with('success', 'Selected posts deleted successfully.');
                
            case 'mark_helpful':
                $posts->update(['is_solution' => true]);
                return back()->with('success', 'Selected posts marked as helpful.');
                
            case 'unmark_helpful':
                $posts->update(['is_solution' => false]);
                return back()->with('success', 'Selected posts unmarked as helpful.');
        }

        return back()->with('error', 'Invalid action.');
    }

    // Statistics
    public function statistics(Request $request)
    {
        $days = $request->get('days', 30);
        $fromDate = now()->subDays($days);

        // Basic counts
        $stats = [
            'categories_count' => ForumCategory::count(),
            'topics_count' => ForumTopic::count(),
            'posts_count' => ForumPost::count(),
            'active_users_count' => Student::whereHas('forumTopics')->orWhereHas('forumPosts')->count(),
            'pinned_topics_count' => ForumTopic::where('is_pinned', true)->count(),
            'locked_topics_count' => ForumTopic::where('is_locked', true)->count(),
            'active_topics_count' => ForumTopic::where('last_activity_at', '>=', now()->subDays(7))->count(),
        ];

        // Recent activity (last 7 days)
        $recentActivity = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = ForumPost::whereDate('created_at', $date->format('Y-m-d'))->count();
            $recentActivity->put($date->format('M j'), $count);
        }
        $stats['recent_activity'] = $recentActivity;

        // Top categories with topic and post counts
        $stats['top_categories'] = ForumCategory::withCount(['topics', 'posts'])
            ->orderByDesc('posts_count')
            ->limit(5)
            ->get();

        // Top contributors
        $stats['top_contributors'] = Student::withCount(['forumTopics', 'forumPosts'])
            ->whereHas('forumTopics')
            ->orWhereHas('forumPosts')
            ->orderByDesc('forum_posts_count')
            ->orderByDesc('forum_topics_count')
            ->limit(5)
            ->get();

        // Popular topics (most replies)
        $stats['popular_topics'] = ForumTopic::with(['category'])
            ->selectRaw('*, (SELECT COUNT(*) FROM forum_posts WHERE topic_id = forum_topics.id) as posts_count')
            ->selectRaw('*, (SELECT COUNT(*) FROM forum_post_likes fpl JOIN forum_posts fp ON fpl.post_id = fp.id WHERE fp.topic_id = forum_topics.id) as likes_count')
            ->selectRaw('*, 0 as views_count') // Placeholder for views
            ->orderByDesc('replies_count')
            ->limit(5)
            ->get();

        // Forum health metrics
        $totalTopics = ForumTopic::count();
        $topicsWithReplies = ForumTopic::where('replies_count', '>', 0)->count();
        $stats['response_rate'] = $totalTopics > 0 ? round(($topicsWithReplies / $totalTopics) * 100) : 0;

        $totalPosts = ForumPost::count();
        $helpfulPosts = ForumPost::where('is_solution', true)->count();
        $stats['helpful_posts_ratio'] = $totalPosts > 0 ? round(($helpfulPosts / $totalPosts) * 100) : 0;

        $stats['avg_response_time'] = 'N/A'; // This would need timestamp analysis

        return view('admin.forums.statistics', compact('stats'));
    }
}