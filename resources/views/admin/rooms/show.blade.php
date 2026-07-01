@extends('layouts.app')

@section('title', 'Detail Ruang - Admin')
@section('page_title', 'Detail Ruang')
@section('page_subtitle', $room->full_name)

@section('content')

<div class="max-w-6xl mx-auto font-sora space-y-6">

    {{-- ===== NAV BACK ===== --}}
    <div class="fade-up">
        <a href="{{ route('admin.rooms.index') }}"
           class="text-sm text-slate-500 hover:text-slate-700 transition flex items-center gap-2">
            ← Kembali ke Manajemen Ruang
        </a>
    </div>

    {{-- ===== HERO ===== --}}
    <div class="fade-up relative h-56 md:h-72 rounded-3xl overflow-hidden bg-slate-200">
        <img src="{{ $room->foto ? asset('storage/' . $room->foto) : asset('images/default-room.jpg') }}"
             alt="{{ $room->nama }}"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

        <span class="absolute top-4 right-4 px-4 py-2 rounded-full text-sm font-bold
            {{ $room->status == 'Tersedia' ? 'bg-emerald-400/90 text-emerald-900' : 'bg-red-400/90 text-red-900' }}">
            {{ $room->status_label }}
        </span>

        <div class="absolute bottom-4 left-6 right-6">
            <h1 class="text-white text-2xl md:text-3xl font-bold">{{ $room->nama }}</h1>
            <p class="text-white/70 text-sm mt-1">{{ $room->kode }} · {{ $room->location }}</p>
        </div>
    </div>

    {{-- ===== INFO GRID ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-up delay-1">

        {{-- Kiri: Info --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Stat Cards --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 text-center">
                    <p class="text-xs text-slate-400 mb-1">Kapasitas</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $room->kapasitas }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">orang</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 text-center">
                    <p class="text-xs text-slate-400 mb-1">Lantai</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $room->lantai }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $room->gedung }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 text-center">
                    <p class="text-xs text-slate-400 mb-1">Max Durasi</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $room->max_durasi_jam }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">jam/hari</p>
                </div>
            </div>

            {{-- Deskripsi --}}
            @if($room->deskripsi)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Tentang Ruangan</p>
                <p class="text-sm text-slate-600 leading-relaxed">{{ $room->deskripsi }}</p>
            </div>
            @endif

            {{-- Alamat --}}
            @if($room->alamat)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">📍 Lokasi</p>
                <p class="text-sm text-slate-600">{{ $room->alamat }}</p>
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($room->alamat) }}"
                   target="_blank"
                   class="text-xs text-blue-600 hover:text-blue-700 mt-2 inline-block">
                    Lihat di Google Maps →
                </a>
            </div>
            @endif

            {{-- Fasilitas --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Fasilitas</p>
                @if($room->facilities->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($room->facilities as $facility)
                    <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 border border-slate-100">
                        @if($facility->icon)<span class="text-lg">{{ $facility->icon }}</span>@endif
                        <span class="text-sm text-slate-700">{{ $facility->nama }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $facility->pivot->status == 'tersedia' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            {{ $facility->pivot->status_label }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-slate-400">Tidak ada fasilitas terdaftar</p>
                @endif
            </div>

            {{-- Jam Operasional --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Jam Operasional</p>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-slate-400">Jam Buka</p>
                        <p class="font-semibold text-slate-800">{{ $room->formatted_jam_buka }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Jam Tutup</p>
                        <p class="font-semibold text-slate-800">{{ $room->formatted_jam_tutup }}</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Kanan: Aksi --}}
        <div class="space-y-4">

            {{-- Action Buttons --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5 space-y-3">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tindakan</p>

                <a href="{{ route('admin.rooms.edit', $room->id) }}"
                   class="block w-full px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white text-center rounded-xl font-semibold text-sm transition">
                    ✏️ Edit Ruang
                </a>

                <form action="{{ route('admin.rooms.toggle-status', $room->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="block w-full px-4 py-3 bg-amber-500 hover:bg-amber-600 text-white text-center rounded-xl font-semibold text-sm transition">
                        🔄 Ubah Status ke {{ $room->status == 'Tersedia' ? 'Maintenance' : 'Tersedia' }}
                    </button>
                </form>

                <button type="button"
                        class="btn-delete-room-show block w-full px-4 py-3 bg-red-500 hover:bg-red-600 text-white text-center rounded-xl font-semibold text-sm transition"
                        data-name="{{ $room->nama }}"
                        data-url="{{ route('admin.rooms.destroy', $room->id) }}">
                    🗑 Hapus Ruang
                </button>
            </div>

            {{-- Faculty Info --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Fakultas</p>
                @if($room->faculty)
                <p class="font-semibold text-slate-800">{{ $room->faculty->name }}</p>
                <p class="text-xs text-slate-400">Kode: {{ $room->faculty->code }}</p>
                @else
                <p class="text-sm text-slate-400">Tidak terikat fakultas</p>
                @endif
            </div>

            {{-- Booking Count --}}
            <div class="bg-slate-950 rounded-3xl p-5 text-white">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Total Booking</p>
                <p class="text-3xl font-bold">{{ $room->bookings->count() }}</p>
                <p class="text-xs text-slate-400 mt-1">sepanjang waktu</p>
            </div>
        </div>

    </div>

    {{-- ===== DAFTAR BOOKING TERBARU ===== --}}
    @if($room->bookings->count() > 0)
    <div class="fade-up delay-2">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Booking Terbaru</p>
                <span class="text-xs text-slate-400">10 terakhir</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                        <tr>
                            <th class="text-left px-4 py-3">Kode</th>
                            <th class="text-left px-4 py-3">Kegiatan</th>
                            <th class="text-left px-4 py-3">Tanggal</th>
                            <th class="text-left px-4 py-3">Waktu</th>
                            <th class="text-left px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($room->bookings->take(10) as $booking)
                        <tr>
                            <td class="px-4 py-3 font-mono text-xs">{{ $booking->booking_code }}</td>
                            <td class="px-4 py-3">{{ $booking->kegiatan }}</td>
                            <td class="px-4 py-3">{{ $booking->tanggal->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-xs">
                                {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-bold
                                    {{ $booking->status == 'approved' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $booking->status == 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $booking->status == 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $booking->status == 'completed' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $booking->status == 'cancelled' ? 'bg-slate-100 text-slate-700' : '' }}
                                    {{ $booking->status == 'no_show' ? 'bg-red-200 text-red-800' : '' }}">
                                    {{ $booking->status_label }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

@endsection

@push('scripts')
// Di bagian @push('scripts') atau di file JS terpisah
$(document).ready(function() {
    // Delete Facility
    $('.btn-delete-facility').on('click', function(e) {
        e.preventDefault();
        const name = $(this).data('name');
        const url = $(this).data('url');

        Swal.fire({
            title: 'Hapus ' + name + '?',
            text: 'Tindakan ini akan menghapus semua relasi dengan ruangan dan tidak dapat dibatalkan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                        setTimeout(() => location.reload(), 1000);
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan, coba lagi.', 'error');
                    }
                });
            }
        });
    });

    // Delete Room
    $('.btn-delete-room, .btn-delete-room-show').on('click', function(e) {
        e.preventDefault();
        const name = $(this).data('name');
        const url = $(this).data('url');

        Swal.fire({
            title: 'Hapus ' + name + '?',
            text: 'Tindakan ini tidak dapat dibatalkan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                        setTimeout(() => location.reload(), 1000);
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan, coba lagi.', 'error');
                    }
                });
            }
        });
    });
});
@endpush
