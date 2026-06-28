<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'actor_type',
        'status_sebelumnya',
        'status_baru',
        'keterangan',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== CONSTANTS ==========

    const ACTOR_USER = 'user';
    const ACTOR_ADMIN = 'admin';
    const ACTOR_SYSTEM = 'system';

    // ========== RELATIONS ==========

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ========== SCOPES ==========

    public function scopeUserAction(Builder $query): Builder
    {
        return $query->where('actor_type', self::ACTOR_USER);
    }

    public function scopeAdminAction(Builder $query): Builder
    {
        return $query->where('actor_type', self::ACTOR_ADMIN);
    }

    public function scopeSystemAction(Builder $query): Builder
    {
        return $query->where('actor_type', self::ACTOR_SYSTEM);
    }

    // ========== HELPER METHODS ==========

    public function isUserAction(): bool
    {
        return $this->actor_type === self::ACTOR_USER;
    }

    public function isAdminAction(): bool
    {
        return $this->actor_type === self::ACTOR_ADMIN;
    }

    public function isSystemAction(): bool
    {
        return $this->actor_type === self::ACTOR_SYSTEM;
    }

    public function getActorLabelAttribute(): string
    {
        return match($this->actor_type) {
            self::ACTOR_USER => 'User',
            self::ACTOR_ADMIN => 'Admin',
            self::ACTOR_SYSTEM => 'Sistem',
            default => 'Unknown',
        };
    }
}
