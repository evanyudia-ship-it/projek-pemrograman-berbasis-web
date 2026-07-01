<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Riwayat extends Model
{
    use SoftDeletes;

    protected $table = 'riwayat';

    protected $fillable = [
        'user_id',
        'jenis_aktivitas',
        'deskripsi',
        'entitas_terkait',
        'entitas_id',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== CONSTANTS ==========

    const ACTIVITY_BOOKING = 'peminjaman_ruangan';
    const ACTIVITY_UPDATE_PROFILE = 'update_profil';
    const ACTIVITY_CHECKIN = 'checkin';
    const ACTIVITY_CANCELLATION = 'pembatalan';
    const ACTIVITY_APPROVAL = 'approval';
    const ACTIVITY_REJECTION = 'penolakan';

    // ========== RELATIONS ==========

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ========== HELPERS ==========

    public function setEntity($model): void
    {
        if ($model) {
            $this->entitas_terkait = $model->getTable();
            $this->entitas_id = (string) $model->id;
        }
    }

    public function getEntity()
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

    public static function log(
        int $userId,
        string $jenisAktivitas,
        string $deskripsi = null,
        $entity = null
    ): self {
        $log = new self([
            'user_id' => $userId,
            'jenis_aktivitas' => $jenisAktivitas,
            'deskripsi' => $deskripsi,
        ]);

        if ($entity) {
            $log->setEntity($entity);
        }

        $log->save();
        return $log;
    }

    // ========== SCOPES ==========

    public function scopeOfType($query, string $type)
    {
        return $query->where('jenis_aktivitas', $type);
    }

    public function scopeBookingActivities($query)
    {
        return $query->where('jenis_aktivitas', self::ACTIVITY_BOOKING);
    }

    public function scopeCheckinActivities($query)
    {
        return $query->where('jenis_aktivitas', self::ACTIVITY_CHECKIN);
    }
}
