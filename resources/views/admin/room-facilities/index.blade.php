@extends('layouts.app')

@section('title', 'Kelola Fasilitas Ruang - Admin')
@section('page_title', 'Kelola Fasilitas Ruang')
@section('page_subtitle', 'Atur fasilitas yang tersedia di setiap ruangan')

@section('content')

{{-- ===== FLASH MESSAGES ===== --}}
@if(session('success'))
<div id="flashMsg"
     class="mb-5 flex items-center gap-3 px-5 py-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold">
    <span class="text-lg">✅</span>
    {{ session('success') }}
    <button onclick="document.getElementById('flashMsg').remove()"
            class="ml-auto text-emerald-500 hover:text-emerald-700 font-bold">✕</button>
</div>
@endif

@if(session('error'))
<div id="flashMsg"
     class="mb-5 flex items-center gap-3 px-5 py-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm font-semibold">
    <span class="text-lg">❌</span>
    {{ session('error') }}
    <button onclick="document.getElementById('flashMsg').remove()"
            class="ml-auto text-red-500 hover:text-red-700 font-bold">✕</button>
</div>
@endif

@if(session('info'))
<div id="flashMsg"
     class="mb-5 flex items-center gap-3 px-5 py-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-700 text-sm font-semibold">
    <span class="text-lg">ℹ️</span>
    {{ session('info') }}
    <button onclick="document.getElementById('flashMsg').remove()"
            class="ml-auto text-blue-500 hover:text-blue-700 font-bold">✕</button>
</div>
@endif

