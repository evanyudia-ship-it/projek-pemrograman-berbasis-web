<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReputationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'point',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'point' => 'integer',
            'is_active' => 'boolean',
        ];
    }

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
