@extends('layouts.app')

@section('title', 'Data Booking - Smart Classroom')
@section('page_title', 'Data Booking')
@section('page_subtitle', 'Daftar booking Anda')

@section('content')

@php
    $isAdmin = in_array(session('user_role'), ['admin', 'superadmin']);
    $userId = session('user_id');
@endphp

<div class="max-w-7xl mx-auto font-sora space-y-6">

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 font-semibold text-sm flex items-center gap-3">
        <span class="text-lg">✅</span>
        {{ session('success') }}
        <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-500 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 font-semibold text-sm flex items-center gap-3">
        <span class="text-lg">❌</span>
        {{ session('error') }}
        <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- STATISTIK CARD --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Booking</p>
                    <p class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Menunggu</p>
                    <p class="text-2xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Disetujui</p>
                    <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['approved'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">No Show</p>
                    <p class="text-2xl font-extrabold text-red-600 dark:text-red-400 mt-1">{{ $stats['no_show'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- TABEL BOOKING --}}
    {{-- ============================================================ --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">

        {{-- HEADER --}}
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-list-ul text-indigo-500"></i>
                        Daftar Booking Saya
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Total {{ $stats['total'] }} booking ·
                        <span class="text-amber-600 dark:text-amber-400 font-medium">{{ $stats['pending'] }} menunggu</span> ·
                        <span class="text-emerald-600 dark:text-emerald-400 font-medium">{{ $stats['approved'] }} disetujui</span> ·
                        <span class="text-red-600 dark:text-red-400 font-medium">{{ $stats['no_show'] }} no-show</span>
                    </p>
                </div>
                <a href="{{ route('bookings.create') }}"
                   class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold text-sm transition shadow-md hover:shadow-lg flex items-center gap-2 flex-shrink-0">
                    <i class="fas fa-plus-circle"></i> Ajukan Booking
                </a>
            </div>
        </div>

        {{-- TABEL --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-4 font-semibold">Kode</th>
                        <th class="text-left px-6 py-4 font-semibold">Ruang</th>
                        <th class="text-left px-6 py-4 font-semibold">Kegiatan</th>
                        <th class="text-left px-6 py-4 font-semibold">Jenis Kegiatan</th>
                        <th class="text-left px-6 py-4 font-semibold">Prioritas</th>
                        <th class="text-left px-6 py-4 font-semibold">Tanggal & Jam</th>
                        <th class="text-left px-6 py-4 font-semibold">Status</th>
                        <th class="text-center px-6 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($bookings as $bk)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition {{ $bk->status == 'no_show' ? 'bg-red-50/50 dark:bg-red-950/20' : '' }}">
                        {{-- Kode --}}
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-700 dark:text-slate-300 font-mono text-sm">{{ $bk->booking_code }}</span>
                        </td>

                        {{-- Ruang --}}
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-800 dark:text-white">{{ $bk->room->nama }}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 font-mono">{{ $bk->room->kode }}</p>
                        </td>

                        {{-- Kegiatan --}}
                        <td class="px-6 py-4">
                            <p class="text-slate-700 dark:text-slate-300 font-medium">{{ $bk->kegiatan }}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 line-clamp-1">{{ $bk->tujuan }}</p>
                        </td>

                        {{-- Jenis Kegiatan --}}
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-600 dark:text-slate-400">
                                {{ $bk->jenis_kegiatan ? App\Helpers\PriorityHelper::getLabel($bk->jenis_kegiatan) : '-' }}
                            </span>
                        </td>

                        {{-- Prioritas --}}
                        <td class="px-6 py-4">
                            @php
                                $priorityColor = App\Helpers\PriorityHelper::getPriorityColor($bk->priority_level);
                                $priorityLabel = App\Helpers\PriorityHelper::getPriorityLabel($bk->priority_level);
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $priorityColor }}">
                                {{ $priorityLabel }}
                            </span>
                        </td>

                        {{-- Tanggal & Jam --}}
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $bk->tanggal->translatedFormat('d M Y') }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($bk->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($bk->jam_selesai)->format('H:i') }}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">{{ $bk->durasi_menit }} menit</p>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            @php
                                $badge = match($bk->status) {
                                    'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
                                    'approved' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
                                    'ongoing' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                                    'completed' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
                                    'cancelled' => 'bg-slate-100 text-slate-400 dark:bg-slate-700 dark:text-slate-400',
                                    'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                    'no_show' => 'bg-red-200 text-red-800 dark:bg-red-900/50 dark:text-red-400',
                                    default => 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400',
                                };
                                $label = match($bk->status) {
                                    'pending' => '⏳ Menunggu',
                                    'approved' => '✅ Disetujui',
                                    'ongoing' => '🔄 Berlangsung',
                                    'completed' => '✔️ Selesai',
                                    'cancelled' => '❌ Dibatalkan',
                                    'rejected' => '🚫 Ditolak',
                                    'no_show' => '⚠️ No Show',
                                    default => ucfirst($bk->status),
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badge }}">
                                {{ $label }}
                            </span>
                            @if($bk->check_in_status == 'checkin_tepat_waktu')
                                <span class="block text-[10px] text-emerald-600 dark:text-emerald-400 mt-1">
                                    <i class="fas fa-check-circle mr-0.5"></i> Check-in tepat waktu
                                </span>
                            @elseif($bk->check_in_status == 'checkin_terlambat')
                                <span class="block text-[10px] text-red-600 dark:text-red-400 mt-1">
                                    <i class="fas fa-exclamation-circle mr-0.5"></i> Check-in terlambat
                                </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-1.5 flex-wrap">
                                {{-- DETAIL --}}
                                <a href="{{ route('bookings.show', $bk->id) }}"
                                   class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 font-semibold text-xs transition flex items-center gap-1"
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- EDIT - Hanya untuk booking pending milik sendiri --}}
                                @if($bk->status == 'pending' && $bk->user_id == $userId)
                                <a href="{{ route('bookings.edit', $bk->id) }}"
                                   class="px-3 py-1.5 rounded-lg bg-yellow-100 dark:bg-yellow-900/50 hover:bg-yellow-200 dark:hover:bg-yellow-800 text-yellow-700 dark:text-yellow-300 font-semibold text-xs transition flex items-center gap-1"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif

                                {{-- CANCEL - Hanya untuk booking yang bisa dibatalkan dan milik sendiri --}}
                                @if($bk->canBeCancelledByUser() && $bk->user_id == $userId && $bk->status != 'ongoing')
                                <a href="{{ route('bookings.cancel.create', $bk->id) }}"
                                   class="px-3 py-1.5 rounded-lg bg-red-100 dark:bg-red-900/50 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 font-semibold text-xs transition flex items-center gap-1"
                                   title="Batalkan">
                                    <i class="fas fa-times"></i>
                                </a>
                                @endif

                                {{-- CHECK-IN - Hanya untuk booking approved dan belum check-in milik sendiri --}}
                                @if($bk->status == 'approved' && $bk->check_in_status == 'belum_checkin' && $bk->user_id == $userId)
                                <form method="POST" action="{{ route('bookings.checkin', $bk->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 hover:bg-emerald-200 dark:hover:bg-emerald-800 text-emerald-700 dark:text-emerald-300 font-semibold text-xs transition flex items-center gap-1"
                                            title="Check-in">
                                        <i class="fas fa-fingerprint"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- COMPLETE - Hanya untuk booking ongoing milik sendiri --}}
                                @if($bk->status == 'ongoing' && $bk->user_id == $userId)
                                <form method="POST" action="{{ route('bookings.complete', $bk->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Apakah Anda sudah selesai menggunakan ruangan {{ $bk->room->nama }}?')"
                                            class="px-3 py-1.5 rounded-lg bg-blue-100 dark:bg-blue-900/50 hover:bg-blue-200 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-300 font-semibold text-xs transition flex items-center gap-1"
                                            title="Selesaikan">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- AKSI ADMIN - APPROVE --}}
                                @if($isAdmin && $bk->status == 'pending')
                                <form method="POST" action="{{ route('admin.approvals.approve', $bk->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 hover:bg-emerald-200 dark:hover:bg-emerald-800 text-emerald-700 dark:text-emerald-300 font-semibold text-xs transition flex items-center gap-1"
                                            title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>

                                <button type="button"
                                        onclick="showRejectModal('{{ $bk->id }}', '{{ $bk->booking_code }}')"
                                        class="px-3 py-1.5 rounded-lg bg-red-100 dark:bg-red-900/50 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 font-semibold text-xs transition flex items-center gap-1"
                                        title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif

                                {{-- ADMIN: Tandai No-Show --}}
                                @if($isAdmin && $bk->status == 'approved' && $bk->check_in_status == 'belum_checkin')
                                <form method="POST" action="{{ route('admin.bookings.mark-no-show', $bk->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Tandai booking {{ $bk->booking_code }} sebagai No-Show?')"
                                            class="px-3 py-1.5 rounded-lg bg-red-200 dark:bg-red-900/50 hover:bg-red-300 dark:hover:bg-red-800 text-red-800 dark:text-red-300 font-semibold text-xs transition flex items-center gap-1"
                                            title="Tandai No-Show">
                                        <i class="fas fa-user-slash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-4xl mb-4">
                                    <i class="fas fa-inbox text-slate-400 dark:text-slate-500"></i>
                                </div>
                                <p class="font-semibold text-slate-600 dark:text-slate-400 text-lg">Belum ada booking</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Ajukan booking pertama Anda sekarang</p>
                                <a href="{{ route('bookings.create') }}"
                                   class="mt-4 px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white text-sm font-semibold transition shadow-md hover:shadow-lg flex items-center gap-2">
                                    <i class="fas fa-plus-circle"></i> Ajukan Booking
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($bookings->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL REJECT (HANYA UNTUK ADMIN) --}}
{{-- ============================================================ --}}
@if($isAdmin)
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md p-6 animate-fade-in-up">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <h4 class="font-bold text-lg text-slate-800 dark:text-white">Tolak Booking</h4>
                <p class="text-sm text-slate-500 dark:text-slate-400">Berikan alasan penolakan</p>
            </div>
        </div>

        <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">
            Booking: <strong id="rejectBookingCode" class="font-mono">-</strong>
        </p>

        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="reason" id="rejectReason" rows="3"
                      class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition resize-none"
                      placeholder="Masukkan alasan penolakan..." required></textarea>
            <p id="rejectError" class="text-xs text-red-500 mt-1 hidden">
                <i class="fas fa-exclamation-circle mr-1"></i> Alasan penolakan wajib diisi
            </p>

            <div class="flex justify-end gap-3 mt-5">
                <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-xl font-semibold text-sm transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold text-sm transition shadow-sm">
                    <i class="fas fa-times mr-1"></i> Tolak Booking
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
.animate-fade-in-up {
    animation: fadeInUp 0.25s ease-out forwards;
}
</style>

<script>
function showRejectModal(id, code) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    const reason = document.getElementById('rejectReason');
    const error = document.getElementById('rejectError');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('rejectBookingCode').textContent = code;
    form.action = '{{ route("admin.approvals.reject", ["id" => "REPLACE_ID"]) }}'.replace('REPLACE_ID', id);
    reason.value = '';
    error.classList.add('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

// Validasi sebelum submit
document.getElementById('rejectForm').addEventListener('submit', function(e) {
    const reason = document.getElementById('rejectReason');
    const error = document.getElementById('rejectError');

    if (!reason.value.trim()) {
        e.preventDefault();
        error.classList.remove('hidden');
        reason.focus();
        return false;
    }
    error.classList.add('hidden');
    return true;
});

// Tutup modal jika klik di luar
document.addEventListener('click', function(e) {
    if (e.target.id === 'rejectModal') {
        closeRejectModal();
    }
});

// Tutup modal dengan tombol ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRejectModal();
    }
});
</script>
@endif

@endsection
