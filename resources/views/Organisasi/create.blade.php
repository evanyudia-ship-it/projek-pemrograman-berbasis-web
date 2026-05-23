@extends('layouts.app')

@section('title', 'Ajukan Perwakilan Organisasi')
@section('page_title', 'Ajukan Perwakilan Organisasi')
@section('page_subtitle', 'Isi form berikut untuk mendaftarkan diri sebagai perwakilan organisasi kampus')

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Back link --}}
    <a href="{{ route('organization.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-700 mb-5 transition-colors">
        ← Kembali ke Daftar Pengajuan
    </a>

    {{-- ── FORM CARD ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        {{-- Card Header --}}
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/70">
            <h3 class="font-bold text-base text-slate-800">Form Pengajuan Perwakilan</h3>
            <p class="text-xs text-slate-400 mt-0.5">
                Pengajuan akan diverifikasi superadmin. Pastikan data yang diisi sudah benar.
            </p>
        </div>

        <form method="POST" action="{{ route('organization.store') }}" enctype="multipart/form-data" class="p-6 space-y-5" novalidate>
            @csrf

            {{-- ── GLOBAL VALIDATION ERROR ──────────────────────────── --}}
            @if($errors->any())
            <div class="flex gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                <span class="text-lg leading-none shrink-0">⚠️</span>
                <div>
                    <p class="font-semibold mb-1">Terdapat kesalahan pada form:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- ── PILIH ORGANISASI ─────────────────────────────────── --}}
            <div>
                <label for="organisasi_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Nama Organisasi <span class="text-red-500">*</span>
                </label>
                <select id="organisasi_id" name="organisasi_id"
                    class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition
                           focus:ring-2 focus:ring-blue-100 focus:border-blue-400 bg-white
                           {{ $errors->has('organisasi_id') ? 'border-red-400 bg-red-50' : 'border-slate-200' }}">
                    <option value="">— Pilih Organisasi —</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org['id'] }}"
                            {{ old('organisasi_id') == $org['id'] ? 'selected' : '' }}>
                            {{ $org['nama'] }} ({{ $org['singkatan'] }})
                        </option>
                    @endforeach
                </select>
                @error('organisasi_id')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── JABATAN ──────────────────────────────────────────── --}}
            <div>
                <label for="jabatan" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Jabatan / Posisi <span class="text-red-500">*</span>
                </label>
                <select id="jabatan" name="jabatan"
                    class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition
                           focus:ring-2 focus:ring-blue-100 focus:border-blue-400 bg-white
                           {{ $errors->has('jabatan') ? 'border-red-400 bg-red-50' : 'border-slate-200' }}">
                    <option value="">— Pilih Jabatan —</option>
                    @foreach($jabatanList as $jab)
                        <option value="{{ $jab }}" {{ old('jabatan') === $jab ? 'selected' : '' }}>
                            {{ $jab }}
                        </option>
                    @endforeach
                </select>
                @error('jabatan')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── PERIODE KEPENGURUSAN ───────────────────────────── --}}
            <div>
                <label for="periode" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Periode Kepengurusan <span class="text-red-500">*</span>
                </label>
                <input type="text" id="periode" name="periode" 
                    placeholder="Contoh: 2025/2026 atau 2024-2025"
                    value="{{ old('periode') }}"
                    class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition
                            focus:ring-2 focus:ring-blue-100 focus:border-blue-400
                            {{ $errors->has('periode') ? 'border-red-400 bg-red-50' : 'border-slate-200' }}">
                @error('periode')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── KONTAK ORGANISASI ─────────────────────────────── --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Kontak Organisasi <span class="text-red-500">*</span>
                </label>
                <div class="space-y-2">
                    <input type="email" name="email_organisasi" placeholder="Email organisasi (contoh: bem@undiksha.ac.id)"
                        value="{{ old('email_organisasi') }}"
                        class="w-full rounded-xl border px-4 py-2 text-sm {{ $errors->has('email_organisasi') ? 'border-red-400' : 'border-slate-200' }}">
                    <input type="text" name="instagram" placeholder="Instagram (contoh: @bemundiksha)"
                        value="{{ old('instagram') }}"
                        class="w-full rounded-xl border px-4 py-2 text-sm {{ $errors->has('instagram') ? 'border-red-400' : 'border-slate-200' }}">
                    <input type="text" name="whatsapp" placeholder="Nomor WhatsApp (contoh: 081234567890)"
                        value="{{ old('whatsapp') }}"
                        class="w-full rounded-xl border px-4 py-2 text-sm {{ $errors->has('whatsapp') ? 'border-red-400' : 'border-slate-200' }}">
                </div>
                <p class="text-xs text-slate-400 mt-1">Minimal salah satu kontak diisi.</p>
                @error('email_organisasi')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
                @error('instagram')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
                @error('whatsapp')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── BUKTI PENDUKUNG ───────────────────────────────── --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Bukti Pendukung <span class="text-red-500">*</span>
                </label>
                <div class="rounded-xl border border-slate-200 p-4 bg-slate-50/50">
                    <p class="text-xs text-slate-500 mb-2">Upload minimal satu dokumen (PDF/JPG/PNG, maks 2MB)</p>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="jenis_bukti[]" value="surat_kepengurusan"> Surat Kepengurusan
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="jenis_bukti[]" value="sk_organisasi"> SK Organisasi
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="jenis_bukti[]" value="surat_rekomendasi"> Surat Rekomendasi
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="jenis_bukti[]" value="kartu_anggota"> Kartu Anggota
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="jenis_bukti[]" value="screenshot_struktur"> Screenshot struktur organisasi
                        </label>
                    </div>
                    <p class="text-xs text-slate-500 mb-2">Semakin lengkap bukti pendukung yang diunggah, semakin mudah proses verifikasi dilakukan oleh Super Admin.</p>
                    <input type="file" name="file_bukti" accept=".pdf,.jpg,.png"
                        class="w-full mt-3 text-sm text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                @error('file_bukti')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
                @error('jenis_bukti')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── DESKRIPSI / KETERANGAN ───────────────────────────── --}}
            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Deskripsi / Keterangan <span class="text-red-500">*</span>
                </label>
                <textarea id="deskripsi" name="deskripsi"
                          rows="5"
                          maxlength="1000"
                          placeholder="Jelaskan peran Anda dalam organisasi ini, tanggung jawab, dan alasan pengajuan…"
                          class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition resize-none
                                 focus:ring-2 focus:ring-blue-100 focus:border-blue-400
                                 {{ $errors->has('deskripsi') ? 'border-red-400 bg-red-50' : 'border-slate-200' }}">{{ old('deskripsi') }}</textarea>

                <div class="flex justify-between items-center mt-1.5">
                    <span class="text-xs">
                        @error('deskripsi')
                            <span class="text-red-500">{{ $message }}</span>
                        @else
                            <span class="text-slate-400">Minimal 20 karakter.</span>
                        @enderror
                    </span>
                    <span id="char-count" class="text-xs text-slate-400">0 / 1000</span>
                </div>
            </div>

            {{-- ── INFO BOX ─────────────────────────────────────────── --}}
            <div class="p-4 rounded-xl bg-blue-50 border border-blue-100">
                <p class="text-xs font-bold text-blue-700 mb-1.5">ℹ️ Informasi Penting</p>
                <ul class="space-y-1 text-xs text-blue-600 list-disc list-inside">
                    <li>Pengajuan Anda sedang ditinjau oleh Super Admin.</li>
                    <li>Proses verifikasi membutuhkan waktu sekitar 1 hari hingga maksimal 1 minggu.</li>
                    <li>Anda hanya dapat memiliki <strong>1 pengajuan aktif</strong> dalam satu waktu.</li>
                    <li>Jika pengajuan ditolak, Anda dapat mengajukan kembali.</li>
                    <li>Pengajuan yang sudah disetujui tidak dapat dibatalkan.</li>
                </ul>
            </div>

            {{-- ── ACTIONS ──────────────────────────────────────────── --}}
            <div class="flex gap-3 pt-1">
                <a href="{{ route('organization.index') }}"
                   class="flex-1 text-center py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold
                          hover:bg-slate-50 active:scale-95 transition text-sm">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-95
                           text-white font-semibold transition text-sm shadow-sm shadow-blue-200">
                    Kirim Pengajuan →
                </button>
            </div>

        </form>
    </div>{{-- /form card --}}

</div>

@push('scripts')
<script>
$(document).ready(function () {
    const $textarea  = $('#deskripsi');
    const $counter   = $('#char-count');

    function updateCounter() {
        const len = $textarea.val().length;
        $counter.text(len + ' / 1000');
        $counter.toggleClass('text-red-500 font-semibold', len >= 980);
        $counter.toggleClass('text-slate-400', len < 980);
    }

    $textarea.on('input', updateCounter);
    updateCounter(); // init on page load (handles old() value)
});
</script>
@endpush

@endsection