<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Room extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'gedung',
        'lantai',
        'kapasitas',
        'alamat',
        'deskripsi',
        'foto',
        'jam_buka',
        'jam_tutup',
        'max_durasi_jam',
        'status',
        'faculty_id'
    ];

    protected $casts = [
        'lantai' => 'integer',
        'kapasitas' => 'integer',
        'max_durasi_jam' => 'integer',
        'jam_buka' => 'datetime:H:i:s',
        'jam_tutup' => 'datetime:H:i:s',
    ];

    // ========== CONSTANTS ==========

    const STATUS_AVAILABLE = 'Tersedia';
    const STATUS_MAINTENANCE = 'Maintenance';

    // ========== RELATIONS ==========

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(
            Facility::class,
            'room_facilities'
        )->withPivot('status')->withTimestamps();
    }

    public function roomFacilities(): HasMany
    {
        return $this->hasMany(RoomFacility::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(RoomSchedule::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // ========== SCOPES ==========

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function scopeMaintenance(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }

    public function scopeByFaculty(Builder $query, int $facultyId): Builder
    {
        return $query->where('faculty_id', $facultyId);
    }

    public function scopeByBuilding(Builder $query, string $building): Builder
    {
        return $query->where('gedung', $building);
    }

    public function scopeMinCapacity(Builder $query, int $capacity): Builder
    {
        return $query->where('kapasitas', '>=', $capacity);
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama', 'LIKE', "%{$keyword}%")
                ->orWhere('kode', 'LIKE', "%{$keyword}%")
                ->orWhere('gedung', 'LIKE', "%{$keyword}%")
                ->orWhere('alamat', 'LIKE', "%{$keyword}%");
        });
    }

    // ========== STATUS CHECKS ==========

    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function isMaintenance(): bool
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    // ========== HELPER METHODS ==========

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'Tersedia',
            self::STATUS_MAINTENANCE => 'Maintenance',
            default => 'Unknown',
        };
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->nama} ({$this->kode})";
    }

    public function getLocationAttribute(): string
    {
        return "{$this->gedung} - Lantai {$this->lantai}";
    }

    public function getFormattedJamBukaAttribute(): string
    {
        return $this->jam_buka ? Carbon::parse($this->jam_buka)->format('H:i') : '-';
    }

    public function getFormattedJamTutupAttribute(): string
    {
        return $this->jam_tutup ? Carbon::parse($this->jam_tutup)->format('H:i') : '-';
    }

    public function getMaxDurasiLabelAttribute(): string
    {
        return $this->max_durasi_jam ? "{$this->max_durasi_jam} jam/hari" : '-';
    }

    /**
     * Check if room is available at specific date and time.
     */
    public function isAvailableAt(string $date, string $startTime, string $endTime): bool
    {
        if ($this->isMaintenance()) {
            return false;
        }

        // Check if within operating hours
        $open = Carbon::parse($this->jam_buka->format('H:i:s'));
        $close = Carbon::parse($this->jam_tutup->format('H:i:s'));
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        if ($start->lt($open) || $end->gt($close)) {
            return false;
        }

        // Check for overlapping bookings
        $overlapping = $this->bookings()
            ->whereDate('tanggal', $date)
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->where('status', '!=', Booking::STATUS_REJECTED)
            ->where('status', '!=', Booking::STATUS_COMPLETED)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('jam_mulai', '<', $endTime)
                      ->where('jam_selesai', '>', $startTime);
                });
            })
            ->exists();

        return !$overlapping;
    }

    /**
     * Get available facilities count.
     */
    public function getAvailableFacilitiesCount(): int
    {
        return $this->roomFacilities()
            ->where('status', 'tersedia')
            ->count();
    }

    /**
     * Get facility status for a specific facility.
     */
    public function getFacilityStatus(int $facilityId): ?string
    {
        $pivot = $this->roomFacilities()
            ->where('facility_id', $facilityId)
            ->first();

        return $pivot ? $pivot->status : null;
    }

    /**
     * Get available facilities list.
     */
    public function getAvailableFacilities(): array
    {
        return $this->roomFacilities()
            ->where('status', 'tersedia')
            ->with('facility')
            ->get()
            ->pluck('facility.nama')
            ->toArray();
    }

    /**
     * Get all facility names.
     */
    public function getFacilityNamesAttribute(): array
    {
        return $this->facilities->pluck('nama')->toArray();
    }

    /**
     * Get facilities with status.
     */
    public function getFacilitiesWithStatus(): array
    {
        return $this->facilities->map(function ($facility) {
            return [
                'id' => $facility->id,
                'nama' => $facility->nama,
                'status' => $facility->pivot->status,
                'status_label' => $facility->pivot->getStatusLabelAttribute(),
                'icon' => $facility->icon,
            ];
        })->toArray();
    }
}
