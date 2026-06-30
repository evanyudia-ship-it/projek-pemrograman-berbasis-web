<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    use HasFactory, SoftDeletes;

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
        'deleted_at',
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
     * Get related entity (polymorphic-like).
     */
    public function getRelatedEntityAttribute()
    {
        if ($this->entitas_terkait && $this->entitas_id) {
            $modelMap = [
                'bookings' => Booking::class,
                'users' => User::class,
                'rooms' => Room::class,
                'organizations' => Organization::class,
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

    /**
     * Get notification icon by type.
     */
    public function getIconAttribute(): string
    {
        return match($this->tipe) {
            'success' => '✅',
            'warning' => '⚠️',
            'approval' => '⏳',
            'danger' => '❌',
            'info' => 'ℹ️',
            default => '🔔',
        };
    }

    /**
     * Get notification color class by type.
     */
    public function getColorClassAttribute(): string
    {
        return match($this->tipe) {
            'success' => 'border-emerald-200 bg-emerald-50',
            'warning' => 'border-amber-200 bg-amber-50',
            'approval' => 'border-blue-200 bg-blue-50',
            'danger' => 'border-red-200 bg-red-50',
            'info' => 'border-slate-200 bg-slate-50',
            default => 'border-slate-200 bg-slate-50',
        };
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

    public function scopeByEntity(Builder $query, string $entity, ?string $entityId = null): Builder
    {
        $query->where('entitas_terkait', $entity);

        if ($entityId) {
            $query->where('entitas_id', $entityId);
        }

        return $query;
    }
}
