@extends('layouts.app')

@section('title', 'Detail Booking - Smart Classroom')
@section('page_title', 'Detail Booking')
@section('page_subtitle', 'Informasi lengkap pengajuan booking')

@section('content')

@if(session('success'))
    <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold text-sm flex items-center gap-3">
        <span class="text-lg">✅</span>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 font-semibold text-sm flex items-center gap-3">
        <span class="text-lg">❌</span>
        {{ session('error') }}
    </div>
@endif

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        {{-- HEADER --}}
        <div class="p-6 border-b border-slate-200 bg-slate-50/50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <h3 class="font-bold text-xl text-slate-800">{{ $booking->booking_code }}</h3>
                        @php
                            $badge = match($booking->status) {
                                'pending' => 'bg-amber-100 text-amber-700',
                                'approved' => 'bg-emerald-100 text-emerald-700',
                                'ongoing' => 'bg-blue-100 text-blue-700',
                                'completed' => 'bg-slate-100 text-slate-700',
                                'cancelled' => 'bg-slate-100 text-slate-400',
                                'rejected' => 'bg-red-100 text-red-700',
                                'no_show' => 'bg-red-200 text-red-800',
                                default => 'bg-slate-100 text-slate-500',
                            };
                            $label = match($booking->status) {
                                'pending' => '⏳ Menunggu',
                                'approved' => '✅ Disetujui',
                                'ongoing' => '🔄 Sedang Berlangsung',
                                'completed' => '✔️ Selesai',
                                'cancelled' => '❌ Dibatalkan',
                                'rejected' => '🚫 Ditolak',
                                'no_show' => '⚠️ No Show',
                                default => ucfirst($booking->status),
                            };
                        @endphp
                        <span class="px-4 py-1.5 rounded-full text-sm font-bold {{ $badge }}">
                            {{ $label }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 mt-1">
                        Dibuat: {{ $booking->created_at->translatedFormat('d M Y, H:i') }}
                    </p>
                </div>
                <a href="{{ route('bookings.index') }}"
                   class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition flex items-center gap-2">
                    ← Kembali
                </a>
            </div>
        </div>

        {{-- BODY --}}
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Informasi Pemohon --}}
                <div class="space-y-4">
                    <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">
                        👤 Informasi Pemohon
                    </h4>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Jenis Kegiatan</span>
                        <span class="font-semibold text-slate-800">
                            {{ $booking->jenis_kegiatan ? App\Helpers\PriorityHelper::getLabel($booking->jenis_kegiatan) : '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Prioritas</span>
                        @php
                            $priorityColor = App\Helpers\PriorityHelper::getPriorityColor($booking->priority_level);
                            $priorityLabel = App\Helpers\PriorityHelper::getPriorityLabel($booking->priority_level);
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $priorityColor }}">
                            {{ $priorityLabel }}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Nama</span>
                            <span class="font-semibold text-slate-800">{{ $booking->user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Email</span>
                            <span class="font-semibold text-slate-800">{{ $booking->user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Role</span>
                            <span class="font-semibold text-slate-800">{{ ucfirst($booking->user->role) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Fakultas</span>
                            <span class="font-semibold text-slate-800">{{ $booking->user->faculty->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Informasi Ruang --}}
                <div class="space-y-4">
                    <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">
                        🏫 Informasi Ruang
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Nama Ruang</span>
                            <span class="font-semibold text-slate-800">{{ $booking->room->nama }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Kode</span>
                            <span class="font-semibold text-slate-800 font-mono">{{ $booking->room->kode }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Lokasi</span>
                            <span class="font-semibold text-slate-800">{{ $booking->room->gedung }}, Lantai {{ $booking->room->lantai }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Kapasitas</span>
                            <span class="font-semibold text-slate-800">{{ $booking->room->kapasitas }} orang</span>
                        </div>
                    </div>
                </div>

                {{-- Detail Booking --}}
                <div class="md:col-span-2 space-y-4">
                    <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">
                        📋 Detail Booking
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Kegiatan</span>
                            <span class="font-semibold text-slate-800">{{ $booking->kegiatan }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Prioritas</span>
                            <span class="font-semibold text-slate-800">{{ $booking->priority_label }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Tanggal</span>
                            <span class="font-semibold text-slate-800">{{ $booking->tanggal->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Jam</span>
                            <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Durasi</span>
                            <span class="font-semibold text-slate-800">{{ $booking->durasi_menit }} menit</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Check-in Deadline</span>
                            <span class="font-semibold text-slate-800 {{ $booking->checkin_deadline && $booking->checkin_deadline < now() ? 'text-red-600' : '' }}">
                                {{ $booking->checkin_deadline ? $booking->checkin_deadline->format('H:i') : '-' }}
                                @if($booking->checkin_deadline && $booking->checkin_deadline < now() && $booking->check_in_status == 'belum_checkin')
                                    <span class="text-red-600 text-xs">(Lewat)</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Tujuan --}}
                <div class="md:col-span-2 space-y-4">
                    <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">
                        📝 Tujuan Penggunaan
                    </h4>
                    <p class="text-sm text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">
                        {{ $booking->tujuan }}
                    </p>
                </div>

                {{-- Check-in Status --}}
                <div class="md:col-span-2 space-y-4">
                    <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">
                        ✅ Check-in Status
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Status Check-in</span>
                            <span class="font-semibold">
                                @if($booking->check_in_status == 'belum_checkin')
                                    <span class="text-amber-600">⏳ Belum Check-in</span>
                                @elseif($booking->check_in_status == 'checkin_tepat_waktu')
                                    <span class="text-emerald-600">✅ Tepat Waktu</span>
                                @elseif($booking->check_in_status == 'checkin_terlambat')
                                    <span class="text-red-600">⚠️ Terlambat</span>
                                @elseif($booking->check_in_status == 'no_show')
                                    <span class="text-red-700">🚫 No Show</span>
                                @else
                                    <span>{{ $booking->check_in_status }}</span>
                                @endif
                            </span>
                        </div>
                        @if($booking->check_in_at)
                        <div class="flex justify-between">
                            <span class="text-slate-500">Waktu Check-in</span>
                            <span class="font-semibold text-slate-800">{{ $booking->check_in_at->format('d M Y, H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Catatan Admin / Alasan Pembatalan --}}
                @if($booking->catatan_admin)
                <div class="md:col-span-2 space-y-4">
                    <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">
                        📌 Catatan Admin
                    </h4>
                    <p class="text-sm text-slate-600 bg-amber-50 p-4 rounded-xl border border-amber-100">
                        {{ $booking->catatan_admin }}
                    </p>
                </div>
                @endif

                @if($booking->cancellation_reason)
                <div class="md:col-span-2 space-y-4">
                    <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">
                        📌 Alasan Pembatalan
                    </h4>
                    <p class="text-sm text-slate-600 bg-red-50 p-4 rounded-xl border border-red-100">
                        {{ $booking->cancellation_reason }}
                    </p>
                </div>
                @endif

                {{-- Informasi Approval --}}
                @if($booking->disetujui_oleh)
                <div class="md:col-span-2 space-y-4">
                    <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">
                        📌 Informasi Persetujuan
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Disetujui Oleh</span>
                            <span class="font-semibold text-slate-800">{{ $booking->approvedBy->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Waktu Disetujui</span>
                            <span class="font-semibold text-slate-800">{{ $booking->disetujui_at ? $booking->disetujui_at->format('d M Y, H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- FOOTER - AKSI --}}
        <div class="p-6 border-t border-slate-200 bg-slate-50/50">
            <div class="flex flex-wrap gap-3">

                {{-- EDIT - Hanya jika pending dan milik sendiri --}}
                @if($booking->status == 'pending' && session('user_id') == $booking->user_id)
                    <a href="{{ route('bookings.edit', $booking->id) }}"
                       class="px-5 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white font-semibold transition shadow-sm flex items-center gap-2">
                        ✏️ Edit Booking
                    </a>
                @endif

                {{-- CANCEL - Hanya jika bisa dibatalkan dan milik sendiri --}}
                @if($booking->canBeCancelledByUser() && session('user_id') == $booking->user_id && $booking->status != 'ongoing')
                    <a href="{{ route('bookings.cancel.create', $booking->id) }}"
                       class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold transition shadow-sm flex items-center gap-2">
                        🗑 Batalkan Booking
                    </a>
                @endif

                {{-- CHECK-IN - Hanya jika approved dan belum check-in --}}
                @if($booking->status == 'approved' && $booking->check_in_status == 'belum_checkin' && session('user_id') == $booking->user_id)
                    <form method="POST" action="{{ route('bookings.checkin', $booking->id) }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold transition shadow-sm flex items-center gap-2">
                            ✅ Check-in Sekarang
                        </button>
                    </form>
                @endif

                {{-- COMPLETE - Hanya jika ongoing dan milik sendiri --}}
                @if($booking->status == 'ongoing' && session('user_id') == $booking->user_id)
                    <form method="POST" action="{{ route('bookings.complete', $booking->id) }}" class="inline">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Apakah Anda sudah selesai menggunakan ruangan {{ $booking->room->nama }}?')"
                                class="px-5 py-2.5 rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-semibold transition shadow-sm flex items-center gap-2">
                            ✔️ Selesaikan Booking
                        </button>
                    </form>
                @endif

                {{-- AKSI ADMIN --}}
                @if(in_array(session('user_role'), ['admin', 'superadmin']))
                    @if($booking->status == 'pending')
                        <form method="POST" action="{{ route('admin.approvals.approve', $booking->id) }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold transition shadow-sm flex items-center gap-2">
                                ✅ Setujui
                            </button>
                        </form>

                        <button type="button"
                                onclick="openRejectModal('{{ $booking->id }}', '{{ $booking->booking_code }}')"
                                class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold transition shadow-sm flex items-center gap-2">
                            🚫 Tolak
                        </button>
                    @endif

                    @if($booking->status == 'approved' && $booking->check_in_status == 'belum_checkin')
                        <form method="POST" action="{{ route('admin.bookings.mark-no-show', $booking->id) }}" class="inline">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Tandai booking {{ $booking->booking_code }} sebagai No-Show?')"
                                    class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition shadow-sm flex items-center gap-2">
                                🚫 Tandai No-Show
                            </button>
                        </form>
                    @endif
                @endif

                {{-- KEMBALI --}}
                <a href="{{ route('bookings.index') }}"
                   class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition flex items-center gap-2 ml-auto">
                    ← Kembali
                </a>

            </div>
        </div>

    </div>
</div>

{{-- MODAL REJECT (HANYA UNTUK ADMIN) --}}
@if(in_array(session('user_role'), ['admin', 'superadmin']))
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h4 class="font-bold text-lg mb-2">Tolak Booking</h4>
        <p class="text-sm text-slate-500 mb-4">Berikan alasan penolakan untuk booking <strong id="rejectBookingCode"></strong></p>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="reason" rows="3"
                      class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Alasan penolakan..." required></textarea>
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold text-sm transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold text-sm transition">
                    Ya, Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(id, code) {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
    document.getElementById('rejectBookingCode').textContent = code;
    document.getElementById('rejectBookingId').value = id;
    document.getElementById('rejectForm').action = '{{ url("admin/approvals") }}/' + id + '/reject';
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
}

// Tutup modal jika klik di luar
document.addEventListener('click', function(e) {
    if (e.target.id === 'rejectModal') {
        closeRejectModal();
    }
});
</script>
@endif

@endsection
