<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('certificate_number')->unique();
            $table->datetime('issued_date');
            $table->datetime('expiry_date')->nullable();
            $table->enum('status', ['active', 'revoked', 'suspended', 'pending'])->default('active');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('verification_code')->unique();
            $table->string('grade');
            $table->decimal('completion_percentage', 5, 2);
            $table->foreignId('issued_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['student_id', 'course_id']);
            $table->index(['status', 'issued_date']);
            $table->index('verification_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
