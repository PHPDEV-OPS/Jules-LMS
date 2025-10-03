<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gradings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('marks_obtained', 8, 2);
            $table->decimal('total_marks', 8, 2);
            $table->decimal('percentage', 5, 2);
            $table->string('grade', 3);
            $table->enum('status', ['pending', 'passed', 'failed', 'in_review'])->default('pending');
            $table->text('feedback')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users');
            $table->datetime('graded_at')->nullable();
            $table->datetime('submission_date');
            $table->timestamps();
            
            $table->unique(['assessment_id', 'student_id']);
            $table->index(['status', 'graded_at']);
            $table->index(['grade', 'percentage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gradings');
    }
};
