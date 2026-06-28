<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // ========== CONSTANTS ==========

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    // ========== RELATIONS ==========

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function adminFaculties(): HasMany
    {
        return $this->hasMany(AdminFaculty::class);
    }

    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'admin_faculties')
            ->withPivot('position', 'status')
            ->withTimestamps();
    }

    public function activeAdmins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'admin_faculties')
            ->withPivot('position', 'status')
            ->wherePivot('status', 'active')
            ->withTimestamps();
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    // ========== SCOPES ==========

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    // ========== STATUS CHECKS ==========

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    // ========== HELPER METHODS ==========

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_INACTIVE => 'Tidak Aktif',
            default => 'Unknown',
        };
    }

    public function getTotalUsersAttribute(): int
    {
        return $this->users()->count();
    }

    public function getTotalAdminsAttribute(): int
    {
        return $this->adminFaculties()
            ->where('status', 'active')
            ->count();
    }

    public function getTotalRoomsAttribute(): int
    {
        return $this->rooms()->count();
    }
}
