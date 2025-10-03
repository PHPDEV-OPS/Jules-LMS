<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'type',
        'total_marks',
        'passing_marks',
        'duration_minutes',
        'attempts_allowed',
        'instructions',
        'is_active',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssessmentSubmission::class);
    }

    public function questions()
    {
        return $this->hasMany(AssessmentQuestion::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        $now = now();
        return $query->where('is_active', true)
                    ->where(function($q) use ($now) {
                        $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
                    })
                    ->where(function($q) use ($now) {
                        $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
                    });
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_minutes) return 'No time limit';
        
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        
        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }
        return $minutes . ' minutes';
    }

    public function getPassingPercentageAttribute()
    {
        if ($this->total_marks <= 0) return 0;
        return round(($this->passing_marks / $this->total_marks) * 100, 1);
    }
}