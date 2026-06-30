<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomFacility extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $table = 'room_facilities';

    protected $fillable = [
        'room_id',
        'facility_id',
        'status',
        'deleted_at',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    const STATUS_AVAILABLE = 'tersedia';
    const STATUS_BROKEN = 'rusak';
    const STATUS_MAINTENANCE = 'maintenance';

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function scopeBroken(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_BROKEN);
    }

    public function scopeMaintenance(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }

    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function isBroken(): bool
    {
        return $this->status === self::STATUS_BROKEN;
    }

    public function isMaintenance(): bool
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'Tersedia',
            self::STATUS_BROKEN => 'Rusak',
            self::STATUS_MAINTENANCE => 'Maintenance',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'green',
            self::STATUS_BROKEN => 'red',
            self::STATUS_MAINTENANCE => 'yellow',
            default => 'gray',
        };
    }
}
