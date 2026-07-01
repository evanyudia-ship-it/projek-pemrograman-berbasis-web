<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReputationSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'point',
        'description',
        'is_active',
        'deleted_at',
    ];

    protected function casts(): array
    {
        return [
            'point' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * 1 aturan reputasi bisa dipakai oleh banyak log reputasi.
     */
    public function reputationLogs()
    {
        return $this->hasMany(ReputationLog::class);
    }

    public function isReward(): bool
    {
        return $this->type === 'reward';
    }

    public function isPenalty(): bool
    {
        return $this->type === 'penalty';
    }

    public function isActive(): bool
    {
        return $this->is_active === true;
    }
}
