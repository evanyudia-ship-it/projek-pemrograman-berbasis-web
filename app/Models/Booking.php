<?php

namespace App\Models;

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
    ];

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
     * Check if booking is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if booking is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if booking is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if booking is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if booking is no_show.
     */
    public function isNoShow(): bool
    {
        return $this->status === 'no_show';
    }

    /**
     * Check if booking can be checked in.
     */
    public function canCheckIn(): bool
    {
        return $this->status === 'approved'
            && $this->check_in_status === 'belum_checkin'
            && now()->lessThanOrEqualTo($this->checkin_deadline);
    }

    /**
     * Check if booking is expired (no check-in).
     */
    public function isExpired(): bool
    {
        return $this->status === 'approved'
            && $this->check_in_status === 'belum_checkin'
            && now()->greaterThan($this->checkin_deadline);
    }
}
