<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'question_text',
        'question_type',
        'options',
        'correct_answer',
        'points',
        'order'
    ];

    protected $casts = [
        'options' => 'array',
        'points' => 'decimal:2'
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}