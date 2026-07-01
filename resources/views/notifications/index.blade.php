@extends('layouts.app')

@section('title', 'Notifikasi - Smart Classroom')
@section('page_title', 'Notifikasi')
@section('page_subtitle', 'Semua pemberitahuan dan aktivitas Anda')

@section('content')

<div class="max-w-5xl mx-auto font-sora space-y-6">

    {{-- ============================================================ --}}
    {{-- FLASH MESSAGES --}}
    {{-- ============================================================ --}}
    @if(session('success'))
    <div id="flashMsg"
         class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm font-semibold shadow-sm">
        <span class="text-lg">✅</span>
        {{ session('success') }}
        <button onclick="document.getElementById('flashMsg').remove()"
                class="ml-auto text-emerald-500 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-200 font-bold">✕</button>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- HEADER --}}
    {{-- ============================================================ --}}
    <div class="bg-gradient-to-br from-indigo-600 to-blue-600 dark:from-indigo-800 dark:to-blue-800 rounded-2xl p-6 md:p-8 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-3xl">
                    🔔
                </div>
                <div>
                    <p class="text-xs font-semibold text-white/70 uppercase tracking-widest">Pusat Notifikasi</p>
                    <h1 class="text-2xl md:text-3xl font-bold mt-0.5">Semua Notifikasi</h1>
                    <p class="text-sm text-white/80 mt-1 flex items-center gap-3">
                        <span>📬 {{ $totalCount }} total</span>
                        <span class="w-px h-4 bg-white/30"></span>
                        <span class="font-semibold {{ $unreadCount > 0 ? 'text-amber-300' : 'text-emerald-300' }}">
                            {{ $unreadCount > 0 ? '🔴 ' : '✅ ' }}{{ $unreadCount }} belum dibaca
                        </span>
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                @if($unreadCount > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2.5 bg-white/20 hover:bg-white/30 backdrop-blur text-white text-sm font-semibold rounded-xl transition flex items-center gap-2 border border-white/10">
                        <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                    </button>
                </form>
                @endif
                <form action="{{ route('notifications.delete-read') }}" method="POST"
                      onsubmit="return confirm('Hapus semua notifikasi yang sudah dibaca?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur text-white/80 hover:text-white text-sm font-semibold rounded-xl transition flex items-center gap-2 border border-white/5">
                        <i class="fas fa-trash-alt"></i> Hapus Dibaca
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- FILTER --}}
    {{-- ============================================================ --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
        <div class="flex flex-wrap gap-1.5">
            <a href="{{ route('notifications.index') }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition
                  {{ !request()->has('status') && !request()->has('tipe')
                      ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 dark:shadow-indigo-900/30'
                      : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                <i class="fas fa-list mr-1.5"></i> Semua
            </a>
            <a href="{{ route('notifications.index', ['status' => 'belum_dibaca']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition
                  {{ request('status') == 'belum_dibaca'
                      ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 dark:shadow-indigo-900/30'
                      : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                <i class="fas fa-circle text-red-500 text-[10px] mr-1.5"></i> Belum Dibaca
            </a>
            <a href="{{ route('notifications.index', ['status' => 'sudah_dibaca']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition
                  {{ request('status') == 'sudah_dibaca'
                      ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 dark:shadow-indigo-900/30'
                      : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                <i class="fas fa-check-circle text-emerald-500 mr-1.5"></i> Sudah Dibaca
            </a>
            <span class="w-px bg-slate-200 dark:bg-slate-600 mx-1.5"></span>
            <a href="{{ route('notifications.index', ['tipe' => 'approval']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition
                  {{ request('tipe') == 'approval'
                      ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 dark:shadow-indigo-900/30'
                      : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                <i class="fas fa-hourglass-half text-blue-500 mr-1.5"></i> Approval
            </a>
            <a href="{{ route('notifications.index', ['tipe' => 'success']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition
                  {{ request('tipe') == 'success'
                      ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 dark:shadow-indigo-900/30'
                      : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                <i class="fas fa-check-circle text-emerald-500 mr-1.5"></i> Sukses
            </a>
            <a href="{{ route('notifications.index', ['tipe' => 'warning']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition
                  {{ request('tipe') == 'warning'
                      ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 dark:shadow-indigo-900/30'
                      : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                <i class="fas fa-exclamation-triangle text-amber-500 mr-1.5"></i> Peringatan
            </a>
            <a href="{{ route('notifications.index', ['tipe' => 'danger']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition
                  {{ request('tipe') == 'danger'
                      ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 dark:shadow-indigo-900/30'
                      : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                <i class="fas fa-exclamation-circle text-red-500 mr-1.5"></i> Penting
            </a>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- NOTIFICATION LIST --}}
    {{-- ============================================================ --}}
    <div class="space-y-3">

        @forelse($notifications as $notif)
        @php
            $colorMap = [
                'success' => ['border' => 'border-emerald-200 dark:border-emerald-800', 'bg' => 'bg-emerald-50 dark:bg-emerald-950/20', 'icon' => 'text-emerald-500', 'badge' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300'],
                'warning' => ['border' => 'border-amber-200 dark:border-amber-800', 'bg' => 'bg-amber-50 dark:bg-amber-950/20', 'icon' => 'text-amber-500', 'badge' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300'],
                'approval' => ['border' => 'border-blue-200 dark:border-blue-800', 'bg' => 'bg-blue-50 dark:bg-blue-950/20', 'icon' => 'text-blue-500', 'badge' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300'],
                'danger' => ['border' => 'border-red-200 dark:border-red-800', 'bg' => 'bg-red-50 dark:bg-red-950/20', 'icon' => 'text-red-500', 'badge' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300'],
                'info' => ['border' => 'border-slate-200 dark:border-slate-700', 'bg' => 'bg-slate-50 dark:bg-slate-800/50', 'icon' => 'text-slate-500', 'badge' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300'],
            ];
            $style = $colorMap[$notif['tipe']] ?? $colorMap['info'];
            $isUnread = !$notif['is_read'];
        @endphp

        <div class="notification-item bg-white dark:bg-slate-800 rounded-2xl border {{ $isUnread ? 'border-l-4 border-l-indigo-500' : 'border-slate-200 dark:border-slate-700' }}
                    {{ $isUnread ? 'shadow-md shadow-indigo-100 dark:shadow-indigo-900/20' : 'shadow-sm' }}
                    p-5 hover:shadow-lg transition-all duration-200 group"
             data-id="{{ $notif['id'] }}">

            <div class="flex items-start gap-4">
                {{-- Icon --}}
                <div class="w-12 h-12 rounded-2xl {{ $style['bg'] }} border {{ $style['border'] }} flex items-center justify-center text-2xl shrink-0 {{ $style['icon'] }}">
                    {!! $notif['icon'] !!}
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-slate-800 dark:text-white text-sm">
                                    {{ $notif['judul'] }}
                                </h3>
                                @if($isUnread)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-500 text-white animate-pulse">
                                    BARU
                                </span>
                                @endif
                                <span class="text-xs px-2.5 py-0.5 rounded-full {{ $style['badge'] }} font-medium">
                                    {{ ucfirst($notif['tipe']) }}
                                </span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-300 mt-1.5 leading-relaxed">
                                {{ $notif['pesan'] }}
                            </p>
                            <div class="flex items-center gap-3 mt-2 text-xs text-slate-400 dark:text-slate-500">
                                <span class="flex items-center gap-1">
                                    <i class="far fa-clock"></i> {{ $notif['waktu'] }}
                                </span>
                                @if($notif['entitas_terkait'] && $notif['entitas_id'])
                                <span class="w-px h-3 bg-slate-300 dark:bg-slate-600"></span>
                                <a href="{{ route('bookings.show', $notif['entitas_id']) }}"
                                   class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium flex items-center gap-1">
                                    Lihat detail <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1.5 shrink-0">
                            @if($isUnread)
                            <button class="mark-read-btn px-3.5 py-2 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-semibold transition flex items-center gap-1.5 shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30"
                                    data-id="{{ $notif['id'] }}">
                                <i class="fas fa-check"></i> Tandai Dibaca
                            </button>
                            @endif
                            <form action="{{ route('notifications.destroy', $notif['id']) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus notifikasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="opacity-0 group-hover:opacity-100 transition px-3 py-2 rounded-xl bg-red-50 dark:bg-red-950/30 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-500 hover:text-red-700 text-xs font-semibold">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-16 text-center shadow-sm">
            <div class="flex flex-col items-center">
                <div class="w-24 h-24 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-5xl mb-4">
                    🔕
                </div>
                <p class="text-xl font-bold text-slate-600 dark:text-slate-400">Tidak ada notifikasi</p>
                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Semua notifikasi akan muncul di sini</p>
            </div>
        </div>
        @endforelse

    </div>

    {{-- ============================================================ --}}
    {{-- PAGINATION --}}
    {{-- ============================================================ --}}
    @if($notifications->hasPages())
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
        {{ $notifications->links() }}
    </div>
    @endif

</div>

{{-- ============================================================ --}}
{{-- TOAST NOTIFICATION --}}
{{-- ============================================================ --}}
<div id="toastContainer" class="fixed bottom-6 right-6 z-50 space-y-3"></div>

@push('scripts')
<script>
$(document).ready(function () {

    // ──────────────────────────────────────────────────────────────
    // MARK AS READ (AJAX)
    // ──────────────────────────────────────────────────────────────
    $(document).on('click', '.mark-read-btn', function (e) {
        e.preventDefault();

        const $btn = $(this);
        const id = $btn.data('id');
        const $item = $btn.closest('.notification-item');
        const originalText = $btn.html();

        // Disable button
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: "{{ url('notifications') }}/" + id + "/read",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update UI dengan animasi
                    $item.removeClass('border-l-4 border-l-indigo-500 shadow-md shadow-indigo-100 dark:shadow-indigo-900/20')
                         .addClass('border-slate-200 dark:border-slate-700 shadow-sm opacity-75');
                    $item.find('.text-red-500.animate-pulse').remove();
                    $item.find('.bg-red-500.text-white.animate-pulse').remove();
                    $btn.fadeOut(300, function() {
                        $(this).remove();
                    });

                    // Update counter
                    updateNotificationCounter();

                    // Show toast
                    showToast('✅ Notifikasi ditandai sudah dibaca', 'success');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(originalText);
                showToast('❌ Gagal memperbarui notifikasi', 'error');
            }
        });
    });

    // ──────────────────────────────────────────────────────────────
    // UPDATE NOTIFICATION COUNTER
    // ──────────────────────────────────────────────────────────────
    function updateNotificationCounter() {
        $.ajax({
            url: "{{ route('notifications.unread-count') }}",
            method: 'GET',
            success: function(response) {
                const $badge = $('.notification-badge');
                if (response.unread_count > 0) {
                    if ($badge.length) {
                        $badge.text(response.unread_count > 99 ? '99+' : response.unread_count);
                    } else {
                        $('#notificationToggle').append(
                            `<span class="notification-badge absolute -top-1 -right-1 min-w-5 h-5 px-1 rounded-full bg-red-500 text-white text-[10px] flex items-center justify-center font-bold shadow-sm">${response.unread_count > 99 ? '99+' : response.unread_count}</span>`
                        );
                    }
                } else {
                    $badge.remove();
                }
            }
        });
    }

    // ──────────────────────────────────────────────────────────────
    // TOAST NOTIFICATION
    // ──────────────────────────────────────────────────────────────
    function showToast(message, type = 'success') {
        const colors = {
            success: 'bg-emerald-600 text-white',
            error: 'bg-red-600 text-white',
            info: 'bg-indigo-600 text-white'
        };

        const icons = {
            success: '✅',
            error: '❌',
            info: 'ℹ️'
        };

        const $toast = $(`
            <div class="flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl ${colors[type] || colors.success} animate-slide-up">
                <span class="text-xl">${icons[type] || icons.success}</span>
                <span class="text-sm font-semibold">${message}</span>
                <button class="ml-2 text-white/70 hover:text-white text-sm font-bold" onclick="$(this).closest('.animate-slide-up').remove()">✕</button>
            </div>
        `);

        $('#toastContainer').append($toast);

        setTimeout(() => {
            $toast.fadeOut(400, function () {
                $(this).remove();
            });
        }, 4000);
    }

    // ──────────────────────────────────────────────────────────────
    // AUTO-DISMISS FLASH MESSAGE
    // ──────────────────────────────────────────────────────────────
    const flashMsg = $('#flashMsg');
    if (flashMsg.length) {
        setTimeout(() => {
            flashMsg.fadeOut(400, function () {
                $(this).remove();
            });
        }, 4000);
    }

});
</script>

<style>
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
.animate-slide-up {
    animation: slideUp 0.3s ease-out forwards;
}

/* Dark mode scrollbar */
.dark ::-webkit-scrollbar {
    width: 6px;
}
.dark ::-webkit-scrollbar-track {
    background: #1e293b;
}
.dark ::-webkit-scrollbar-thumb {
    background: #475569;
    border-radius: 9999px;
}
</style>
@endpush

@endsection
