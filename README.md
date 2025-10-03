# Laravel Course Enrollment System

A comprehensive Learning Management System (LMS) built with Laravel, implementing course enrollment features including students, courses, and enrollment management.

## Features Implemented

### Section B — Practical Short Tasks

#### 5. Migration & Model (8 marks) ✅
- ✅ Students table migration with: `id`, `first_name`, `last_name`, `email` (unique), `date_of_birth`, `timestamps`
- ✅ Student model with `full_name` accessor that concatenates first and last names
- ✅ Additional authentication fields for API functionality

#### 6. Form Request Validation (6 marks) ✅
- ✅ `StoreCourseRequest` with validation rules:
  - `course_code`: required, string, unique in courses
  - `course_name`: required, string, max 255
  - `credits`: required, integer, min 1, max 6
- ✅ Usage demonstrated in `CourseController@store`

#### 7. Blade Component (6 marks) ✅
- ✅ `<x-student-card>` component accepting `:student` prop
- ✅ Displays full name and email in styled card with Tailwind CSS
- ✅ Example usage in loop with pagination

#### 8. API Resource & Pagination (10 marks) ✅
- ✅ `StudentResource` returning: `id`, `full_name`, `email`, `date_of_birth`, enrolled courses list
- ✅ Controller method `index()` with JSON pagination (10 students per page)
- ✅ Proper resource relationships and data structure

### Section C — Full Practical Task (50 marks)

#### Course Enrollment Feature ✅
**Entities Implemented:**
- ✅ Student, Course, Enrollment models with proper relationships
- ✅ Many-to-many relationship between Students and Courses
- ✅ Enrollment pivot table with: `student_id`, `course_id`, `enrolled_on`, `status`

**API Endpoints:**
1. ✅ `GET /api/students/{id}` → Returns student with their courses
2. ✅ `POST /api/enrollments` → Enroll student in course with validation:
   - Student and course must exist
   - Prevents duplicate enrollment
3. ✅ `GET /api/my-courses` → Returns authenticated student's courses

**Additional Features:**
- ✅ Database transactions for safe enrollment creation
- ✅ `EnrollmentCreated` event fired after successful enrollment
- ✅ Authentication required for enrollment endpoints
- ✅ Comprehensive PHPUnit tests for enrollment functionality

## Database Schema

### Students Table
```sql
- id (primary key)
- first_name (string)
- last_name (string) 
- email (unique string)
- date_of_birth (date)
- password (hashed)
- remember_token
- timestamps
```

### Courses Table
```sql
- id (primary key)
- course_code (unique string)
- course_name (string)
- credits (integer, 1-6)
- timestamps
```

### Enrollments Table
```sql
- id (primary key)
- student_id (foreign key)
- course_id (foreign key)
- enrolled_on (timestamp)
- status (enum: 'active', 'dropped')
- timestamps
- unique constraint on (student_id, course_id)
```

## Model Relationships

```php
class Student extends Authenticatable {
    public function courses() {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot('enrolled_on', 'status')
            ->withTimestamps();
    }
}

class Course extends Model {
    public function students() {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->withPivot('enrolled_on', 'status')
            ->withTimestands();
    }
}
```

## API Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/students` | List paginated students | No |
| GET | `/api/students/{id}` | Get student with courses | No |
| GET | `/api/my-courses` | Get authenticated student's courses | Yes |
| POST | `/api/enrollments` | Enroll student in course | Yes |
| GET | `/api/courses` | List all courses | No |
| POST | `/api/courses` | Create new course | No |

## Installation & Setup

1. **Clone and Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Setup**
   ```bash
   # Create SQLite database (already exists)
   php artisan migrate
   php artisan db:seed
   ```

4. **Install Laravel Sanctum (for API authentication)**
   ```bash
   composer require laravel/sanctum
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   php artisan migrate
   ```

## Running the Application

1. **Start the Development Server**
   ```bash
   php artisan serve
   ```

2. **Run Tests**
   ```bash
   php artisan test
   ```

3. **Access the Application**
   - Web Interface: `http://localhost:8000/students`
   - API Endpoints: `http://localhost:8000/api/students`

## Testing

The project includes comprehensive tests:

### Feature Tests
- ✅ Student enrollment functionality
- ✅ Duplicate enrollment prevention
- ✅ Authentication requirements
- ✅ Validation testing
- ✅ API pagination testing

### Unit Tests
- ✅ Student model `full_name` accessor
- ✅ Model relationship testing
- ✅ Pivot data validation

Run tests with: `php artisan test`

## Usage Examples

### Enroll a Student in a Course
```bash
curl -X POST http://localhost:8000/api/enrollments \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token}" \
  -d '{"student_id": 1, "course_id": 1}'
```

### Get Student's Courses
```bash
curl -X GET http://localhost:8000/api/my-courses \
  -H "Authorization: Bearer {token}"
```

### Using the Blade Component
```blade
@foreach($students as $student)
    <x-student-card :student="$student" />
@endforeach
```

## File Structure

```
app/
├── Events/
│   └── EnrollmentCreated.php
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── EnrollmentController.php
│   │   │   └── StudentController.php
│   │   ├── CourseController.php
│   │   └── StudentWebController.php
│   ├── Requests/
│   │   └── StoreCourseRequest.php
│   └── Resources/
│       ├── CourseResource.php
│       └── StudentResource.php
├── Models/
│   ├── Course.php
│   ├── Enrollment.php
│   └── Student.php
database/
├── factories/
│   ├── CourseFactory.php
│   ├── EnrollmentFactory.php
│   └── StudentFactory.php
├── migrations/
│   ├── 2025_10_03_000001_create_students_table.php
│   ├── 2025_10_03_000002_create_courses_table.php
│   └── 2025_10_03_000003_create_enrollments_table.php
└── seeders/
    └── DatabaseSeeder.php
resources/views/
├── components/
│   └── student-card.blade.php
└── students/
    └── index.blade.php
tests/
├── Feature/
│   └── EnrollmentTest.php
└── Unit/
    └── StudentTest.php
```

## License

This project is licensed under the MIT License.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
