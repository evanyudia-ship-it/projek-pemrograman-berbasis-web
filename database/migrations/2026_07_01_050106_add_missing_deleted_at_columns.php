<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // 1. Bookings
        // ============================================================
        if (!Schema::hasColumn('bookings', 'deleted_at')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // ============================================================
        // 2. Notifikasi
        // ============================================================
        if (!Schema::hasColumn('notifikasi', 'deleted_at')) {
            Schema::table('notifikasi', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // ============================================================
        // 3. Cek tabel lain yang mungkin belum punya deleted_at
        // PERBAIKAN: Gunakan nama variabel berbeda untuk loop dan closure
        // ============================================================
        $tables = [
            'admin_faculties',
            'reputation_levels',
            'reputation_settings',
            'booking_histories',
            'booking_cancellations',
            'room_schedules',
            'riwayat',
            'appeals',
        ];

        foreach ($tables as $tableName) {  // ← PERBAIKAN: ganti $table menjadi $tableName
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'deleted_at')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {  // ← PERBAIKAN: use($tableName)
                    $table->softDeletes();
                });
            }
        }
    }

    public function down(): void
    {
        // Tidak perlu rollback karena ini hanya menambahkan kolom
        // Tapi jika ingin rollback, kita hapus kolomnya
        $tables = [
            'bookings',
            'notifikasi',
            'admin_faculties',
            'reputation_levels',
            'reputation_settings',
            'booking_histories',
            'booking_cancellations',
            'room_schedules',
            'riwayat',
            'appeals',
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
