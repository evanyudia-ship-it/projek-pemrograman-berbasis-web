<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Users
        if (!Schema::hasColumn('users', 'deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Faculties
        if (!Schema::hasColumn('faculties', 'deleted_at')) {
            Schema::table('faculties', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Rooms
        if (!Schema::hasColumn('rooms', 'deleted_at')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Bookings
        if (!Schema::hasColumn('bookings', 'deleted_at')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Facilities
        if (!Schema::hasColumn('facilities', 'deleted_at')) {
            Schema::table('facilities', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Notifikasi
        if (!Schema::hasColumn('notifikasi', 'deleted_at')) {
            Schema::table('notifikasi', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // RoomFacility
        if (!Schema::hasColumn('room_facilities', 'deleted_at')) {
            Schema::table('room_facilities', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // ReputationLogs
        if (!Schema::hasColumn('reputation_logs', 'deleted_at')) {
            Schema::table('reputation_logs', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('faculties', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('room_facilities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('reputation_logs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
