<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class RoomSchedule extends Model
{
    protected $fillable = [
        'room_id',
        'booking_id',
        'label',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'jenis_jadwal'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime:H:i:s',
        'waktu_selesai' => 'datetime:H:i:s',
    ];

    // ========== CONSTANTS ==========

    const TYPE_BOOKING = 'booking';
    const TYPE_MAINTENANCE = 'maintenance';
    const TYPE_ACADEMIC = 'academic_schedule';
    const TYPE_PRIVATE = 'private_event';

    // ========== RELATIONS ==========

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    // ========== SCOPES ==========

    public function scopeBooking(Builder $query): Builder
    {
        return $query->where('jenis_jadwal', self::TYPE_BOOKING);
    }

    public function scopeMaintenance(Builder $query): Builder
    {
        return $query->where('jenis_jadwal', self::TYPE_MAINTENANCE);
    }

    public function scopeAcademic(Builder $query): Builder
    {
        return $query->where('jenis_jadwal', self::TYPE_ACADEMIC);
    }

    public function scopePrivate(Builder $query): Builder
    {
        return $query->where('jenis_jadwal', self::TYPE_PRIVATE);
    }

    public function scopeDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('tanggal', $date);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('tanggal', today());
    }

    // ========== HELPER METHODS ==========

    public function getTypeLabelAttribute(): string
    {
        return match($this->jenis_jadwal) {
            self::TYPE_BOOKING => 'Booking',
            self::TYPE_MAINTENANCE => 'Maintenance',
            self::TYPE_ACADEMIC => 'Jadwal Akademik',
            self::TYPE_PRIVATE => 'Event Privat',
            default => 'Unknown',
        };
    }

    public function getDurationInMinutes(): int
    {
        $start = Carbon::parse($this->waktu_mulai);
        $end = Carbon::parse($this->waktu_selesai);

        return $start->diffInMinutes($end);
    }

    public function getTimeRangeAttribute(): string
    {
        return $this->waktu_mulai->format('H:i') . ' - ' . $this->waktu_selesai->format('H:i');
    }

    public function isBooking(): bool
    {
        return $this->jenis_jadwal === self::TYPE_BOOKING;
    }

    public function isMaintenance(): bool
    {
        return $this->jenis_jadwal === self::TYPE_MAINTENANCE;
    }
}
