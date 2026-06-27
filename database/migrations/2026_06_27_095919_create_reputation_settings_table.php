<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reputation_settings', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            // contoh: CHECK_IN_SUCCESS, ROOM_USAGE_GOOD, NO_SHOW

            $table->string('name');
            // contoh: Check-in Berhasil

            $table->string('type');
            // reward, penalty

            $table->integer('point');
            // contoh: 5, 2, -15

            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reputation_settings');
    }
};
