<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumPostLike extends Model
{
    protected $fillable = [
        'post_id',
        'student_id',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(ForumPost::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}