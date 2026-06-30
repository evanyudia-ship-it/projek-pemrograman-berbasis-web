<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Display all notifications for current user.
     */
    public function index(Request $request)
    {
        $userId = session('user_id');

        $query = Notifikasi::where('user_id', $userId);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->through(function ($notif) {
                return [
                    'id' => $notif->id,
                    'judul' => $notif->judul,
                    'pesan' => $notif->pesan,
                    'tipe' => $notif->tipe,
                    'status' => $notif->status,
                    'entitas_terkait' => $notif->entitas_terkait,
                    'entitas_id' => $notif->entitas_id,
                    'dibaca_at' => $notif->dibaca_at,
                    'created_at' => $notif->created_at,
                    'waktu' => Carbon::parse($notif->created_at)->diffForHumans(),
                    'icon' => $this->getIcon($notif->tipe),
                    'is_read' => $notif->status === 'sudah_dibaca',
                ];
            });

        $unreadCount = Notifikasi::where('user_id', $userId)
            ->where('status', 'belum_dibaca')
            ->count();

        $totalCount = Notifikasi::where('user_id', $userId)->count();

        return view('notifications.index', compact(
            'notifications',
            'unreadCount',
            'totalCount'
        ));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(int $id)
    {
        $notification = Notifikasi::where('user_id', session('user_id'))
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca.'
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $userId = session('user_id');

        Notifikasi::where('user_id', $userId)
            ->where('status', 'belum_dibaca')
            ->update([
                'status' => 'sudah_dibaca',
                'dibaca_at' => now()
            ]);

        return redirect()->back()
            ->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }

    /**
     * Delete a notification.
     */
    public function destroy(int $id)
    {
        $notification = Notifikasi::where('user_id', session('user_id'))
            ->findOrFail($id);

        // Gunakan soft delete
        $notification->delete();

        return redirect()->back()
            ->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Permanently delete a notification (hard delete).
     */
    public function forceDelete(int $id)
    {
        $notification = Notifikasi::where('user_id', session('user_id'))
            ->withTrashed()
            ->findOrFail($id);

        $notification->forceDelete();

        return redirect()->back()
            ->with('success', 'Notifikasi berhasil dihapus permanen.');
    }

    /**
     * Delete all read notifications.
     */
    public function deleteAllRead()
    {
        $userId = session('user_id');

        Notifikasi::where('user_id', $userId)
            ->where('status', 'sudah_dibaca')
            ->delete();

        return redirect()->back()
            ->with('success', 'Semua notifikasi yang sudah dibaca telah dihapus.');
    }

    /**
     * Permanently delete all read notifications.
     */
    public function forceDeleteAllRead()
    {
        $userId = session('user_id');

        Notifikasi::where('user_id', $userId)
            ->where('status', 'sudah_dibaca')
            ->forceDelete();

        return redirect()->back()
            ->with('success', 'Semua notifikasi yang sudah dibaca telah dihapus permanen.');
    }

    /**
     * Get unread notifications count (AJAX).
     */
    public function getUnreadCount()
    {
        $userId = session('user_id');

        $count = Notifikasi::where('user_id', $userId)
            ->where('status', 'belum_dibaca')
            ->count();

        return response()->json([
            'unread_count' => $count,
            'has_unread' => $count > 0,
        ]);
    }

    /**
     * Get latest notifications (AJAX).
     */
    public function getLatest(Request $request)
    {
        $userId = session('user_id');
        $limit = $request->input('limit', 5);

        $notifications = Notifikasi::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'judul' => $notif->judul,
                    'pesan' => $notif->pesan,
                    'tipe' => $notif->tipe,
                    'waktu' => Carbon::parse($notif->created_at)->diffForHumans(),
                    'icon' => $this->getIcon($notif->tipe),
                    'is_read' => $notif->status === 'sudah_dibaca',
                ];
            });

        $unreadCount = Notifikasi::where('user_id', $userId)
            ->where('status', 'belum_dibaca')
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Create notification helper.
     */
    public static function create(
        int $userId,
        string $judul,
        string $pesan,
        string $tipe = 'info',
        ?string $entitasTerkait = null,
        ?string $entitasId = null
    ): Notifikasi {
        return Notifikasi::create([
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'status' => 'belum_dibaca',
            'entitas_terkait' => $entitasTerkait,
            'entitas_id' => $entitasId,
        ]);
    }

    /**
     * Send booking notification to user.
     */
    public static function sendBookingNotification(
        int $userId,
        string $judul,
        string $pesan,
        string $tipe = 'info',
        int $bookingId = null
    ): void {
        self::create(
            $userId,
            $judul,
            $pesan,
            $tipe,
            'bookings',
            $bookingId ? (string) $bookingId : null
        );
    }

    /**
     * Send notification to admin about new booking.
     */
    public static function notifyAdmins(string $judul, string $pesan, string $tipe = 'info', ?int $bookingId = null): void
    {
        // Get all admin users
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();

        foreach ($admins as $admin) {
            self::create(
                $admin->id,
                $judul,
                $pesan,
                $tipe,
                'bookings',
                $bookingId ? (string) $bookingId : null
            );
        }
    }

    /**
     * Send notification to multiple users.
     */
    public static function sendToMany(
        array $userIds,
        string $judul,
        string $pesan,
        string $tipe = 'info',
        ?string $entitasTerkait = null,
        ?string $entitasId = null
    ): void {
        foreach ($userIds as $userId) {
            self::create($userId, $judul, $pesan, $tipe, $entitasTerkait, $entitasId);
        }
    }

    /**
     * Get notification icon by type.
     */
    private function getIcon(string $type): string
    {
        return match($type) {
            'success' => '✅',
            'warning' => '⚠️',
            'approval' => '⏳',
            'danger' => '❌',
            'info' => 'ℹ️',
            default => '🔔',
        };
    }

    /**
     * Get notification color by type.
     */
    public static function getColor(string $type): string
    {
        return match($type) {
            'success' => 'green',
            'warning' => 'amber',
            'approval' => 'blue',
            'danger' => 'red',
            'info' => 'slate',
            default => 'slate',
        };
    }

    /**
     * Get notification type label.
     */
    public static function getTypeLabel(string $type): string
    {
        return match($type) {
            'success' => 'Berhasil',
            'warning' => 'Peringatan',
            'approval' => 'Persetujuan',
            'danger' => 'Penting',
            'info' => 'Informasi',
            default => 'Lainnya',
        };
    }

        /**
     * Get color class for notification type.
     */
    public function getColorClass(string $type): string
    {
        return match($type) {
            'success' => 'border-emerald-200 bg-emerald-50',
            'warning' => 'border-amber-200 bg-amber-50',
            'approval' => 'border-blue-200 bg-blue-50',
            'danger' => 'border-red-200 bg-red-50',
            'info' => 'border-slate-200 bg-slate-50',
            default => 'border-slate-200 bg-slate-50',
        };
    }

    /**
     * Get URL for entity.
     */
    public function getEntityUrl(string $entity, string $id): string
    {
        return match($entity) {
            'bookings' => route('bookings.show', $id),
            'rooms' => route('rooms.show', $id),
            'users' => route('profile.index', $id),
            'organizations' => route('organization.show', $id),
            default => '#',
        };
    }
}
