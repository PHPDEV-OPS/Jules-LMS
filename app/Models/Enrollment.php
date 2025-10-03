<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'enrolled_on',
        'status',
        'completion_date',
        'grade',
        'progress',
        'notes'
    ];

    protected $casts = [
        'enrolled_on' => 'datetime',
        'completion_date' => 'datetime',
        'progress' => 'integer'
    ];

    /**
     * Relationship: An enrollment belongs to a student.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relationship: An enrollment belongs to a course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'enrolled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    // Accessors & Mutators
    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'enrolled';
    }

    public function getFormattedGradeAttribute()
    {
        return $this->grade ? $this->grade . '%' : 'Not graded';
    }

    public function getProgressPercentageAttribute()
    {
        return $this->progress ?? 0;
    }

    public function getDurationAttribute()
    {
        if ($this->enrolled_on && $this->completion_date) {
            return $this->enrolled_on->diffInDays($this->completion_date) . ' days';
        } elseif ($this->enrolled_on) {
            return $this->enrolled_on->diffInDays(now()) . ' days enrolled';
        }
        return 'No duration data';
    }
}