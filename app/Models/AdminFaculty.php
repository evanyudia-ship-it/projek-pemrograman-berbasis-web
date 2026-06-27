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
}
