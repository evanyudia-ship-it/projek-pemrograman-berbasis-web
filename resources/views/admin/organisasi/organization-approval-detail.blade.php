@extends('layouts.app')

@section('title', 'Detail Pengajuan - ' . $submission['id'])
@section('page_title', 'Detail Pengajuan Perwakilan Organisasi')
@section('page_subtitle', 'Kode Pengajuan: ' . $submission['id'])

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('admin.organization-approvals.index') }}" 
           class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700 transition">
            ← Kembali ke Daftar Pengajuan
        </a>
    </div>

    {{-- Status Banner --}}
    @if($submission['status'] === 'pending')
    <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800">
        <div class="flex items-center gap-3">
            <span class="text-2xl">⏳</span>
            <div>
                <p class="font-semibold">Menunggu Verifikasi</p>
                <p class="text-sm opacity-80">Pengajuan ini belum diproses. Silakan lakukan approval atau penolakan.</p>
            </div>
        </div>
    </div>
    @elseif($submission['status'] === 'approved')
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800">
        <div class="flex items-center gap-3">
            <span class="text-2xl">✅</span>
            <div>
                <p class="font-semibold">Disetujui</p>
                <p class="text-sm opacity-80">Pengajuan ini telah disetujui pada {{ $submission['diproses'] ?? '-' }}</p>
            </div>
        </div>
    </div>
    @else
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
        <div class="flex items-center gap-3">
            <span class="text-2xl">❌</span>
            <div>
                <p class="font-semibold">Ditolak</p>
                <p class="text-sm opacity-80">Pengajuan ini ditolak pada {{ $submission['diproses'] ?? '-' }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Main Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        {{-- Header --}}
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-lg text-slate-800">Informasi Lengkap Pengajuan</h3>
            <p class="text-sm text-slate-500 mt-0.5">Data mahasiswa dan detail perwakilan organisasi</p>
        </div>

        {{-- ========== ISI KONTENT GRID (DIPERBAIKI) ========== --}}
        <div class="p-8">
            
            {{-- 2 Kolom Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- KOLOM KIRI: Data Mahasiswa --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-lg">👤</span>
                        <h4 class="font-semibold text-slate-700">Data Mahasiswa</h4>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-400 uppercase tracking-wide">Nama Lengkap</span>
                            <span class="text-sm font-medium text-slate-800">{{ $submission['nama_mahasiswa'] }}</span>
                        </div>
                        
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-400 uppercase tracking-wide">NIM</span>
                            <span class="text-sm font-mono font-medium text-slate-800">{{ $submission['nim'] }}</span>
                        </div>
                        
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-400 uppercase tracking-wide">Email</span>
                            <span class="text-sm text-slate-800">{{ $submission['email'] }}</span>
                        </div>
                        
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-400 uppercase tracking-wide">Tanggal Pengajuan</span>
                            <span class="text-sm text-slate-800">{{ $submission['tgl_ajuan'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Data Organisasi --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-lg">🏢</span>
                        <h4 class="font-semibold text-slate-700">Data Organisasi</h4>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-400 uppercase tracking-wide">Nama Organisasi</span>
                            <span class="text-sm font-medium text-slate-800">{{ $submission['organisasi'] }}</span>
                            <span class="text-xs text-slate-400">{{ $submission['singkatan'] }}</span>
                        </div>
                        
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-400 uppercase tracking-wide">Jabatan</span>
                            <span class="text-sm text-slate-800">{{ $submission['jabatan'] }}</span>
                        </div>
                        
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-400 uppercase tracking-wide">Periode Kepengurusan</span>
                            <span class="text-sm font-mono text-slate-800">{{ $submission['periode'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Separator --}}
            <div class="border-t border-slate-100 my-6"></div>

            {{-- Kontak Organisasi --}}
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-lg">📞</span>
                    <h4 class="font-semibold text-slate-700">Kontak Organisasi</h4>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex flex-col p-3 bg-slate-50 rounded-xl">
                        <span class="text-xs text-slate-400 uppercase tracking-wide">Email</span>
                        <span class="text-sm text-slate-800">{{ $submission['email_organisasi'] ?? '—' }}</span>
                    </div>
                    <div class="flex flex-col p-3 bg-slate-50 rounded-xl">
                        <span class="text-xs text-slate-400 uppercase tracking-wide">Instagram</span>
                        <span class="text-sm text-slate-800">{{ $submission['instagram'] ?? '—' }}</span>
                    </div>
                    <div class="flex flex-col p-3 bg-slate-50 rounded-xl">
                        <span class="text-xs text-slate-400 uppercase tracking-wide">WhatsApp</span>
                        <span class="text-sm text-slate-800">{{ $submission['whatsapp'] ?? '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-lg">📝</span>
                    <h4 class="font-semibold text-slate-700">Deskripsi / Alasan Pengajuan</h4>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl">
                    <p class="text-sm text-slate-700 leading-relaxed">{{ $submission['deskripsi'] }}</p>
                </div>
            </div>

            {{-- Bukti Pendukung --}}
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-lg">📎</span>
                    <h4 class="font-semibold text-slate-700">Bukti Pendukung</h4>
                </div>
                
                {{-- Jenis Bukti --}}
                <div class="mb-3">
                    <span class="text-xs text-slate-400 uppercase tracking-wide block mb-2">Jenis Dokumen</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($submission['jenis_bukti'] as $bukti)
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                {{ str_replace('_', ' ', ucfirst($bukti)) }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- File Upload --}}
                <div>
                    <span class="text-xs text-slate-400 uppercase tracking-wide block mb-2">File Bukti</span>
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                        <span class="text-2xl">📄</span>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-slate-700">{{ $submission['file_bukti_nama'] }}</p>
                            <p class="text-xs text-slate-400">PDF/JPG/PNG • Maks 2MB</p>
                        </div>
                        <a href="{{ route('admin.organization-approvals.download', $submission['id']) }}"
                           class="px-3 py-1.5 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-semibold transition">
                            ↓ Download
                        </a>
                    </div>
                </div>
            </div>

            {{-- Catatan Admin (jika ada) --}}
            @if(!empty($submission['catatan']) && $submission['catatan'] !== 'Menunggu verifikasi superadmin')
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-lg">💬</span>
                    <h4 class="font-semibold text-slate-700">Catatan Admin</h4>
                </div>
                <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
                    <p class="text-sm text-amber-800">{{ $submission['catatan'] }}</p>
                </div>
            </div>
            @endif

            {{-- Action Buttons (hanya untuk pending) --}}
            @if($submission['status'] === 'pending')
            <div class="border-t border-slate-100 pt-6 mt-4">
                <div class="flex gap-3 justify-end">
                    <button class="btn-approve px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition shadow-sm"
                            data-id="{{ $submission['id'] }}"
                            data-action="{{ route('admin.organization-approvals.approve', $submission['id']) }}">
                        ✓ Setujui Pengajuan
                    </button>
                    <button class="btn-reject px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition shadow-sm"
                            data-id="{{ $submission['id'] }}"
                            data-action="{{ route('admin.organization-approvals.reject', $submission['id']) }}">
                        ✕ Tolak Pengajuan
                    </button>
                </div>
            </div>
            @endif

        </div>{{-- end content grid --}}
    </div>{{-- end main card --}}
</div>

{{-- MODAL REJECT --}}
<div id="modal-reject" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4">
        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-2xl mb-4">⚠️</div>
        <h3 class="font-bold text-lg mb-1">Tolak Pengajuan</h3>
        <p class="text-sm text-slate-500 mb-5">Berikan alasan penolakan yang jelas agar mahasiswa dapat mengajukan ulang.</p>

        <form id="form-reject" method="POST">
            @csrf
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alasan Penolakan</label>
            <textarea name="reason" id="reject-reason" rows="4"
                class="w-full rounded-xl border border-slate-200 p-3 text-sm focus:border-red-400 outline-none resize-none"
                placeholder="Contoh: Bukti kepengurusan tidak lengkap..."></textarea>
            <p class="text-xs text-red-500 mt-1 hidden" id="reason-error">Alasan penolakan wajib diisi (minimal 10 karakter).</p>

            <div class="flex gap-3 mt-6">
                <button type="button" id="btn-cancel-reject"
                    class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition">
                    Tolak Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {
    // Approve dengan SweetAlert
    $('.btn-approve').on('click', function () {
        const id = $(this).data('id');
        const action = $(this).data('action');

        Swal.fire({
            title: 'Setujui Pengajuan?',
            text: `Mahasiswa akan menjadi perwakilan resmi organisasi dengan ID ${id}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: action,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() {
                        Swal.fire('Disetujui!', 'Pengajuan berhasil disetujui.', 'success');
                        location.href = '{{ route("admin.organization-approvals.index") }}';
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan, coba lagi.', 'error');
                    }
                });
            }
        });
    });

    // Buka modal reject
    $('.btn-reject').on('click', function () {
        const action = $(this).data('action');
        $('#form-reject').attr('action', action);
        $('#reject-reason').val('');
        $('#reason-error').addClass('hidden');
        $('#modal-reject').removeClass('hidden').addClass('flex');
    });

    // Tutup modal
    $('#btn-cancel-reject, #modal-reject').on('click', function (e) {
        if (e.target === this || e.target.id === 'btn-cancel-reject') {
            $('#modal-reject').removeClass('flex').addClass('hidden');
        }
    });

    // Validasi reject
    $('#form-reject').on('submit', function (e) {
        const reason = $('#reject-reason').val().trim();
        if (reason.length < 10) {
            e.preventDefault();
            $('#reason-error').removeClass('hidden');
        }
    });
});
</script>
@endpush

@endsection