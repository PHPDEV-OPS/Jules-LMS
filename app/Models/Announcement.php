<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'type',
        'priority',
        'target_audience',
        'course_id',
        'is_published',
        'published_at',
        'expires_at',
        'created_by',
        'attachment_url',
        'is_pinned'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_published' => 'boolean',
        'is_pinned' => 'boolean'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function readings()
    {
        return $this->hasMany(AnnouncementReading::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where('published_at', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now());
                    });
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeForAudience($query, $audience)
    {
        return $query->where('target_audience', $audience)
                    ->orWhere('target_audience', 'all');
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('course_id');
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getTypeColorAttribute()
    {
        $colors = [
            'general' => 'bg-blue-100 text-blue-800',
            'important' => 'bg-red-100 text-red-800',
            'update' => 'bg-green-100 text-green-800',
            'maintenance' => 'bg-yellow-100 text-yellow-800',
            'event' => 'bg-purple-100 text-purple-800'
        ];

        return $colors[$this->type] ?? 'bg-gray-100 text-gray-800';
    }

    public function getPriorityColorAttribute()
    {
        $colors = [
            'low' => 'text-green-600',
            'medium' => 'text-yellow-600',
            'high' => 'text-red-600',
            'urgent' => 'text-red-800 font-bold'
        ];

        return $colors[$this->priority] ?? 'text-gray-600';
    }

    public function getReadCountAttribute()
    {
        return $this->readings()->count();
    }

    public function isReadBy($userId)
    {
        return $this->readings()->where('user_id', $userId)->exists();
    }
}