<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subjects = ['MATH', 'ENG', 'SCI', 'HIST', 'CS', 'PHYS', 'CHEM', 'BIO'];
        
        // Default course images from Unsplash
        $courseImages = [
            'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80', // Cooking utensils
            'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80', // Kitchen scene
            'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80', // Chef cooking
            'https://images.unsplash.com/photo-1556908114-4bdc5b1c2585?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80', // Kitchen preparation
            'https://images.unsplash.com/photo-1577303935007-0d306ee638cf?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80', // Cooking class
        ];
        
        return [
            'course_code' => fake()->unique()->regexify('[A-Z]{2,4}[0-9]{3,4}'),
            'course_name' => fake()->sentence(3, true),
            'credits' => fake()->numberBetween(1, 6),
            'description' => fake()->paragraph(3),
            'instructor' => 'Chef ' . fake()->firstName() . ' ' . fake()->lastName(),
            'image_url' => fake()->randomElement($courseImages),
            'price' => fake()->numberBetween(29, 199),
            'max_students' => fake()->numberBetween(15, 50),
            'status' => fake()->randomElement(['active', 'inactive']),
            'start_date' => fake()->dateTimeBetween('now', '+30 days'),
            'end_date' => fake()->dateTimeBetween('+60 days', '+120 days'),
        ];
    }
}