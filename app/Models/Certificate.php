<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'course_id',
        'certificate_number',
        'issued_date',
        'expiry_date',
        'status',
        'template_id',
        'verification_code',
        'grade',
        'completion_percentage',
        'issued_by'
    ];

    protected $casts = [
        'issued_date' => 'datetime',
        'expiry_date' => 'datetime',
        'completion_percentage' => 'decimal:2'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function template()
    {
        return $this->belongsTo(CertificateTemplate::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function getIsExpiredAttribute()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function getFormattedGradeAttribute()
    {
        $grades = [
            'A+' => 'Excellent (A+)',
            'A' => 'Outstanding (A)',
            'B+' => 'Very Good (B+)',
            'B' => 'Good (B)',
            'C+' => 'Satisfactory (C+)',
            'C' => 'Pass (C)',
            'F' => 'Fail (F)'
        ];

        return $grades[$this->grade] ?? $this->grade;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'bg-green-100 text-green-800',
            'revoked' => 'bg-red-100 text-red-800',
            'suspended' => 'bg-yellow-100 text-yellow-800',
            'pending' => 'bg-blue-100 text-blue-800'
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}