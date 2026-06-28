@extends('layouts.app')

@section('title', 'Ajukan Booking')
@section('page_title', 'Ajukan Booking Ruang')
@section('page_subtitle', 'Isi formulir untuk mengajukan penggunaan ruang kelas')

@section('content')
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Ruang</label>
                        <select name="room_id" id="room_id" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none focus:border-blue-400" required>
                            <option value="">Pilih ruang</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" data-capacity="{{ $room->kapasitas }}">{{ $room->kode }} - {{ $room->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Jumlah Peserta</label>
                        <input type="number" name="participant_count" id="participant_count" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none focus:border-blue-400" placeholder="Contoh: 25">
                        <p id="capacityInfo" class="text-xs text-slate-500 mt-1"></p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-slate-700">Judul Kegiatan</label>
                        <input type="text" name="kegiatan" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none focus:border-blue-400" placeholder="Contoh: Diskusi Tugas Kelompok" required>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Tanggal</label>
                        <input type="date" name="tanggal" id="date" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none focus:border-blue-400" min="{{ now()->toDateString() }}" required>
                    </div>
                    <div></div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="start_time" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none focus:border-blue-400" required>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="end_time" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none focus:border-blue-400" required>
                        <p id="durationInfo" class="text-xs mt-1"></p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-slate-700">Tujuan Penggunaan</label>
                        <textarea name="tujuan" rows="4" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none focus:border-blue-400" placeholder="Jelaskan tujuan penggunaan ruang" required></textarea>
                    </div>
                </div>
                <div class="mt-6 flex flex-col md:flex-row gap-3 md:justify-end">
                    <a href="{{ route('rooms.index') }}" class="px-5 py-3 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-center transition">Batal</a>
                    <button type="submit" class="px-5 py-3 rounded-xl bg-blue-600 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-500 text-white font-semibold transition shadow-sm">Ajukan Booking</button>
                </div>
            </form>
        </div>

        <!-- Aturan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-bold text-lg mb-2">Aturan Booking</h3>
            <p class="text-sm text-slate-500 mb-5">Pastikan mematuhi ketentuan berikut:</p>
            <div class="space-y-4 text-sm">
                <div class="p-4 rounded-xl bg-blue-50 border border-blue-100">
                    <p class="font-bold text-blue-700 mb-1">Batas Durasi Booking</p>
                    <ul class="text-xs text-slate-600 space-y-1">
                        <li>• Mahasiswa : Maksimal <strong>2 jam</strong></li>
                        <li>• Dosen : Maksimal <strong>6 jam</strong></li>
                        <li>• Organisasi : Fleksibel (wajib persetujuan)</li>
                    </ul>
                </div>
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-100">
                    <p class="font-bold text-amber-700">Check-in Wajib</p>
                    <p class="text-xs text-slate-600 mt-1">Maksimal <strong>30 menit</strong> setelah jam mulai. Jika terlambat, booking otomatis dianggap no-show.</p>
                </div>
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100">
                    <p class="font-bold text-emerald-700">Status Booking</p>
                    <p class="text-xs text-slate-600 mt-1">Semua booking berstatus <strong>Pending</strong> hingga disetujui admin dalam 1×24 jam.</p>
                </div>
                <div class="p-4 rounded-xl bg-red-50 border border-red-100">
                    <p class="font-bold text-red-700">Larangan</p>
                    <ul class="text-xs text-red-600 space-y-1 mt-1">
                        <li>• Booking fiktif / palsu</li>
                        <li>• Menggunakan akun orang lain</li>
                        <li>• Menggunakan ruang tanpa booking resmi</li>
                    </ul>
                </div>
            </div>
            <div class="mt-6 text-center">
                <a href="{{ route('help.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium flex items-center justify-center gap-1">📘 Lihat Aturan Lengkap di Pusat Bantuan</a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function calculateDuration() {
            let start = document.getElementById('start_time').value;
            let end = document.getElementById('end_time').value;
            let info = document.getElementById('durationInfo');
            if (!start || !end) { info.textContent = ''; return; }
            let startTime = new Date('2000-01-01 ' + start);
            let endTime = new Date('2000-01-01 ' + end);
            let diff = (endTime - startTime) / 1000 / 60;
            if (diff <= 0) {
                info.className = 'text-xs mt-1 text-red-600';
                info.textContent = 'Jam selesai harus lebih besar dari jam mulai.';
                return;
            }
            let hours = Math.floor(diff / 60);
            let minutes = diff % 60;
            let label = hours > 0 ? hours + ' jam ' : '';
            label += minutes > 0 ? minutes + ' menit' : '';
            info.className = 'text-xs mt-1 text-emerald-600';
            info.textContent = 'Durasi booking: ' + label.trim();
        }

        document.getElementById('start_time').addEventListener('change', calculateDuration);
        document.getElementById('end_time').addEventListener('change', calculateDuration);

        document.getElementById('room_id').addEventListener('change', function() {
            let capacity = this.options[this.selectedIndex]?.dataset?.capacity;
            let info = document.getElementById('capacityInfo');
            if (capacity) {
                info.textContent = 'Kapasitas ruang: ' + capacity + ' orang.';
            } else {
                info.textContent = '';
            }
        });

        document.getElementById('participant_count').addEventListener('input', function() {
            let capacity = document.getElementById('room_id').options[document.getElementById('room_id').selectedIndex]?.dataset?.capacity;
            let info = document.getElementById('capacityInfo');
            let val = parseInt(this.value);
            if (capacity && val > parseInt(capacity)) {
                info.className = 'text-xs mt-1 text-red-600';
                info.textContent = 'Jumlah peserta melebihi kapasitas ruang!';
            } else if (capacity) {
                info.className = 'text-xs mt-1 text-slate-500';
                info.textContent = 'Kapasitas ruang: ' + capacity + ' orang.';
            }
        });
    </script>
    @endpush
@endsection