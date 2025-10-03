<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'template_content',
        'background_image',
        'orientation',
        'size',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}