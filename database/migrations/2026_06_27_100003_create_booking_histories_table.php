<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_histories', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('NULL jika perubahan dilakukan oleh sistem (cron/otomatis)');

            // Actor & Status
            $table->enum('actor_type', ['user', 'admin', 'system'])->default('user');
            $table->string('status_sebelumnya')->nullable();
            $table->string('status_baru');
            $table->text('keterangan')->nullable()->comment('Wajib diisi di app layer jika status_baru = rejected');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('booking_id');
            $table->index(['booking_id', 'created_at']);
            $table->index('user_id');
            $table->index('status_baru');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_histories');
    }
};
