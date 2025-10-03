<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class AdminFeatureSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample categories
        $categories = [
            [
                'name' => 'Programming',
                'slug' => 'programming',
                'description' => 'Learn various programming languages and frameworks',
                'color' => '#3b82f6',
                'icon' => 'code',
                'is_active' => true
            ],
            [
                'name' => 'Design',
                'slug' => 'design',
                'description' => 'UI/UX design and graphic design courses',
                'color' => '#ec4899',
                'icon' => 'design_services',
                'is_active' => true
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Business management and entrepreneurship',
                'color' => '#22c55e',
                'icon' => 'business',
                'is_active' => true
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Digital marketing and advertising strategies',
                'color' => '#f97316',
                'icon' => 'campaign',
                'is_active' => true
            ],
            [
                'name' => 'Data Science',
                'slug' => 'data-science',
                'description' => 'Data analysis, machine learning, and AI',
                'color' => '#8b5cf6',
                'icon' => 'analytics',
                'is_active' => true
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Create sample tutors if they don't exist
        $tutors = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@lms.com',
                'password' => bcrypt('password'),
                'role' => 'tutor'
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@lms.com',
                'password' => bcrypt('password'),
                'role' => 'tutor'
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.brown@lms.com',
                'password' => bcrypt('password'),
                'role' => 'tutor'
            ]
        ];

        foreach ($tutors as $tutorData) {
            User::firstOrCreate(
                ['email' => $tutorData['email']],
                $tutorData
            );
        }

        $this->command->info('Admin feature sample data seeded successfully!');
    }
}
