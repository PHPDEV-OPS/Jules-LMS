<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'action_url',
        'priority',
        'expires_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>=', now());
        });
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function getIsReadAttribute()
    {
        return !is_null($this->read_at);
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getPriorityColorAttribute()
    {
        $colors = [
            'low' => 'bg-gray-100 text-gray-800 border-gray-300',
            'medium' => 'bg-blue-100 text-blue-800 border-blue-300',
            'high' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
            'urgent' => 'bg-red-100 text-red-800 border-red-300'
        ];

        return $colors[$this->priority] ?? $colors['medium'];
    }

    public function getTypeIconAttribute()
    {
        $icons = [
            'info' => 'info',
            'success' => 'check_circle',
            'warning' => 'warning',
            'error' => 'error',
            'enrollment' => 'school',
            'course' => 'book',
            'assessment' => 'assignment',
            'certificate' => 'workspace_premium',
            'announcement' => 'campaign',
            'payment' => 'payment',
            'system' => 'settings'
        ];

        return $icons[$this->type] ?? 'notifications';
    }

    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update(['read_at' => now()]);
        }
    }

    public function markAsUnread()
    {
        if ($this->is_read) {
            $this->update(['read_at' => null]);
        }
    }

    public static function createForUser($userId, $type, $title, $message, $data = [], $actionUrl = null, $priority = 'medium', $expiresAt = null)
    {
        return static::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'action_url' => $actionUrl,
            'priority' => $priority,
            'expires_at' => $expiresAt
        ]);
    }

    public static function createForAllUsers($type, $title, $message, $data = [], $actionUrl = null, $priority = 'medium', $expiresAt = null)
    {
        $users = User::all();
        $notifications = [];

        foreach ($users as $user) {
            $notifications[] = [
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'action_url' => $actionUrl,
                'priority' => $priority,
                'expires_at' => $expiresAt,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        return static::insert($notifications);
    }
}