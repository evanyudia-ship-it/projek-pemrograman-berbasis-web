@extends('layouts.app')

@section('title', 'Detail Booking')
@section('page_title', 'Detail Booking')
@section('page_subtitle', 'Informasi lengkap pengajuan booking')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="font-bold text-xl">{{ $booking->booking_code }}</h3>
                    <p class="text-sm text-slate-500">Dibuat: {{ $booking->created_at->translatedFormat('d M Y H:i') }}</p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-bold
                    @if($booking->status == 'pending') bg-amber-100 text-amber-700
                    @elseif($booking->status == 'approved') bg-emerald-100 text-emerald-700
                    @elseif($booking->status == 'completed') bg-slate-100 text-slate-700
                    @elseif($booking->status == 'cancelled') bg-slate-100 text-slate-400
                    @elseif($booking->status == 'rejected') bg-red-100 text-red-700
                    @elseif($booking->status == 'no_show') bg-red-100 text-red-700
                    @endif">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-slate-500">Pemohon</p>
                    <p class="font-semibold">{{ $booking->user->name }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Role</p>
                    <p class="font-semibold">{{ ucfirst($booking->user->role) }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Ruang</p>
                    <p class="font-semibold">{{ $booking->room->nama }} ({{ $booking->room->kode }})</p>
                </div>
                <div>
                    <p class="text-slate-500">Kapasitas</p>
                    <p class="font-semibold">{{ $booking->room->kapasitas }} orang</p>
                </div>
                <div>
                    <p class="text-slate-500">Kegiatan</p>
                    <p class="font-semibold">{{ $booking->kegiatan }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Tanggal</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Jam</p>
                    <p class="font-semibold">{{ $booking->jam_mulai->format('H:i') }} - {{ $booking->jam_selesai->format('H:i') }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Durasi</p>
                    <p class="font-semibold">{{ $booking->durasi_menit }} menit</p>
                </div>
                <div class="col-span-2">
                    <p class="text-slate-500">Tujuan</p>
                    <p class="font-semibold">{{ $booking->tujuan }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-slate-500">Check-in Status</p>
                    <p class="font-semibold">
                        @if($booking->check_in_status == 'belum_checkin')
                            <span class="text-amber-600">Belum Check-in</span>
                        @elseif($booking->check_in_status == 'checkin_tepat_waktu')
                            <span class="text-emerald-600">Tepat Waktu</span>
                        @elseif($booking->check_in_status == 'checkin_terlambat')
                            <span class="text-red-600">Terlambat</span>
                        @else
                            {{ $booking->check_in_status }}
                        @endif
                    </p>
                </div>
                @if($booking->catatan_admin)
                <div class="col-span-2">
                    <p class="text-slate-500">Catatan Admin</p>
                    <p class="font-semibold">{{ $booking->catatan_admin }}</p>
                </div>
                @endif
                @if($booking->cancellation_reason)
                <div class="col-span-2">
                    <p class="text-slate-500">Alasan Pembatalan</p>
                    <p class="font-semibold">{{ $booking->cancellation_reason }}</p>
                </div>
                @endif
            </div>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('bookings.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition">Kembali</a>
                @if($booking->status == 'pending' && session('user_id') == $booking->user_id)
                    <a href="{{ route('bookings.edit', $booking->id) }}" class="px-5 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white font-semibold transition">Edit</a>
                    <a href="{{ route('bookings.cancel.create', $booking->id) }}" class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold transition">Batalkan</a>
                @endif
                @if($booking->status == 'approved' && $booking->check_in_status == 'belum_checkin' && session('user_id') == $booking->user_id)
                    <form method="POST" action="{{ route('bookings.checkin', $booking->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-semibold transition">Check-in Sekarang</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection