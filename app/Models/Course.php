<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'course_code',
        'course_name',
        'description',
        'credits',
        'instructor',
        'start_date',
        'end_date',
        'price',
        'max_students',
        'status',
        'image_url',
        'category'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
        'credits' => 'integer',
        'max_students' => 'integer'
    ];

    /**
     * Relationship: A course can have many students.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->withPivot('enrolled_on', 'status')
            ->withTimestamps();
    }

    /**
     * Relationship: A course has many enrollments.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Relationship: A course belongs to a category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: A course has many assessments.
     */
    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    /**
     * Relationship: A course has many certificates.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Relationship: A course has many announcements.
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')->where('start_date', '>', now());
    }

    // Accessors & Mutators
    public function getAvailableSlotsAttribute()
    {
        return $this->max_students - $this->enrollments()->where('status', 'enrolled')->count();
    }

    public function getIsFullAttribute()
    {
        return $this->available_slots <= 0;
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    public function getDurationAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInWeeks($this->end_date) . ' weeks';
        }
        return 'Duration not set';
    }

    public function getTitleAttribute()
    {
        return $this->course_name;
    }

    /**
     * Get the proper image URL (handles both local storage and external URLs)
     */
    public function getImageAttribute()
    {
        if (!$this->image_url) {
            return null;
        }

        // If it's already a full URL (starts with http), return as is
        if (str_starts_with($this->image_url, 'http')) {
            return $this->image_url;
        }

        // Otherwise, treat as local storage file
        return \Storage::url($this->image_url);
    }

    /**
     * Get a fallback icon class based on category or course name
     */
    public function getFallbackIconAttribute()
    {
        if ($this->category) {
            return match(strtolower($this->category->name)) {
                'business', 'marketing' => 'business_center',
                'programming', 'data science' => 'code',
                'design' => 'palette',
                'cooking', 'culinary' => 'restaurant',
                default => 'school'
            };
        }

        // Fallback based on course name keywords
        $courseName = strtolower($this->course_name);
        if (str_contains($courseName, 'cook') || str_contains($courseName, 'baking') || str_contains($courseName, 'food')) {
            return 'restaurant';
        } elseif (str_contains($courseName, 'code') || str_contains($courseName, 'programming')) {
            return 'code';
        } elseif (str_contains($courseName, 'design') || str_contains($courseName, 'art')) {
            return 'palette';
        } elseif (str_contains($courseName, 'business') || str_contains($courseName, 'management')) {
            return 'business_center';
        }

        return 'school';
    }
}