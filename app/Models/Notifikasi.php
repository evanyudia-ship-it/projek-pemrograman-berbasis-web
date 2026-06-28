<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifikasi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'status',
        'entitas_terkait',
        'entitas_id',
        'dibaca_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dibaca_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification.
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

    /**
     * Mark notification as read.
     */
    public function markAsRead(): bool
    {
        $this->status = 'sudah_dibaca';
        $this->dibaca_at = now();

        return $this->save();
    }

    /**
     * Check if notification is read.
     */
    public function isRead(): bool
    {
        return $this->status === 'sudah_dibaca';
    }

    // ========== SCOPES ==========

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', 'belum_dibaca');
    }

    public function scopeRead(Builder $query): Builder
    {
        return $query->where('status', 'sudah_dibaca');
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('tipe', $type);
    }

}
