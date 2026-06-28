<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminFaculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'faculty_id',
        'position',
        'status',
    ];

    /**
     * Data admin fakultas dimiliki oleh 1 user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Data admin fakultas dimiliki oleh 1 fakultas.
     */
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