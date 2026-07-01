<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingHistory;
use App\Models\Room;
use App\Models\RoomSchedule;
use App\Models\User;
use App\Models\ReputationSetting;
use App\Helpers\PriorityHelper;
use App\Services\NotificationService;
use App\Services\ReputationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingService
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

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
            $user = User::find($userId);
            $maxDurasi = config('booking.max_duration.' . ($user->role ?? 'default'), 5);

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
                'priority_level' => $priority,
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
            $this->notificationService->notifyAdmins(
                'Booking Baru Menunggu Approval',
                "Booking {$bookingCode} oleh {$booking->user->name} menunggu persetujuan.",
                'approval',
                $booking
            );

            // Send notification to user
            $this->notificationService->send(
                $userId,
                'Booking Berhasil Diajukan',
                "Booking {$bookingCode} berhasil diajukan. Menunggu persetujuan admin.",
                'info',
                $booking
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
            // Cek penalti
            $shouldPenalty = $this->shouldApplyPenalty($booking);
            $penaltyPoint = 0;

            if ($shouldPenalty) {
                $penaltySetting = ReputationSetting::where('code', 'CANCEL_SUDDEN')
                    ->where('is_active', true)
                    ->first();
                $penaltyPoint = $penaltySetting ? abs($penaltySetting->point) : 10;
            }

            // Update booking
            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $reason,
                'cancelled_by' => $cancelledBy,
            ]);

            // Create history
            $this->createHistory($booking, 'cancelled', $reason, 'user');

            // Delete schedule
            if ($booking->schedule) {
                $booking->schedule()->delete();
            }

            // Create cancellation record
            $booking->cancellation()->create([
                'cancelled_by' => $cancelledBy,
                'actor_type' => 'user',
                'alasan' => $reason,
                'kena_penalti' => $shouldPenalty,
                'penalti_point' => $penaltyPoint,
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
            $this->notificationService->bookingCancelled($booking, $reason);

            return $booking;
        });
    }

    /**
     * Validasi booking sebelum create
     */
    public function validateBooking(array $data, User $user, Room $room): array
    {
        $errors = [];

        // 1. Validasi Kapasitas
        if (isset($data['participant_count']) && $data['participant_count'] > $room->kapasitas) {
            $errors[] = 'Jumlah peserta (' . $data['participant_count'] . ') melebihi kapasitas ruang (Max: ' . $room->kapasitas . ' orang)';
        }

        // 2. Validasi Durasi
        $start = Carbon::parse($data['tanggal'] . ' ' . $data['jam_mulai']);
        $end = Carbon::parse($data['tanggal'] . ' ' . $data['jam_selesai']);
        $durasiJam = $start->diffInHours($end);

        $maxDurasi = config('booking.max_duration.' . ($user->role ?? 'default'), 5);
        if ($durasiJam > $maxDurasi) {
            $errors[] = 'Durasi booking (' . $durasiJam . ' jam) melebihi batas maksimal untuk ' . ucfirst($user->role) . ' (Max: ' . $maxDurasi . ' jam)';
        }

        // 3. Validasi Max Booking per hari
        $todayBookings = Booking::where('user_id', $user->id)
            ->whereDate('tanggal', $data['tanggal'])
            ->whereIn('status', ['pending', 'approved', 'ongoing'])
            ->count();

        if ($todayBookings >= 3) {
            $errors[] = 'Anda sudah mencapai batas maksimal 3 booking untuk hari ini (' . $data['tanggal'] . ').';
        }

        // 4. Validasi Reputasi
        if ($user->reputation_points < 30) {
            $errors[] = 'Reputasi Anda rendah (' . $user->reputation_points . '). Minimal 30 poin untuk melakukan booking.';
        }

        // 5. Validasi Bentrok Jadwal
        if (!$room->isAvailableAt($data['tanggal'], $data['jam_mulai'], $data['jam_selesai'])) {
            $errors[] = 'Ruang tidak tersedia pada waktu yang dipilih. Silakan pilih waktu lain.';
        }

        return $errors;
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

            // Create history
            $this->createHistory($booking, 'approved', 'Booking disetujui oleh admin', 'admin');

            // Send notification
            $this->notificationService->bookingApproved($booking);

            // Apply reputation
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

            // Create history
            $this->createHistory($booking, 'rejected', $reason, 'admin');

            // Delete schedule
            if ($booking->schedule) {
                $booking->schedule()->delete();
            }

            // Send notification
            $this->notificationService->bookingRejected($booking, $reason);

            return $booking;
        });
    }

    /**
     * Create booking history record.
     */
    private function createHistory(Booking $booking, string $statusBaru, ?string $keterangan = null, string $actorType = 'admin'): void
    {
        BookingHistory::create([
            'booking_id' => $booking->id,
            'user_id' => auth()->id() ?? session('user_id'),
            'actor_type' => $actorType,
            'status_sebelumnya' => $booking->getOriginal('status'),
            'status_baru' => $statusBaru,
            'keterangan' => $keterangan,
        ]);
    }

    /**
     * Check-in with transaction.
     */
    public function checkIn(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            $now = now();
            // ✅ PERBAIKAN: Gunakan Carbon::parse() langsung pada string jam_mulai
            $startTime = Carbon::parse($booking->tanggal->format('Y-m-d') . ' ' . $booking->jam_mulai);
            $checkinStatus = $now->lte($startTime) ? 'checkin_tepat_waktu' : 'checkin_terlambat';

            $booking->update([
                'check_in_status' => $checkinStatus,
                'check_in_at' => $now,
                'status' => 'ongoing',
            ]);

            $this->createHistory($booking, 'ongoing', 'Check-in berhasil', 'user');

            // Apply reputation using ReputationService
            $reputationService = app(ReputationService::class);

            if ($checkinStatus === 'checkin_tepat_waktu') {
                $reputationService->apply(
                    $booking->user,
                    'CHECK_IN_ON_TIME',
                    $booking,
                    'Check-in tepat waktu'
                );

                $this->notificationService->checkInSuccess($booking);
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

            $this->createHistory($booking, 'completed', 'Booking selesai', 'user');

            // Apply reputation using ReputationService
            $reputationService = app(ReputationService::class);
            $reputationService->apply(
                $booking->user,
                'ROOM_USAGE_GOOD',
                $booking,
                'Menggunakan ruang sesuai jadwal'
            );

            // Send notification
            $this->notificationService->send(
                $booking->user_id,
                'Booking Selesai ✅',
                "Booking {$booking->booking_code} di ruang {$booking->room->nama} telah selesai. Terima kasih telah menggunakan ruangan dengan baik.",
                'success',
                $booking
            );

            return $booking;
        });
    }

    /**
     * Process a single booking as no-show (admin triggered or system).
     */
    public function processNoShow(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            $this->applyNoShowPenalty($booking, 'No Show (admin)');
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
                        $this->notificationService->send(
                            $booking->user_id,
                            'Booking Selesai Otomatis ✅',
                            "Booking {$booking->booking_code} di ruang {$booking->room->nama} telah selesai otomatis. Terima kasih telah menggunakan ruangan dengan baik.",
                            'success',
                            $booking
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
        $bookings = Booking::where('status', 'approved')
            ->where('check_in_status', 'belum_checkin')
            ->where('checkin_deadline', '<', now())
            ->get();

        foreach ($bookings as $booking) {
            DB::transaction(function () use ($booking, &$count) {
                $this->applyNoShowPenalty($booking, 'No Show');
                $count++;
            });
        }

        return $count;
    }

    /**
     * PRIVAT METHOD: Terapkan penalty No Show ke booking
     */
    private function applyNoShowPenalty(Booking $booking, string $reason): void
    {
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
            $reason,
            auth()->id() ?? session('user_id')
        );

        // Send notification
        $this->notificationService->noShow($booking);

        Log::info("Booking marked as no-show: " . $booking->booking_code);
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
