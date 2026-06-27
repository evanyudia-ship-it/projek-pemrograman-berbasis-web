<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_schedules', function (Blueprint $table) {

            $table->id();

            $table->foreignId('room_id')
                  ->constrained('rooms')
                  ->cascadeOnDelete();

            $table->foreignId('booking_id')
                  ->nullable()
                  ->constrained('bookings')
                  ->nullOnDelete();

            $table->string('label');

            $table->date('tanggal');

            $table->time('waktu_mulai');

            $table->time('waktu_selesai');

            $table->enum('jenis_jadwal',[
                'booking',
                'maintenance',
                'academic_schedule',
                'private_event'
            ])->default('booking');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_schedules');
    }
};