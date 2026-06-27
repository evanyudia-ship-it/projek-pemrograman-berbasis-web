<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomSchedule extends Model
{
    protected $fillable = [
        'room_id',
        'booking_id',
        'label',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'jenis_jadwal'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}