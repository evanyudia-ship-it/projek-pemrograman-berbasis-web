<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Kolom yang boleh diisi lewat create/update.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim',
        'nidn',
        'phone',
        'faculty_id',
        'status',
        'reputation_points',
        'deleted_at',
    ];

    /**
     * Kolom yang disembunyikan saat data user ditampilkan.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'reputation_points' => 'integer',
        ];
    }

    // ============================================================
    // RELATIONS
    // ============================================================

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function adminFaculties()
    {
        return $this->hasMany(AdminFaculty::class);
    }

    public function managedFaculties()
    {
        return $this->belongsToMany(Faculty::class, 'admin_faculties')
            ->withPivot('position', 'status')
            ->withTimestamps();
    }

    public function reputationLogs()
    {
        return $this->hasMany(ReputationLog::class);
    }

    public function createdReputationLogs()
    {
        return $this->hasMany(ReputationLog::class, 'created_by');
    }

    // ============================================================
    // ROLE CHECKS
    // ============================================================

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    /**
     * PERBAIKAN: Tambahkan method untuk role organisasi
     */
    public function isOrganisasi(): bool
    {
        return $this->role === 'organisasi';
    }

    /**
     * PERBAIKAN: Cek apakah user adalah role umum (bukan superadmin/admin)
     */
    public function isRegularUser(): bool
    {
        return in_array($this->role, ['mahasiswa', 'dosen', 'organisasi']);
    }

    // ============================================================
    // STATUS CHECKS
    // ============================================================

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }
}
