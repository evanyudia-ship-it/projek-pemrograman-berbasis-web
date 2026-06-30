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

    /**
     * Relasi: 1 user memiliki 1 fakultas.
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Relasi: 1 user/admin bisa memiliki banyak data admin fakultas.
     */
    public function adminFaculties()
    {
        return $this->hasMany(AdminFaculty::class);
    }

    /**
     * Relasi many-to-many:
     * 1 admin bisa mengelola banyak fakultas.
     */
    public function managedFaculties()
    {
        return $this->belongsToMany(Faculty::class, 'admin_faculties')
            ->withPivot('position', 'status')
            ->withTimestamps();
    }

    /**
     * Relasi: 1 user punya banyak log reputasi.
     */
    public function reputationLogs()
    {
        return $this->hasMany(ReputationLog::class);
    }

    /**
     * Relasi: user/admin yang membuat log reputasi.
     */
    public function createdReputationLogs()
    {
        return $this->hasMany(ReputationLog::class, 'created_by');
    }

    /**
     * Cek role superadmin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Cek role admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek role dosen.
     */
    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }

    /**
     * Cek role mahasiswa.
     */
    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    /**
     * Cek akun aktif.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Cek akun pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Cek akun nonaktif.
     */
    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    /**
     * Cek akun banned.
     */
    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }
}
