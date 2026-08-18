<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('short_description')->nullable();
            $table->longText('content')->nullable();
            $table->string('vimeo_id')->nullable();
            $table->text('vimeo_embed')->nullable();
            $table->string('video_path')->nullable();
            $table->string('video_duration')->nullable();
            $table->string('resource_path')->nullable();
            $table->string('resource_type')->nullable();
            $table->integer('view_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};