@extends('layouts.app')

@section('title', 'Manajemen Booking (Admin)')
@section('page_title', 'Manajemen Booking')
@section('page_subtitle', 'Daftar semua booking di sistem')

@section('content')
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 font-semibold text-sm">
            ❌ {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        {{-- FILTER --}}
        <div class="p-6 border-b border-slate-200">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="text-sm font-semibold text-slate-700">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:border-blue-400 w-48" 
                        placeholder="Kode, kegiatan, pemohon...">
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700">Status</label>
                    <select name="status" class="rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:border-blue-400">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="no_show" {{ request('status') == 'no_show' ? 'selected' : '' }}>No Show</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700">Tanggal Mulai</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                        class="rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:border-blue-400">
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700">Tanggal Akhir</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                        class="rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:border-blue-400">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition">Filter</button>
                    <a href="{{ route('admin.bookings.index') }}" class="px-5 py-2 bg-slate-200 text-slate-700 rounded-xl font-semibold hover:bg-slate-300 transition">Reset</a>
                </div>
            </form>
        </div>

        {{-- TABEL --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="text-left px-6 py-4">Kode</th>
                        <th class="text-left px-6 py-4">Pemohon</th>
                        <th class="text-left px-6 py-4">Ruang</th>
                        <th class="text-left px-6 py-4">Kegiatan</th>
                        <th class="text-left px-6 py-4">Tanggal</th>
                        <th class="text-left px-6 py-4">Jam</th>
                        <th class="text-left px-6 py-4">Status</th>
                        <th class="text-center px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bookings as $bk)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-slate-700">{{ $bk->booking_code }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold">{{ $bk->user->name ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ ucfirst($bk->user->role ?? '-') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold">{{ $bk->room->nama ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $bk->room->kode ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">{{ $bk->kegiatan }}</td>
                            <td class="px-6 py-4">{{ $bk->tanggal->translatedFormat('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold">{{ $bk->jam_mulai->format('H:i') }} - {{ $bk->jam_selesai->format('H:i') }}</p>
                                <p class="text-xs text-slate-500">{{ $bk->durasi_menit }} menit</p>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $badge = match($bk->status) {
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'approved' => 'bg-emerald-100 text-emerald-700',
                                        'completed' => 'bg-slate-100 text-slate-700',
                                        'cancelled' => 'bg-slate-100 text-slate-400',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'no_show' => 'bg-red-100 text-red-700',
                                        default => 'bg-slate-100 text-slate-500',
                                    };
                                    $label = match($bk->status) {
                                        'pending' => 'Pending',
                                        'approved' => 'Approved',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                        'rejected' => 'Rejected',
                                        'no_show' => 'No Show',
                                        default => ucfirst($bk->status),
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badge }}">{{ $label }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2 flex-wrap">
                                    <a href="{{ route('admin.bookings.show', $bk->id) }}" 
                                        class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                                        Detail
                                    </a>

                                    @if($bk->status === 'pending')
                                        <form method="POST" action="{{ route('admin.approvals.approve', $bk->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                class="px-3 py-2 rounded-lg bg-emerald-100 hover:bg-emerald-200 text-emerald-700 font-semibold text-xs transition">
                                                Setujui
                                            </button>
                                        </form>

                                        <button type="button" 
                                            onclick="showRejectModal('{{ $bk->id }}', '{{ $bk->booking_code }}')"
                                            class="px-3 py-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 font-semibold text-xs transition">
                                            Tolak
                                        </button>

                                        {{-- Modal Reject (simple) --}}
                                        <div id="rejectModal-{{ $bk->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                                            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
                                                <h4 class="font-bold text-lg mb-2">Tolak Booking</h4>
                                                <p class="text-sm text-slate-500 mb-4">Berikan alasan penolakan untuk booking <strong>{{ $bk->booking_code }}</strong></p>
                                                <form method="POST" action="{{ route('admin.approvals.reject', $bk->id) }}">
                                                    @csrf
                                                    <textarea name="reason" rows="3" class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:border-blue-400" placeholder="Alasan penolakan..." required></textarea>
                                                    <div class="flex justify-end gap-3 mt-4">
                                                        <button type="button" onclick="closeRejectModal('{{ $bk->id }}')" 
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
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                <p class="text-4xl mb-3">📭</p>
                                <p class="font-semibold text-slate-500">Tidak ada booking</p>
                                <p class="text-xs mt-1">Belum ada data booking di sistem.</p>
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

    <script>
        function showRejectModal(id, code) {
            document.getElementById('rejectModal-' + id).classList.remove('hidden');
            document.getElementById('rejectModal-' + id).classList.add('flex');
        }

        function closeRejectModal(id) {
            document.getElementById('rejectModal-' + id).classList.add('hidden');
            document.getElementById('rejectModal-' + id).classList.remove('flex');
        }

        // Tutup modal jika klik di luar area modal
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('fixed')) {
                const modals = document.querySelectorAll('[id^="rejectModal-"]');
                modals.forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                });
            }
        });
    </script>
@endsection