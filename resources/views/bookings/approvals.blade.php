@extends('layouts.app')

@section('title', 'Approval Booking')
@section('page_title', 'Approval Booking')
@section('page_subtitle', 'Daftar booking yang menunggu persetujuan')

@section('content')
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 font-semibold text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="font-bold text-lg">Daftar Booking Pending</h3>
            <p class="text-sm text-slate-500">Total {{ $bookings->total() }} booking menunggu persetujuan</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="text-left px-6 py-4">Kode</th>
                        <th class="text-left px-6 py-4">Pemohon</th>
                        <th class="text-left px-6 py-4">Ruang</th>
                        <th class="text-left px-6 py-4">Kegiatan</th>
                        <th class="text-left px-6 py-4">Tanggal</th>
                        <th class="text-left px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bookings as $bk)
                        <tr>
                            <td class="px-6 py-4 font-bold">{{ $bk->booking_code }}</td>
                            <td class="px-6 py-4">{{ $bk->user->name }}</td>
                            <td class="px-6 py-4">{{ $bk->room->nama }}</td>
                            <td class="px-6 py-4">{{ $bk->kegiatan }}</td>
                            <td class="px-6 py-4">{{ $bk->tanggal->translatedFormat('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.bookings.show', $bk->id) }}" class="text-blue-600 hover:underline">Detail</a>
                                    <form method="POST" action="{{ route('admin.approvals.approve', $bk->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-emerald-600 hover:underline font-semibold">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.approvals.reject', $bk->id) }}" class="inline">
                                        @csrf
                                        <input type="text" name="reason" placeholder="Alasan tolak..." class="text-xs border rounded px-2 py-1 w-32">
                                        <button type="submit" class="text-red-600 hover:underline font-semibold">Tolak</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-10 text-slate-400">Tidak ada booking pending.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200">{{ $bookings->links() }}</div>
    </div>
@endsection