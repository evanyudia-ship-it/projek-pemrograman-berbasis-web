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
        Schema::create('riwayat', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Activity
            $table->string('jenis_aktivitas')->comment('peminjaman_ruangan, update_profil, checkin, pembatalan, dsb');
            $table->text('deskripsi')->nullable();

            // Related Entity (polymorphic-like)
            $table->string('entitas_terkait')->nullable()->comment('ENUM di app: bookings, users, rooms');
            $table->string('entitas_id')->nullable()->comment('Selalu disimpan sebagai string. Integer ID dikonversi eksplisit saat insert');

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
            $table->index('jenis_aktivitas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat');
    }
};
