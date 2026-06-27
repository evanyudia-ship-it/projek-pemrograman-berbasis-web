<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReputationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reputation_setting_id',
        'booking_id',
        'point_before',
        'point_change',
        'point_after',
        'type',
        'reason',
        'description',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'point_before' => 'integer',
            'point_change' => 'integer',
            'point_after' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reputationSetting()
    {
        return $this->belongsTo(ReputationSetting::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isReward(): bool
    {
        return $this->type === 'reward';
    }

    public function isPenalty(): bool
    {
        return $this->type === 'penalty';
    }
}
