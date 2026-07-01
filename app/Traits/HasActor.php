<?php

namespace App\Traits;

/**
 * @property string $actor_type
 */
trait HasActor
{
    /**
     * Check if actor is user
     *
     * @return bool
     */
    public function isUserAction(): bool
    {
        return $this->actor_type === 'user';
    }

    /**
     * Check if actor is admin
     *
     * @return bool
     */
    public function isAdminAction(): bool
    {
        return $this->actor_type === 'admin';
    }

    /**
     * Check if actor is system
     *
     * @return bool
     */
    public function isSystemAction(): bool
    {
        return $this->actor_type === 'system';
    }

    /**
     * Get actor label
     *
     * @return string
     */
    public function getActorLabelAttribute(): string
    {
        return match($this->actor_type) {
            'user' => 'User',
            'admin' => 'Admin',
            'system' => 'Sistem',
            default => 'Unknown',
        };
    }

    /**
     * Get actor icon
     *
     * @return string
     */
    public function getActorIconAttribute(): string
    {
        return match($this->actor_type) {
            'user' => '👤',
            'admin' => '👨‍💼',
            'system' => '🤖',
            default => '❓',
        };
    }
}
