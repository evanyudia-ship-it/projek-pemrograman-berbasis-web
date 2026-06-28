<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ReputationLog;
use App\Models\ReputationLevel;
use App\Models\ReputationSetting;
use Illuminate\Http\Request;

class ReputationController extends Controller
{
    /**
     * Tampilkan halaman reputasi user.
     */
    public function index()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login.');
        }

        $user = User::findOrFail($userId);

        // Log reputasi user
        $logs = ReputationLog::with(['user', 'booking'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Level saat ini
        $level = ReputationLevel::where('min_points', '<=', $user->reputation_points)
            ->where(function ($q) {
                $q->whereNull('max_points')
                  ->orWhere('max_points', '>=', $user->reputation_points);
            })
            ->first();

        // Level selanjutnya
        $nextLevel = ReputationLevel::where('min_points', '>', $user->reputation_points)
            ->orderBy('min_points', 'asc')
            ->first();

        return view('reputation.index', compact('user', 'logs', 'level', 'nextLevel'));
    }

    /**
     * Admin: Pengaturan reputasi.
     */
    public function settings()
    {
        $this->authorizeAdmin();
        $settings = ReputationSetting::all();
        return view('admin.reputation.settings', compact('settings'));
    }

    /**
     * Admin: Update pengaturan.
     */
    public function updateSettings(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'settings.*.id' => 'required|exists:reputation_settings,id',
            'settings.*.points' => 'required|integer|min:0',
        ]);

        foreach ($request->settings as $item) {
            ReputationSetting::where('id', $item['id'])->update(['points' => $item['points']]);
        }

        return redirect()->route('admin.reputation.settings')
            ->with('success', 'Pengaturan reputasi berhasil diperbarui.');
    }

    /**
     * Admin: Log reputasi.
     */
    public function logs(Request $request)
    {
        $this->authorizeAdmin();

        $query = ReputationLog::with(['user', 'booking', 'createdBy'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $logs = $query->paginate(20);
        $users = User::orderBy('name')->get();

        return view('admin.reputation.logs', compact('logs', 'users'));
    }

    /**
     * Admin: Kelola level reputasi.
     */
    public function levels()
    {
        $this->authorizeAdmin();
        $levels = ReputationLevel::orderBy('min_points')->get();
        return view('admin.reputation.levels', compact('levels'));
    }

    /**
     * Admin: Update level.
     */
    public function updateLevel(Request $request, $id)
    {
        $this->authorizeAdmin();

        $level = ReputationLevel::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:50',
            'min_points' => 'required|integer|min:0',
            'max_points' => 'nullable|integer|gt:min_points',
            'color' => 'nullable|string|max:20',
        ]);

        $level->update($request->only(['name', 'min_points', 'max_points', 'color']));

        return redirect()->route('admin.reputation.levels')
            ->with('success', 'Level reputasi berhasil diperbarui.');
    }

    /**
     * Cek otorisasi admin.
     */
    private function authorizeAdmin()
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'superadmin'])) {
            abort(403, 'Akses tidak diizinkan.');
        }
    }
}