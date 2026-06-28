@extends('layouts.app')

@section('title', 'Batalkan Booking')
@section('page_title', 'Batalkan Booking')
@section('page_subtitle', 'Berikan alasan pembatalan')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="mb-6">
                <h3 class="font-bold text-lg">Konfirmasi Pembatalan</h3>
                <p class="text-sm text-slate-500">Anda akan membatalkan booking dengan kode <strong>{{ $booking->booking_code }}</strong> untuk ruang <strong>{{ $booking->room->nama }}</strong> pada tanggal {{ $booking->tanggal->translatedFormat('d M Y') }}.</p>
            </div>
            <form method="POST" action="{{ route('bookings.cancel.store', $booking->id) }}">
                @csrf
                <div class="mb-4">
                    <label class="text-sm font-semibold text-slate-700">Alasan Pembatalan <span class="text-red-500">*</span></label>
                    <textarea name="reason" rows="4" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none focus:border-blue-400" placeholder="Jelaskan alasan Anda membatalkan booking..." required></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <a href="{{ route('bookings.show', $booking->id) }}" class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition">Kembali</a>
                    <button type="submit" class="px-5 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition shadow-sm">Ya, Batalkan Booking</button>
                </div>
            </form>
        </div>
    </div>
@endsection