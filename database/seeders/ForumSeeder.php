<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ForumCategory;
use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\Student;
use App\Models\Course;

class ForumSeeder extends Seeder
{
    public function run()
    {
        // Create forum categories
        $categories = [
            [
                'name' => 'General Discussion',
                'description' => 'General topics and discussions',
                'color' => '#3B82F6',
                'sort_order' => 1,
            ],
            [
                'name' => 'Course Support',
                'description' => 'Get help with your courses',
                'color' => '#10B981',
                'sort_order' => 2,
            ],
            [
                'name' => 'Study Groups',
                'description' => 'Form and join study groups',
                'color' => '#8B5CF6',
                'sort_order' => 3,
            ],
            [
                'name' => 'Technical Issues',
                'description' => 'Report and discuss technical problems',
                'color' => '#F59E0B',
                'sort_order' => 4,
            ],
            [
                'name' => 'Feedback & Suggestions',
                'description' => 'Share your feedback and suggestions',
                'color' => '#EF4444',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            ForumCategory::create($category);
        }

        // Create some sample topics and posts
        $students = Student::take(3)->get();
        $courses = Course::take(3)->get();
        $categories = ForumCategory::all();

        if ($students->count() > 0 && $courses->count() > 0) {
            // Sample topics
            $topics = [
                [
                    'title' => 'Welcome to the Learning Management System!',
                    'content' => 'Hello everyone! Welcome to our LMS community. Feel free to introduce yourself and ask any questions you may have.',
                    'category_id' => $categories->where('name', 'General Discussion')->first()->id,
                    'is_pinned' => true,
                ],
                [
                    'title' => 'Tips for Effective Online Learning',
                    'content' => 'Share your best practices and tips for making the most out of online courses. What strategies work best for you?',
                    'category_id' => $categories->where('name', 'Study Groups')->first()->id,
                ],
                [
                    'title' => 'Having trouble with video playback',
                    'content' => 'Is anyone else experiencing issues with course videos not loading properly? The player seems to freeze after a few minutes.',
                    'category_id' => $categories->where('name', 'Technical Issues')->first()->id,
                ],
            ];

            foreach ($topics as $index => $topicData) {
                $topic = ForumTopic::create([
                    'title' => $topicData['title'],
                    'content' => $topicData['content'],
                    'category_id' => $topicData['category_id'],
                    'course_id' => $index === 1 ? $courses->first()->id : null,
                    'student_id' => $students->random()->id,
                    'is_pinned' => $topicData['is_pinned'] ?? false,
                    'last_activity_at' => now()->subHours(rand(1, 72)),
                ]);

                // Add some replies
                $replyCount = rand(0, 5);
                for ($i = 0; $i < $replyCount; $i++) {
                    $post = ForumPost::create([
                        'topic_id' => $topic->id,
                        'student_id' => $students->random()->id,
                        'content' => $this->getRandomReply(),
                    ]);
                    
                    $topic->increment('replies_count');
                    $topic->update([
                        'last_post_id' => $post->id,
                        'last_activity_at' => now()->subHours(rand(0, 24)),
                    ]);
                }
            }
        }
    }

    private function getRandomReply()
    {
        $replies = [
            "Thanks for sharing this! Really helpful information.",
            "I agree with your points. This has been my experience as well.",
            "Great topic! I'd love to hear more perspectives on this.",
            "This is exactly what I was looking for. Much appreciated!",
            "I had a similar issue and found this solution worked well for me.",
            "Interesting perspective. I hadn't considered that approach before.",
            "Thanks for the detailed explanation. Very clear and helpful.",
            "I'm having the same problem. Any updates on a solution?",
        ];

        return $replies[array_rand($replies)];
    }
}