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
        Schema::table('enrollments', function (Blueprint $table) {
            $table->timestamp('completion_date')->nullable()->after('status');
            $table->decimal('grade', 5, 2)->nullable()->after('completion_date');
            $table->integer('progress')->default(0)->after('grade');
            $table->text('notes')->nullable()->after('progress');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['completion_date', 'grade', 'progress', 'notes']);
        });
    }
};
