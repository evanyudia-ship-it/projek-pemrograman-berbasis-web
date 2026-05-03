<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    public function index()
    {
        // dummy data booking pending (nanti dari DB)
        $bookings = [
            [
                'code' => 'BK-002',
                'name' => 'BEM Kampus',
                'role' => 'Organisasi',
                'room' => 'LAB-01',
                'title' => 'Rapat Organisasi',
                'time' => '03 Mei 2026, 13.00 - 16.00',
                'priority' => 'Medium',
                'created_at' => Carbon::now()->subHours(5),
            ],
            [
                'code' => 'BK-004',
                'name' => 'Pak Budi',
                'role' => 'Dosen',
                'room' => 'R-301',
                'title' => 'Kelas Pengganti',
                'time' => '04 Mei 2026, 08.00 - 11.00',
                'priority' => 'High',
                'created_at' => Carbon::now()->subHours(20),
            ],
        ];

        // statistik dummy
        $stats = [
            'pending' => 7,
            'approved_today' => 5,
            'expired' => 1,
        ];

        return view('admin.approvals', compact('bookings', 'stats'));
    }

    // approve booking (nanti ke DB)
    public function approve($id)
    {
        return response()->json([
            'message' => "Booking $id berhasil di-approve"
        ]);
    }

    // reject booking (nanti ke DB)
    public function reject(Request $request, $id)
    {
        return response()->json([
            'message' => "Booking $id ditolak",
            'reason' => $request->reason
        ]);
    }
}