<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('home');

// Student Authentication Routes
Route::get('register', [StudentAuthController::class, 'showRegistrationForm'])->name('student.register');
Route::post('register', [StudentAuthController::class, 'register']);
Route::get('login', [StudentAuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [StudentAuthController::class, 'login']);
Route::post('logout', [StudentAuthController::class, 'logout'])->name('student.logout');

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
    
    // Protected Admin Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        
        // Admin Course Management
        Route::resource('courses', AdminCourseController::class);
        Route::post('courses/{course}/toggle-status', [AdminCourseController::class, 'toggleStatus'])->name('courses.toggle-status');
        Route::post('courses/{course}/duplicate', [AdminCourseController::class, 'duplicate'])->name('courses.duplicate');
        
        // Admin Analytics
        Route::get('analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics');
        Route::get('reports', [App\Http\Controllers\Admin\AnalyticsController::class, 'reports'])->name('reports');
        
        // Staff Management
        Route::resource('staff', App\Http\Controllers\Admin\StaffController::class);
        Route::post('staff/{staff}/toggle-status', [App\Http\Controllers\Admin\StaffController::class, 'toggleStatus'])->name('staff.toggle-status');
        
        // Category Management
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
        Route::post('categories/{category}/toggle-status', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        
        // Assessment Management
        Route::resource('assessments', App\Http\Controllers\Admin\AssessmentController::class);
        Route::post('assessments/{assessment}/toggle-status', [App\Http\Controllers\Admin\AssessmentController::class, 'toggleStatus'])->name('assessments.toggle-status');
        Route::post('assessments/{assessment}/duplicate', [App\Http\Controllers\Admin\AssessmentController::class, 'duplicate'])->name('assessments.duplicate');
        
        // Certificate Management
        Route::resource('certificates', App\Http\Controllers\Admin\CertificateController::class);
        Route::post('certificates/{certificate}/revoke', [App\Http\Controllers\Admin\CertificateController::class, 'revoke'])->name('certificates.revoke');
        Route::post('certificates/{certificate}/activate', [App\Http\Controllers\Admin\CertificateController::class, 'activate'])->name('certificates.activate');
        Route::post('certificates/bulk-issue', [App\Http\Controllers\Admin\CertificateController::class, 'bulkIssue'])->name('certificates.bulk-issue');
        Route::get('certificates/verify/{code?}', [App\Http\Controllers\Admin\CertificateController::class, 'verify'])->name('certificates.verify');
        
        // Grading Management
        Route::resource('gradings', App\Http\Controllers\Admin\GradingController::class);
        Route::post('gradings/bulk-grade', [App\Http\Controllers\Admin\GradingController::class, 'bulkGrade'])->name('gradings.bulk-grade');
        Route::get('assessments/{assessment}/stats', [App\Http\Controllers\Admin\GradingController::class, 'assessmentStats'])->name('gradings.assessment-stats');
        Route::get('assessments/{assessment}/export-grades', [App\Http\Controllers\Admin\GradingController::class, 'exportGrades'])->name('gradings.export-grades');
        
        // Announcement Management
        Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);
        Route::post('announcements/{announcement}/toggle-published', [App\Http\Controllers\Admin\AnnouncementController::class, 'togglePublished'])->name('announcements.toggle-published');
        Route::post('announcements/{announcement}/toggle-pinned', [App\Http\Controllers\Admin\AnnouncementController::class, 'togglePinned'])->name('announcements.toggle-pinned');
        Route::post('announcements/bulk-action', [App\Http\Controllers\Admin\AnnouncementController::class, 'bulkAction'])->name('announcements.bulk-action');
        
        // Email Template Management
        Route::resource('email-templates', App\Http\Controllers\Admin\EmailTemplateController::class);
        Route::post('email-templates/{emailTemplate}/toggle-status', [App\Http\Controllers\Admin\EmailTemplateController::class, 'toggleStatus'])->name('email-templates.toggle-status');
        Route::post('email-templates/{emailTemplate}/duplicate', [App\Http\Controllers\Admin\EmailTemplateController::class, 'duplicate'])->name('email-templates.duplicate');
        Route::post('email-templates/{emailTemplate}/preview', [App\Http\Controllers\Admin\EmailTemplateController::class, 'preview'])->name('email-templates.preview');
        Route::post('email-templates/{emailTemplate}/send-test', [App\Http\Controllers\Admin\EmailTemplateController::class, 'sendTest'])->name('email-templates.send-test');
        Route::get('email-templates/type-variables/{type}', [App\Http\Controllers\Admin\EmailTemplateController::class, 'getTypeVariables'])->name('email-templates.type-variables');
        
        // Notification Management
        Route::resource('notifications', App\Http\Controllers\Admin\NotificationController::class);
        Route::post('notifications/{notification}/mark-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('notifications/{notification}/mark-unread', [App\Http\Controllers\Admin\NotificationController::class, 'markAsUnread'])->name('notifications.mark-unread');
        Route::post('notifications/bulk-action', [App\Http\Controllers\Admin\NotificationController::class, 'bulkAction'])->name('notifications.bulk-action');
        Route::post('notifications/send-system', [App\Http\Controllers\Admin\NotificationController::class, 'sendSystemNotification'])->name('notifications.send-system');
        Route::delete('notifications/cleanup-expired', [App\Http\Controllers\Admin\NotificationController::class, 'cleanupExpired'])->name('notifications.cleanup-expired');
        Route::get('notifications/statistics', [App\Http\Controllers\Admin\NotificationController::class, 'statistics'])->name('notifications.statistics');
        
        // Forum Management
        Route::prefix('forums')->name('forums.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ForumController::class, 'index'])->name('index');
            Route::get('/statistics', [App\Http\Controllers\Admin\ForumController::class, 'statistics'])->name('statistics');
            
            // Categories
            Route::get('/categories', [App\Http\Controllers\Admin\ForumController::class, 'categories'])->name('categories.index');
            Route::get('/categories/create', [App\Http\Controllers\Admin\ForumController::class, 'createCategory'])->name('categories.create');
            Route::post('/categories', [App\Http\Controllers\Admin\ForumController::class, 'storeCategory'])->name('categories.store');
            Route::get('/categories/{category}/edit', [App\Http\Controllers\Admin\ForumController::class, 'editCategory'])->name('categories.edit');
            Route::put('/categories/{category}', [App\Http\Controllers\Admin\ForumController::class, 'updateCategory'])->name('categories.update');
            Route::delete('/categories/{category}', [App\Http\Controllers\Admin\ForumController::class, 'destroyCategory'])->name('categories.destroy');
            
            // Topics
            Route::get('/topics', [App\Http\Controllers\Admin\ForumController::class, 'topics'])->name('topics.index');
            Route::get('/topics/{topic}', [App\Http\Controllers\Admin\ForumController::class, 'showTopic'])->name('topics.show');
            Route::patch('/topics/{topic}/pin', [App\Http\Controllers\Admin\ForumController::class, 'pinTopic'])->name('topics.pin');
            Route::patch('/topics/{topic}/lock', [App\Http\Controllers\Admin\ForumController::class, 'lockTopic'])->name('topics.lock');
            Route::delete('/topics/{topic}', [App\Http\Controllers\Admin\ForumController::class, 'destroyTopic'])->name('topics.destroy');
            Route::post('/topics/bulk-action', [App\Http\Controllers\Admin\ForumController::class, 'bulkActionTopics'])->name('topics.bulk-action');
            
            // Posts
            Route::get('/posts', [App\Http\Controllers\Admin\ForumController::class, 'posts'])->name('posts.index');
            Route::get('/posts/{post}', [App\Http\Controllers\Admin\ForumController::class, 'showPost'])->name('posts.show');
            Route::patch('/posts/{post}/toggle-helpful', [App\Http\Controllers\Admin\ForumController::class, 'toggleHelpfulPost'])->name('posts.toggle-helpful');
            Route::delete('/posts/{post}', [App\Http\Controllers\Admin\ForumController::class, 'destroyPost'])->name('posts.destroy');
            Route::post('/posts/bulk-action', [App\Http\Controllers\Admin\ForumController::class, 'bulkActionPosts'])->name('posts.bulk-action');
        });

        // System Management
        Route::prefix('system')->name('system.')->group(function () {
            // System Settings
            Route::get('/settings', [App\Http\Controllers\Admin\SystemSettingsController::class, 'index'])->name('settings');
            Route::post('/settings/general', [App\Http\Controllers\Admin\SystemSettingsController::class, 'updateGeneral'])->name('settings.general');
            Route::post('/settings/email', [App\Http\Controllers\Admin\SystemSettingsController::class, 'updateEmail'])->name('settings.email');
            Route::post('/settings/security', [App\Http\Controllers\Admin\SystemSettingsController::class, 'updateSecurity'])->name('settings.security');
            Route::post('/settings/clear-cache', [App\Http\Controllers\Admin\SystemSettingsController::class, 'clearCache'])->name('settings.clear-cache');
            Route::post('/settings/optimize', [App\Http\Controllers\Admin\SystemSettingsController::class, 'optimizeApplication'])->name('settings.optimize');
            Route::post('/settings/test-email', [App\Http\Controllers\Admin\SystemSettingsController::class, 'testEmail'])->name('settings.test-email');

            // Backup & Restore
            Route::get('/backup', [App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backup');
            Route::post('/backup/create', [App\Http\Controllers\Admin\BackupController::class, 'createBackup'])->name('backup.create');
            Route::get('/backup/download/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'downloadBackup'])->name('backup.download');
            Route::delete('/backup/delete/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'deleteBackup'])->name('backup.delete');
            Route::post('/backup/restore/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'restoreBackup'])->name('backup.restore');
            Route::post('/backup/schedule', [App\Http\Controllers\Admin\BackupController::class, 'scheduleBackup'])->name('backup.schedule');

            // Activity Logs
            Route::get('/activity-logs', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs');
            Route::get('/activity-logs/{log}', [App\Http\Controllers\Admin\ActivityLogController::class, 'show'])->name('activity-logs.show');
            Route::delete('/activity-logs/{log}', [App\Http\Controllers\Admin\ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
            Route::post('/activity-logs/bulk-delete', [App\Http\Controllers\Admin\ActivityLogController::class, 'bulkDelete'])->name('activity-logs.bulk-delete');
            Route::post('/activity-logs/clear-old', [App\Http\Controllers\Admin\ActivityLogController::class, 'clearOld'])->name('activity-logs.clear-old');
            Route::get('/activity-logs/export', [App\Http\Controllers\Admin\ActivityLogController::class, 'export'])->name('activity-logs.export');

            // Help & Support
            Route::get('/help', [App\Http\Controllers\Admin\HelpSupportController::class, 'index'])->name('help');
            Route::get('/help/tickets', [App\Http\Controllers\Admin\HelpSupportController::class, 'tickets'])->name('help.tickets');
            Route::get('/help/tickets/{ticket}', [App\Http\Controllers\Admin\HelpSupportController::class, 'showTicket'])->name('help.tickets.show');
            Route::post('/help/tickets/{ticket}/update', [App\Http\Controllers\Admin\HelpSupportController::class, 'updateTicketStatus'])->name('help.tickets.update');
            Route::post('/help/tickets/{ticket}/respond', [App\Http\Controllers\Admin\HelpSupportController::class, 'respondToTicket'])->name('help.tickets.respond');
            
            Route::get('/help/articles', [App\Http\Controllers\Admin\HelpSupportController::class, 'articles'])->name('help.articles');
            Route::get('/help/articles/create', [App\Http\Controllers\Admin\HelpSupportController::class, 'createArticle'])->name('help.articles.create');
            Route::post('/help/articles', [App\Http\Controllers\Admin\HelpSupportController::class, 'storeArticle'])->name('help.articles.store');
            Route::get('/help/articles/{article}/edit', [App\Http\Controllers\Admin\HelpSupportController::class, 'editArticle'])->name('help.articles.edit');
            Route::put('/help/articles/{article}', [App\Http\Controllers\Admin\HelpSupportController::class, 'updateArticle'])->name('help.articles.update');
            Route::delete('/help/articles/{article}', [App\Http\Controllers\Admin\HelpSupportController::class, 'destroyArticle'])->name('help.articles.destroy');
            
            Route::get('/system-info', [App\Http\Controllers\Admin\HelpSupportController::class, 'systemInfo'])->name('system-info');
            Route::get('/documentation', [App\Http\Controllers\Admin\HelpSupportController::class, 'documentation'])->name('documentation');
        });

        // Enrollment Management
        Route::resource('enrollments', App\Http\Controllers\Admin\EnrollmentController::class);
        Route::get('enrollments/export', [App\Http\Controllers\Admin\EnrollmentController::class, 'export'])->name('enrollments.export');
        Route::post('enrollments/bulk-update', [App\Http\Controllers\Admin\EnrollmentController::class, 'bulkUpdate'])->name('enrollments.bulk-update');
        Route::patch('enrollments/{enrollment}/complete', [App\Http\Controllers\Admin\EnrollmentController::class, 'complete'])->name('enrollments.complete');
        Route::patch('enrollments/{enrollment}/suspend', [App\Http\Controllers\Admin\EnrollmentController::class, 'suspend'])->name('enrollments.suspend');
        Route::patch('enrollments/{enrollment}/reactivate', [App\Http\Controllers\Admin\EnrollmentController::class, 'reactivate'])->name('enrollments.reactivate');
        Route::patch('enrollments/{enrollment}/drop', [App\Http\Controllers\Admin\EnrollmentController::class, 'drop'])->name('enrollments.drop');
    });
});

// Protected Student Routes (Legacy - redirects to new dashboard)
Route::middleware(['auth:student', 'learner'])->group(function () {
    Route::get('dashboard', function() {
        return redirect()->route('student.dashboard.analytics');
    })->name('legacy.student.dashboard');
});

// Admin and Tutor shared routes (both can access)
Route::middleware(['auth'])->group(function () {
    Route::get('students', [StudentController::class, 'index'])->name('students.index');
    Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show');
    
    Route::get('enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('enrollments/export', [EnrollmentController::class, 'export'])->name('enrollments.export');
    Route::get('enrollments/create', [EnrollmentController::class, 'create'])->name('enrollments.create');
    Route::post('enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');
    Route::get('enrollments/{enrollment}', [EnrollmentController::class, 'show'])->name('enrollments.show');
    Route::get('enrollments/{enrollment}/edit', [EnrollmentController::class, 'edit'])->name('enrollments.edit');
    Route::put('enrollments/{enrollment}', [EnrollmentController::class, 'update'])->name('enrollments.update');
    Route::patch('enrollments/{enrollment}', [EnrollmentController::class, 'update'])->name('enrollments.update.patch');
    Route::post('enrollments/bulk-update', [EnrollmentController::class, 'bulkUpdate'])->name('enrollments.bulk-update');
    
    // Enrollment status updates
    Route::patch('enrollments/{enrollment}/complete', [EnrollmentController::class, 'complete'])->name('enrollments.complete');
    Route::patch('enrollments/{enrollment}/suspend', [EnrollmentController::class, 'suspend'])->name('enrollments.suspend');
    Route::patch('enrollments/{enrollment}/reactivate', [EnrollmentController::class, 'reactivate'])->name('enrollments.reactivate');
    Route::patch('enrollments/{enrollment}/drop', [EnrollmentController::class, 'drop'])->name('enrollments.drop');
});

// Admin-only routes (full CRUD access)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('students', StudentController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('courses', CourseController::class)->except(['index', 'show']);
    Route::delete('enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
});

// Public Course routes (anyone can view courses)
Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');

// Student routes (comprehensive student dashboard and features)
Route::middleware(['auth:student', 'learner'])->prefix('student')->name('student.')->group(function () {
    // Main Dashboard
    Route::get('dashboard', [StudentDashboardController::class, 'dashboard'])->name('dashboard');
    
    // Analytics Dashboard (legacy)
    Route::get('dashboard/analytics', [StudentDashboard::class, 'analytics'])->name('dashboard.analytics');
    
    // Course Management
    Route::get('courses', [App\Http\Controllers\Student\CourseController::class, 'index'])->name('courses.index');
    Route::get('courses/{course}', [App\Http\Controllers\Student\CourseController::class, 'show'])->name('courses.show');
    Route::post('courses/{course}/enroll', [App\Http\Controllers\Student\CourseController::class, 'enroll'])->name('courses.enroll');
    Route::delete('courses/{course}/drop', [App\Http\Controllers\Student\CourseController::class, 'drop'])->name('courses.drop');
    
    // Assessment Management
    Route::get('assessments', [App\Http\Controllers\Student\AssessmentController::class, 'index'])->name('assessments.index');
    Route::get('assessments/{assessment}', [App\Http\Controllers\Student\AssessmentController::class, 'show'])->name('assessments.show');
    Route::get('assessments/{assessment}/take', [App\Http\Controllers\Student\AssessmentController::class, 'take'])->name('assessments.take');
    Route::post('assessments/{assessment}/submit', [App\Http\Controllers\Student\AssessmentController::class, 'submit'])->name('assessments.submit');
    Route::get('assessment-results/{submission}', [App\Http\Controllers\Student\AssessmentController::class, 'result'])->name('assessments.result');
    
    // Certificate Management
    Route::get('certificates', [App\Http\Controllers\Student\CertificateController::class, 'index'])->name('certificates.index');
    Route::get('certificates/{certificate}', [App\Http\Controllers\Student\CertificateController::class, 'show'])->name('certificates.show');
    Route::get('certificates/{certificate}/download', [App\Http\Controllers\Student\CertificateController::class, 'download'])->name('certificates.download');
    
    // Announcement Management
    Route::get('announcements', [App\Http\Controllers\Student\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('announcements/{announcement}', [App\Http\Controllers\Student\AnnouncementController::class, 'show'])->name('announcements.show');
    Route::post('announcements/{announcement}/mark-read', [App\Http\Controllers\Student\AnnouncementController::class, 'markAsRead'])->name('announcements.mark-read');
    
    // Notification Management
    Route::get('notifications', [App\Http\Controllers\Student\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/{notification}', [App\Http\Controllers\Student\NotificationController::class, 'show'])->name('notifications.show');
    Route::post('notifications/{notification}/mark-read', [App\Http\Controllers\Student\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [App\Http\Controllers\Student\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    
    // Profile Management
    Route::get('profile', [App\Http\Controllers\Student\ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [App\Http\Controllers\Student\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [App\Http\Controllers\Student\ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/password', [App\Http\Controllers\Student\ProfileController::class, 'passwordForm'])->name('profile.password');
    Route::put('profile/password', [App\Http\Controllers\Student\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('profile/avatar', [App\Http\Controllers\Student\ProfileController::class, 'deleteAvatar'])->name('profile.delete-avatar');
    
    // Grades Management
    Route::get('grades', [App\Http\Controllers\Student\GradeController::class, 'index'])->name('grades.index');
    Route::get('grades/course/{course}', [App\Http\Controllers\Student\GradeController::class, 'course'])->name('grades.course');
    Route::get('grades/report', [App\Http\Controllers\Student\GradeController::class, 'report'])->name('grades.report');
    Route::get('grades/export/{format}', [App\Http\Controllers\Student\GradeController::class, 'export'])->name('grades.export');
    
    // Forum Management
    Route::get('forums', [App\Http\Controllers\Student\ForumController::class, 'index'])->name('forums.index');
    Route::get('forums/category/{category}', [App\Http\Controllers\Student\ForumController::class, 'category'])->name('forums.category');
    Route::get('forums/topic/{topic}', [App\Http\Controllers\Student\ForumController::class, 'topic'])->name('forums.topic');
    Route::post('forums/topic/{topic}/reply', [App\Http\Controllers\Student\ForumController::class, 'reply'])->name('forums.reply');
    Route::get('forums/create-topic', [App\Http\Controllers\Student\ForumController::class, 'createTopic'])->name('forums.create-topic');
    Route::post('forums/store-topic', [App\Http\Controllers\Student\ForumController::class, 'storeTopic'])->name('forums.store-topic');
    Route::post('forums/post/{post}/like', [App\Http\Controllers\Student\ForumController::class, 'likePost'])->name('forums.like-post');
    
    // Settings
    Route::get('settings', [App\Http\Controllers\Student\SettingsController::class, 'index'])->name('settings');
    Route::post('settings/notifications', [App\Http\Controllers\Student\SettingsController::class, 'updateNotifications'])->name('settings.notifications');
    Route::post('settings/privacy', [App\Http\Controllers\Student\SettingsController::class, 'updatePrivacy'])->name('settings.privacy');
    
    // Practice Tests
    Route::get('practice-tests', [App\Http\Controllers\Student\PracticeTestController::class, 'index'])->name('practice-tests.index');
    Route::get('practice-tests/{assessment}', [App\Http\Controllers\Student\PracticeTestController::class, 'show'])->name('practice-tests.show');
    Route::get('practice-tests/{assessment}/start', [App\Http\Controllers\Student\PracticeTestController::class, 'start'])->name('practice-tests.start');
    Route::get('practice-tests/{assessment}/question/{question}', [App\Http\Controllers\Student\PracticeTestController::class, 'question'])->name('practice-tests.question');
    Route::post('practice-tests/{assessment}/question/{question}/save', [App\Http\Controllers\Student\PracticeTestController::class, 'saveAnswer'])->name('practice-tests.save-answer');
    Route::get('practice-tests/{assessment}/submit', [App\Http\Controllers\Student\PracticeTestController::class, 'submit'])->name('practice-tests.submit');
    Route::get('practice-tests/{assessment}/restart', [App\Http\Controllers\Student\PracticeTestController::class, 'restart'])->name('practice-tests.restart');
    
    // Legacy enrollment actions (for backward compatibility)
    Route::post('enroll', [StudentDashboardController::class, 'enroll'])->name('enroll');
    Route::patch('enrollments/{enrollment}/drop', [StudentDashboardController::class, 'drop'])->name('drop');
    Route::get('enrollments/{enrollment}', [StudentDashboardController::class, 'viewEnrollment'])->name('enrollment.details');
});


