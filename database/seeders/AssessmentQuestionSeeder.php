<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\Course;

class AssessmentQuestionSeeder extends Seeder
{
    public function run()
    {
        $courses = Course::take(3)->get();
        
        foreach ($courses as $course) {
            // Create sample assessments
            $assessments = [
                [
                    'title' => 'Chapter 1 Quiz',
                    'description' => 'Test your knowledge of the fundamentals covered in Chapter 1.',
                    'course_id' => $course->id,
                    'type' => 'quiz',
                    'is_active' => true,
                    'duration_minutes' => 30,
                    'total_marks' => 100,
                    'passing_marks' => 70,
                    'attempts_allowed' => 3,
                    'instructions' => 'Read each question carefully and select the best answer.',
                ],
                [
                    'title' => 'Midterm Examination',
                    'description' => 'Comprehensive assessment covering topics from the first half of the course.',
                    'course_id' => $course->id,
                    'type' => 'exam',
                    'is_active' => true,
                    'duration_minutes' => 90,
                    'total_marks' => 200,
                    'passing_marks' => 140,
                    'attempts_allowed' => 1,
                    'instructions' => 'This is a comprehensive examination. You have 90 minutes to complete all questions.',
                ],
            ];

            foreach ($assessments as $assessmentData) {
                $assessment = Assessment::create($assessmentData);

                // Add sample questions for each assessment
                $this->createQuestionsForAssessment($assessment);
            }
        }
    }

    private function createQuestionsForAssessment($assessment)
    {
        $questions = [
            // Multiple Choice Questions
            [
                'assessment_id' => $assessment->id,
                'question_text' => 'What is the primary purpose of a Learning Management System (LMS)?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'a' => 'To replace traditional classrooms entirely',
                    'b' => 'To deliver, track, and manage educational content and training programs',
                    'c' => 'To grade assignments automatically',
                    'd' => 'To connect students with social media platforms'
                ],
                'correct_answer' => 'b',
                'points' => 10,
                'order' => 1,
            ],
            [
                'assessment_id' => $assessment->id,
                'question_text' => 'Which of the following is NOT typically a feature of an LMS?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'a' => 'Course content delivery',
                    'b' => 'Progress tracking',
                    'c' => 'Video game development',
                    'd' => 'Assessment and grading'
                ],
                'correct_answer' => 'c',
                'points' => 10,
                'order' => 2,
            ],

            // True/False Questions
            [
                'assessment_id' => $assessment->id,
                'question_text' => 'Online learning can be just as effective as traditional face-to-face learning when properly implemented.',
                'question_type' => 'true_false',
                'options' => ['true' => 'True', 'false' => 'False'],
                'correct_answer' => 'true',
                'points' => 5,
                'order' => 3,
            ],
            [
                'assessment_id' => $assessment->id,
                'question_text' => 'All students learn in exactly the same way and at the same pace.',
                'question_type' => 'true_false',
                'options' => ['true' => 'True', 'false' => 'False'],
                'correct_answer' => 'false',
                'points' => 5,
                'order' => 4,
            ],

            // Short Answer Questions
            [
                'assessment_id' => $assessment->id,
                'question_text' => 'Explain two key benefits of using an LMS for educational institutions.',
                'question_type' => 'short_answer',
                'options' => null,
                'correct_answer' => 'Key benefits include: 1) Centralized content management and delivery, 2) Automated tracking of student progress and performance, 3) Improved accessibility for remote learners, 4) Streamlined grading and feedback processes.',
                'points' => 15,
                'order' => 5,
            ],
        ];

        // Create a subset of questions for each assessment
        $questionsToCreate = array_slice($questions, 0, rand(3, 5));
        
        foreach ($questionsToCreate as $questionData) {
            AssessmentQuestion::create($questionData);
        }

        // Questions created successfully
    }
}