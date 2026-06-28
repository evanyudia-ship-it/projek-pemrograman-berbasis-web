<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReputationLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_points',
        'max_points',
        'color',
        'description',
        'is_banned',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'min_points' => 'integer',
            'max_points' => 'integer',
            'is_banned' => 'boolean',
        ];
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isBannedLevel(): bool
    {
        return $this->is_banned === true;
    }

    /**
     * Mencari level reputasi berdasarkan poin user.
     */
    public static function getLevelByPoints(int $points)
    {
        return self::where('status', 'active')
            ->where('min_points', '<=', $points)
            ->where(function ($query) use ($points) {
                $query->where('max_points', '>=', $points)
                    ->orWhereNull('max_points');
            })
            ->first();
    }
}