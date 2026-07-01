<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    use SoftDeletes;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'entitas_terkait',
        'entitas_id',
        'dibaca_at',
        'deleted_at',
    ];

    protected $casts = [
        'dibaca_at' => 'datetime',
    ];

    // ========== CONSTANTS ==========

    const TYPE_INFO = 'info';
    const TYPE_WARNING = 'warning';
    const TYPE_SUCCESS = 'success';
    const TYPE_APPROVAL = 'approval';
    const TYPE_DANGER = 'danger';

    const STATUS_UNREAD = 'belum_dibaca';
    const STATUS_READ = 'sudah_dibaca';

    protected static function booted()
    {
        static::saving(function ($notifikasi) {
            // Validasi tipe (hanya jika tipe tidak null)
            if ($notifikasi->tipe && !in_array($notifikasi->tipe, self::getValidTypes())) {
                throw new \InvalidArgumentException(
                    "Tipe '{$notifikasi->tipe}' tidak valid. Gunakan: " . implode(', ', self::getValidTypes())
                );
            }

            // Validasi status (hanya jika status tidak null)
            if ($notifikasi->status && !in_array($notifikasi->status, self::getValidStatuses())) {
                throw new \InvalidArgumentException(
                    "Status '{$notifikasi->status}' tidak valid. Gunakan: " . implode(', ', self::getValidStatuses())
                );
            }
        });
    }


    public static function getValidTypes(): array
    {
        return [
            self::TYPE_INFO,
            self::TYPE_WARNING,
            self::TYPE_SUCCESS,
            self::TYPE_APPROVAL,
            self::TYPE_DANGER,
        ];
    }

    public static function getValidStatuses(): array
    {
        return [
            self::STATUS_UNREAD,
            self::STATUS_READ,
        ];
    }

    // ========== RELATIONS ==========

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ========== SCOPES ==========

    public function scopeUnread($query)
    {
        return $query->where('status', self::STATUS_UNREAD);
    }

    public function scopeRead($query)
    {
        return $query->where('status', self::STATUS_READ);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('tipe', $type);
    }

    // ========== HELPERS ==========

        /**
     * Set entitas dengan polymorphic style (tanpa migration)
     */
    public function setEntity($model): void
    {
        if ($model) {
            $this->entitas_terkait = $model->getTable();
            $this->entitas_id = (string) $model->id;
        }
    }

    /**
     * Get entitas dengan polymorphic style
     */
    public function getEntity()
    {
        if ($this->entitas_terkait && $this->entitas_id) {
            $modelMap = [
                'bookings' => Booking::class,
                'users' => User::class,
                'rooms' => Room::class,
                'faculties' => Faculty::class,
            ];

            if (isset($modelMap[$this->entitas_terkait])) {
                return $modelMap[$this->entitas_terkait]::find($this->entitas_id);
            }
        }
        return null;
    }

    /**
     * Helper untuk membuat notifikasi dengan entitas
     */
    public static function createWithEntity(
        int $userId,
        string $judul,
        string $pesan,
        string $tipe = 'info',
        ?Model $entity = null
    ): self {
        $notif = new self([
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'status' => self::STATUS_UNREAD,
        ]);

        if ($entity) {
            $notif->setEntity($entity);
        }

        $notif->save();
        return $notif;
    }

    public function markAsRead(): bool
    {
        $this->status = self::STATUS_READ;
        $this->dibaca_at = now();
        return $this->save();
    }

    public function isRead(): bool
    {
        return $this->status === self::STATUS_READ;
    }

    public function getIconAttribute(): string
    {
        return match($this->tipe) {
            self::TYPE_SUCCESS => '✅',
            self::TYPE_WARNING => '⚠️',
            self::TYPE_APPROVAL => '⏳',
            self::TYPE_DANGER => '❌',
            self::TYPE_INFO => 'ℹ️',
            default => '🔔',
        };
    }

    public function getColorClassAttribute(): string
    {
        return match($this->tipe) {
            self::TYPE_SUCCESS => 'border-emerald-200 bg-emerald-50',
            self::TYPE_WARNING => 'border-amber-200 bg-amber-50',
            self::TYPE_APPROVAL => 'border-blue-200 bg-blue-50',
            self::TYPE_DANGER => 'border-red-200 bg-red-50',
            self::TYPE_INFO => 'border-slate-200 bg-slate-50',
            default => 'border-slate-200 bg-slate-50',
        };
    }

    public function getRelatedEntityAttribute()
    {
        if ($this->entitas_terkait && $this->entitas_id) {
            $modelMap = [
                'bookings' => Booking::class,
                'users' => User::class,
                'rooms' => Room::class
            ];

            if (isset($modelMap[$this->entitas_terkait])) {
                return $modelMap[$this->entitas_terkait]::find($this->entitas_id);
            }
        }
        return null;
    }
}
