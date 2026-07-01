<?php

namespace App\Http\Controllers;

use App\Models\Appeal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannedController extends Controller
{
    /**
     * Tampilkan halaman banned.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Jika user tidak banned, redirect ke dashboard
        if ($user->status !== 'banned') {
            return redirect()->route('dashboard.redirect');
        }

        $reputation = $user->reputation_points ?? 0;
        $reason = 'Reputasi Anda di bawah 30 poin. Minimal 30 poin untuk menggunakan sistem.';

        if ($reputation < 30) {
            $reason = 'Reputasi Anda (' . $reputation . ' poin) di bawah batas minimum 30 poin.';
        }

        // Cek apakah user sudah mengirim banding
        $hasAppeal = Appeal::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        $lastAppeal = Appeal::where('user_id', $user->id)
            ->latest()
            ->first();

        return view('auth.banned', compact('user', 'reason', 'reputation', 'hasAppeal', 'lastAppeal'));
    }

    /**
     * Kirim banding.
     */
    public function appeal(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->status !== 'banned') {
            return redirect()->route('dashboard.redirect');
        }

        $request->validate([
            'message' => 'required|string|min:10|max:500',
        ], [
            'message.required' => 'Pesan banding wajib diisi.',
            'message.min' => 'Pesan banding minimal 10 karakter.',
            'message.max' => 'Pesan banding maksimal 500 karakter.',
        ]);

        // Cek apakah sudah ada banding pending
        $existing = Appeal::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengirim banding. Mohon tunggu proses admin.');
        }

        Appeal::create([
            'user_id' => $user->id,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Banding Anda telah dikirim. Admin akan memproses dalam 1×24 jam.');
    }

    // ============================================================
    // ADMIN METHODS
    // ============================================================

    /**
     * ADMIN: Daftar semua banding.
     */
    public function adminIndex()
    {
        $this->authorizeAdmin();

        $appeals = Appeal::with(['user', 'processedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => Appeal::count(),
            'pending' => Appeal::where('status', 'pending')->count(),
            'approved' => Appeal::where('status', 'approved')->count(),
            'rejected' => Appeal::where('status', 'rejected')->count(),
        ];

        return view('admin.appeals.index', compact('appeals', 'stats'));
    }

    /**
     * ADMIN: Detail banding.
     */
    public function adminShow($id)
    {
        $this->authorizeAdmin();

        $appeal = Appeal::with(['user', 'processedBy'])->findOrFail($id);

        return view('admin.appeals.show', compact('appeal'));
    }

    /**
     * ADMIN: Setujui banding.
     */
    public function adminApprove(Request $request, $id)
    {
        $this->authorizeAdmin();

        $appeal = Appeal::with(['user'])->findOrFail($id);

        if ($appeal->status !== 'pending') {
            return back()->with('error', 'Banding sudah diproses.');
        }

        $response = $request->input('response') ?? 'Banding disetujui. Akun Anda diaktifkan kembali.';

        // Update appeal
        $appeal->update([
            'status' => 'approved',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
            'admin_response' => $response,
        ]);

        // Aktifkan user
        $appeal->user->update([
            'status' => 'active',
            'reputation_points' => 30, // Reset ke minimal
        ]);

        return redirect()
            ->route('admin.appeals.index')
            ->with('success', 'Banding disetujui. Akun user telah diaktifkan kembali.');
    }

    /**
     * ADMIN: Tolak banding.
     */
    public function adminReject(Request $request, $id)
    {
        $this->authorizeAdmin();

        $request->validate([
            'response' => 'required|string|min:10|max:500',
        ], [
            'response.required' => 'Alasan penolakan wajib diisi.',
            'response.min' => 'Alasan penolakan minimal 10 karakter.',
            'response.max' => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        $appeal = Appeal::with(['user'])->findOrFail($id);

        if ($appeal->status !== 'pending') {
            return back()->with('error', 'Banding sudah diproses.');
        }

        $appeal->update([
            'status' => 'rejected',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
            'admin_response' => $request->response,
        ]);

        return redirect()
            ->route('admin.appeals.index')
            ->with('success', 'Banding ditolak.');
    }

    /**
     * Cek otorisasi admin.
     */
    private function authorizeAdmin()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Akses tidak diizinkan. Hanya admin yang dapat mengakses halaman ini.');
        }
    }
}
