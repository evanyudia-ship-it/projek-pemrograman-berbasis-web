<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $room = $request->room;
        $date = $request->date ?? now()->format('Y-m-d');

        // =========================
        // DUMMY DATA BOOKING
        // =========================
        $allBookings = collect([
            (object)[
                'room_id' => 'R-201',
                'title' => 'Kelas Pengganti',
                'start_time' => '08:00',
                'date' => now()->format('Y-m-d'),
                'status' => 'approved'
            ],
            (object)[
                'room_id' => 'LAB-01',
                'title' => 'Rapat Organisasi',
                'start_time' => '13:00',
                'date' => now()->format('Y-m-d'),
                'status' => 'approved'
            ],
            (object)[
                'room_id' => 'R-105',
                'title' => 'Diskusi Kelompok',
                'start_time' => '18:00',
                'date' => now()->format('Y-m-d'),
                'status' => 'approved'
            ],
        ]);

        // filter room
        if ($room && $room != 'all') {
            $allBookings = $allBookings->where('room_id', $room);
        }

        // filter date
        if ($date) {
            $allBookings = $allBookings->where('date', $date);
        }

        $bookings = $allBookings->values();

        // dummy rooms filter
        $rooms = collect([
            (object)['id' => 'R-201'],
            (object)['id' => 'R-105'],
            (object)['id' => 'LAB-01'],
            (object)['id' => 'AULA'],
        ]);

        return view('schedule.index', compact('bookings', 'rooms', 'room', 'date'));
    }
}