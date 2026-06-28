@php
    $userId = session('user_id');
    $notifications = [];
    $unreadCount = 0;

    if ($userId) {
        $notifications = App\Models\Notifikasi::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($notif) {
                $iconMap = [
                    'success' => '✅',
                    'warning' => '⚠️',
                    'approval' => '⏳',
                    'danger' => '❌',
                    'info' => 'ℹ️',
                ];
                return [
                    'icon' => $iconMap[$notif->tipe] ?? '🔔',
                    'title' => $notif->judul,
                    'message' => $notif->pesan,
                    'time' => $notif->created_at->diffForHumans(),
                    'type' => $notif->tipe,
                    'read' => $notif->status === 'sudah_dibaca',
                    'id' => $notif->id,
                    'entity' => $notif->entitas_terkait,
                    'entity_id' => $notif->entitas_id,
                ];
            })
            ->toArray();

        $unreadCount = App\Models\Notifikasi::where('user_id', $userId)
            ->where('status', 'belum_dibaca')
            ->count();
    }
@endphp

<div class="relative">
    {{-- ===== BUTTON NOTIFIKASI ===== --}}
    <button type="button"
            id="notificationToggle"
            class="hidden md:flex w-9 h-9 items-center justify-center rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-500 hover:text-blue-600 transition relative dark:bg-bg-muted dark:text-text-secondary dark:hover:bg-nav-hover-bg dark:hover:text-nav-hover-txt"
            aria-label="Notifikasi"
            title="Notifikasi">
        🔔

        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 min-w-5 h-5 px-1 rounded-full bg-red-500 text-white text-[10px] flex items-center justify-center font-bold">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    {{-- ===== DROPDOWN NOTIFIKASI ===== --}}
    <div id="notificationDropdown"
         class="hidden absolute right-0 mt-3 w-96 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 overflow-hidden dark:bg-bg-surface dark:border-border">

        <div class="px-4 py-3 border-b border-slate-100 dark:border-border flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-text-primary">
                    Notifikasi
                </h3>
                <p class="text-xs text-slate-400">
                    Terbaru
                </p>
            </div>

            @if($unreadCount > 0)
                <a href="{{ route('notifications.index', ['status' => 'belum_dibaca']) }}"
                   class="text-xs px-2 py-1 rounded-full bg-red-50 text-red-600 font-semibold hover:bg-red-100 transition">
                    {{ $unreadCount }} baru
                </a>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notif)
                @php
                    $typeClass = match($notif['type'] ?? 'info') {
                        'success' => 'bg-green-50 text-green-700',
                        'danger' => 'bg-red-50 text-red-700',
                        'warning' => 'bg-amber-50 text-amber-700',
                        'approval' => 'bg-blue-50 text-blue-700',
                        default => 'bg-slate-50 text-slate-700',
                    };
                @endphp

                <div class="px-4 py-3 border-b border-slate-100 hover:bg-slate-50 dark:border-border dark:hover:bg-bg-muted {{ empty($notif['read']) ? 'bg-blue-50/40' : '' }}">
                    <div class="flex gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg {{ $typeClass }}">
                            {{ $notif['icon'] ?? '🔔' }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-sm font-semibold text-slate-800 dark:text-text-primary">
                                    {{ $notif['title'] ?? 'Notifikasi' }}
                                </p>

                                @if(empty($notif['read']))
                                    <span class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 shrink-0"></span>
                                @endif
                            </div>

                            <p class="text-xs text-slate-500 dark:text-text-secondary mt-0.5 leading-relaxed">
                                {{ $notif['message'] ?? '-' }}
                            </p>

                            <p class="text-[11px] text-slate-400 mt-1">
                                {{ $notif['time'] ?? 'Baru saja' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <div class="text-3xl mb-2">🔕</div>
                    <p class="text-sm font-semibold text-slate-600 dark:text-text-primary">
                        Belum ada notifikasi
                    </p>
                    <p class="text-xs text-slate-400">
                        Notifikasi aktivitas akan muncul di sini.
                    </p>
                </div>
            @endforelse
        </div>

        <div class="px-4 py-3 border-t border-slate-100 dark:border-border text-center">
            <a href="{{ route('notifications.index') }}"
               class="text-xs text-blue-600 hover:text-blue-700 font-medium transition">
                Lihat semua notifikasi →
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const notificationToggle = document.getElementById('notificationToggle');
    const notificationDropdown = document.getElementById('notificationDropdown');

    if (notificationToggle && notificationDropdown) {
        // Toggle dropdown
        notificationToggle.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            notificationDropdown.classList.toggle('hidden');
        });

        // Prevent dropdown from closing when clicking inside
        notificationDropdown.addEventListener('click', function (event) {
            event.stopPropagation();
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function () {
            notificationDropdown.classList.add('hidden');
        });
    }
});
</script>
@endpush
