<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
        'deleted_at',
    ];

    /**
     * 1 fakultas memiliki banyak user.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * 1 fakultas memiliki banyak data admin fakultas.
     */
    public function adminFaculties()
    {
        return $this->hasMany(AdminFaculty::class);
    }

    /**
     * Many-to-many:
     * 1 fakultas bisa dikelola banyak admin.
     */
    public function admins()
    {
        return $this->belongsToMany(User::class, 'admin_faculties')
            ->withPivot('position', 'status')
            ->withTimestamps();
    }

    /**
     * Admin fakultas yang aktif.
     */
    public function activeAdmins()
    {
        return $this->belongsToMany(User::class, 'admin_faculties')
            ->withPivot('position', 'status')
            ->wherePivot('status', 'active')
            ->withTimestamps();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }
}
