<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
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
}