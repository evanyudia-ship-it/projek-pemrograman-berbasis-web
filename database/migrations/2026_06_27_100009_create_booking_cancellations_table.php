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
        Schema::create('booking_cancellations', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('cancelled_by')->constrained('users')->onDelete('cascade');

            // Actor & Reason
            $table->enum('actor_type', ['user', 'admin', 'system'])->default('user');
            $table->text('alasan');

            // Penalty
            $table->boolean('kena_penalti')->default(false);
            $table->integer('penalti_point')->default(0)->comment('Jumlah poin yang dikurangi akibat pembatalan');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('booking_id');
            $table->index('cancelled_by');
            $table->index('created_at');
            $table->index(['booking_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_cancellations');
    }
};
