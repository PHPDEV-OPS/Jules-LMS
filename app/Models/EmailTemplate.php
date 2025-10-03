<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'body',
        'type',
        'variables',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getVariableListAttribute()
    {
        return $this->variables ? implode(', ', array_map(function($var) {
            return '{' . $var . '}';
        }, $this->variables)) : '';
    }

    public function renderSubject($variables = [])
    {
        $subject = $this->subject;
        
        foreach ($variables as $key => $value) {
            $subject = str_replace('{' . $key . '}', $value, $subject);
        }
        
        return $subject;
    }

    public function renderBody($variables = [])
    {
        $body = $this->body;
        
        foreach ($variables as $key => $value) {
            $body = str_replace('{' . $key . '}', $value, $body);
        }
        
        return $body;
    }

    public static function getAvailableTypes()
    {
        return [
            'welcome' => 'Welcome Email',
            'enrollment_confirmation' => 'Enrollment Confirmation',
            'course_completion' => 'Course Completion',
            'certificate_issued' => 'Certificate Issued',
            'password_reset' => 'Password Reset',
            'payment_confirmation' => 'Payment Confirmation',
            'announcement' => 'Announcement',
            'reminder' => 'Reminder',
            'custom' => 'Custom'
        ];
    }

    public static function getDefaultVariables($type)
    {
        $commonVars = ['user_name', 'user_email', 'site_name', 'site_url', 'current_date'];
        
        $typeSpecificVars = [
            'welcome' => ['login_url'],
            'enrollment_confirmation' => ['course_title', 'course_url', 'enrollment_date'],
            'course_completion' => ['course_title', 'completion_date', 'certificate_url'],
            'certificate_issued' => ['course_title', 'certificate_number', 'certificate_url'],
            'password_reset' => ['reset_url', 'reset_token'],
            'payment_confirmation' => ['amount', 'transaction_id', 'payment_date'],
            'announcement' => ['announcement_title', 'announcement_content'],
            'reminder' => ['reminder_message', 'action_url'],
            'custom' => []
        ];

        return array_merge($commonVars, $typeSpecificVars[$type] ?? []);
    }
}