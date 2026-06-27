<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $room = strip_tags($request->input('room', ''));
        $date = $request->has('date') && !empty($request->date)
            ? $request->date
            : null;

        // Dummy data (bisa diganti dengan query database nanti)
        $allBookings = collect([
            (object)[
                'room_id'   => 'R-201',
                'title'     => 'Kelas Pengganti',
                'start_time'=> '08:00',
                'date'      => now()->format('Y-m-d'),
                'status'    => 'approved',
                'warna'     => 'emerald'
            ],
            (object)[
                'room_id'   => 'LAB-01',
                'title'     => 'Rapat Organisasi',
                'start_time'=> '13:00',
                'date'      => now()->format('Y-m-d'),
                'status'    => 'approved',
                'warna'     => 'yellow'
            ],
            (object)[
                'room_id'   => 'R-105',
                'title'     => 'Diskusi Kelompok',
                'start_time'=> '18:00',
                'date'      => now()->format('Y-m-d'),
                'status'    => 'approved',
                'warna'     => 'blue'
            ],
        ]);

        // Filter berdasarkan room
        if ($room && $room != 'all' && $room != '') {
            $allBookings = $allBookings->where('room_id', $room);
        }

        // Filter berdasarkan tanggal (jika ada)
        if ($date) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                $date = null;
            } else {
                $allBookings = $allBookings->where('date', $date);
            }
        }

        $totalBookingsAfterFilter = $allBookings->count();
        $hasActiveFilter = !empty($room) && $room !== 'all' || !empty($date);

        // Konversi ke format yang dibutuhkan Blade
        $bookingsGrouped = $allBookings->groupBy('date')->map(function ($items) {
            return $items->map(function ($b) {
                return [
                    'ruang'  => $b->room_id,
                    'jam'    => $b->start_time,
                    'warna'  => $b->warna ?? 'blue',
                    'title'  => $b->title ?? '',
                    'status' => $b->status ?? 'approved',
                ];
            });
        })->toArray();

        // Daftar Ruangan
        $rooms = collect([
            (object)['id' => 'R-201', 'nama' => 'Ruang Kelas 201'],
            (object)['id' => 'R-105', 'nama' => 'Ruang Kelas 105'],
            (object)['id' => 'LAB-01', 'nama' => 'Laboratorium Komputer'],
            (object)['id' => 'AULA',   'nama' => 'Aula Utama'],
        ]);

        // Data Kalender (Status Hari)
        $today = Carbon::today();
        $tahun = $request->get('tahun', $today->year);
        $bulan = $request->get('bulan', $today->month);

        $bulanIni = Carbon::createFromDate($tahun, $bulan, 1);
        $totalHari = $bulanIni->daysInMonth;

        $dateStatuses = [];

        for ($hari = 1; $hari <= $totalHari; $hari++) {
            $tglStr = $bulanIni->format('Y-m') . '-' . str_pad($hari, 2, '0', STR_PAD_LEFT);
            $dateStatuses[$tglStr] = [
                'isPast'  => Carbon::parse($tglStr)->lt($today),
                'isToday' => $tglStr === $today->format('Y-m-d'),
            ];
        }

        return view('schedule.index', compact(
            'bookingsGrouped',
            'rooms',
            'room',
            'date',
            'totalBookingsAfterFilter',
            'hasActiveFilter',
            'dateStatuses',
            'tahun',
            'bulan'
        ));
    }
}
