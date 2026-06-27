<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reputation_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('reputation_setting_id')
                ->nullable()
                ->constrained('reputation_settings')
                ->nullOnDelete();

            // Booking dibuat unsignedBigInteger agar tidak error
            // jika tabel bookings dibuat oleh bagian lain setelah migration ini.
            $table->unsignedBigInteger('booking_id')->nullable()->index();

            $table->integer('point_before')->default(0);
            $table->integer('point_change')->default(0);
            $table->integer('point_after')->default(0);

            $table->string('type');
            // reward, penalty

            $table->string('reason')->nullable();
            $table->text('description')->nullable();

            // Admin/sistem yang memberi perubahan poin
            $table->unsignedBigInteger('created_by')->nullable()->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reputation_logs');
    }
};
