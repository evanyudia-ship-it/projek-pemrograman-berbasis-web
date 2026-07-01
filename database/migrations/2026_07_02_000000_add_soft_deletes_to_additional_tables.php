<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // 1. Admin Faculties
        // ============================================================
        if (!Schema::hasColumn('admin_faculties', 'deleted_at')) {
            Schema::table('admin_faculties', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // ============================================================
        // 2. Reputation Levels
        // ============================================================
        if (!Schema::hasColumn('reputation_levels', 'deleted_at')) {
            Schema::table('reputation_levels', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // ============================================================
        // 3. Reputation Settings
        // ============================================================
        if (!Schema::hasColumn('reputation_settings', 'deleted_at')) {
            Schema::table('reputation_settings', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // ============================================================
        // 4. Booking Histories
        // ============================================================
        if (!Schema::hasColumn('booking_histories', 'deleted_at')) {
            Schema::table('booking_histories', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // ============================================================
        // 5. Booking Cancellations
        // ============================================================
        if (!Schema::hasColumn('booking_cancellations', 'deleted_at')) {
            Schema::table('booking_cancellations', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // ============================================================
        // 6. Room Schedules
        // ============================================================
        if (!Schema::hasColumn('room_schedules', 'deleted_at')) {
            Schema::table('room_schedules', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // ============================================================
        // 7. Riwayat
        // ============================================================
        if (!Schema::hasColumn('riwayat', 'deleted_at')) {
            Schema::table('riwayat', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::table('admin_faculties', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('reputation_levels', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('reputation_settings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('booking_histories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('booking_cancellations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('room_schedules', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('riwayat', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
