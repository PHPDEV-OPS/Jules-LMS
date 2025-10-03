<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Forum Categories
        Schema::create('forum_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#3B82F6'); // Hex color code
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Forum Topics
        Schema::create('forum_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('forum_categories')->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('replies_count')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->foreignId('last_post_id')->nullable()->constrained('forum_posts')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['category_id', 'is_pinned', 'last_activity_at']);
        });

        // Forum Posts (Replies)
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('forum_topics')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_solution')->default(false);
            $table->integer('likes_count')->default(0);
            $table->timestamps();
            
            $table->index(['topic_id', 'created_at']);
        });

        // Forum Post Likes
        Schema::create('forum_post_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('forum_posts')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['post_id', 'student_id']);
        });

        // Update the forum_topics table to add the foreign key constraint properly
        Schema::table('forum_topics', function (Blueprint $table) {
            $table->dropForeign(['last_post_id']);
        });
        
        Schema::table('forum_topics', function (Blueprint $table) {
            $table->foreign('last_post_id')->references('id')->on('forum_posts')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('forum_post_likes');
        Schema::dropIfExists('forum_posts');
        Schema::dropIfExists('forum_topics');
        Schema::dropIfExists('forum_categories');
    }
};