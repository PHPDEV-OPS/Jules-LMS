<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the original test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create demo student for authentication testing
        Student::create([
            'first_name' => 'Demo',
            'last_name' => 'User',
            'email' => 'demo@example.com',
            'date_of_birth' => '1990-01-01',
            'password' => bcrypt('password'),
        ]);

        // Create students with cooking-interested names
        $studentData = [
            ['first_name' => 'Jannete', 'last_name' => 'Adams', 'email' => 'jannete.adams@example.com'],
            ['first_name' => 'Jennifer', 'last_name' => 'Diaz', 'email' => 'jennifer.diaz@example.com'],
            ['first_name' => 'Jason', 'last_name' => 'Gold', 'email' => 'jason.gold@example.com'],
            ['first_name' => 'Kate', 'last_name' => 'Eddison', 'email' => 'kate.eddison@example.com'],
            ['first_name' => 'Maria', 'last_name' => 'Rodriguez', 'email' => 'maria.rodriguez@example.com'],
            ['first_name' => 'David', 'last_name' => 'Chen', 'email' => 'david.chen@example.com'],
            ['first_name' => 'Sarah', 'last_name' => 'Johnson', 'email' => 'sarah.johnson@example.com'],
            ['first_name' => 'Michael', 'last_name' => 'Thompson', 'email' => 'michael.thompson@example.com'],
        ];
        
        $students = collect();
        foreach ($studentData as $data) {
            $students->push(Student::factory()->create($data));
        }
        
        // Create additional random students
        $additionalStudents = Student::factory(12)->create();
        $students = $students->concat($additionalStudents);

        // Create cooking courses with specific course codes and images
        $cookingCourses = [
            ['code' => 'COOK101', 'name' => 'The Secrets of Cakes', 'credits' => 3, 'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
            ['code' => 'BREAD201', 'name' => 'All About Bread Baking', 'credits' => 4, 'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
            ['code' => 'TEA301', 'name' => 'The Ancient Art of Tea', 'credits' => 2, 'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
            ['code' => 'KOREAN101', 'name' => 'Cooking Korean Food', 'credits' => 3, 'image' => 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
            ['code' => 'BASIC101', 'name' => 'Cooking Made Simple', 'credits' => 2, 'image' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
            ['code' => 'BURGER201', 'name' => 'Cooking Burgers', 'credits' => 2, 'image' => 'https://images.unsplash.com/photo-1551615593-ef5fe247e8f7?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
            ['code' => 'MEXICO301', 'name' => 'Mexican Recipes', 'credits' => 3, 'image' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
            ['code' => 'SAUCE101', 'name' => 'Master Sauce Making', 'credits' => 3, 'image' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
            ['code' => 'PASTA201', 'name' => 'Italian Pasta Mastery', 'credits' => 4, 'image' => 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
            ['code' => 'GRILL301', 'name' => 'Grilling Fundamentals', 'credits' => 3, 'image' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
            ['code' => 'KNIFE101', 'name' => 'Knife Skills Basics', 'credits' => 1, 'image' => 'https://images.unsplash.com/photo-1567016432779-094069958ea5?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
            ['code' => 'SEAFOOD201', 'name' => 'Fresh Seafood Cooking', 'credits' => 4, 'image' => 'https://images.unsplash.com/photo-1544943910-4c1dc44aab44?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
            ['code' => 'VEGAN301', 'name' => 'Plant-Based Cooking', 'credits' => 3, 'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
            ['code' => 'DESSERT201', 'name' => 'Dessert Artistry', 'credits' => 3, 'image' => 'https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
        ];
        
        foreach ($cookingCourses as $courseData) {
            Course::factory()->create([
                'course_code' => $courseData['code'],
                'course_name' => $courseData['name'],
                'credits' => $courseData['credits'],
                'description' => $this->getCookingDescription($courseData['name']),
                'image_url' => $courseData['image'],
                'instructor' => $this->getRandomInstructor(),
                'price' => rand(49, 299),
                'max_students' => rand(20, 100),
                'status' => 'active',
                'start_date' => now()->addDays(rand(1, 30)),
                'end_date' => now()->addDays(rand(60, 120)),
            ]);
        }

        $courses = Course::all();

        // Create some enrollments
        $students->each(function (Student $student) use ($courses) {
            // Each student enrolls in 2-5 random courses
            $randomCourses = $courses->random(rand(2, 5));
            
            foreach ($randomCourses as $course) {
                // Avoid duplicate enrollments
                if (!Enrollment::where('student_id', $student->id)->where('course_id', $course->id)->exists()) {
                    Enrollment::factory()->create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'status' => rand(1, 10) > 2 ? 'active' : 'dropped', // 80% active, 20% dropped
                    ]);
                }
            }
        });
    }

    private function getCookingDescription($courseName): string
    {
        return match($courseName) {
            'The Secrets of Cakes' => 'Master the art of cake making from basic sponges to elaborate layer cakes. Learn professional techniques for mixing, baking, and decorating that will transform your dessert game.',
            'All About Bread Baking' => 'Discover the ancient craft of bread making. From understanding yeast and fermentation to creating perfect crusts and crumb structures, become a confident bread baker.',
            'The Ancient Art of Tea' => 'Explore the sophisticated world of tea preparation, brewing techniques, and food pairings. Learn about different tea varieties and their cultural significance.',
            'Cooking Korean Food' => 'Journey through Korean cuisine with authentic recipes and techniques. Master kimchi, bulgogi, bibimbap, and traditional fermentation methods.',
            'Cooking Made Simple' => 'Perfect for beginners! Learn fundamental cooking techniques, knife skills, and essential recipes that form the foundation of great cooking.',
            'Cooking Burgers' => 'Elevate the humble burger with professional techniques. Learn meat selection, seasoning, cooking methods, and creative topping combinations.',
            'Mexican Recipes' => 'Dive into the vibrant flavors of Mexican cuisine. Master traditional techniques for salsas, moles, and authentic regional dishes.',
            'Master Sauce Making' => 'Learn the five mother sauces and their variations. Master emulsification, reduction, and flavor balancing to elevate any dish.',
            'Italian Pasta Mastery' => 'From fresh pasta making to classic sauce pairings, discover the secrets of authentic Italian pasta preparation and regional specialties.',
            'Grilling Fundamentals' => 'Master outdoor cooking with proper heat management, marinades, and grilling techniques for meats, vegetables, and seafood.',
            'Knife Skills Basics' => 'Build confidence in the kitchen with proper knife handling, cutting techniques, and knife maintenance for safer, more efficient cooking.',
            'Fresh Seafood Cooking' => 'Learn to select, prepare, and cook various seafood with confidence. Master techniques for fish, shellfish, and seasonal preparations.',
            'Plant-Based Cooking' => 'Explore the creative world of vegan cuisine with protein alternatives, flavor building, and nutritionally balanced plant-based meals.',
            'Dessert Artistry' => 'Create stunning desserts with professional techniques for pastry, chocolate work, and plating that will impress family and friends.',
            default => 'An expertly crafted cooking course designed to elevate your culinary skills with professional techniques and authentic flavors.',
        };
    }

    private function getRandomInstructor(): string
    {
        $instructors = [
            'Chef Maria Rodriguez',
            'Chef David Chen',
            'Chef Sarah Johnson',
            'Chef Michael Thompson',
            'Chef Elena Vasquez',
            'Chef Robert Kim',
            'Chef Isabella Garcia',
            'Chef James Wilson',
            'Chef Anna Petrov',
            'Chef Carlos Mendez',
            'Chef Sophie Laurent',
            'Chef Ahmad Hassan',
            'Chef Grace Lee',
            'Chef Marco Rossi'
        ];
        
        return $instructors[array_rand($instructors)];
    }
}
