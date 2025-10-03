<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'date_of_birth',
        'password',
        'role',
        'student_id',
        'bio',
        'notification_preferences',
        'privacy_settings',
    ];

    /**
     * Check if student is learner (always true for students)
     */
    public function isLearner(): bool
    {
        return $this->role === 'learner';
    }

    protected $casts = [
        'date_of_birth' => 'date',
        'password' => 'hashed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Accessor for full_name that concatenates first and last names.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Relationship: A student can enroll in many courses.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot('enrolled_on', 'status')
            ->withTimestamps();
    }

    /**
     * Relationship: A student has many enrollments.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Relationship: A student has many certificates.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Relationship: A student has many grading records.
     */
    public function gradings()
    {
        return $this->hasMany(Grading::class);
    }

    /**
     * Relationship: A student belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    /**
     * Relationship: A student can create many forum topics.
     */
    public function forumTopics()
    {
        return $this->hasMany(ForumTopic::class);
    }

    /**
     * Relationship: A student can create many forum posts.
     */
    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class);
    }
}