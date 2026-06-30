<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait AuthenticatesUser
{
    /**
     * Get current user from Auth or session.
     */
    protected function currentUser(): ?User
    {
        if (Auth::check()) {
            return Auth::user();
        }

        if (session()->has('user_id')) {
            return User::find(session('user_id'));
        }

        return null;
    }

    /**
     * Get current user ID.
     */
    protected function currentUserId(): ?int
    {
        $user = $this->currentUser();
        return $user ? $user->id : null;
    }

    /**
     * Get current user role.
     */
    protected function currentRole(): ?string
    {
        $user = $this->currentUser();
        return $user ? $user->role : session('user_role');
    }

    /**
     * Check if user is logged in.
     */
    protected function isLoggedIn(): bool
    {
        return Auth::check() || session('logged_in');
    }

    /**
     * Check if current user has specific role.
     */
    protected function hasRole(string|array $roles): bool
    {
        $role = $this->currentRole();

        if (is_array($roles)) {
            return in_array($role, $roles);
        }

        return $role === $roles;
    }

    /**
     * Authorize admin access.
     */
    protected function authorizeAdmin(): void
    {
        if (!$this->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Akses tidak diizinkan.');
        }
    }
}
