<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Daftar semua tabel yang butuh soft deletes
        $tables = [
            'users',
            'faculties',
            'admin_faculties',
            'reputation_levels',
            'reputation_settings',
            'reputation_logs',
            'rooms',
            'facilities',
            'room_facilities',
            'bookings',
            'booking_histories',
            'booking_cancellations',
            'room_schedules',
            'notifikasi',
            'riwayat',
            'appeals',
            'help_categories',
            'help_articles',
            'faqs',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'deleted_at')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->softDeletes();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'users',
            'faculties',
            'admin_faculties',
            'reputation_levels',
            'reputation_settings',
            'reputation_logs',
            'rooms',
            'facilities',
            'room_facilities',
            'bookings',
            'booking_histories',
            'booking_cancellations',
            'room_schedules',
            'notifikasi',
            'riwayat',
            'appeals',
            'help_categories',
            'help_articles',
            'faqs',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'deleted_at')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};
