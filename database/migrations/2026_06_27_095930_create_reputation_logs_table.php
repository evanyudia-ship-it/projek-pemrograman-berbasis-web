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

            $table->foreignId('booking_id')
                ->nullable()
                ->constrained('bookings')
                ->nullOnDelete();

            $table->integer('point_before')->default(0);
            $table->integer('point_change')->default(0);
            $table->integer('point_after')->default(0);

            $table->string('type');
            // reward, penalty

            $table->string('reason')->nullable();
            $table->text('description')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reputation_logs');
    }
};
