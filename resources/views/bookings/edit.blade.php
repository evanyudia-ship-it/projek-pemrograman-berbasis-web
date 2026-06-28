@extends('layouts.app')

@section('title', 'Edit Booking')
@section('page_title', 'Edit Booking')
@section('page_subtitle', 'Perbarui data pengajuan booking Anda')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Ruang</label>
                        <select name="room_id" id="room_id" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none focus:border-blue-400" required>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ $room->id == $booking->room_id ? 'selected' : '' }}>{{ $room->kode }} - {{ $room->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $booking->tanggal->format('Y-m-d') }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none focus:border-blue-400" min="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-slate-700">Judul Kegiatan</label>
                        <input type="text" name="kegiatan" value="{{ $booking->kegiatan }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none focus:border-blue-400" required>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Jam Mulai</label>
                        <input type="time" name="jam_mulai" value="{{ $booking->jam_mulai->format('H:i') }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none focus:border-blue-400" required>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Jam Selesai</label>
                        <input type="time" name="jam_selesai" value="{{ $booking->jam_selesai->format('H:i') }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none focus:border-blue-400" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-slate-700">Tujuan Penggunaan</label>
                        <textarea name="tujuan" rows="4" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none focus:border-blue-400" required>{{ $booking->tujuan }}</textarea>
                    </div>
                </div>
                <div class="mt-6 flex flex-col md:flex-row gap-3 md:justify-end">
                    <a href="{{ route('bookings.show', $booking->id) }}" class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-center transition">Batal</a>
                    <button type="submit" class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition shadow-sm">Perbarui Booking</button>
                </div>
            </form>
        </div>
    </div>
@endsection