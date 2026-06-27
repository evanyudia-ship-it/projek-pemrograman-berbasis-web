<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'nama',
        'icon',
        'kategori',
        'deskripsi'
    ];

    public function rooms()
    {
        return $this->belongsToMany(
            Room::class,
            'room_facilities'
        )->withPivot('status');
    }
}