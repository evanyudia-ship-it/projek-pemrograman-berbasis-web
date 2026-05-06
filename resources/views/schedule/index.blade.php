@extends('layouts.app')

@section('title', 'Jadwal Ruangan')
@section('page_title', 'Jadwal Ruangan')
@section('page_subtitle', 'Kalender penggunaan semua ruang kelas')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div>
            <label class="text-sm font-semibold text-slate-700">Pilih Ruang</label>
            <select class="mt-2 w-full">
                <option>Semua Ruang</option>
                <option>R-201</option>
                <option>R-105</option>
                <option>LAB-01</option>
                <option>AULA</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-700">Tanggal</label>
            <input type="date"
                class="mt-2 w-full">
        </div>

        <div class="flex items-end">
            <button class="w-full bg-blue-500 hover:bg-slate-800 text-white font-semibold py-3 rounded-xl">
                Filter
            </button>
        </div>

    </div>
</div>

{{-- Calendar View --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

    <div class="flex justify-between items-center mb-4">
        <h3 class="font-bold text-lg">Kalender Booking</h3>
        <span class="text-sm text-slate-500">Overview semua jadwal</span>
    </div>

    <div class="grid grid-cols-7 gap-2 text-center text-sm font-semibold text-slate-500 mb-2">
        <div>Sen</div>
        <div>Sel</div>
        <div>Rab</div>
        <div>Kam</div>
        <div>Jum</div>
        <div>Sab</div>
        <div>Min</div>
    </div>

    <div class="grid grid-cols-7 gap-2">

        @for ($i = 1; $i <= 30; $i++)
            <div class="h-24 border rounded-xl p-2 text-xs relative bg-slate-50 hover:bg-slate-100">

                <div class="font-bold text-slate-600">{{ $i }}</div>

                {{-- contoh booking --}}
                @if ($i == 3)
                    <div class="mt-1 bg-emerald-100 text-emerald-700 p-1 rounded text-[10px]">
                        R-201 08:00
                    </div>
                @endif

                @if ($i == 5)
                    <div class="mt-1 bg-yellow-100 text-yellow-700 p-1 rounded text-[10px]">
                        LAB-01 13:00
                    </div>
                @endif

                @if ($i == 8)
                    <div class="mt-1 bg-blue-100 text-blue-700 p-1 rounded text-[10px]">
                        R-105 18:00
                    </div>
                @endif

            </div>
        @endfor

    </div>
</div>


@endsection