<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasActor;

class BookingCancellation extends Model
{
    use HasFactory, SoftDeletes, HasActor;

    protected $fillable = [
        'booking_id',
        'cancelled_by',
        'actor_type',
        'alasan',
        'kena_penalti',
        'penalti_point',
        'deleted_at',
    ];

    protected $casts = [
        'kena_penalti' => 'boolean',
        'penalti_point' => 'integer',
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

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    // ========== SCOPES ==========

    public function scopeWithPenalty(Builder $query): Builder
    {
        return $query->where('kena_penalti', true);
    }

    public function scopeWithoutPenalty(Builder $query): Builder
    {
        return $query->where('kena_penalti', false);
    }

    public function scopeUserCancellation(Builder $query): Builder
    {
        return $query->where('actor_type', self::ACTOR_USER);
    }

    public function scopeAdminCancellation(Builder $query): Builder
    {
        return $query->where('actor_type', self::ACTOR_ADMIN);
    }

    public function scopeSystemCancellation(Builder $query): Builder
    {
        return $query->where('actor_type', self::ACTOR_SYSTEM);
    }

    // ========== HELPER METHODS ==========

    public function hasPenalty(): bool
    {
        return $this->kena_penalti && $this->penalti_point > 0;
    }

    public function isUserCancellation(): bool
    {
        return $this->actor_type === self::ACTOR_USER;
    }

    public function isAdminCancellation(): bool
    {
        return $this->actor_type === self::ACTOR_ADMIN;
    }

    public function isSystemCancellation(): bool
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
