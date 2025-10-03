<?php

namespace Tests\Feature;

use App\Events\EnrollmentCreated;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Disable foreign key constraints to avoid transaction issues in tests
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF');
        }
    }

    public function test_student_can_enroll_in_course()
    {
        Event::fake();

        $student = Student::factory()->create();
        $course = Course::factory()->create();

        // Create token for student
        $token = $student->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/enrollments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
        ]);

        Event::assertDispatched(EnrollmentCreated::class);
    }

    public function test_student_cannot_enroll_twice_in_same_course()
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create();

        // Create first enrollment
        Enrollment::factory()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
        ]);

        $token = $student->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/enrollments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $response->assertStatus(422)
                 ->assertJson(['message' => 'Already enrolled']);
    }

    public function test_enrollment_requires_valid_student_and_course()
    {
        $student = Student::factory()->create();
        $token = $student->createToken('test-token')->plainTextToken;

        // Test with invalid student ID
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/enrollments', [
            'student_id' => 999,
            'course_id' => Course::factory()->create()->id,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['student_id']);

        // Test with invalid course ID
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/enrollments', [
            'student_id' => $student->id,
            'course_id' => 999,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['course_id']);
    }

    public function test_enrollment_requires_authentication()
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create();

        $response = $this->postJson('/api/enrollments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $response->assertStatus(401);
    }

    public function test_student_can_view_their_courses()
    {
        $student = Student::factory()->create();
        $courses = Course::factory(3)->create();

        // Enroll student in courses
        foreach ($courses as $course) {
            Enrollment::factory()->create([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'status' => 'active',
            ]);
        }

        $token = $student->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/my-courses');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'full_name',
                         'email',
                         'date_of_birth',
                         'enrolled_courses' => [
                             '*' => [
                                 'id',
                                 'course_code',
                                 'course_name',
                                 'credits',
                             ]
                         ]
                     ]
                 ]);
    }

    public function test_can_view_student_with_courses()
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create();

        Enrollment::factory()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
        ]);

        $response = $this->getJson("/api/students/{$student->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'full_name',
                         'email',
                         'date_of_birth',
                         'enrolled_courses',
                     ]
                 ]);
    }

    public function test_students_are_paginated()
    {
        Student::factory(15)->create();

        $response = $this->getJson('/api/students');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data',
                     'links',
                     'meta' => [
                         'current_page',
                         'per_page',
                         'total',
                     ]
                 ]);

        // Should have 10 students per page
        $this->assertCount(10, $response->json('data'));
    }
}