<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function adminFaculties()
    {
        return $this->hasMany(AdminFaculty::class);
    }

    public function admins()
    {
        return $this->belongsToMany(User::class, 'admin_faculties')
            ->withPivot('position', 'status')
            ->withTimestamps();
    }

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
}
