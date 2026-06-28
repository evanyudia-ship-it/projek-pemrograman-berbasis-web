@extends('layouts.app')

@section('title', 'Check-in Booking')
@section('page_title', 'Check-in Booking')
@section('page_subtitle', 'Konfirmasi kehadiran Anda')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="text-center mb-6">
                <h3 class="font-bold text-xl">Konfirmasi Check-in</h3>
                <p class="text-sm text-slate-500">Pastikan Anda berada di ruang yang dipesan sebelum melakukan check-in.</p>
            </div>
            <div class="bg-slate-50 rounded-xl p-5 mb-6">
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div><span class="text-slate-500">Kode Booking</span><br><strong>{{ $booking->booking_code }}</strong></div>
                    <div><span class="text-slate-500">Ruang</span><br><strong>{{ $booking->room->nama }}</strong></div>
                    <div><span class="text-slate-500">Tanggal</span><br><strong>{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d M Y') }}</strong></div>
                    <div><span class="text-slate-500">Jam</span><br><strong>{{ $booking->jam_mulai->format('H:i') }} - {{ $booking->jam_selesai->format('H:i') }}</strong></div>
                </div>
            </div>
            <div class="flex justify-center gap-4">
                <a href="{{ route('bookings.show', $booking->id) }}" class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition">Kembali</a>
                <form method="POST" action="{{ route('bookings.checkin', $booking->id) }}">
                    @csrf
                    <button type="submit" class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition shadow-sm">✅ Check-in Sekarang</button>
                </form>
            </div>
        </div>
    </div>
@endsection