<div class="max-w-7xl mx-auto font-sora">

    {{-- ===== FILTER RUANG ===== --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 mb-6">
        <form action="{{ route('admin.room-facilities.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label class="text-sm font-semibold text-slate-700">Pilih Ruangan</label>
                <select name="room_id" class="mt-1 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500">
                    <option value="">Semua Ruangan</option>
                    @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                        {{ $room->nama }} ({{ $room->kode }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit"
                        class="px-5 py-2.5 bg-slate-900 hover:bg-slate-700 text-white rounded-xl font-semibold text-sm transition">
                    🔍 Filter
                </button>
                <a href="{{ route('admin.room-facilities.index') }}"
                   class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-sm transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- ===== DAFTAR RUANGAN ===== --}}
    @foreach($rooms as $room)
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-6">

        {{-- Header Ruang --}}
        <div class="p-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800">{{ $room->nama }}</h3>
                <p class="text-xs text-slate-400 font-mono">{{ $room->kode }} · {{ $room->location }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-500">
                    {{ $room->facilities->count() }} fasilitas
                </span>
                <button class="btn-add-facility px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-xl transition"
                        data-room-id="{{ $room->id }}"
                        data-room-name="{{ $room->nama }}">
                    ➕ Tambah Fasilitas
                </button>
            </div>
        </div>

        {{-- Body: Daftar Fasilitas --}}
        <div class="p-5">
            @if($room->facilities->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                @foreach($room->facilities as $facility)
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-2xl border border-slate-100 group hover:shadow-sm transition">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-2xl shrink-0">
                        {{ $facility->icon ?? '📦' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-700 text-sm truncate">{{ $facility->nama }}</p>
                        <form action="{{ route('admin.room-facilities.update', ['roomId' => $room->id, 'facilityId' => $facility->id]) }}"
                              method="POST" class="mt-1">
                            @csrf
                            @method('PUT')
                            <select name="status"
                                    onchange="this.form.submit()"
                                    class="text-xs rounded-lg border-slate-200 focus:border-green-500 focus:ring-green-500 py-0.5 px-2">
                                <option value="tersedia" {{ $facility->pivot->status == 'tersedia' ? 'selected' : '' }}>
                                    ✅ Tersedia
                                </option>
                                <option value="rusak" {{ $facility->pivot->status == 'rusak' ? 'selected' : '' }}>
                                    ❌ Rusak
                                </option>
                                <option value="maintenance" {{ $facility->pivot->status == 'maintenance' ? 'selected' : '' }}>
                                    🔧 Maintenance
                                </option>
                            </select>
                        </form>
                    </div>
                    <form action="{{ route('admin.room-facilities.detach', ['roomId' => $room->id, 'facilityId' => $facility->id]) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus fasilitas {{ addslashes($facility->nama) }} dari ruang {{ addslashes($room->nama) }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="opacity-0 group-hover:opacity-100 transition text-red-400 hover:text-red-600 text-sm font-bold p-1">
                            ✕
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-slate-400">
                <p class="text-2xl mb-2">📭</p>
                <p class="text-sm">Belum ada fasilitas terpasang di ruang ini</p>
                <p class="text-xs mt-1">Klik "Tambah Fasilitas" untuk menambahkan</p>
            </div>
            @endif
        </div>

    </div>
    @endforeach

    @if($rooms->isEmpty())
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-12 text-center">
        <p class="text-5xl mb-4">🏫</p>
        <p class="font-semibold text-slate-500">Belum ada ruangan terdaftar</p>
        <p class="text-sm text-slate-400 mt-1">Tambahkan ruangan terlebih dahulu melalui Manajemen Ruang</p>
        <a href="{{ route('admin.rooms.index') }}"
           class="inline-block mt-4 px-4 py-2 bg-slate-900 hover:bg-slate-700 text-white text-sm font-semibold rounded-xl transition">
            Manajemen Ruang →
        </a>
    </div>
    @endif

</div>

{{-- ===== MODAL TAMBAH FASILITAS ===== --}}
<div id="addFacilityModal"
     class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">

        {{-- Modal Header --}}
        <div class="p-5 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg" id="modalTitle">Tambah Fasilitas</h3>
                <p class="text-sm text-slate-500" id="modalRoomName">Ke ruang: -</p>
            </div>
            <button class="btn-close-modal w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 transition font-bold text-slate-500">
                ✕
            </button>
        </div>

        {{-- Modal Form --}}
        <form id="addFacilityForm" action="{{ route('admin.room-facilities.attach') }}" method="POST" class="p-5">
            @csrf
            <input type="hidden" name="room_id" id="modalRoomId">

            <div class="space-y-4">
                {{-- Pilih Fasilitas --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Pilih Fasilitas <span class="text-red-500">*</span></label>
                    <select name="facility_id" id="modalFacilityId" class="mt-1 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500" required>
                        <option value="">-- Pilih Fasilitas --</option>
                        @foreach($facilities ?? [] as $facility)
                        <option value="{{ $facility->id }}">
                            {{ $facility->icon ?? '📦' }} {{ $facility->nama }}
                            @if($facility->kategori) ({{ $facility->kategori }}) @endif
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="mt-1 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500" required>
                        <option value="tersedia">✅ Tersedia</option>
                        <option value="rusak">❌ Rusak</option>
                        <option value="maintenance">🔧 Maintenance</option>
                    </select>
                </div>
            </div>

            {{-- Footer --}}
            <div class="mt-6 flex flex-col md:flex-row gap-3 md:justify-end border-t border-slate-100 pt-5">
                <button type="button" class="btn-close-modal px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-700 text-white font-semibold transition">
                    ➕ Tambahkan
                </button>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {
    // ── Modal Tambah Fasilitas ──────────────────────────────────────────
    const $modal = $('#addFacilityModal');
    const $form = $('#addFacilityForm');

    $('.btn-add-facility').on('click', function () {
        const roomId = $(this).data('room-id');
        const roomName = $(this).data('room-name');

        $('#modalRoomId').val(roomId);
        $('#modalRoomName').text('Ke ruang: ' + roomName);
        $('#modalFacilityId').val('');

        $modal.removeClass('hidden').css('display', 'flex');
        $('body').css('overflow', 'hidden');
    });

    function closeModal() {
        $modal.addClass('hidden').css('display', '');
        $('body').css('overflow', '');
        $form[0].reset();
    }

    $('.btn-close-modal').on('click', closeModal);
    $modal.on('click', function (e) {
        if (e.target.id === 'addFacilityModal') closeModal();
    });

    // ── Auto-dismiss flash message ───────────────────────────────────────
    const flashMsg = $('#flashMsg');
    if (flashMsg.length) {
        setTimeout(() => {
            flashMsg.fadeOut(400, function () {
                $(this).remove();
            });
        }, 4000);
    }
});
</script>
@endpush

@endsection
