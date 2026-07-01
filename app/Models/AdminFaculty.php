<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminFaculty extends Model
{
    use HasFactory, SoftDeletes;

    // ✅ TAMBAHKAN INI
    protected $table = 'admin_faculties';

    protected $fillable = [
        'user_id',
        'faculty_id',
        'position',
        'status',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
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
