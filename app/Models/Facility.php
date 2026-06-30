<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'icon',
        'kategori',
        'deskripsi',
        'deleted_at',
    ];

    public function rooms()
    {
        return $this->belongsToMany(
            Room::class,
            'room_facilities'
        )->withPivot('status');
    }
}
