<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumPost extends Model
{
    protected $fillable = [
        'topic_id',
        'student_id',
        'content',
        'is_solution',
        'likes_count',
    ];

    protected $casts = [
        'is_solution' => 'boolean',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(ForumTopic::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ForumPostLike::class, 'post_id');
    }

    public function isLikedBy($studentId): bool
    {
        return $this->likes()->where('student_id', $studentId)->exists();
    }

    public function toggleLike($studentId)
    {
        $like = $this->likes()->where('student_id', $studentId)->first();
        
        if ($like) {
            $like->delete();
            $this->decrement('likes_count');
            return false; // unliked
        } else {
            $this->likes()->create(['student_id' => $studentId]);
            $this->increment('likes_count');
            return true; // liked
        }
    }
}