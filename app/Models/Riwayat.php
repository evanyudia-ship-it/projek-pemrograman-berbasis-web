<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Riwayat extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'riwayat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'jenis_aktivitas',
        'deskripsi',
        'entitas_terkait',
        'entitas_id',
        'deleted_at',
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
     * Activity types constants.
     */
    const ACTIVITY_BOOKING = 'peminjaman_ruangan';
    const ACTIVITY_UPDATE_PROFILE = 'update_profil';
    const ACTIVITY_CHECKIN = 'checkin';
    const ACTIVITY_CANCELLATION = 'pembatalan';
    const ACTIVITY_APPROVAL = 'approval';
    const ACTIVITY_REJECTION = 'penolakan';

    /**
     * Get the user that owns the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get related entity (polymorphic).
     */
    public function getRelatedEntityAttribute()
    {
        if ($this->entitas_terkait && $this->entitas_id) {
            $modelMap = [
                'bookings' => Booking::class,
                'users' => User::class,
                'rooms' => Room::class,
            ];

            if (isset($modelMap[$this->entitas_terkait])) {
                return $modelMap[$this->entitas_terkait]::find($this->entitas_id);
            }
        }

        return null;
    }

    // ========== SCOPES ==========

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('jenis_aktivitas', $type);
    }

    public function scopeBookingActivities(Builder $query): Builder
    {
        return $query->where('jenis_aktivitas', self::ACTIVITY_BOOKING);
    }

    public function scopeCheckinActivities(Builder $query): Builder
    {
        return $query->where('jenis_aktivitas', self::ACTIVITY_CHECKIN);
    }

}
