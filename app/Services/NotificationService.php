<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;

class NotificationService
{
    /**
     * Send notification to single user (synchronous)
     */
    public function send(
        int $userId,
        string $judul,
        string $pesan,
        string $tipe = 'info',
        ?Model $entity = null
    ): Notifikasi {
        $notif = new Notifikasi([
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'status' => 'belum_dibaca',
        ]);

        if ($entity) {
            $notif->setEntity($entity);
        }

        $notif->save();
        return $notif;
    }

    /**
     * Send notification to multiple users
     */
    public function sendToMany(
        array $userIds,
        string $judul,
        string $pesan,
        string $tipe = 'info',
        ?Model $entity = null
    ): void {
        foreach ($userIds as $userId) {
            $this->send($userId, $judul, $pesan, $tipe, $entity);
        }
    }

    /**
     * Send notification to all admins
     */
    public function notifyAdmins(
        string $judul,
        string $pesan,
        string $tipe = 'info',
        ?Model $entity = null
    ): void {
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        $this->sendToMany($admins->pluck('id')->toArray(), $judul, $pesan, $tipe, $entity);
    }

    /**
     * Notification: Booking Approved
     */
    public function bookingApproved(Booking $booking): void
    {
        $this->send(
            $booking->user_id,
            'Booking Disetujui ✅',
            "Booking {$booking->booking_code} untuk ruang {$booking->room->nama} telah disetujui.",
            'success',
            $booking
        );
    }

    /**
     * Notification: Booking Rejected
     */
    public function bookingRejected(Booking $booking, string $reason): void
    {
        $this->send(
            $booking->user_id,
            'Booking Ditolak ❌',
            "Booking {$booking->booking_code} ditolak. Alasan: {$reason}",
            'danger',
            $booking
        );
    }

    /**
     * Notification: Booking Cancelled
     */
    public function bookingCancelled(Booking $booking, string $reason): void
    {
        $this->send(
            $booking->user_id,
            'Booking Dibatalkan 🔄',
            "Booking {$booking->booking_code} dibatalkan. Alasan: {$reason}",
            'warning',
            $booking
        );
    }

    /**
     * Notification: Check-in Success
     */
    public function checkInSuccess(Booking $booking): void
    {
        $this->send(
            $booking->user_id,
            'Check-in Berhasil ✅',
            "Check-in untuk booking {$booking->booking_code} di ruang {$booking->room->nama} berhasil.",
            'success',
            $booking
        );
    }

    /**
     * Notification: No Show
     */
    public function noShow(Booking $booking): void
    {
        $this->send(
            $booking->user_id,
            'No Show ❌',
            "Booking {$booking->booking_code} di ruang {$booking->room->nama} dinyatakan No Show. Reputasi Anda berkurang.",
            'danger',
            $booking
        );
    }
}
