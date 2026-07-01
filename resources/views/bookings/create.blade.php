@extends('layouts.app')

@section('title', 'Ajukan Booking')
@section('page_title', 'Ajukan Booking Ruang')
@section('page_subtitle', 'Isi formulir untuk mengajukan penggunaan ruang kelas')

@section('content')

<div class="max-w-7xl mx-auto font-sora space-y-6">

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('error'))
    <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 font-semibold text-sm flex items-center gap-3">
        <span class="text-lg">❌</span>
        {{ session('error') }}
    </div>
    @endif

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold text-sm flex items-center gap-3">
        <span class="text-lg">✅</span>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
        <ul class="list-disc ml-5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ============================================================ --}}
        {{-- FORM BOOKING --}}
        {{-- ============================================================ --}}
        <div class="xl:col-span-2">

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">

                {{-- Header Form --}}
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <i class="fas fa-pen-fancy text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white">Formulir Booking</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Lengkapi data dengan benar untuk mempercepat proses approval</p>
                        </div>
                    </div>
                </div>

                {{-- Body Form --}}
                <div class="p-6">
                    <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- Pilih Ruang --}}
                            <div>
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <i class="fas fa-door-open text-indigo-500 text-xs"></i>
                                    Ruang <span class="text-red-500">*</span>
                                </label>
                                <select name="room_id" id="room_id"
                                    class="mt-2 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                    required>
                                    <option value="">— Pilih ruang —</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" data-capacity="{{ $room->kapasitas }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->kode }} - {{ $room->nama }} ({{ $room->kapasitas }} org)
                                        </option>
                                    @endforeach
                                </select>
                                <div id="roomInfo" class="mt-2 text-xs text-slate-500 dark:text-slate-400 hidden">
                                    <span class="inline-flex items-center gap-1"><i class="fas fa-users text-indigo-400"></i> Kapasitas: <strong id="capacityDisplay">-</strong> orang</span>
                                    <span class="mx-2">|</span>
                                    <span class="inline-flex items-center gap-1"><i class="fas fa-clock text-indigo-400"></i> Buka: <strong id="roomHours">-</strong></span>
                                </div>
                            </div>

                            {{-- Jumlah Peserta --}}
                            <div>
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <i class="fas fa-users text-indigo-500 text-xs"></i>
                                    Jumlah Peserta
                                </label>
                                <input type="number" name="participant_count" id="participant_count"
                                    value="{{ old('participant_count') }}"
                                    class="mt-2 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                    placeholder="Contoh: 25" min="1">
                                <p id="capacityInfo" class="text-xs mt-1 text-slate-500 dark:text-slate-400"></p>
                            </div>

                            {{-- Judul Kegiatan --}}
                            <div class="md:col-span-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <i class="fas fa-tag text-indigo-500 text-xs"></i>
                                    Judul Kegiatan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="kegiatan" value="{{ old('kegiatan') }}"
                                    class="mt-2 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                    placeholder="Contoh: Diskusi Tugas Kelompok Metode Numerik" required>
                            </div>

                            {{-- ========================================================= --}}
                            {{-- JENIS KEGIATAN - TANPA LABEL PRIORITAS --}}
                            {{-- ========================================================= --}}
                            <div class="md:col-span-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <i class="fas fa-list text-indigo-500 text-xs"></i>
                                    Jenis Kegiatan <span class="text-red-500">*</span>
                                </label>
                                <select name="jenis_kegiatan" id="jenis_kegiatan"
                                    class="mt-2 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                    required>
                                    <option value="">— Pilih Jenis Kegiatan —</option>
                                    @foreach($prioritas as $priority => $items)
                                        {{--
                                            TANPA LABEL PRIORITAS!
                                            User hanya melihat jenis kegiatan, tidak tahu prioritasnya.
                                            Sistem yang akan menentukan prioritas secara otomatis.
                                        --}}
                                        @foreach($items as $key => $label)
                                            <option value="{{ $key }}" {{ old('jenis_kegiatan') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>

                                {{--
                                    INFO PRIORITAS (READ-ONLY) - HANYA SEBAGAI INFORMASI
                                    TIDAK BISA DIPILIH OLEH USER
                                --}}
                                <div id="priorityInfo" class="mt-2 text-xs text-slate-500 dark:text-slate-400 hidden">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="fas fa-flag"></i>
                                        Prioritas (otomatis):
                                        <strong id="priorityDisplay" class="px-2 py-0.5 rounded-full text-white text-[10px]">-</strong>
                                        <span class="text-slate-400 dark:text-slate-500 ml-1">(ditentukan sistem)</span>
                                    </span>
                                </div>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <i class="fas fa-calendar-day text-indigo-500 text-xs"></i>
                                    Tanggal <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal" id="date"
                                    value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                                    class="mt-2 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                    min="{{ now()->toDateString() }}" required>
                            </div>

                            {{-- Spacer --}}
                            <div></div>

                            {{-- Jam Mulai --}}
                            <div>
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <i class="fas fa-play text-indigo-500 text-xs"></i>
                                    Jam Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="jam_mulai" id="start_time"
                                    value="{{ old('jam_mulai') }}"
                                    class="mt-2 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                    required>
                            </div>

                            {{-- Jam Selesai --}}
                            <div>
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <i class="fas fa-stop text-indigo-500 text-xs"></i>
                                    Jam Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="jam_selesai" id="end_time"
                                    value="{{ old('jam_selesai') }}"
                                    class="mt-2 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                    required>
                                <p id="durationInfo" class="text-xs mt-1 text-slate-500 dark:text-slate-400"></p>
                            </div>

                            {{-- Tujuan --}}
                            <div class="md:col-span-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <i class="fas fa-align-left text-indigo-500 text-xs"></i>
                                    Tujuan Penggunaan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="tujuan" rows="4"
                                    class="mt-2 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                                    placeholder="Jelaskan tujuan penggunaan ruang secara jelas..." required>{{ old('tujuan') }}</textarea>
                            </div>

                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="mt-8 flex flex-col sm:flex-row gap-3 sm:justify-end border-t border-slate-200 dark:border-slate-700 pt-6">
                            <a href="{{ route('rooms.index') }}"
                               class="px-6 py-3 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-semibold text-center transition flex items-center justify-center gap-2">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit"
                                    class="px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold transition shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                <i class="fas fa-paper-plane"></i> Ajukan Booking
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- SIDEBAR: ATURAN BOOKING --}}
        {{-- ============================================================ --}}
        <div class="xl:col-span-1 space-y-6">

            {{-- Aturan --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sticky top-24">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <i class="fas fa-gavel text-sm"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 dark:text-white">Aturan Booking</h3>
                </div>

                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Pastikan mematuhi ketentuan berikut:</p>

                <div class="space-y-3 text-sm">

                    {{-- Durasi --}}
                    <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-800">
                        <div class="flex items-start gap-3">
                            <span class="text-blue-600 dark:text-blue-400 mt-0.5"><i class="fas fa-clock"></i></span>
                            <div>
                                <p class="font-bold text-blue-700 dark:text-blue-300 text-sm">Batas Durasi Booking</p>
                                <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-0.5 mt-1">
                                    <li>• Mahasiswa : Maksimal <strong>2 jam</strong></li>
                                    <li>• Dosen : Maksimal <strong>6 jam</strong></li>
                                    <li>• Organisasi : Maksimal <strong>4 jam</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Check-in --}}
                    <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-100 dark:border-amber-800">
                        <div class="flex items-start gap-3">
                            <span class="text-amber-600 dark:text-amber-400 mt-0.5"><i class="fas fa-fingerprint"></i></span>
                            <div>
                                <p class="font-bold text-amber-700 dark:text-amber-300 text-sm">Check-in Wajib</p>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                                    Maksimal <strong>15 menit</strong> setelah jam mulai. Jika terlambat, reputasi berkurang.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-800">
                        <div class="flex items-start gap-3">
                            <span class="text-emerald-600 dark:text-emerald-400 mt-0.5"><i class="fas fa-check-circle"></i></span>
                            <div>
                                <p class="font-bold text-emerald-700 dark:text-emerald-300 text-sm">Status Booking</p>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                                    Berstatus <strong>Pending</strong> hingga disetujui admin dalam 1×24 jam.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Larangan --}}
                    <div class="p-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-100 dark:border-red-800">
                        <div class="flex items-start gap-3">
                            <span class="text-red-600 dark:text-red-400 mt-0.5"><i class="fas fa-ban"></i></span>
                            <div>
                                <p class="font-bold text-red-700 dark:text-red-300 text-sm">Larangan</p>
                                <ul class="text-xs text-red-600 dark:text-red-400 space-y-0.5 mt-1">
                                    <li>• Booking fiktif / palsu</li>
                                    <li>• Menggunakan akun orang lain</li>
                                    <li>• Menggunakan ruang tanpa booking</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Reputasi --}}
                    <div class="p-4 rounded-xl bg-purple-50 dark:bg-purple-950/30 border border-purple-100 dark:border-purple-800">
                        <div class="flex items-start gap-3">
                            <span class="text-purple-600 dark:text-purple-400 mt-0.5"><i class="fas fa-star"></i></span>
                            <div>
                                <p class="font-bold text-purple-700 dark:text-purple-300 text-sm">Syarat Reputasi</p>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                                    Minimal <strong>30 poin</strong> untuk mengajukan booking.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-5 pt-4 border-t border-slate-200 dark:border-slate-700 text-center">
                    <a href="{{ route('help.index') }}#faq"
                       class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium flex items-center justify-center gap-1 transition">
                        <i class="fas fa-book-open"></i> Lihat Aturan Lengkap di Pusat Bantuan
                    </a>
                </div>
            </div>

            {{-- Tips --}}
            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30 rounded-2xl border border-indigo-100 dark:border-indigo-800 p-5">
                <div class="flex items-start gap-3">
                    <span class="text-indigo-600 dark:text-indigo-400 text-xl"><i class="fas fa-lightbulb"></i></span>
                    <div>
                        <p class="font-bold text-slate-800 dark:text-white text-sm">💡 Tips</p>
                        <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1 mt-1">
                            <li>• Pilih ruang sesuai kapasitas peserta</li>
                            <li>• Sertakan tujuan yang jelas untuk approval cepat</li>
                            <li>• Booking minimal 1 jam sebelum penggunaan</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    'use strict';

    // ================================================================
    // VARIABLES
    // ================================================================
    const $roomSelect = $('#room_id');
    const $capacityInfo = $('#capacityInfo');
    const $participantCount = $('#participant_count');
    const $startTime = $('#start_time');
    const $endTime = $('#end_time');
    const $durationInfo = $('#durationInfo');
    const $jenisKegiatan = $('#jenis_kegiatan');
    const $priorityInfo = $('#priorityInfo');
    const $priorityDisplay = $('#priorityDisplay');
    const $roomInfo = $('#roomInfo');
    const $capacityDisplay = $('#capacityDisplay');

    // ================================================================
    // ROOM SELECT - TAMPILKAN INFO KAPASITAS
    // ================================================================
    function updateRoomInfo() {
        const selected = $roomSelect.find('option:selected');
        const capacity = selected.data('capacity');

        if (capacity) {
            $capacityDisplay.text(capacity);
            $roomInfo.removeClass('hidden');
            $capacityInfo.text('Kapasitas ruang: ' + capacity + ' orang.');
        } else {
            $roomInfo.addClass('hidden');
            $capacityInfo.text('');
        }

        // Cek peserta vs kapasitas
        validateParticipantCount();
    }

    $roomSelect.on('change', updateRoomInfo);

    // ================================================================
    // VALIDASI JUMLAH PESERTA vs KAPASITAS
    // ================================================================
    function validateParticipantCount() {
        const selected = $roomSelect.find('option:selected');
        const capacity = selected.data('capacity');
        const val = parseInt($participantCount.val());

        if (capacity && val > parseInt(capacity)) {
            $capacityInfo
                .removeClass('text-slate-500 dark:text-slate-400 text-emerald-600')
                .addClass('text-red-600 dark:text-red-400')
                .html('<i class="fas fa-exclamation-circle mr-1"></i> Jumlah peserta (' + val + ') melebihi kapasitas ruang (' + capacity + ' orang)!');
        } else if (capacity) {
            $capacityInfo
                .removeClass('text-red-600 dark:text-red-400')
                .addClass('text-slate-500 dark:text-slate-400')
                .text('Kapasitas ruang: ' + capacity + ' orang.');
        }
    }

    $participantCount.on('input', validateParticipantCount);

    // ================================================================
    // DURASI BOOKING
    // ================================================================
    function calculateDuration() {
        const start = $startTime.val();
        const end = $endTime.val();

        if (!start || !end) {
            $durationInfo.text('');
            return;
        }

        const startTime = new Date('2000-01-01 ' + start);
        const endTime = new Date('2000-01-01 ' + end);
        const diff = (endTime - startTime) / 1000 / 60;

        if (diff <= 0) {
            $durationInfo
                .removeClass('text-slate-500 dark:text-slate-400 text-emerald-600')
                .addClass('text-red-600 dark:text-red-400')
                .html('<i class="fas fa-exclamation-circle mr-1"></i> Jam selesai harus lebih besar dari jam mulai.');
            return;
        }

        const hours = Math.floor(diff / 60);
        const minutes = diff % 60;
        let label = hours > 0 ? hours + ' jam ' : '';
        label += minutes > 0 ? minutes + ' menit' : '';

        $durationInfo
            .removeClass('text-red-600 dark:text-red-400')
            .addClass('text-emerald-600 dark:text-emerald-400')
            .html('<i class="fas fa-clock mr-1"></i> Durasi booking: <strong>' + label.trim() + '</strong>');
    }

    $startTime.on('change', calculateDuration);
    $endTime.on('change', calculateDuration);

    // ================================================================
    // JENIS KEGIATAN - TAMPILKAN PRIORITAS (READ-ONLY)
    // ================================================================
    function updatePriorityInfo() {
        const selected = $jenisKegiatan.find('option:selected');
        const value = selected.val();

        if (!value) {
            $priorityInfo.addClass('hidden');
            return;
        }

        // Dapatkan prioritas dari data attribute atau mapping
        const priorityMap = {
            // HIGH
            'seminar_nasional': { label: 'High', color: 'bg-red-500' },
            'wisuda': { label: 'High', color: 'bg-red-500' },
            'kuliah_umum': { label: 'High', color: 'bg-red-500' },
            'workshop_institusi': { label: 'High', color: 'bg-red-500' },
            // MEDIUM-HIGH
            'ujian': { label: 'Medium-High', color: 'bg-orange-500' },
            'sidang_skripsi': { label: 'Medium-High', color: 'bg-orange-500' },
            'seminar_proposal': { label: 'Medium-High', color: 'bg-orange-500' },
            'praktikum': { label: 'Medium-High', color: 'bg-orange-500' },
            'kompetisi': { label: 'Medium-High', color: 'bg-orange-500' },
            // MEDIUM
            'kuliah_reguler': { label: 'Medium', color: 'bg-yellow-500' },
            'rapat_fakultas': { label: 'Medium', color: 'bg-yellow-500' },
            'organisasi_mahasiswa': { label: 'Medium', color: 'bg-yellow-500' },
            'penelitian': { label: 'Medium', color: 'bg-yellow-500' },
            'sosialisasi': { label: 'Medium', color: 'bg-yellow-500' },
            // LOW
            'diskusi_kelompok': { label: 'Low', color: 'bg-blue-500' },
            'bimbingan_kelompok': { label: 'Low', color: 'bg-blue-500' },
            'belajar_mandiri': { label: 'Low', color: 'bg-blue-500' },
            'latihan_presentasi': { label: 'Low', color: 'bg-blue-500' },
            'pertemuan_komunitas': { label: 'Low', color: 'bg-blue-500' },
        };

        const priority = priorityMap[value];
        if (priority) {
            $priorityDisplay
                .text(priority.label)
                .removeClass('bg-red-500 bg-orange-500 bg-yellow-500 bg-blue-500')
                .addClass(priority.color);
            $priorityInfo.removeClass('hidden');
        } else {
            $priorityInfo.addClass('hidden');
        }
    }

    $jenisKegiatan.on('change', updatePriorityInfo);

    // ================================================================
    // TAMPILKAN INFO ROOM SAAT HALAMAN DIMUAT
    // ================================================================
    updateRoomInfo();
    calculateDuration();
    updatePriorityInfo();

    // ================================================================
    // VALIDASI SEBELUM SUBMIT (Client Side)
    // ================================================================
    $('#bookingForm').on('submit', function(e) {
        const selected = $roomSelect.find('option:selected');
        const capacity = selected.data('capacity');
        const val = parseInt($participantCount.val());

        if (capacity && val > parseInt(capacity)) {
            e.preventDefault();
            alert('Jumlah peserta (' + val + ') melebihi kapasitas ruang (' + capacity + ' orang)!');
            return false;
        }

        const start = $startTime.val();
        const end = $endTime.val();
        if (start && end) {
            const startTime = new Date('2000-01-01 ' + start);
            const endTime = new Date('2000-01-01 ' + end);
            if (endTime <= startTime) {
                e.preventDefault();
                alert('Jam selesai harus lebih besar dari jam mulai!');
                return false;
            }
        }

        return true;
    });

});
</script>
@endpush

@endsection
