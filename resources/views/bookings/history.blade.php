@extends('layouts.app')

@section('title', 'Riwayat Booking')
@section('page_title', 'Riwayat Booking')
@section('page_subtitle', 'Booking yang telah selesai, dibatalkan, atau ditolak')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        @if($bookings->count())
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="text-left px-4 py-3">Kode</th>
                        <th class="text-left px-4 py-3">Ruang</th>
                        <th class="text-left px-4 py-3">Kegiatan</th>
                        <th class="text-left px-4 py-3">Tanggal</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($bookings as $bk)
                        <tr>
                            <td class="px-4 py-3 font-bold">{{ $bk->booking_code }}</td>
                            <td class="px-4 py-3">{{ $bk->room->nama }}</td>
                            <td class="px-4 py-3">{{ $bk->kegiatan }}</td>
                            <td class="px-4 py-3">{{ $bk->tanggal->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    @if($bk->status == 'completed') bg-slate-100 text-slate-700
                                    @elseif($bk->status == 'cancelled') bg-slate-100 text-slate-400
                                    @elseif($bk->status == 'rejected') bg-red-100 text-red-700
                                    @elseif($bk->status == 'no_show') bg-red-100 text-red-700
                                    @endif">
                                    {{ ucfirst($bk->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('bookings.show', $bk->id) }}" class="text-blue-600 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $bookings->links() }}</div>
        @else
            <p class="text-center text-slate-400 py-8">Tidak ada riwayat booking.</p>
        @endif
        <div class="mt-4">
            <a href="{{ route('bookings.index') }}" class="px-4 py-2 bg-slate-100 rounded-xl text-sm font-semibold hover:bg-slate-200 transition">Kembali ke Daftar Booking</a>
        </div>
    </div>
@endsection