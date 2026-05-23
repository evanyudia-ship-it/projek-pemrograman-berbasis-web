@extends('layouts.app')

@section('title', 'Manajemen Ruang')
@section('page_title', 'Manajemen Ruang')
@section('page_subtitle', 'Tambah, edit, hapus, dan atur status ruang kampus')

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

{{-- ===== STAT CARDS ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-xs text-slate-500 mb-1">Total Ruang</p>
        <p class="text-3xl font-extrabold text-slate-900">{{ $totalRooms }}</p>
        <p class="text-xs text-slate-400 mt-1">ruang terdaftar</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-xs text-slate-500 mb-1">Tersedia</p>
        <p class="text-3xl font-extrabold text-emerald-600">{{ $totalTersedia }}</p>
        <p class="text-xs text-slate-400 mt-1">siap digunakan</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-xs text-slate-500 mb-1">Dipakai</p>
        <p class="text-3xl font-extrabold text-red-500">{{ $totalDipakai }}</p>
        <p class="text-xs text-slate-400 mt-1">sedang digunakan</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-xs text-slate-500 mb-1">Total Kapasitas</p>
        <p class="text-3xl font-extrabold text-blue-600">{{ number_format($totalKapasitas) }}</p>
        <p class="text-xs text-slate-400 mt-1">orang (seluruh ruang)</p>
    </div>

</div>

{{-- ===== TABEL RUANG ===== --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    {{-- Header --}}
    <div class="p-6 border-b border-slate-200">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg">Daftar Ruang Kampus</h3>
                <p class="text-sm text-slate-500">Kelola semua ruang, kapasitas, fasilitas, dan statusnya</p>
            </div>
            <div class="flex gap-3 flex-wrap">
                {{-- Search --}}
                <input type="text" id="searchRoom"
                       placeholder="Cari nama, kode, gedung..."
                       class="rounded-xl text-sm">

                {{-- Filter status --}}
                <select id="filterStatus" class="rounded-xl text-sm">
                    <option value="">Semua Status</option>
                    <option value="Tersedia">Tersedia</option>
                    <option value="Dipakai">Dipakai</option>
                </select>

                {{-- Tambah --}}
                <button id="btnTambah"
                        class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-700 text-white font-semibold text-sm transition flex items-center gap-2">
                    <span>➕</span> Tambah Ruang
                </button>

                {{-- Reset --}}
                <form action="{{ route('admin.rooms.reset') }}" method="POST"
                      onsubmit="return confirm('Reset semua data ruang ke default?')">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-500 hover:text-slate-700 font-semibold text-sm transition">
                        🔄 Reset
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-6 py-4">Ruang</th>
                    <th class="text-left px-6 py-4">Lokasi</th>
                    <th class="text-left px-6 py-4">Kapasitas</th>
                    <th class="text-left px-6 py-4">Jam Operasional</th>
                    <th class="text-left px-6 py-4">Fasilitas</th>
                    <th class="text-left px-6 py-4">Status</th>
                    <th class="text-center px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100" id="roomTable">

                @forelse($rooms as $room)
                <tr class="room-row hover:bg-slate-50 transition"
                    data-search="{{ strtolower($room['nama']) }} {{ strtolower($room['kode']) }} {{ strtolower($room['gedung']) }}"
                    data-status="{{ $room['status'] }}">

                    {{-- Foto + Nama --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-14 rounded-xl overflow-hidden shrink-0 bg-slate-100">
                                <img src="{{ $room['foto'] }}"
                                     alt="{{ $room['nama'] }}"
                                     class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">{{ $room['nama'] }}</p>
                                <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $room['kode'] }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Lokasi --}}
                    <td class="px-6 py-4">
                        <p class="font-semibold text-slate-700">{{ $room['gedung'] }}</p>
                        <p class="text-xs text-slate-400">Lantai {{ $room['lantai'] }}</p>
                    </td>

                    {{-- Kapasitas --}}
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">{{ $room['kapasitas'] }}</p>
                        <p class="text-xs text-slate-400">orang</p>
                    </td>

                    {{-- Jam --}}
                    <td class="px-6 py-4">
                        <p class="font-semibold text-slate-700 text-xs">{{ $room['jam_buka'] }} – {{ $room['jam_tutup'] }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Maks. {{ $room['max_durasi'] }}</p>
                    </td>

                    {{-- Fasilitas --}}
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1 max-w-50">
                            @foreach(array_slice($room['fasilitas'], 0, 3) as $f)
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded-lg font-medium">{{ $f }}</span>
                            @endforeach
                            @if(count($room['fasilitas']) > 3)
                            <span class="bg-blue-50 text-blue-600 text-[10px] px-2 py-0.5 rounded-lg font-medium">+{{ count($room['fasilitas']) - 3 }}</span>
                            @endif
                        </div>
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.rooms.toggleStatus', $room['id']) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    title="Klik untuk ubah status"
                                    class="px-3 py-1.5 rounded-full text-xs font-bold transition hover:opacity-80
                                        {{ $room['status'] === 'Tersedia'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-red-100 text-red-700' }}">
                                {{ $room['status'] }}
                            </button>
                        </form>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('rooms.show', $room['id']) }}"
                               target="_blank"
                               class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs transition">
                                👁 Lihat
                            </a>
                            <button class="btn-edit px-3 py-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold text-xs transition"
                                data-room="{{ htmlspecialchars(json_encode($room, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') }}">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('admin.rooms.destroy', $room['id']) }}" method="POST"
                                  onsubmit="return confirm('Hapus ruang {{ addslashes($room['nama']) }}? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 font-semibold text-xs transition">
                                    🗑 Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-slate-400">
                        <p class="text-4xl mb-3">🏫</p>
                        <p class="font-semibold">Belum ada ruang terdaftar</p>
                        <p class="text-xs mt-1">Klik "Tambah Ruang" untuk menambahkan</p>
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

</div>

{{-- ===== MODAL TAMBAH / EDIT ===== --}}
<div id="roomModal"
     class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

        {{-- Modal Header --}}
        <div class="p-6 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white z-10">
            <div>
                <h3 class="font-bold text-lg" id="modalTitle">Tambah Ruang</h3>
                <p class="text-sm text-slate-500">Isi data ruang dengan lengkap</p>
            </div>
            <button id="btnCloseModal"
                    class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 transition font-bold text-slate-500">
                ✕
            </button>
        </div>

        {{-- Modal Form --}}
        <form id="roomForm" method="POST" class="p-6">
            @csrf
            <div id="methodField"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Nama --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-700">Nama Ruang <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="fNama"
                           class="mt-2 w-full" placeholder="Contoh: Ruang Seminar A - Lt. 3" required>
                </div>

                {{-- Kode --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Kode Ruang <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" id="fKode"
                           class="mt-2 w-full" placeholder="Contoh: RSA-301" required>
                </div>

                {{-- Status --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="fStatus" class="mt-2 w-full" required>
                        <option value="Tersedia">Tersedia</option>
                        <option value="Dipakai">Dipakai</option>
                    </select>
                </div>

                {{-- Gedung --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Gedung <span class="text-red-500">*</span></label>
                    <input type="text" name="gedung" id="fGedung"
                           class="mt-2 w-full" placeholder="Contoh: Gedung A" required>
                </div>

                {{-- Lantai --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Lantai <span class="text-red-500">*</span></label>
                    <input type="number" name="lantai" id="fLantai"
                           class="mt-2 w-full" min="1" placeholder="Contoh: 3" required>
                </div>

                {{-- Kapasitas --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Kapasitas (orang) <span class="text-red-500">*</span></label>
                    <input type="number" name="kapasitas" id="fKapasitas"
                           class="mt-2 w-full" min="1" max="9999" 
                           placeholder="Contoh: 40" required>
                </div>

                {{-- Max Durasi --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Maks. Durasi <span class="text-red-500">*</span></label>
                    <input type="text" name="max_durasi" id="fMaxDurasi"
                           class="mt-2 w-full" placeholder="Contoh: 6 jam/hari" required>
                </div>

                {{-- Jam Buka --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Jam Buka <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_buka" id="fJamBuka"
                           class="mt-2 w-full" required>
                </div>

                {{-- Jam Tutup --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Jam Tutup <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_tutup" id="fJamTutup"
                           class="mt-2 w-full" required>
                </div>

                {{-- Fasilitas --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-700">Fasilitas</label>
                    <input type="text" name="fasilitas_string" id="fFasilitas"
                           class="mt-2 w-full"
                           placeholder="Pisahkan dengan koma: AC, Proyektor, WiFi">
                    <p class="text-xs text-slate-400 mt-1">Contoh: AC, Proyektor, WiFi, Whiteboard</p>
                </div>

                {{-- URL Foto --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-700">URL Foto Ruang</label>
                    <input type="url" name="foto" id="fFoto"
                           class="mt-2 w-full"
                           placeholder="https://images.unsplash.com/...">
                    <p class="text-xs text-slate-400 mt-1">Kosongkan untuk menggunakan foto default</p>
                    {{-- Preview foto --}}
                    <div id="fotoPreviewWrap" class="hidden mt-3">
                        <img id="fotoPreview"
                             src="" alt="Preview"
                             class="w-full h-36 object-cover rounded-xl border border-slate-200">
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-700">Deskripsi</label>
                    <textarea name="deskripsi" id="fDeskripsi" rows="3"
                              class="mt-2 w-full"
                              placeholder="Deskripsi singkat ruangan..."></textarea>
                </div>

            </div>

            {{-- Footer --}}
            <div class="mt-6 flex flex-col md:flex-row gap-3 md:justify-end">
                <button type="button" id="btnBatal"
                        class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-3 rounded-xl bg-slate-900 hover:bg-slate-700 text-white font-semibold">
                    Simpan Ruang
                </button>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {

    // ── Search & Filter ──────────────────────────────────────────────────
    function filterRooms() {
        const keyword = $('#searchRoom').val().toLowerCase();
        const status  = $('#filterStatus').val();

        $('.room-row').each(function () {
            const matchSearch = $(this).data('search').includes(keyword);
            const matchStatus = status === '' || $(this).data('status') === status;
            $(this).toggle(matchSearch && matchStatus);
        });
    }

    $('#searchRoom').on('input', filterRooms);
    $('#filterStatus').on('change', filterRooms);

    // ── Validasi Global Form ─────────────────────────────────────────────
    function setupFormValidation() {
        $('#roomForm').off('submit');
        $('#roomForm').on('submit', function(e) {

            const jamBuka = $('#fJamBuka').val();
            const jamTutup = $('#fJamTutup').val();    
            if (jamBuka && jamTutup && jamBuka >= jamTutup) {
                e.preventDefault();
                alert('Jam buka harus lebih awal dari jam tutup');
                return false;
            }

            if (jamBuka && jamTutup) {
                const jamBukaInt = parseInt(jamBuka.replace(':', ''));
                const jamTutupInt = parseInt(jamTutup.replace(':', ''));
                
                if (jamTutupInt - jamBukaInt < 100) { // Minimal 1 jam (contoh: 09:00 - 10:00 = 100)
                    if (!confirm('Jam operasional kurang dari 1 jam? Apakah ini benar?')) {
                        e.preventDefault();
                        return false;
                    }
                }
            }
            
            const lantai = parseInt($('#fLantai').val());
            const kapasitas = parseInt($('#fKapasitas').val());
            
            if (isNaN(lantai) || lantai < 1) {
                e.preventDefault();
                alert('Lantai minimal adalah 1');
                return false;
            }
            
            if (isNaN(kapasitas) || kapasitas < 1) {
                e.preventDefault();
                alert('Kapasitas minimal adalah 1 orang');
                return false;
            }
            
            if (kapasitas > 1000) {
                if (!confirm('Kapasitas lebih dari 1000 orang? Apakah ini benar?')) {
                    e.preventDefault();
                    return false;
                }
            }
            const fasilitasInput = $('#fFasilitas').val();
            if (fasilitasInput && fasilitasInput.trim()) {
                const fasilitasArray = fasilitasInput.split(',').map(f => f.trim()).filter(f => f);
                $(this).find('input[name="fasilitas"], input[name="fasilitas[]"]').remove();
                fasilitasArray.forEach(f => {
                    $(this).append(`<input type="hidden" name="fasilitas[]" value="${escapeHtml(f)}">`);
                });
            }
            
            // Loading state
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<span class="inline-block animate-spin mr-2">⏳</span> Menyimpan...').prop('disabled', true);
            
            $(this).data('original-text', originalText);
        });
    }
    
    // Helper function untuk escape HTML
    function escapeHtml(str) {
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    // ── Buka modal TAMBAH ────────────────────────────────────────────────
    $('#btnTambah').on('click', function () {
        $('#modalTitle').text('Tambah Ruang Baru');
        $('#roomForm')[0].reset();
        $('#methodField').html('');
        $('#roomForm').attr('action', '{{ route("admin.rooms.store") }}');
        $('#fotoPreviewWrap').addClass('hidden');
        setupFormValidation(); // Setup ulang validasi
        openModal();
    });

    // ── Buka modal EDIT ──────────────────────────────────────────────────
    $(document).on('click', '.btn-edit', function () {
        const room = $(this).data('room');

        $('#modalTitle').text('Edit Ruang');
        $('#methodField').html('<input type="hidden" name="_method" value="PUT">');
        $('#roomForm').attr('action', '{{ route("admin.rooms.update", ":id") }}'.replace(':id', room.id));

        // Isi form
        $('#fNama').val(room.nama);
        $('#fKode').val(room.kode);
        $('#fStatus').val(room.status);
        $('#fGedung').val(room.gedung);
        $('#fLantai').val(room.lantai);
        $('#fKapasitas').val(room.kapasitas);
        $('#fMaxDurasi').val(room.max_durasi);
        $('#fJamBuka').val(room.jam_buka);
        $('#fJamTutup').val(room.jam_tutup);
        
        let fasilitasStr = '';
        if (Array.isArray(room.fasilitas)) fasilitasStr = room.fasilitas.join(', ');
        else if (typeof room.fasilitas === 'string') fasilitasStr = room.fasilitas;
        $('#fFasilitas').val(fasilitasStr);
        
        $('#fDeskripsi').val(room.deskripsi);
        $('#fFoto').val(room.foto);
        
        // Tampilkan preview foto
        if (room.foto && room.foto.trim()) {
            $('#fotoPreview').attr('src', room.foto);
            $('#fotoPreviewWrap').removeClass('hidden');
        } else {
            $('#fotoPreviewWrap').addClass('hidden');
        }
        
        setupFormValidation(); // Setup ulang validasi
        openModal();
    });

    // ── Preview foto saat URL diketik ────────────────────────────────────
    $('#fFoto').on('input', function () {
        const url = $(this).val().trim();
        if (url && isValidUrl(url)) {
            $('#fotoPreview').attr('src', url);
            $('#fotoPreviewWrap').removeClass('hidden');
        } else {
            $('#fotoPreviewWrap').addClass('hidden');
        }
    });
    
    // Validasi URL sederhana
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }

    // ── Tutup modal ──────────────────────────────────────────────────────
    function openModal()  { 
        $('#roomModal').removeClass('hidden');
        $('body').css('overflow', 'hidden'); // Prevent scroll
    }
    
    function closeModal() { 
        $('#roomModal').addClass('hidden');
        $('body').css('overflow', '');
        $('#roomForm')[0].reset(); // Reset form saat tutup
        $('#fotoPreviewWrap').addClass('hidden');
    }

    $('#btnCloseModal, #btnBatal').on('click', closeModal);
    $('#roomModal').on('click', function (e) {
        if (e.target.id === 'roomModal') closeModal();
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
    
    setupFormValidation();
});
</script>
@endpush

@endsection