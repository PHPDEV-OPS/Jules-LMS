<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->longText('template_content'); // HTML/CSS template
            $table->string('background_image')->nullable();
            $table->enum('orientation', ['portrait', 'landscape'])->default('landscape');
            $table->enum('size', ['A4', 'Letter', 'Custom'])->default('A4');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificate_templates');
    }
};