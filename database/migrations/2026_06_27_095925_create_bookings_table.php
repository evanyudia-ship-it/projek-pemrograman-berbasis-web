<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 20)->unique()->comment('Format: BK-XXXXX');

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');

            // Detail Booking
            $table->string('kegiatan', 150);
            $table->text('tujuan');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai')->comment('Harus > jam_mulai, validasi di app/trigger');
            $table->integer('durasi_menit')->comment('Durasi dalam menit, dihitung otomatis');

            // Prioritas berdasarkan Jenis/Keperluan
            $table->enum('priority_level', ['High', 'Medium-High', 'Medium', 'Low'])->default('Medium');


            $table->string('status')->default('pending');
            // pending, approved, rejected, ongoing, completed, cancelled, no_show

            $table->text('catatan_admin')->nullable();

            $table->foreignId('disetujui_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('disetujui_at')->nullable();

            // Check-in System
            $table->enum('check_in_status', ['belum_checkin', 'checkin_tepat_waktu', 'checkin_terlambat', 'no_show'])->default('belum_checkin');
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('checkin_deadline')->nullable()->comment('Dihitung dari tanggal+jam_mulai+30 menit toleransi');

            // Penalty & Cancellation
            $table->boolean('is_penalty_applied')->default(false);
            $table->string('cancellation_reason', 255)->nullable()->comment('Wajib diisi di app layer jika status = cancelled/rejected');

            $table->foreignId('cancelled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['room_id', 'tanggal', 'jam_mulai']);
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at'], 'idx_bookings_status_created');
            $table->index(['room_id', 'tanggal', 'status'], 'idx_bookings_room_date_status');
            $table->index(['tanggal', 'status']);
            $table->index(['checkin_deadline', 'status'], 'idx_bookings_checkin_deadline');
            $table->index('priority_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
