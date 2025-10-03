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
        
        return [
            'course_code' => fake()->unique()->regexify('[A-Z]{2,4}[0-9]{3,4}'),
            'course_name' => fake()->sentence(3, true),
            'credits' => fake()->numberBetween(1, 6),
        ];
    }
}