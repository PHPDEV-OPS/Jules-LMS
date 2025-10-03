<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('instructor')->nullable()->after('description');
            $table->date('start_date')->nullable()->after('instructor');
            $table->date('end_date')->nullable()->after('start_date');
            $table->decimal('price', 8, 2)->default(0)->after('end_date');
            $table->integer('max_students')->default(30)->after('price');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active')->after('max_students');
            $table->string('image_url')->nullable()->after('status');
            $table->string('category')->nullable()->after('image_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'instructor',
                'start_date',
                'end_date',
                'price',
                'max_students',
                'status',
                'image_url',
                'category'
            ]);
        });
    }
};
