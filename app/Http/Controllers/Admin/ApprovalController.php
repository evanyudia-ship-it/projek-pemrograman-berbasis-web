<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApprovalController extends Controller
{
    /**
     * Menampilkan daftar booking pending dan riwayat approval.
     */
    public function index()
    {
        // Booking yang masih pending (menunggu persetujuan)
        $pending = Booking::with(['user', 'room'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        // Riwayat booking yang sudah diproses (approved, rejected, cancelled, completed)
        $history = Booking::with(['user', 'room'])
            ->whereIn('status', ['approved', 'rejected', 'cancelled', 'completed', 'no_show'])
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();

        // Statistik
        $stats = [
            'pending'  => Booking::where('status', 'pending')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'rejected' => Booking::where('status', 'rejected')->count(),
            'expired'  => Booking::where('status', 'no_show')->count(), // atau expired jika ada
        ];

        return view('admin.bookings.approvals', compact('pending', 'history', 'stats'));
    }

    /**
     * Menyetujui booking.
     */
    public function approve(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking sudah diproses.');
        }

        $booking->update([
            'status'          => 'approved',
            'disetujui_oleh'  => session('user_id'),
            'disetujui_at'    => now(),
        ]);

        // (Opsional) Tambah reputasi +2 poin untuk user
        // $booking->user->addReputationPoints(2, 'Booking disetujui', null, $booking->id);

        return back()->with('success', "Booking {$booking->booking_code} berhasil disetujui.");
    }

    /**
     * Menolak booking dengan alasan.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking sudah diproses.');
        }

        $booking->update([
            'status'               => 'rejected',
            'catatan_admin'        => $request->reason,
            'cancellation_reason'  => $request->reason,
            'disetujui_oleh'       => session('user_id'),
            'disetujui_at'         => now(),
        ]);

        return back()->with('success', "Booking {$booking->booking_code} berhasil ditolak.");
    }
}