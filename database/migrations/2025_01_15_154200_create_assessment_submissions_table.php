<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assessment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->json('submission_data'); // Store answers
            $table->timestamp('submitted_at');
            $table->decimal('marks', 8, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->enum('status', ['submitted', 'graded', 'in_progress'])->default('submitted');
            $table->integer('attempt_number')->default(1);
            $table->timestamps();

            $table->unique(['assessment_id', 'student_id', 'attempt_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('assessment_submissions');
    }
};