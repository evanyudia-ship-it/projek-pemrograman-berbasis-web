<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter
        $roomId   = $request->input('room');
        $date     = $request->input('date');
        $bulan    = $request->input('bulan', now()->month);
        $tahun    = $request->input('tahun', now()->year);

        // Daftar ruang untuk dropdown
        $rooms = Room::where('status', 'Tersedia')->orderBy('nama')->get();

        // Query booking yang approved atau completed
        $query = Booking::with(['room', 'user'])
            ->whereIn('status', ['approved', 'completed']);

        // Filter ruang
        if ($roomId) {
            $query->where('room_id', $roomId);
        }

        // Filter tanggal spesifik
        if ($date) {
            $query->whereDate('tanggal', $date);
        } else {
            // Jika tidak ada filter tanggal, ambil berdasarkan bulan & tahun
            $query->whereYear('tanggal', $tahun)
                  ->whereMonth('tanggal', $bulan);
        }

        $bookings = $query->orderBy('tanggal')->orderBy('jam_mulai')->get();

        // Group booking berdasarkan tanggal
        $bookingsGrouped = [];
        $dateStatuses = [];

        if ($bookings->count() > 0) {
            foreach ($bookings as $bk) {
                $tanggal = $bk->tanggal->format('Y-m-d');
                if (!isset($bookingsGrouped[$tanggal])) {
                    $bookingsGrouped[$tanggal] = [];
                }

                // Warna berdasarkan prioritas atau tipe
                $warnaMap = [
                    'High' => 'red',
                    'Medium-High' => 'purple',
                    'Medium' => 'yellow',
                    'Low' => 'blue',
                ];
                $warna = $warnaMap[$bk->priority_level] ?? 'blue';

                $bookingsGrouped[$tanggal][] = [
                    'ruang' => $bk->room->nama ?? '-',
                    'jam'   => $bk->jam_mulai->format('H:i') . '-' . $bk->jam_selesai->format('H:i'),
                    'title' => $bk->kegiatan,
                    'warna' => $warna,
                    'status' => $bk->status,
                    'id' => $bk->id,
                ];
            }
        }

        // Jika ada filter date, kita perlu status hari (past, today, etc) untuk kalender
        // Tapi karena kita menampilkan kalender bulanan, kita buat status untuk semua tanggal di bulan tersebut
        $bulanObj = Carbon::createFromDate($tahun, $bulan, 1);
        $startOfMonth = $bulanObj->copy()->startOfMonth();
        $endOfMonth = $bulanObj->copy()->endOfMonth();

        $dateStatuses = [];
        for ($dateLoop = $startOfMonth->copy(); $dateLoop->lte($endOfMonth); $dateLoop->addDay()) {
            $dateStr = $dateLoop->format('Y-m-d');
            $dateStatuses[$dateStr] = [
                'isPast' => $dateLoop->isPast() && !$dateLoop->isToday(),
                'isToday' => $dateLoop->isToday(),
            ];
        }

        // Jika ada filter tanggal spesifik, kita hanya tampilkan hari itu (tapi tetap di kalender)
        // Kita juga set variable untuk info filter
        $hasActiveFilter = !empty($roomId) || !empty($date);
        $totalBookingsAfterFilter = $bookings->count();

        return view('schedule.index', compact(
            'bookingsGrouped',
            'rooms',
            'roomId',
            'date',
            'bulan',
            'tahun',
            'hasActiveFilter',
            'totalBookingsAfterFilter',
            'dateStatuses'
        ));
    }
}