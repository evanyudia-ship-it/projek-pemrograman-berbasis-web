<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_facilities', function (Blueprint $table) {

            $table->id();

            $table->foreignId('room_id')
                  ->constrained('rooms')
                  ->cascadeOnDelete();

            $table->foreignId('facility_id')
                  ->constrained('facilities')
                  ->cascadeOnDelete();

            $table->enum('status',[
                'tersedia',
                'rusak',
                'maintenance'
            ])->default('tersedia');

            $table->timestamp('updated_at')->useCurrent();

            $table->unique([
                'room_id',
                'facility_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_facilities');
    }
};