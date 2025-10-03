<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_name_accessor()
    {
        $student = Student::factory()->make([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('John Doe', $student->full_name);
    }

    public function test_student_can_have_many_courses()
    {
        $student = Student::factory()->create();
        $courses = Course::factory(3)->create();

        foreach ($courses as $course) {
            Enrollment::factory()->create([
                'student_id' => $student->id,
                'course_id' => $course->id,
            ]);
        }

        $this->assertCount(3, $student->courses);
    }

    public function test_student_course_relationship_includes_pivot_data()
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create();

        $enrollment = Enrollment::factory()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
        ]);

        $studentCourse = $student->courses()->first();

        $this->assertNotNull($studentCourse->pivot);
        $this->assertEquals('active', $studentCourse->pivot->status);
        $this->assertEquals($enrollment->enrolled_on, $studentCourse->pivot->enrolled_on);
    }
}