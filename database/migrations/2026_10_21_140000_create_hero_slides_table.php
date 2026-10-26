<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('tab', 24);
            $table->string('label', 80)->nullable();
            $table->string('title', 160);
            $table->string('highlight', 80)->nullable();
            $table->text('description')->nullable();
            $table->string('pattern', 20)->default('spotlight');
            $table->string('points_style', 20)->default('none');
            $table->text('points')->nullable();
            $table->string('image')->nullable();
            $table->string('image_mobile')->nullable();
            $table->string('site_image', 40)->nullable();
            $table->string('image_position', 20)->default('center');
            $table->string('button_text', 40)->nullable();
            $table->string('button_target', 30)->nullable();
            $table->string('button2_text', 40)->nullable();
            $table->string('button2_target', 30)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
