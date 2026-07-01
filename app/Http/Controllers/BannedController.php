<?php

namespace App\Http\Controllers;

use App\Models\Appeal;
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

        $reputation = $user->reputation_points ?? 0;
        $reason = 'Reputasi Anda di bawah 30 poin. Minimal 30 poin untuk menggunakan sistem.';

        // Cek apakah user sudah mengirim banding
        $hasAppeal = Appeal::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        return view('auth.banned', compact('user', 'reason', 'reputation', 'hasAppeal'));
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

        $request->validate([
            'message' => 'required|string|min:10|max:500',
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

        return back()->with('success', 'Banding Anda telah dikirim. Admin akan memproses dalam 1x24 jam.');
    }
}