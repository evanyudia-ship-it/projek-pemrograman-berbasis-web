<?php

namespace App\Helpers;

use App\Models\Notifikasi;
use App\Models\User;
use App\Jobs\SendNotification;
use App\Jobs\SendBulkNotification;

class NotificationHelper
{
    /**
     * Send notification to a single user.
     */
    public static function send(
        int $userId,
        string $judul,
        string $pesan,
        string $tipe = 'info',
        ?string $entitasTerkait = null,
        ?string $entitasId = null
    ): void {
        // Simplified: langsung create tanpa queue dulu
        Notifikasi::create([
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'status' => 'belum_dibaca',
            'entitas_terkait' => $entitasTerkait,
            'entitas_id' => $entitasId,
        ]);
    }

    /**
     * Send notification to multiple users.
     */
    public static function sendToMany(
        array $userIds,
        string $judul,
        string $pesan,
        string $tipe = 'info',
        ?string $entitasTerkait = null,
        ?string $entitasId = null
    ): void {
        foreach ($userIds as $userId) {
            self::send($userId, $judul, $pesan, $tipe, $entitasTerkait, $entitasId);
        }
    }

    /**
     * Send notification to all admins.
     */
    public static function notifyAdmins(
        string $judul,
        string $pesan,
        string $tipe = 'info',
        ?int $bookingId = null
    ): void {
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        $userIds = $admins->pluck('id')->toArray();

        if (!empty($userIds)) {
            self::sendToMany(
                $userIds,
                $judul,
                $pesan,
                $tipe,
                'bookings',
                $bookingId ? (string) $bookingId : null
            );
        }
    }

    /**
     * Send booking notification to user.
     */
    public static function bookingNotification(
        int $userId,
        string $judul,
        string $pesan,
        string $tipe = 'info',
        ?int $bookingId = null
    ): void {
        self::send(
            $userId,
            $judul,
            $pesan,
            $tipe,
            'bookings',
            $bookingId ? (string) $bookingId : null
        );
    }

    /**
     * Send notification about booking approval.
     */
    public static function bookingApproved(int $userId, string $bookingCode, string $roomName): void
    {
        self::send(
            $userId,
            'Booking Disetujui ✅',
            "Booking {$bookingCode} untuk ruang {$roomName} telah disetujui.",
            'success',
            'bookings',
            null
        );
    }

    /**
     * Send notification about booking rejection.
     */
    public static function bookingRejected(int $userId, string $bookingCode, string $reason): void
    {
        self::send(
            $userId,
            'Booking Ditolak ❌',
            "Booking {$bookingCode} ditolak. Alasan: {$reason}",
            'danger',
            'bookings',
            null
        );
    }

    /**
     * Send notification about booking cancellation.
     */
    public static function bookingCancelled(int $userId, string $bookingCode, string $reason): void
    {
        self::send(
            $userId,
            'Booking Dibatalkan 🔄',
            "Booking {$bookingCode} dibatalkan. Alasan: {$reason}",
            'warning',
            'bookings',
            null
        );
    }

    /**
     * Send notification about check-in.
     */
    public static function checkInSuccess(int $userId, string $bookingCode, string $roomName): void
    {
        self::send(
            $userId,
            'Check-in Berhasil ✅',
            "Check-in untuk booking {$bookingCode} di ruang {$roomName} berhasil.",
            'success',
            'bookings',
            null
        );
    }

    /**
     * Send notification about no-show.
     */
    public static function noShow(int $userId, string $bookingCode, string $roomName): void
    {
        self::send(
            $userId,
            'No Show ❌',
            "Booking {$bookingCode} di ruang {$roomName} dinyatakan No Show. Reputasi Anda berkurang.",
            'danger',
            'bookings',
            null
        );
    }
}
