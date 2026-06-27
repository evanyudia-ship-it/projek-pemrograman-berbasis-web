<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingCancellation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'cancelled_by',
        'actor_type',
        'alasan',
        'kena_penalti',
        'penalti_point',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'kena_penalti' => 'boolean',
        'penalti_point' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the booking that was cancelled.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who cancelled the booking.
     */
    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Check if cancellation has penalty.
     */
    public function hasPenalty(): bool
    {
        return $this->kena_penalti && $this->penalti_point > 0;
    }

    /**
     * Check if cancellation was done by user.
     */
    public function isUserCancellation(): bool
    {
        return $this->actor_type === 'user';
    }

    /**
     * Check if cancellation was done by admin.
     */
    public function isAdminCancellation(): bool
    {
        return $this->actor_type === 'admin';
    }

    /**
     * Check if cancellation was done by system.
     */
    public function isSystemCancellation(): bool
    {
        return $this->actor_type === 'system';
    }
}
