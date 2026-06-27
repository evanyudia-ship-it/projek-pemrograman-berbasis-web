<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomFacility extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'room_id',
        'facility_id',
        'status'
    ];

    protected $table = 'room_facilities';

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}