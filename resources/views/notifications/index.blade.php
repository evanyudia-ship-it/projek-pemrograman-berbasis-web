@extends('layouts.app')

@section('title', 'Notifikasi - Smart Classroom')
@section('page_title', 'Notifikasi')
@section('page_subtitle', 'Semua pemberitahuan dan aktivitas Anda')

@section('content')

<div class="max-w-5xl mx-auto font-sora space-y-6">

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success'))
    <div id="flashMsg"
         class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold">
        <span class="text-lg">✅</span>
        {{ session('success') }}
        <button onclick="document.getElementById('flashMsg').remove()"
                class="ml-auto text-emerald-500 hover:text-emerald-700 font-bold">✕</button>
    </div>
    @endif

    {{-- ===== HEADER ===== --}}
    <div class="fade-up">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-semibold text-green-600 uppercase tracking-widest">🔔 Pusat Notifikasi</p>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mt-1">Semua Notifikasi</h1>
                <p class="text-slate-500 text-sm mt-1">
                    {{ $totalCount }} total ·
                    <span class="font-semibold text-{{ $unreadCount > 0 ? 'red' : 'emerald' }}-600">
                        {{ $unreadCount }} belum dibaca
                    </span>
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if($unreadCount > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-xl transition">
                        ✅ Tandai Semua Dibaca
                    </button>
                </form>
                @endif
                <form action="{{ route('notifications.delete-read') }}" method="POST"
                      onsubmit="return confirm('Hapus semua notifikasi yang sudah dibaca?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition">
                        🗑 Hapus Dibaca
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== FILTER ===== --}}
    <div class="fade-up delay-1">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('notifications.index') }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition
                  {{ !request()->has('status') && !request()->has('tipe')
                      ? 'bg-slate-900 text-white'
                      : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua
            </a>
            <a href="{{ route('notifications.index', ['status' => 'belum_dibaca']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition
                  {{ request('status') == 'belum_dibaca'
                      ? 'bg-slate-900 text-white'
                      : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                🔴 Belum Dibaca
            </a>
            <a href="{{ route('notifications.index', ['status' => 'sudah_dibaca']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition
                  {{ request('status') == 'sudah_dibaca'
                      ? 'bg-slate-900 text-white'
                      : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                ✅ Sudah Dibaca
            </a>
            <span class="w-px bg-slate-200 mx-1"></span>
            <a href="{{ route('notifications.index', ['tipe' => 'approval']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition
                  {{ request('tipe') == 'approval'
                      ? 'bg-slate-900 text-white'
                      : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                ⏳ Approval
            </a>
            <a href="{{ route('notifications.index', ['tipe' => 'success']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition
                  {{ request('tipe') == 'success'
                      ? 'bg-slate-900 text-white'
                      : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                ✅ Sukses
            </a>
            <a href="{{ route('notifications.index', ['tipe' => 'warning']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition
                  {{ request('tipe') == 'warning'
                      ? 'bg-slate-900 text-white'
                      : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                ⚠️ Peringatan
            </a>
            <a href="{{ route('notifications.index', ['tipe' => 'danger']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition
                  {{ request('tipe') == 'danger'
                      ? 'bg-slate-900 text-white'
                      : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                ❌ Penting
            </a>
        </div>
    </div>

    {{-- ===== NOTIFICATION LIST ===== --}}
    <div class="fade-up delay-2 space-y-3">

        @forelse($notifications as $notif)
        <div class="notification-item bg-white rounded-3xl border {{ $notif['is_read'] ? 'border-slate-100' : 'border-slate-300 shadow-md' }}
                    p-5 hover:shadow-lg transition-all group
                    {{ $notif['is_read'] ? 'opacity-80' : '' }}"
             data-id="{{ $notif['id'] }}">

            <div class="flex items-start gap-4">
                {{-- Icon --}}
                <div class="w-12 h-12 rounded-2xl {{ $this->getColorClass($notif['tipe']) }} flex items-center justify-center text-2xl shrink-0">
                    {{ $notif['icon'] }}
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-slate-800 text-sm">
                                    {{ $notif['judul'] }}
                                </h3>
                                @if(!$notif['is_read'])
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-red-500 text-white">
                                    BARU
                                </span>
                                @endif
                                <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 font-medium">
                                    {{ ucfirst($notif['tipe']) }}
                                </span>
                            </div>
                            <p class="text-sm text-slate-600 mt-1 leading-relaxed">
                                {{ $notif['pesan'] }}
                            </p>
                            <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                                <span>🕐</span> {{ $notif['waktu'] }}
                                @if($notif['entitas_terkait'] && $notif['entitas_id'])
                                <span class="mx-1">·</span>
                                <a href="{{ $this->getEntityUrl($notif['entitas_terkait'], $notif['entitas_id']) }}"
                                   class="text-blue-600 hover:underline">
                                    Lihat detail →
                                </a>
                                @endif
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 shrink-0">
                            @if(!$notif['is_read'])
                            <button class="mark-read-btn px-3 py-1.5 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold transition"
                                    data-id="{{ $notif['id'] }}">
                                ✅ Tandai Dibaca
                            </button>
                            @endif
                            <form action="{{ route('notifications.destroy', $notif['id']) }}" method="POST"
                                  onsubmit="return confirm('Hapus notifikasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="opacity-0 group-hover:opacity-100 transition px-3 py-1.5 rounded-xl bg-red-100 hover:bg-red-200 text-red-600 text-xs font-semibold">
                                    🗑
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-3xl border border-slate-100 p-16 text-center">
            <p class="text-6xl mb-4">🔕</p>
            <p class="text-xl font-bold text-slate-600">Tidak ada notifikasi</p>
            <p class="text-sm text-slate-400 mt-1">Semua notifikasi akan muncul di sini</p>
        </div>
        @endforelse

    </div>

    {{-- ===== PAGINATION ===== --}}
    @if($notifications->hasPages())
    <div class="fade-up delay-3">
        {{ $notifications->links() }}
    </div>
    @endif

</div>

@push('scripts')
<script>
$(document).ready(function () {
    // ── Mark as Read (AJAX) ──────────────────────────────────────────
    $('.mark-read-btn').on('click', function (e) {
        e.preventDefault();

        const $btn = $(this);
        const id = $btn.data('id');
        const $item = $btn.closest('.notification-item');

        $.ajax({
            url: "{{ url('notifications') }}/" + id + "/read",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $btn.text('⏳ Memproses...').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    // Update UI
                    $item.removeClass('border-slate-300 shadow-md').addClass('border-slate-100 opacity-80');
                    $item.find('.bg-red-500').remove();
                    $btn.remove();

                    // Update counter
                    updateNotificationCounter();

                    // Show success message
                    showToast('✅ Notifikasi ditandai sudah dibaca');
                }
            },
            error: function() {
                $btn.text('✅ Tandai Dibaca').prop('disabled', false);
                showToast('❌ Gagal memperbarui notifikasi');
            }
        });
    });

    // ── Update Notification Counter ──────────────────────────────────
    function updateNotificationCounter() {
        $.ajax({
            url: "{{ route('notifications.unread-count') }}",
            method: 'GET',
            success: function(response) {
                // Update badge di header
                const $badge = $('.notification-badge');
                if (response.unread_count > 0) {
                    if ($badge.length) {
                        $badge.text(response.unread_count > 99 ? '99+' : response.unread_count);
                    } else {
                        $('.notification-button').append(
                            `<span class="notification-badge absolute -top-1 -right-1 min-w-5 h-5 px-1 rounded-full bg-red-500 text-white text-[10px] flex items-center justify-center font-bold">${response.unread_count > 99 ? '99+' : response.unread_count}</span>`
                        );
                    }
                } else {
                    $badge.remove();
                }
            }
        });
    }

    // ── Toast Notification ────────────────────────────────────────────
    function showToast(message) {
        const $toast = $(`
            <div class="fixed bottom-4 right-4 bg-slate-900 text-white px-6 py-3 rounded-2xl shadow-lg text-sm font-semibold z-50 animate-fade-in-up">
                ${message}
            </div>
        `);
        $('body').append($toast);
        setTimeout(() => {
            $toast.fadeOut(400, function () {
                $(this).remove();
            });
        }, 3000);
    }

    // ── Auto-dismiss flash message ────────────────────────────────────
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
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out;
}
</style>
@endpush

@endsection
