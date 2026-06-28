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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Content
            $table->string('judul', 255);
            $table->text('pesan')->nullable();

            // Type & Status
            $table->enum('tipe', ['info', 'warning', 'success', 'approval', 'danger'])->default('info');
            $table->enum('status', ['belum_dibaca', 'sudah_dibaca'])->default('belum_dibaca');

            // Related Entity (polymorphic-like)
            $table->string('entitas_terkait')->nullable()->comment('ENUM di app: bookings, organizations, users, rooms');
            $table->string('entitas_id')->nullable()->comment('Selalu disimpan sebagai string. Integer ID dikonversi eksplisit saat insert');

            // Timestamps
            $table->timestamps();
            $table->timestamp('dibaca_at')->nullable();

            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
