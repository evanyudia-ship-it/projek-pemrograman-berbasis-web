<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim',
        'nidn',
        'phone',
        'faculty_id',
        'status',
        'reputation_points',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'reputation_points' => 'integer',
        ];
    }

    // ========== RELATIONS ==========

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function adminFaculties(): HasMany
    {
        return $this->hasMany(AdminFaculty::class);
    }

    public function managedFaculties(): BelongsToMany
    {
        return $this->belongsToMany(Faculty::class, 'admin_faculties')
            ->withPivot('position', 'status')
            ->withTimestamps();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reputationLogs(): HasMany
    {
        return $this->hasMany(ReputationLog::class);
    }

    public function createdReputationLogs(): HasMany
    {
        return $this->hasMany(ReputationLog::class, 'created_by');
    }

    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function riwayats(): HasMany
    {
        return $this->hasMany(Riwayat::class);
    }

    public function approvedBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'disetujui_oleh');
    }

    public function cancelledBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'cancelled_by');
    }

    public function bookingCancellations(): HasMany
    {
        return $this->hasMany(BookingCancellation::class, 'cancelled_by');
    }

    // ========== SCOPES ==========

    public function scopeRole(Builder $query, string $role): Builder
    {
        return $query->where('role', $role);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeByFaculty(Builder $query, int $facultyId): Builder
    {
        return $query->where('faculty_id', $facultyId);
    }

    // ========== ROLE CHECKS ==========

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    // ========== STATUS CHECKS ==========

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    // ========== REPUTATION METHODS ==========

    public function getReputationLevel(): ?ReputationLevel
    {
        return ReputationLevel::getLevelByPoints($this->reputation_points);
    }

    public function getReputationLevelName(): string
    {
        $level = $this->getReputationLevel();
        return $level ? $level->name : 'Unknown';
    }

    public function getReputationColor(): string
    {
        $level = $this->getReputationLevel();
        return $level ? $level->color : 'gray';
    }

    public function addReputationPoints(int $points, ?string $reason = null, ?int $settingId = null, ?int $bookingId = null): void
    {
        $pointBefore = $this->reputation_points;
        $pointAfter = $pointBefore + $points;

        $this->update(['reputation_points' => $pointAfter]);

        ReputationLog::create([
            'user_id' => $this->id,
            'reputation_setting_id' => $settingId,
            'booking_id' => $bookingId,
            'point_before' => $pointBefore,
            'point_change' => $points,
            'point_after' => $pointAfter,
            'type' => $points > 0 ? 'reward' : 'penalty',
            'reason' => $reason,
            'created_by' => Auth::user()?->id,
        ]);
    }

    // ========== HELPER METHODS ==========

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'dosen' => 'Dosen',
            'mahasiswa' => 'Mahasiswa',
            default => 'Unknown',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'Aktif',
            'pending' => 'Menunggu',
            'inactive' => 'Tidak Aktif',
            'banned' => 'Diblokir',
            default => 'Unknown',
        };
    }
}
