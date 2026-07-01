<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_code',
        'user_id',
        'room_id',
        'kegiatan',
        'jenis_kegiatan',
        'tujuan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'durasi_menit',
        'priority_level',
        'status',  // ✅ Sekarang string, bukan enum
        'catatan_admin',
        'disetujui_oleh',
        'disetujui_at',
        'check_in_status',
        'check_in_at',
        'checkin_deadline',
        'is_penalty_applied',
        'cancellation_reason',
        'cancelled_by',
        'deleted_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'disetujui_at' => 'datetime',
        'check_in_at' => 'datetime',
        'checkin_deadline' => 'datetime',
        'is_penalty_applied' => 'boolean',
        'durasi_menit' => 'integer',
        'priority_level' => 'string',
    ];

    // ========== ACCESSORS ==========

    public function getJamMulaiFormattedAttribute(): string
    {
        return $this->jam_mulai ? Carbon::parse($this->jam_mulai)->format('H:i') : '-';
    }

    public function getJamSelesaiFormattedAttribute(): string
    {
        return $this->jam_selesai ? Carbon::parse($this->jam_selesai)->format('H:i') : '-';
    }

    public function getTimeRangeAttribute(): string
    {
        return $this->getJamMulaiFormattedAttribute() . ' - ' . $this->getJamSelesaiFormattedAttribute();
    }

    // ========== CONSTANTS ==========

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ONGOING = 'ongoing';
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

    protected static function booted()
    {
        static::saving(function ($booking) {
            // Validasi status
            if (!in_array($booking->status, self::getValidStatuses())) {
                throw new \InvalidArgumentException(
                    "Status '{$booking->status}' tidak valid. Gunakan: " . implode(', ', self::getValidStatuses())
                );
            }

            // Validasi check_in_status
            if (!in_array($booking->check_in_status, self::getValidCheckinStatuses())) {
                throw new \InvalidArgumentException(
                    "Check-in status '{$booking->check_in_status}' tidak valid. Gunakan: " . implode(', ', self::getValidCheckinStatuses())
                );
            }
        });
    }

    public static function getValidStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
            self::STATUS_ONGOING,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
            self::STATUS_NO_SHOW,
        ];
    }

    public static function getValidCheckinStatuses(): array
    {
        return [
            self::CHECKIN_BELUM,
            self::CHECKIN_TEPAT,
            self::CHECKIN_TERLAMBAT,
            self::CHECKIN_NO_SHOW,
        ];
    }

    // ========== RELATIONS ==========

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(BookingHistory::class);
    }

    public function cancellation(): HasOne
    {
        return $this->hasOne(BookingCancellation::class);
    }

    public function reputationLogs(): HasMany
    {
        return $this->hasMany(ReputationLog::class);
    }

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

    public function scopeOngoing(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ONGOING);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('tanggal', today());
    }

    // ========== STATUS CHECKS ==========

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isNoShow(): bool
    {
        return $this->status === self::STATUS_NO_SHOW;
    }

    public function isOngoing(): bool
    {
        return $this->status === self::STATUS_ONGOING;
    }

    public function isActive(): bool
    {
        return in_array($this->status, [
            self::STATUS_APPROVED,
            self::STATUS_PENDING,
            self::STATUS_ONGOING,
        ]);
    }

    public function canCheckIn(): bool
    {
        return $this->status === self::STATUS_APPROVED
            && $this->check_in_status === self::CHECKIN_BELUM
            && now()->lessThanOrEqualTo($this->checkin_deadline);
    }

    public function canBeCancelledByUser(): bool
    {
        return $this->status === self::STATUS_PENDING
            || ($this->status === self::STATUS_APPROVED && !$this->isExpired());
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_APPROVED
            && $this->check_in_status === self::CHECKIN_BELUM
            && now()->greaterThan($this->checkin_deadline);
    }

    // ========== HELPERS ==========

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_ONGOING => 'Sedang Berlangsung',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_NO_SHOW => 'Tidak Hadir',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-amber-100 text-amber-700',
            self::STATUS_APPROVED => 'bg-emerald-100 text-emerald-700',
            self::STATUS_ONGOING => 'bg-blue-100 text-blue-700',
            self::STATUS_COMPLETED => 'bg-slate-100 text-slate-700',
            self::STATUS_CANCELLED => 'bg-slate-100 text-slate-400',
            self::STATUS_REJECTED => 'bg-red-100 text-red-700',
            self::STATUS_NO_SHOW => 'bg-red-200 text-red-800',
            default => 'bg-slate-100 text-slate-500',
        };
    }

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
}
