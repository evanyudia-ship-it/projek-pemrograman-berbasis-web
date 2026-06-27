<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reputation_levels', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            // contoh: Baik, Waspada, Buruk, Banned

            $table->integer('min_points');
            $table->integer('max_points')->nullable();

            $table->string('color')->nullable();
            // contoh: green, yellow, red, gray

            $table->text('description')->nullable();

            $table->boolean('is_banned')->default(false);
            $table->string('status')->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reputation_levels');
    }
};
