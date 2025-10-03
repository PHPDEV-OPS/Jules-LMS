<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    /**
     * Display forums overview
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Get forum categories with topic counts
        $categories = ForumCategory::where('is_active', true)
            ->withCount('topics')
            ->orderBy('sort_order')
            ->get();
        
        // Get recent topics
        $recentTopics = ForumTopic::with(['student', 'category', 'course', 'lastPost.student'])
            ->orderBy('last_activity_at', 'desc')
            ->take(10)
            ->get();
        
        // Get popular topics (most replies)
        $popularTopics = ForumTopic::with(['student', 'category', 'course'])
            ->orderBy('replies_count', 'desc')
            ->take(5)
            ->get();
        
        // Get student's enrolled courses for topic creation
        $enrolledCourses = $student->enrollments()
            ->where('status', 'active')
            ->with('course')
            ->get()
            ->pluck('course');
        
        return view('student.forums.index', compact('categories', 'recentTopics', 'popularTopics', 'enrolledCourses', 'student'));
    }

    /**
     * Display topics in a category
     */
    public function category(ForumCategory $category)
    {
        $student = Auth::guard('student')->user();
        
        // Get topics in this category
        $topics = ForumTopic::where('category_id', $category->id)
            ->with(['student', 'course', 'lastPost.student'])
            ->orderBy('is_pinned', 'desc')
            ->orderBy('last_activity_at', 'desc')
            ->paginate(20);
        
        return view('student.forums.category', compact('category', 'topics', 'student'));
    }

    /**
     * Display a specific topic
     */
    public function topic(ForumTopic $topic)
    {
        $student = Auth::guard('student')->user();
        
        // Increment views
        $topic->incrementViews();
        
        // Get posts with likes info
        $posts = ForumPost::where('topic_id', $topic->id)
            ->with(['student'])
            ->withCount('likes')
            ->orderBy('created_at', 'asc')
            ->paginate(10);
        
        // Load the topic with relationships
        $topic->load(['student', 'category', 'course']);
        
        return view('student.forums.topic', compact('topic', 'posts', 'student'));
    }

    /**
     * Create a new topic form
     */
    public function createTopic()
    {
        $student = Auth::guard('student')->user();
        
        // Get categories and courses
        $categories = ForumCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        $enrolledCourses = $student->enrollments()
            ->where('status', 'active')
            ->with('course')
            ->get()
            ->pluck('course');
        
        return view('student.forums.create-topic', compact('categories', 'enrolledCourses', 'student'));
    }

    /**
     * Store a new topic
     */
    public function storeTopic(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        $validated = $request->validate([
            'category_id' => 'required|exists:forum_categories,id',
            'course_id' => 'nullable|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
        ]);
        
        // Verify course enrollment if course_id is provided
        if ($validated['course_id']) {
            $enrollment = $student->enrollments()
                ->where('course_id', $validated['course_id'])
                ->where('status', 'active')
                ->first();
            
            if (!$enrollment) {
                return back()->withErrors(['course_id' => 'You are not enrolled in this course.']);
            }
        }
        
        $topic = ForumTopic::create([
            'category_id' => $validated['category_id'],
            'course_id' => $validated['course_id'],
            'student_id' => $student->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'last_activity_at' => now(),
        ]);
        
        return redirect()->route('student.forums.topic', $topic)
            ->with('success', 'Topic created successfully!');
    }

    /**
     * Reply to a topic
     */
    public function reply(Request $request, ForumTopic $topic)
    {
        $student = Auth::guard('student')->user();
        
        if ($topic->is_locked) {
            return back()->withErrors(['message' => 'This topic is locked and cannot accept new replies.']);
        }
        
        $validated = $request->validate([
            'content' => 'required|string|max:10000',
        ]);
        
        $post = ForumPost::create([
            'topic_id' => $topic->id,
            'student_id' => $student->id,
            'content' => $validated['content'],
        ]);
        
        // Update topic stats
        $topic->increment('replies_count');
        $topic->update([
            'last_activity_at' => now(),
            'last_post_id' => $post->id,
        ]);
        
        return redirect()->route('student.forums.topic', $topic)
            ->with('success', 'Reply posted successfully!');
    }

    /**
     * Like/unlike a post
     */
    public function likePost(Request $request, ForumPost $post)
    {
        $student = Auth::guard('student')->user();
        
        $liked = $post->toggleLike($student->id);
        
        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $post->fresh()->likes_count,
        ]);
    }
}