@extends('layouts.app')

@section('title', 'Ketersediaan Ruang')
@section('page_title', 'Ketersediaan Ruang')
@section('page_subtitle', 'Lihat ruang kosong dan detail fasilitas ruang kelas')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

        <div class="md:col-span-2">
            <label class="text-sm font-semibold text-slate-700">Cari Ruang</label>
            <input type="text" id="searchRoom"
                   class="mt-2 w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Contoh: R-201, Lab, Aula">
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-700">Tanggal</label>
            <input type="date"
                   class="mt-2 w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-700">Jam Mulai</label>
            <input type="time"
                   class="mt-2 w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-700">Jam Selesai</label>
            <input type="time"
                   class="mt-2 w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
        </div>

    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="roomList">

    @php
        $rooms = [
            ['code' => 'R-201', 'name' => 'Ruang Kelas 201', 'capacity' => 40, 'status' => 'available', 'facility' => 'LCD, AC, Whiteboard'],
            ['code' => 'R-105', 'name' => 'Ruang Diskusi 105', 'capacity' => 20, 'status' => 'used', 'facility' => 'Whiteboard, WiFi'],
            ['code' => 'LAB-01', 'name' => 'Lab Komputer', 'capacity' => 35, 'status' => 'available', 'facility' => 'Komputer, LCD, AC'],
            ['code' => 'AULA', 'name' => 'Aula Serbaguna', 'capacity' => 120, 'status' => 'maintenance', 'facility' => 'Sound System, LCD'],
            ['code' => 'R-301', 'name' => 'Ruang Kelas 301', 'capacity' => 45, 'status' => 'available', 'facility' => 'LCD, AC'],
            ['code' => 'R-302', 'name' => 'Ruang Kelas 302', 'capacity' => 30, 'status' => 'used', 'facility' => 'Whiteboard'],
        ];
    @endphp

    @foreach($rooms as $room)
        <div class="room-card bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden"
             data-name="{{ strtolower($room['code'].' '.$room['name']) }}">

            <div class="h-36 bg-gradient-to-br from-blue-500 to-indigo-700 flex items-center justify-center text-white">
                <div class="text-center">
                    <p class="text-4xl mb-2">🏫</p>
                    <h3 class="text-2xl font-extrabold">{{ $room['code'] }}</h3>
                </div>
            </div>

            <div class="p-5">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-bold text-lg">{{ $room['name'] }}</h3>
                        <p class="text-sm text-slate-500">Kapasitas {{ $room['capacity'] }} orang</p>
                    </div>

                    @if($room['status'] === 'available')
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">Tersedia</span>
                    @elseif($room['status'] === 'used')
                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Dipakai</span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">Maintenance</span>
                    @endif
                </div>

                <p class="text-sm text-slate-600 mb-5">
                    Fasilitas: {{ $room['facility'] }}
                </p>

                <div class="flex gap-3">
                    <button class="flex-1 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm">
                        Detail
                    </button>

                    @if($room['status'] === 'available')
                        <a href="{{ route('bookings.create') }}"
                           class="flex-1 text-center px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm">
                            Booking
                        </a>
                    @else
                        <button disabled
                                class="flex-1 px-4 py-2 rounded-xl bg-slate-200 text-slate-400 font-semibold text-sm cursor-not-allowed">
                            Tidak Bisa
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

</div>

@push('scripts')
<script>
    $('#searchRoom').on('keyup', function () {
        let keyword = $(this).val().toLowerCase();

        $('.room-card').each(function () {
            let name = $(this).data('name');

            if (name.includes(keyword)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
</script>
@endpush

@endsection
