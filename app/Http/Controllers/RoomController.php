<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms.
     */
    public function index(Request $request)
    {
        $query = Room::with(['faculty', 'facilities']);

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by faculty
        if ($request->filled('faculty_id')) {
            $query->byFaculty($request->faculty_id);
        }

        // Filter by building
        if ($request->filled('gedung')) {
            $query->byBuilding($request->gedung);
        }

        // Filter by capacity
        if ($request->filled('min_capacity')) {
            $query->minCapacity($request->min_capacity);
        }

        $rooms = $query->get();

        // Hitung statistik
        $totalTersedia = Room::available()->count();
        $totalDipakai = Room::where('status', '!=', 'Tersedia')->count();
        $totalRooms = Room::count();
        $totalKapasitas = Room::sum('kapasitas');

        // Ambil daftar gedung untuk filter
        $buildings = Room::select('gedung')->distinct()->pluck('gedung');
        $faculties = Faculty::where('status', 'active')->get();

        // Enrich room data untuk view
        $rooms = $rooms->map(function ($room) {
            return $this->enrichRoomForView($room);
        });

        return view('rooms.index', compact(
            'rooms',
            'totalTersedia',
            'totalDipakai',
            'totalRooms',
            'totalKapasitas',
            'buildings',
            'faculties'
        ));
    }

    /**
     * Display room search page.
     */
    public function search(Request $request)
    {
        $query = Room::with(['faculty', 'facilities']);

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('faculty_id')) {
            $query->byFaculty($request->faculty_id);
        }

        if ($request->filled('gedung')) {
            $query->byBuilding($request->gedung);
        }

        if ($request->filled('min_capacity')) {
            $query->minCapacity($request->min_capacity);
        }

        // Filter tanggal dan jam (untuk pencarian ketersediaan)
        if ($request->filled('tanggal') && $request->filled('jam_mulai') && $request->filled('jam_selesai')) {
            $query->whereDoesntHave('bookings', function ($q) use ($request) {
                $q->whereDate('tanggal', $request->tanggal)
                    ->where('status', '!=', Booking::STATUS_CANCELLED)
                    ->where('status', '!=', Booking::STATUS_REJECTED)
                    ->where('status', '!=', Booking::STATUS_COMPLETED)
                    ->where(function ($sub) use ($request) {
                        $sub->where('jam_mulai', '<', $request->jam_selesai)
                            ->where('jam_selesai', '>', $request->jam_mulai);
                    });
            });
        }

        $rooms = $query->get()->map(function ($room) {
            return $this->enrichRoomForView($room);
        });

        $faculties = Faculty::where('status', 'active')->get();
        $buildings = Room::select('gedung')->distinct()->pluck('gedung');

        return view('rooms.search', compact('rooms', 'faculties', 'buildings'));
    }

    /**
     * Display room detail.
     */
    public function show(Request $request, int $id)
    {
        $room = Room::with(['faculty', 'facilities', 'bookings' => function ($query) {
            $query->where('status', '!=', Booking::STATUS_CANCELLED)
                  ->where('status', '!=', Booking::STATUS_REJECTED)
                  ->orderBy('tanggal')
                  ->orderBy('jam_mulai');
        }])->findOrFail($id);

        // Parse jadwal dari bookings untuk kalender
        $jadwal = [];
        foreach ($room->bookings as $booking) {
            $tgl = $booking->tanggal->format('Y-m-d');
            if (!isset($jadwal[$tgl])) {
                $jadwal[$tgl] = [];
            }
            $jadwal[$tgl][] = [
                'label' => $booking->kegiatan,
                'waktu' => Carbon::parse($booking->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($booking->jam_selesai)->format('H:i'),
                'tipe' => $booking->status === 'approved' ? 'penuh' : 'sebagian',
                'status' => $booking->status,
            ];
        }

        // Sederhanakan jadwal untuk view
        $jadwalSimplified = [];
        foreach ($jadwal as $tgl => $items) {
            $isFull = collect($items)->every(fn($item) => $item['tipe'] === 'penuh');
            $jadwalSimplified[$tgl] = [
                'label' => collect($items)->pluck('label')->implode(', '),
                'waktu' => collect($items)->pluck('waktu')->implode(', '),
                'tipe' => $isFull ? 'penuh' : 'sebagian',
                'count' => count($items),
            ];
        }

        $roomData = $this->enrichRoomForView($room);
        $roomData['jadwal'] = $jadwalSimplified;

        // Hitung total booking bulan ini
        $bulanIni = now()->format('Y-m');
        $totalBooking = $room->bookings()
            ->where('tanggal', 'LIKE', $bulanIni . '%')
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->count();

        $tahun = $request->input('tahun', now()->year);
        $bulan = $request->input('bulan', now()->month);

        $tahun = max(2020, min(2030, (int)$tahun));
        $bulan = max(1, min(12, (int)$bulan));

        $roomData['foto'] = $roomData['foto'] ?? asset('images/default-room.jpg');

        return view('rooms.show', compact('roomData', 'totalBooking', 'tahun', 'bulan'));
    }

    /**
     * Enrich room data for view.
     */
    private function enrichRoomForView($room): array
    {
        $fasilitas = $room->facilities->pluck('nama')->toArray();
        $fasilitasJson = json_encode($fasilitas);

        $displayedMeta = [];
        if (str_contains($fasilitasJson, 'kursi') || str_contains($fasilitasJson, 'Kursi')) {
            $displayedMeta[] = 'kursi tersedia';
        }
        if (str_contains($fasilitasJson, 'WiFi') || str_contains($fasilitasJson, 'wifi')) {
            $displayedMeta[] = 'WiFi siap';
        }
        if (str_contains($fasilitasJson, 'listrik') || str_contains($fasilitasJson, 'UPS') || str_contains($fasilitasJson, 'stop')) {
            $displayedMeta[] = 'listrik siap';
        }
        if (str_contains($fasilitasJson, 'AC') || str_contains($fasilitasJson, 'ac')) {
            $displayedMeta[] = 'AC aktif';
        }

        return [
            'id' => $room->id,
            'nama' => $room->nama,
            'kode' => $room->kode,
            'kapasitas' => $room->kapasitas,
            'lantai' => $room->lantai,
            'gedung' => $room->gedung,
            'fasilitas' => $fasilitas,
            'status' => $room->status,
            'status_label' => $room->getStatusLabelAttribute(),
            'jam_buka' => $room->getFormattedJamBukaAttribute(),
            'jam_tutup' => $room->getFormattedJamTutupAttribute(),
            'max_durasi' => $room->getMaxDurasiLabelAttribute(),
            'max_durasi_jam' => $room->max_durasi_jam,
            'foto' => $room->foto ?? asset('images/default-room.jpg'),
            'alamat' => $room->alamat,
            'deskripsi' => $room->deskripsi,
            'displayed_meta' => $displayedMeta,
            'faculty_id' => $room->faculty_id,
            'faculty_name' => $room->faculty?->name,
            'search_keywords' => strtolower($room->nama . ' ' . $room->kode . ' ' . $room->gedung),
            'maps_query' => urlencode(strip_tags($room->alamat ?? '')),
        ];
    }
}
