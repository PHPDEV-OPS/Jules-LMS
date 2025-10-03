<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grading extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'student_id',
        'marks_obtained',
        'total_marks',
        'percentage',
        'grade',
        'status',
        'feedback',
        'graded_by',
        'graded_at',
        'submission_date'
    ];

    protected $casts = [
        'graded_at' => 'datetime',
        'submission_date' => 'datetime',
        'percentage' => 'decimal:2'
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function getGradeColorAttribute()
    {
        $colors = [
            'A+' => 'text-green-600 bg-green-100',
            'A' => 'text-green-600 bg-green-100',
            'B+' => 'text-blue-600 bg-blue-100',
            'B' => 'text-blue-600 bg-blue-100',
            'C+' => 'text-yellow-600 bg-yellow-100',
            'C' => 'text-yellow-600 bg-yellow-100',
            'F' => 'text-red-600 bg-red-100'
        ];

        return $colors[$this->grade] ?? 'text-gray-600 bg-gray-100';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'passed' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'in_review' => 'bg-blue-100 text-blue-800'
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($grading) {
            // Calculate percentage
            if ($grading->total_marks > 0) {
                $grading->percentage = ($grading->marks_obtained / $grading->total_marks) * 100;
            }

            // Determine grade based on percentage
            $grading->grade = static::calculateGrade($grading->percentage);

            // Determine pass/fail status based on assessment passing marks
            if ($grading->assessment && $grading->assessment->passing_marks) {
                $grading->status = $grading->marks_obtained >= $grading->assessment->passing_marks ? 'passed' : 'failed';
            }
        });
    }

    public static function calculateGrade($percentage)
    {
        if ($percentage >= 95) return 'A+';
        if ($percentage >= 85) return 'A';
        if ($percentage >= 75) return 'B+';
        if ($percentage >= 65) return 'B';
        if ($percentage >= 55) return 'C+';
        if ($percentage >= 50) return 'C';
        return 'F';
    }
}