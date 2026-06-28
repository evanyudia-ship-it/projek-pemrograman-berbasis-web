<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_code',
        'user_id',
        'room_id',
        'kegiatan',
        'tujuan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'durasi_menit',
        'priority_level',
        'status',
        'catatan_admin',
        'disetujui_oleh',
        'disetujui_at',
        'check_in_status',
        'check_in_at',
        'checkin_deadline',
        'is_penalty_applied',
        'cancellation_reason',
        'cancelled_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i:s',
        'jam_selesai' => 'datetime:H:i:s',
        'disetujui_at' => 'datetime',
        'check_in_at' => 'datetime',
        'checkin_deadline' => 'datetime',
        'is_penalty_applied' => 'boolean',
        'durasi_menit' => 'integer',
        'priority_level' => 'string',
    ];

    // ========== CONSTANTS ==========

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_NO_SHOW = 'no_show';

    const CHECKIN_BELUM = 'belum_checkin';
    const CHECKIN_TEPAT = 'checkin_tepat_waktu';
    const CHECKIN_TERLAMBAT = 'checkin_terlambat';
    const CHECKIN_NO_SHOW = 'no_show';

    const PRIORITY_HIGH = 'High';
    const PRIORITY_MEDIUM_HIGH = 'Medium-High';
    const PRIORITY_MEDIUM = 'Medium';
    const PRIORITY_LOW = 'Low';



    // ========== RELATIONS ==========

    /**
     * Get the user that owns the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the room that owns the booking.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the admin who approved the booking.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /**
     * Get the user who cancelled the booking.
     */
    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Get the booking histories for the booking.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(BookingHistory::class);
    }

    /**
     * Get the booking cancellation for the booking.
     */
    public function cancellation(): HasOne
    {
        return $this->hasOne(BookingCancellation::class);
    }

    /**
     * Get the reputation logs for the booking.
     */
    public function reputationLogs(): HasMany
    {
        return $this->hasMany(ReputationLog::class);
    }

    /**
     * Get the room schedule for the booking.
     */
    public function schedule(): HasOne
    {
        return $this->hasOne(RoomSchedule::class);
    }

    // ========== SCOPES ==========

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeNoShow(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_NO_SHOW);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('tanggal', today());
    }

    public function scopeDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('tanggal', $date);
    }

    public function scopeByRoom(Builder $query, int $roomId): Builder
    {
        return $query->where('room_id', $roomId);
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeCheckinPending(Builder $query): Builder
    {
        return $query->where('check_in_status', self::CHECKIN_BELUM);
    }

    // ========== STATUS CHECKS ==========

    /**
     * Check if booking is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if booking is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if booking is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if booking is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Check if booking is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if booking is no_show.
     */
    public function isNoShow(): bool
    {
        return $this->status === self::STATUS_NO_SHOW;
    }

    /**
     * Check if booking is active (approved and not completed/cancelled).
     */
    public function isActive(): bool
    {
        return in_array($this->status, [
            self::STATUS_APPROVED,
            self::STATUS_PENDING
        ]);
    }

    /**
     * Check if booking can be checked in.
     */
    public function canCheckIn(): bool
    {
        return $this->status === self::STATUS_APPROVED
            && $this->check_in_status === self::CHECKIN_BELUM
            && now()->lessThanOrEqualTo($this->checkin_deadline);
    }

    /**
     * Check if booking is expired (no check-in).
     */
    public function isExpired(): bool
    {
        return $this->status === self::STATUS_APPROVED
            && $this->check_in_status === self::CHECKIN_BELUM
            && now()->greaterThan($this->checkin_deadline);
    }

    /**
     * Check if booking is check-in timely.
     */
    public function isCheckinOnTime(): bool
    {
        return $this->check_in_status === self::CHECKIN_TEPAT;
    }

    /**
     * Check if booking is check-in late.
     */
    public function isCheckinLate(): bool
    {
        return $this->check_in_status === self::CHECKIN_TERLAMBAT;
    }

    // ========== HELPER METHODS ==========

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_NO_SHOW => 'Tidak Hadir',
            default => 'Unknown',
        };
    }

    /**
     * Get check-in status label.
     */
    public function getCheckinStatusLabelAttribute(): string
    {
        return match($this->check_in_status) {
            self::CHECKIN_BELUM => 'Belum Check-in',
            self::CHECKIN_TEPAT => 'Check-in Tepat Waktu',
            self::CHECKIN_TERLAMBAT => 'Check-in Terlambat',
            self::CHECKIN_NO_SHOW => 'No Show',
            default => 'Unknown',
        };
    }

    /**
     * Get priority label.
     */
    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority_level) {
            self::PRIORITY_HIGH => 'Tinggi',
            self::PRIORITY_MEDIUM_HIGH => 'Sedang Tinggi',
            self::PRIORITY_MEDIUM => 'Sedang',
            self::PRIORITY_LOW => 'Rendah',
            default => 'Unknown',
        };
    }

    /**
     * Check if booking can be cancelled by user.
     */
    public function canBeCancelledByUser(): bool
    {
        return $this->status === self::STATUS_PENDING
            || ($this->status === self::STATUS_APPROVED && $this->isExpired() === false);
    }

    /**
     * Calculate duration in minutes.
     */
    public function calculateDuration(): int
    {
        $start = \Carbon\Carbon::parse($this->tanggal->format('Y-m-d') . ' ' . $this->jam_mulai->format('H:i:s'));
        $end = \Carbon\Carbon::parse($this->tanggal->format('Y-m-d') . ' ' . $this->jam_selesai->format('H:i:s'));

        return $start->diffInMinutes($end);
    }
}
