<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'user_id',
        'actor_type',
        'status_sebelumnya',
        'status_baru',
        'keterangan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the booking that owns the history.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the action was performed by user.
     */
    public function isUserAction(): bool
    {
        return $this->actor_type === 'user';
    }

    /**
     * Check if the action was performed by admin.
     */
    public function isAdminAction(): bool
    {
        return $this->actor_type === 'admin';
    }

    /**
     * Check if the action was performed by system.
     */
    public function isSystemAction(): bool
    {
        return $this->actor_type === 'system';
    }
}
