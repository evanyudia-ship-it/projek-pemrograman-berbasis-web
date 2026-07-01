<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomSchedule;
use App\Helpers\NotificationHelper;
use App\Helpers\PriorityHelper;
use App\Services\ReputationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingService
{
    /**
     * Create a new booking with transaction.
     */
    public function create(array $data, int $userId): Booking
    {
        // Validasi data
        $required = ['room_id', 'kegiatan', 'tujuan', 'tanggal', 'jam_mulai', 'jam_selesai'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                throw new \InvalidArgumentException("Field '{$field}' is required.");
            }
        }

        return DB::transaction(function () use ($data, $userId) {
            // Generate booking code
            $lastBooking = Booking::orderBy('id', 'desc')->first();
            $nextId = $lastBooking ? $lastBooking->id + 1 : 1;
            $bookingCode = 'BK-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            // Calculate duration
            $start = Carbon::parse($data['tanggal'] . ' ' . $data['jam_mulai']);
            $end = Carbon::parse($data['tanggal'] . ' ' . $data['jam_selesai']);
            $durasiMenit = $start->diffInMinutes($end);
            $durasiJam = $start->diffInHours($end);

            // ============================================================
            // ✅ CEK DURASI MAKSIMAL BERDASARKAN ROLE
            // ============================================================
            $user = \App\Models\User::find($userId);
            $maxDurasi = match($user->role ?? 'mahasiswa') {
                'mahasiswa' => 2,      // 2 jam untuk mahasiswa
                'dosen' => 6,          // 6 jam untuk dosen
                'organisasi' => 4,     // 4 jam untuk organisasi
                'admin' => 8,          // 8 jam untuk admin
                'superadmin' => 12,    // 12 jam untuk superadmin
                default => 4,          // default 4 jam
            };

            // Validasi durasi (jika melebihi max, throw error)
            if ($durasiJam > $maxDurasi) {
                throw new \InvalidArgumentException(
                    "Durasi booking ({$durasiJam} jam) melebihi batas maksimal untuk " . ucfirst($user->role ?? 'user') . " (Max: {$maxDurasi} jam)"
                );
            }

            // ============================================================
            // ✅ PRIORITY LEVEL - DARI SISTEM (BUKAN INPUT USER)
            // ============================================================
            $jenisKegiatan = $data['jenis_kegiatan'] ?? null;
            $priority = PriorityHelper::getPriority($jenisKegiatan);

            // Check-in deadline: 30 minutes after start
            $checkinDeadline = $start->copy()->addMinutes(30);

            // Create booking
            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'user_id' => $userId,
                'room_id' => $data['room_id'],
                'kegiatan' => $data['kegiatan'],
                'jenis_kegiatan' => $jenisKegiatan,
                'tujuan' => $data['tujuan'],
                'tanggal' => $data['tanggal'],
                'jam_mulai' => $data['jam_mulai'],
                'jam_selesai' => $data['jam_selesai'],
                'durasi_menit' => $durasiMenit,
                'priority_level' => $priority, // ✅ Sistem yang menentukan!
                'status' => 'pending',
                'check_in_status' => 'belum_checkin',
                'checkin_deadline' => $checkinDeadline,
                'is_penalty_applied' => false,
            ]);

            // Create room schedule
            RoomSchedule::create([
                'room_id' => $data['room_id'],
                'booking_id' => $booking->id,
                'label' => $data['kegiatan'],
                'tanggal' => $data['tanggal'],
                'waktu_mulai' => $data['jam_mulai'],
                'waktu_selesai' => $data['jam_selesai'],
                'jenis_jadwal' => 'booking',
            ]);

            // Send notification to admins
            NotificationHelper::notifyAdmins(
                'Booking Baru Menunggu Approval',
                "Booking {$bookingCode} oleh {$booking->user->name} menunggu persetujuan.",
                'approval',
                $booking->id
            );

            // Send notification to user
            NotificationHelper::bookingNotification(
                $userId,
                'Booking Berhasil Diajukan',
                "Booking {$bookingCode} berhasil diajukan. Menunggu persetujuan admin.",
                'info',
                $booking->id
            );

            return $booking;
        });
    }

    /**
     * Update booking with transaction.
     */
    public function update(Booking $booking, array $data): Booking
    {
        return DB::transaction(function () use ($booking, $data) {
            // Calculate new duration
            $start = Carbon::parse($data['tanggal'] . ' ' . $data['jam_mulai']);
            $end = Carbon::parse($data['tanggal'] . ' ' . $data['jam_selesai']);
            $durasiMenit = $start->diffInMinutes($end);

            // Update check-in deadline
            $checkinDeadline = $start->copy()->addMinutes(30);

            // Update booking
            $booking->update([
                'room_id' => $data['room_id'],
                'kegiatan' => $data['kegiatan'],
                'tujuan' => $data['tujuan'],
                'tanggal' => $data['tanggal'],
                'jam_mulai' => $data['jam_mulai'],
                'jam_selesai' => $data['jam_selesai'],
                'durasi_menit' => $durasiMenit,
                'checkin_deadline' => $checkinDeadline,
            ]);

            // Update room schedule
            $booking->schedule()->update([
                'room_id' => $data['room_id'],
                'label' => $data['kegiatan'],
                'tanggal' => $data['tanggal'],
                'waktu_mulai' => $data['jam_mulai'],
                'waktu_selesai' => $data['jam_selesai'],
            ]);

            return $booking;
        });
    }

    /**
     * Cancel booking with transaction.
     */
    public function cancel(Booking $booking, string $reason, int $cancelledBy): Booking
    {
        return DB::transaction(function () use ($booking, $reason, $cancelledBy) {
            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $reason,
                'cancelled_by' => $cancelledBy,
            ]);

            // Update schedule status
            $booking->schedule()->delete();

            // Create cancellation record
            $shouldPenalty = $this->shouldApplyPenalty($booking);
            $booking->cancellation()->create([
                'cancelled_by' => $cancelledBy,
                'actor_type' => 'user',
                'alasan' => $reason,
                'kena_penalti' => $shouldPenalty,
                'penalti_point' => $shouldPenalty ? 10 : 0,
            ]);

            // Apply penalty if needed
            if ($shouldPenalty) {
                $reputationService = app(ReputationService::class);
                $reputationService->apply(
                    $booking->user,
                    'CANCEL_SUDDEN',
                    $booking,
                    'Pembatalan mendadak (< 1 jam)',
                    $cancelledBy
                );
            }

            // Send notification
            NotificationHelper::bookingCancelled(
                $booking->user_id,
                $booking->booking_code,
                $reason
            );

            return $booking;
        });
    }

    /**
     * Approve booking with transaction.
     */
    public function approve(Booking $booking, int $adminId): Booking
    {
        return DB::transaction(function () use ($booking, $adminId) {
            $booking->update([
                'status' => 'approved',
                'disetujui_oleh' => $adminId,
                'disetujui_at' => now(),
            ]);

            // Send notification to user
            NotificationHelper::bookingApproved(
                $booking->user_id,
                $booking->booking_code,
                $booking->room->nama
            );

            // Apply reputation using ReputationService
            $reputationService = app(ReputationService::class);
            $reputationService->apply(
                $booking->user,
                'BOOKING_APPROVED',
                $booking,
                'Booking disetujui',
                $adminId
            );

            return $booking;
        });
    }

    /**
     * Reject booking with transaction.
     */
    public function reject(Booking $booking, string $reason, int $adminId): Booking
    {
        return DB::transaction(function () use ($booking, $reason, $adminId) {
            $booking->update([
                'status' => 'rejected',
                'catatan_admin' => $reason,
                'cancellation_reason' => $reason,
                'disetujui_oleh' => $adminId,
                'disetujui_at' => now(),
            ]);

            // Delete schedule
            $booking->schedule()->delete();

            // Send notification
            NotificationHelper::bookingRejected(
                $booking->user_id,
                $booking->booking_code,
                $reason
            );

            return $booking;
        });
    }

    /**
     * Check-in with transaction.
     */
    public function checkIn(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            $now = now();
            $startTime = Carbon::parse($booking->tanggal->format('Y-m-d') . ' ' . $booking->jam_mulai->format('H:i:s'));
            $checkinStatus = $now->lte($startTime) ? 'checkin_tepat_waktu' : 'checkin_terlambat';

            $booking->update([
                'check_in_status' => $checkinStatus,
                'check_in_at' => $now,
                'status' => 'ongoing',
            ]);

            // Apply reputation using ReputationService
            $reputationService = app(ReputationService::class);

            if ($checkinStatus === 'checkin_tepat_waktu') {
                $reputationService->apply(
                    $booking->user,
                    'CHECK_IN_ON_TIME',
                    $booking,
                    'Check-in tepat waktu'
                );

                NotificationHelper::checkInSuccess(
                    $booking->user_id,
                    $booking->booking_code,
                    $booking->room->nama
                );
            } else {
                $reputationService->apply(
                    $booking->user,
                    'CHECK_IN_LATE',
                    $booking,
                    'Check-in terlambat'
                );
            }

            return $booking;
        });
    }

    /**
     * Complete booking (after usage).
     */
    public function complete(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            $booking->update([
                'status' => 'completed',
            ]);

            // Apply reputation using ReputationService
            $reputationService = app(ReputationService::class);
            $reputationService->apply(
                $booking->user,
                'ROOM_USAGE_GOOD',
                $booking,
                'Menggunakan ruang sesuai jadwal'
            );

            // Send notification
            NotificationHelper::send(
                $booking->user_id,
                'Booking Selesai ✅',
                "Booking {$booking->booking_code} di ruang {$booking->room->nama} telah selesai. Terima kasih telah menggunakan ruangan dengan baik.",
                'success',
                'bookings',
                (string) $booking->id
            );

            return $booking;
        });
    }

    /**
     * Process a single booking as no-show (admin triggered or system).
     *
     * PERBAIKAN: Method ini ditambahkan untuk menangani No-Show dari admin
     */
    public function processNoShow(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            // Update booking status
            $booking->update([
                'status' => 'no_show',
                'check_in_status' => 'no_show',
            ]);

            // Delete schedule
            if ($booking->schedule) {
                $booking->schedule()->delete();
            }

            // Apply reputation penalty
            $reputationService = app(ReputationService::class);
            $reputationService->apply(
                $booking->user,
                'NO_SHOW',
                $booking,
                'No Show (admin)',
                auth()->id() ?? session('user_id')
            );

            // Send notification to user
            NotificationHelper::noShow(
                $booking->user_id,
                $booking->booking_code,
                $booking->room->nama
            );

            Log::info("Booking marked as no-show by admin: " . $booking->booking_code);

            return $booking;
        });
    }

    /**
     * Process auto-complete for bookings (called by cron).
     * Booking akan otomatis selesai 1 jam setelah jam selesai.
     */
    public function processAutoComplete(): int
    {
        $count = 0;

        try {
            // Cari booking yang statusnya ongoing dan sudah lewat 1 jam dari jam_selesai
            $bookings = Booking::where('status', 'ongoing')
                ->whereRaw("DATE_ADD(CONCAT(tanggal, ' ', jam_selesai), INTERVAL 1 HOUR) < NOW()")
                ->get();

            Log::info("Found " . $bookings->count() . " bookings to auto-complete.");

            foreach ($bookings as $booking) {
                try {
                    DB::transaction(function () use ($booking, &$count) {
                        $booking->update([
                            'status' => 'completed',
                        ]);

                        // Tambah reputasi karena menggunakan ruang dengan baik
                        $reputationService = app(ReputationService::class);
                        $reputationService->apply(
                            $booking->user,
                            'ROOM_USAGE_GOOD',
                            $booking,
                            'Auto-complete: Menggunakan ruang sesuai jadwal'
                        );

                        // Send notification
                        NotificationHelper::send(
                            $booking->user_id,
                            'Booking Selesai Otomatis ✅',
                            "Booking {$booking->booking_code} di ruang {$booking->room->nama} telah selesai otomatis. Terima kasih telah menggunakan ruangan dengan baik.",
                            'success',
                            'bookings',
                            (string) $booking->id
                        );

                        $count++;
                        Log::info("Auto-completed booking: " . $booking->booking_code);
                    });
                } catch (\Exception $e) {
                    Log::error("Error auto-completing booking {$booking->id}: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error("Error in processAutoComplete: " . $e->getMessage());
            throw $e;
        }

        return $count;
    }

    /**
     * Check for no-show bookings (called by cron).
     * Booking akan otomatis menjadi no-show 30 menit setelah jam_mulai.
     */
    public function processNoShows(): int
    {
        $count = 0;

        try {
            $bookings = Booking::where('status', 'approved')
                ->where('check_in_status', 'belum_checkin')
                ->where('checkin_deadline', '<', now())
                ->get();

            Log::info("Found " . $bookings->count() . " bookings to process as no-show.");

            foreach ($bookings as $booking) {
                try {
                    DB::transaction(function () use ($booking, &$count) {
                        $booking->update([
                            'status' => 'no_show',
                            'check_in_status' => 'no_show',
                        ]);

                        // Apply reputation using ReputationService
                        $reputationService = app(ReputationService::class);
                        $reputationService->apply(
                            $booking->user,
                            'NO_SHOW',
                            $booking,
                            'No Show'
                        );

                        // Send notification
                        NotificationHelper::noShow(
                            $booking->user_id,
                            $booking->booking_code,
                            $booking->room->nama
                        );

                        $count++;
                        Log::info("Processed no-show for booking: " . $booking->booking_code);
                    });
                } catch (\Exception $e) {
                    Log::error("Error processing no-show for booking {$booking->id}: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error("Error in processNoShows: " . $e->getMessage());
            throw $e;
        }

        return $count;
    }

    /**
     * Check if booking should apply penalty.
     */
    private function shouldApplyPenalty(Booking $booking): bool
    {
        $startTime = Carbon::parse($booking->tanggal->format('Y-m-d') . ' ' . $booking->jam_mulai->format('H:i:s'));
        return now()->diffInHours($startTime) < 1;
    }
}
