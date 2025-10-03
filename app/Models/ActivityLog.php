<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'student_id',
        'activity_type',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public static function log($activityType, $description, $properties = [], $user = null, $student = null)
    {
        return static::create([
            'user_id' => $user ? $user->id : (auth()->guard('web')->check() ? auth()->id() : null),
            'student_id' => $student ? $student->id : (auth()->guard('student')->check() ? auth()->guard('student')->id() : null),
            'activity_type' => $activityType,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}