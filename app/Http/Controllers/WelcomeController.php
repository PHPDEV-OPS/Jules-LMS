<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Category;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Certificate;

class WelcomeController extends Controller
{
    public function index()
    {
        // Get featured courses (active courses with most enrollments)
        $featuredCourses = Course::active()
            ->withCount('enrollments')
            ->with(['category', 'enrollments'])
            ->orderBy('enrollments_count', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($course) {
                // Ensure course has a proper description
                if (empty($course->description)) {
                    $course->description = "Learn " . strtolower($course->title) . " with hands-on projects and expert guidance. Perfect for beginners and professionals looking to advance their skills.";
                }
                return $course;
            });

        // Get active categories that have courses
        $categories = Category::active()
            ->whereHas('courses')
            ->withCount('courses')
            ->limit(8)
            ->get();

        // Get platform statistics
        $statistics = [
            'total_courses' => Course::active()->count(),
            'total_students' => Student::count(),
            'total_enrollments' => Enrollment::where('status', 'active')->count(),
            'certificates_issued' => Certificate::where('status', 'issued')->count(),
        ];

        // Get recent testimonials (from certificates or create sample data)
        $testimonials = [
            [
                'name' => 'Sarah Anderson',
                'role' => 'Full Stack Developer',
                'rating' => 5,
                'comment' => 'This platform completely transformed my career. The courses are well-structured and the instructors are incredibly knowledgeable. Highly recommended!',
                'initials' => 'SA',
                'color' => 'bg-blue-600'
            ],
            [
                'name' => 'Michael Johnson',
                'role' => 'Data Scientist',
                'rating' => 5,
                'comment' => 'The flexibility to learn at my own pace while having access to expert instructors made all the difference. I landed my dream job!',
                'initials' => 'MJ',
                'color' => 'bg-green-600'
            ],
            [
                'name' => 'Emily Brown',
                'role' => 'UX Designer',
                'rating' => 5,
                'comment' => 'Amazing learning experience! The practical projects and real-world examples helped me apply what I learned immediately in my work.',
                'initials' => 'EB',
                'color' => 'bg-purple-600'
            ]
        ];

        return view('welcome', compact('featuredCourses', 'categories', 'statistics', 'testimonials'));
    }

    /**
     * Get icon for category
     */
    private function getCategoryIcon($categoryName)
    {
        $icons = [
            'Programming' => 'code',
            'Data Science' => 'analytics', 
            'Design' => 'palette',
            'Marketing' => 'campaign',
            'Business' => 'business',
            'Security' => 'security',
            'Technology' => 'computer',
            'Education' => 'school',
            'Health' => 'local_hospital',
            'Finance' => 'account_balance'
        ];

        return $icons[$categoryName] ?? 'book';
    }

    /**
     * Get color classes for category
     */
    private function getCategoryColors($index)
    {
        $colorSets = [
            ['bg' => 'bg-gradient-to-br from-blue-400 to-blue-600', 'badge' => 'bg-blue-100 text-blue-800'],
            ['bg' => 'bg-gradient-to-br from-green-400 to-green-600', 'badge' => 'bg-green-100 text-green-800'],
            ['bg' => 'bg-gradient-to-br from-purple-400 to-purple-600', 'badge' => 'bg-purple-100 text-purple-800'],
            ['bg' => 'bg-gradient-to-br from-red-400 to-red-600', 'badge' => 'bg-red-100 text-red-800'],
            ['bg' => 'bg-gradient-to-br from-yellow-400 to-yellow-600', 'badge' => 'bg-yellow-100 text-yellow-800'],
            ['bg' => 'bg-gradient-to-br from-indigo-400 to-indigo-600', 'badge' => 'bg-indigo-100 text-indigo-800']
        ];

        return $colorSets[$index % count($colorSets)];
    }
}