<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is tutor
     */
    public function isTutor(): bool
    {
        return $this->role === 'tutor';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship: A user has many notifications.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Relationship: A user has created email templates.
     */
    public function emailTemplates()
    {
        return $this->hasMany(EmailTemplate::class, 'created_by');
    }

    /**
     * Relationship: A user has created announcements.
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    /**
     * Relationship: A user has issued certificates.
     */
    public function issuedCertificates()
    {
        return $this->hasMany(Certificate::class, 'issued_by');
    }

    /**
     * Relationship: A user has graded assessments.
     */
    public function gradings()
    {
        return $this->hasMany(Grading::class, 'graded_by');
    }

    /**
     * Relationship: A user might be a student.
     */
    public function student()
    {
        return $this->hasOne(Student::class, 'email', 'email');
    }
}
