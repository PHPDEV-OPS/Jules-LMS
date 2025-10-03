<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function topics(): HasMany
    {
        return $this->hasMany(ForumTopic::class, 'category_id');
    }

    public function activeTopics(): HasMany
    {
        return $this->topics()->where('is_locked', false);
    }

    public function posts()
    {
        return $this->hasManyThrough(ForumPost::class, ForumTopic::class, 'category_id', 'topic_id');
    }
}