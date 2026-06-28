<?php

namespace App\Helpers;

use App\Models\Notifikasi;
use App\Models\User;
use App\Http\Controllers\NotificationController;

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
        NotificationController::create($userId, $judul, $pesan, $tipe, $entitasTerkait, $entitasId);
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
        NotificationController::sendToMany($userIds, $judul, $pesan, $tipe, $entitasTerkait, $entitasId);
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
        NotificationController::notifyAdmins($judul, $pesan, $tipe, $bookingId);
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
        NotificationController::sendBookingNotification($userId, $judul, $pesan, $tipe, $bookingId);
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

    /**
     * Send notification about reputation change.
     */
    public static function reputationChanged(int $userId, int $points, string $reason): void
    {
        $type = $points > 0 ? 'success' : 'danger';
        $emoji = $points > 0 ? '⭐' : '⚠️';
        $action = $points > 0 ? 'bertambah' : 'berkurang';

        self::send(
            $userId,
            "Reputasi {$action} {$points} poin",
            "{$emoji} Poin reputasi Anda {$action} {$points} poin. {$reason}",
            $type,
            'reputation',
            null
        );
    }
}
