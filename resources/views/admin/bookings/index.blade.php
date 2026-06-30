@extends('layouts.app')

@section('title', 'Manajemen Booking (Admin)')
@section('page_title', 'Manajemen Booking')
@section('page_subtitle', 'Daftar semua booking di sistem')

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

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    {{-- FILTER --}}
    <div class="p-6 border-b border-slate-200">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="text-sm font-semibold text-slate-700">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 w-48"
                    placeholder="Kode, kegiatan, pemohon...">
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-700">Status</label>
                <select name="status" class="rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="no_show" {{ request('status') == 'no_show' ? 'selected' : '' }}>No Show</option>
                </select>
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-700">Tanggal Mulai</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-700">Tanggal Akhir</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition">
                    🔍 Filter
                </button>
                <a href="{{ route('admin.bookings.index') }}" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-semibold text-sm transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- TABEL --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-6 py-4">Kode</th>
                    <th class="text-left px-6 py-4">Pemohon</th>
                    <th class="text-left px-6 py-4">Ruang</th>
                    <th class="text-left px-6 py-4">Kegiatan</th>
                    <th class="text-left px-6 py-4">Jenis Kegiatan</th>
                    <th class="text-left px-6 py-4">Prioritas</th>
                    <th class="text-left px-6 py-4">Tanggal</th>
                    <th class="text-left px-6 py-4">Jam</th>
                    <th class="text-left px-6 py-4">Status</th>
                    <th class="text-center px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($bookings as $bk)
                <tr class="hover:bg-slate-50 transition {{ $bk->status == 'no_show' ? 'bg-red-50/50' : '' }}">
                    {{-- Kode --}}
                    <td class="px-6 py-4 font-bold text-slate-700 font-mono">{{ $bk->booking_code }}</td>

                    {{-- Pemohon --}}
                    <td class="px-6 py-4">
                        <p class="font-semibold">{{ $bk->user->name ?? '-' }}</p>
                        <p class="text-xs text-slate-500">{{ ucfirst($bk->user->role ?? '-') }}</p>
                    </td>

                    {{-- Ruang --}}
                    <td class="px-6 py-4">
                        <p class="font-semibold">{{ $bk->room->nama ?? '-' }}</p>
                        <p class="text-xs text-slate-500 font-mono">{{ $bk->room->kode ?? '-' }}</p>
                    </td>

                    {{-- Kegiatan --}}
                    <td class="px-6 py-4">
                        <p class="text-slate-700">{{ $bk->kegiatan }}</p>
                        <p class="text-xs text-slate-400 line-clamp-1">{{ $bk->tujuan }}</p>
                    </td>

                    {{-- Jenis Kegiatan --}}
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-700">
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

                    {{-- Tanggal --}}
                    <td class="px-6 py-4">
                        <p class="font-semibold text-slate-700">{{ $bk->tanggal->translatedFormat('d M Y') }}</p>
                    </td>

                    {{-- Jam --}}
                    <td class="px-6 py-4">
                        <p class="font-semibold text-slate-700">{{ $bk->jam_mulai->format('H:i') }} - {{ $bk->jam_selesai->format('H:i') }}</p>
                        <p class="text-xs text-slate-400">{{ $bk->durasi_menit }} menit</p>
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4">
                        @php
                            $badge = match($bk->status) {
                                'pending' => 'bg-amber-100 text-amber-700',
                                'approved' => 'bg-emerald-100 text-emerald-700',
                                'ongoing' => 'bg-blue-100 text-blue-700',
                                'completed' => 'bg-slate-100 text-slate-700',
                                'cancelled' => 'bg-slate-100 text-slate-400',
                                'rejected' => 'bg-red-100 text-red-700',
                                'no_show' => 'bg-red-200 text-red-800',
                                default => 'bg-slate-100 text-slate-500',
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
                            <span class="block text-[10px] text-emerald-600 mt-1">✅ Check-in tepat waktu</span>
                        @elseif($bk->check_in_status == 'checkin_terlambat')
                            <span class="block text-[10px] text-red-600 mt-1">⚠️ Check-in terlambat</span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2 flex-wrap">
                            <a href="{{ route('admin.bookings.show', $bk->id) }}"
                                class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                                👁 Detail
                            </a>

                            @if($bk->status === 'pending')
                                <form method="POST" action="{{ route('admin.approvals.approve', $bk->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-2 rounded-lg bg-emerald-100 hover:bg-emerald-200 text-emerald-700 font-semibold text-xs transition">
                                        ✅ Setujui
                                    </button>
                                </form>

                                <button type="button"
                                    onclick="showRejectModal('{{ $bk->id }}', '{{ $bk->booking_code }}')"
                                    class="px-3 py-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 font-semibold text-xs transition">
                                    🚫 Tolak
                                </button>
                            @endif

                            @if($bk->status == 'approved' && $bk->check_in_status == 'belum_checkin')
                                <form method="POST" action="{{ route('admin.bookings.mark-no-show', $bk->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Tandai booking {{ $bk->booking_code }} sebagai No-Show?')"
                                        class="px-3 py-2 rounded-lg bg-red-200 hover:bg-red-300 text-red-800 font-semibold text-xs transition">
                                        🚫 No-Show
                                    </button>
                                </form>
                            @endif

                            @if(in_array($bk->status, ['pending', 'approved']))
                                <form method="POST" action="{{ route('admin.bookings.mark-fake', $bk->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Tandai booking {{ $bk->booking_code }} sebagai fiktif? Reputasi user akan berkurang 20 poin.')"
                                        class="px-3 py-2 rounded-lg bg-purple-100 hover:bg-purple-200 text-purple-700 font-semibold text-xs transition">
                                        🎭 Fiktif
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-16 text-center text-slate-400">
                        <p class="text-5xl mb-4">📭</p>
                        <p class="font-semibold text-slate-500">Tidak ada booking</p>
                        <p class="text-sm mt-1">Belum ada data booking di sistem.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="p-4 border-t border-slate-200">
        {{ $bookings->links() }}
    </div>
</div>

{{-- MODAL REJECT --}}
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h4 class="font-bold text-lg mb-2">Tolak Booking</h4>
        <p class="text-sm text-slate-500 mb-4">Berikan alasan penolakan untuk booking <strong id="rejectBookingCode"></strong></p>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="reason" rows="3"
                class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500"
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
function showRejectModal(id, code) {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
    document.getElementById('rejectBookingCode').textContent = code;
    document.getElementById('rejectForm').action = '{{ route("admin.approvals.reject", "") }}/' + id;
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
}

document.addEventListener('click', function(e) {
    if (e.target.id === 'rejectModal') {
        closeRejectModal();
    }
});
</script>

@endsection
