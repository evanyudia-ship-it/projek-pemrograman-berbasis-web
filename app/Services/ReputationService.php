<?php

namespace App\Services;

use App\Models\User;
use App\Models\ReputationLog;
use App\Models\ReputationSetting;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class ReputationService
{

    /**
     * Apply reputation change to user.
     */
    public function apply(
        User $user,
        string $code,
        ?Booking $booking = null,
        ?string $customReason = null,
        ?int $createdBy = null
    ): array {
        // Get setting from database
        $setting = ReputationSetting::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$setting) {
            return [
                'success' => false,
                'message' => "Setting '{$code}' tidak ditemukan atau tidak aktif.",
            ];
        }

        $pointChange = $setting->point;
        $type = $setting->type;
        $reason = $customReason ?? $setting->name;

        return DB::transaction(function () use ($user, $setting, $pointChange, $type, $reason, $booking, $createdBy) {
            $pointBefore = $user->reputation_points;

            // HITUNG POINT AFTER DENGAN BATAS 100
            $pointAfter = $pointBefore + $pointChange;

            // BATASI MAKSIMAL 100
            if ($pointAfter > 100) {
                $pointAfter = 100;
                // Sesuaikan point change agar tidak melebihi 100
                $pointChange = 100 - $pointBefore;
            }

            // BATASI MINIMAL 0
            if ($pointAfter < 0) {
                $pointAfter = 0;
                $pointChange = 0 - $pointBefore;
            }

            // Update user reputation
            $user->update([
                'reputation_points' => $pointAfter,
            ]);

            // Create log
            $log = ReputationLog::create([
                'user_id' => $user->id,
                'reputation_setting_id' => $setting->id,
                'booking_id' => $booking ? $booking->id : null,
                'point_before' => $pointBefore,
                'point_change' => $pointChange,
                'point_after' => $pointAfter,
                'type' => $type,
                'reason' => $reason,
                'description' => $setting->description,
                'created_by' => $createdBy,
            ]);

            return [
                'success' => true,
                'setting' => $setting,
                'log' => $log,
                'point_before' => $pointBefore,
                'point_change' => $pointChange,
                'point_after' => $pointAfter,
                'type' => $type,
                'reason' => $reason,
                'capped' => $pointBefore + $setting->point != $pointAfter, // Indikator apakah dibatasi
            ];
        });
    }

    /**
     * Reset user reputation to specific value.
     */
    public function reset(User $user, int $newValue = 100, ?int $createdBy = null): array
    {
        if ($newValue < 0 || $newValue > 100) {
            throw new \InvalidArgumentException('Reputasi harus antara 0-100.');
        }

        $pointBefore = $user->reputation_points;
        $pointChange = $newValue - $pointBefore;

        return DB::transaction(function () use ($user, $newValue, $pointBefore, $pointChange, $createdBy) {
            $user->update([
                'reputation_points' => $newValue,
            ]);

            $log = ReputationLog::create([
                'user_id' => $user->id,
                'reputation_setting_id' => null,
                'booking_id' => null,
                'point_before' => $pointBefore,
                'point_change' => $pointChange,
                'point_after' => $newValue,
                'type' => $pointChange > 0 ? 'reward' : 'penalty',
                'reason' => 'Reset reputasi oleh admin',
                'description' => "Reputasi direset dari {$pointBefore} ke {$newValue}",
                'created_by' => $createdBy,
            ]);

            return [
                'success' => true,
                'log' => $log,
                'point_before' => $pointBefore,
                'point_change' => $pointChange,
                'point_after' => $newValue,
            ];
        });
    }

    /**
     * Check if user can book based on reputation.
     */
    public function canBook(User $user): bool
    {
        $level = $this->getLevel($user->reputation_points);

        // If user is banned (reputation < 30 or is_banned = true)
        if ($level && $level->is_banned) {
            return false;
        }

        // Jika reputasi < 30, tidak bisa booking
        if ($user->reputation_points < 30) {
            return false;
        }

        return true;
    }

    /**
     * Get reputation level.
     */
    public function getLevel(int $points): ?\App\Models\ReputationLevel
    {
        return \App\Models\ReputationLevel::where('status', 'active')
            ->where('min_points', '<=', $points)
            ->where(function ($query) use ($points) {
                $query->where('max_points', '>=', $points)
                    ->orWhereNull('max_points');
            })
            ->first();
    }

    /**
     * Get max active bookings based on reputation.
     */
    public function getMaxActiveBookings(User $user): int
    {
        $points = $user->reputation_points;

        if ($points >= 80) {
            return 3; // Trusted User
        } elseif ($points >= 50) {
            return 2; // Normal
        } elseif ($points >= 30) {
            return 1; // Dibatasi
        } else {
            return 0; // Diblokir
        }
    }

    /**
     * Check if user has reached max active bookings.
     */
    public function hasReachedMaxActiveBookings(User $user): bool
    {
        $max = $this->getMaxActiveBookings($user);

        if ($max === 0) {
            return true;
        }

        $activeCount = Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved', 'ongoing'])
            ->count();

        return $activeCount >= $max;
    }

    /**
     * Get user's reputation summary.
     */
    public function getSummary(User $user): array
    {
        $level = $this->getLevel($user->reputation_points);
        $maxBookings = $this->getMaxActiveBookings($user);
        $activeCount = Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved', 'ongoing'])
            ->count();

        return [
            'points' => $user->reputation_points,
            'level' => $level,
            'max_bookings' => $maxBookings,
            'active_bookings' => $activeCount,
            'can_book' => $this->canBook($user),
            'has_reached_max' => $activeCount >= $maxBookings,
        ];
    }
}
