<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // ← TAMBAHKAN
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appeal extends Model
{
    use SoftDeletes; // ← TAMBAHKAN

    protected $fillable = [
        'user_id',
        'message',
        'status',
        'admin_response',
        'processed_by', // ← TAMBAHKAN
        'processed_at', // ← TAMBAHKAN
        'deleted_at',   // ← TAMBAHKAN
    ];

    protected $casts = [
        'status' => 'string',
        'processed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // ============================================================
    // CONSTANTS
    // ============================================================
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // ============================================================
    // SCOPES
    // ============================================================
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => '⏳ Menunggu',
            self::STATUS_APPROVED => '✅ Disetujui',
            self::STATUS_REJECTED => '❌ Ditolak',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-amber-100 text-amber-700',
            self::STATUS_APPROVED => 'bg-emerald-100 text-emerald-700',
            self::STATUS_REJECTED => 'bg-red-100 text-red-700',
            default => 'bg-slate-100 text-slate-500',
        };
    }
}
