<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'gedung',
        'lantai',
        'kapasitas',
        'alamat',
        'deskripsi',
        'foto',
        'jam_buka',
        'jam_tutup',
        'max_durasi_jam',
        'status',
        'faculty_id'
    ];

    public function facilities()
    {
        return $this->belongsToMany(
            Facility::class,
            'room_facilities'
        )->withPivot('status');
    }

    public function schedules()
    {
        return $this->hasMany(RoomSchedule::class);
    }
}