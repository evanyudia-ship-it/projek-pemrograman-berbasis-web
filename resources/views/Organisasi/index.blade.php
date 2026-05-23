@extends('layouts.app')

@section('title', 'Perwakilan Organisasi')
@section('page_title', 'Perwakilan Organisasi')
@section('page_subtitle', 'Kelola pengajuan perwakilan organisasi kampus Anda')

@section('content')

{{-- ── FLASH MESSAGES ─────────────────────────────────────────────────── --}}
@if(session('success'))
    <div class="mb-5 flex items-start gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
        <span class="text-lg leading-none">✅</span>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
@endif

@if(session('info'))
    <div class="mb-5 flex items-start gap-3 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-sm">
        <span class="text-lg leading-none">ℹ️</span>
        <span class="font-medium">{{ session('info') }}</span>
    </div>
@endif

{{-- ── STATUS AKTIF BANNER ─────────────────────────────────────────────── --}}
@if($active)
    @php
        $bannerConfig = match($active['status']) {
            'pending'  => ['bg' => 'bg-amber-50',   'border' => 'border-amber-200',  'text' => 'text-amber-800',  'icon' => '⏳', 'msg' => 'Pengajuan Anda sedang menunggu persetujuan superadmin.'],
            'approved' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200','text' => 'text-emerald-800','icon' => '✅', 'msg' => 'Anda sudah menjadi perwakilan organisasi.'],
            default    => null,
        };
    @endphp

    @if($bannerConfig)
    <div class="mb-5 flex items-center justify-between gap-3 p-4 rounded-xl {{ $bannerConfig['bg'] }} border {{ $bannerConfig['border'] }} {{ $bannerConfig['text'] }} text-sm">
        <div class="flex items-center gap-2">
            <span class="text-xl leading-none">{{ $bannerConfig['icon'] }}</span>
            <span class="font-semibold">{{ $bannerConfig['msg'] }}</span>
        </div>
        <span class="text-xs opacity-70 shrink-0">Kode: {{ $active['id'] }}</span>
    </div>
    @endif
@endif

{{-- ── STAT CARDS ──────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @php
        $cards = [
            ['label' => 'Total Pengajuan', 'value' => $stats['total'],    'color' => 'text-slate-700',   'dot' => 'bg-slate-400'],
            ['label' => 'Menunggu',        'value' => $stats['pending'],  'color' => 'text-amber-500',   'dot' => 'bg-amber-400'],
            ['label' => 'Disetujui',       'value' => $stats['approved'], 'color' => 'text-emerald-600', 'dot' => 'bg-emerald-500'],
            ['label' => 'Ditolak',         'value' => $stats['rejected'], 'color' => 'text-red-500',     'dot' => 'bg-red-400'],
        ];
    @endphp

    @foreach($cards as $card)
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 flex flex-col gap-1">
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full {{ $card['dot'] }}"></span>
            <p class="text-xs text-slate-500 font-medium">{{ $card['label'] }}</p>
        </div>
        <h3 class="text-3xl font-extrabold {{ $card['color'] }}">{{ $card['value'] }}</h3>
    </div>
    @endforeach
</div>

{{-- ── MAIN CARD ────────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    {{-- Header --}}
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
        <div>
            <h3 class="font-bold text-base text-slate-800">Riwayat Pengajuan</h3>
            <p class="text-xs text-slate-400 mt-0.5">Semua pengajuan perwakilan organisasi Anda</p>
        </div>

        {{-- Tombol ajukan: nonaktif jika ada pengajuan aktif --}}
        @if($active && in_array($active['status'], ['pending', 'approved']))
            <button disabled
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 text-slate-400 text-sm font-semibold cursor-not-allowed"
                title="Anda sudah memiliki pengajuan aktif">
                ➕ Ajukan Perwakilan Baru
            </button>
        @else
            <a href="{{ route('organization.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-sm font-semibold transition-all duration-150 shadow-sm shadow-blue-200">
                ➕ Ajukan Perwakilan Baru
            </a>
        @endif
    </div>

    {{-- Filter Pills --}}
    <div class="px-6 pt-4 pb-3 flex gap-2 flex-wrap">
        @php
            $filters = [
                'all'      => 'Semua',
                'pending'  => 'Menunggu',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
            ];
        @endphp
        @foreach($filters as $key => $label)
        <button
            class="filter-btn text-xs font-semibold px-3.5 py-1.5 rounded-full transition-all duration-150
                   {{ $key === 'all' ? 'bg-slate-800 text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}"
            data-filter="{{ $key }}">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-400 text-xs font-semibold uppercase tracking-wide">
                    <th class="text-left px-6 py-3">Kode</th>
                    <th class="text-left px-6 py-3">Organisasi</th>
                    <th class="text-left px-6 py-3">Periode</th>
                    <th class="text-left px-6 py-3">Jabatan</th>
                    <th class="text-left px-6 py-3">Kontak</th>
                    <th class="text-left px-6 py-3">Tgl Ajuan</th>
                    <th class="text-left px-6 py-3">Status</th>
                    <th class="text-left px-6 py-3">Catatan Admin</th>
                    <th class="text-center px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50" id="org-table">
                @forelse($submissions as $s)
                <tr class="org-row hover:bg-slate-50/60 transition-colors" data-status="{{ $s['status'] }}">

                    <td class="px-6 py-4">
                        <span class="font-mono text-xs font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded-lg">
                            {{ $s['id'] }}
                        </span>
                    </td>
                
                    <td class="px-6 py-4">
                        <p class="font-semibold text-slate-800 leading-tight">{{ $s['organisasi'] }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $s['singkatan'] }}</p>
                    </td>
                
                    <!-- Kolom Periode -->
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $s['periode'] ?? '-' }}
                    </td>
                
                    <td class="px-6 py-4 text-slate-600">{{ $s['jabatan'] }}</td>
                
                    <!-- Kolom Kontak -->
                    <td class="px-6 py-4 text-xs text-slate-600">
                        @if(!empty($s['email_organisasi']))
                            <div>Email: {{ $s['email_organisasi'] }}</div>
                        @endif
                        @if(!empty($s['instagram']))
                            <div>IG: {{ $s['instagram'] }}</div>
                        @endif
                        @if(!empty($s['whatsapp']))
                            <div>WA: {{ $s['whatsapp'] }}</div>
                        @endif
                        @if(empty($s['email_organisasi']) && empty($s['instagram']) && empty($s['whatsapp']))
                            <span class="text-slate-400">—</span>
                        @endif
                    </td>
                
                    <td class="px-6 py-4 text-slate-400 text-xs whitespace-nowrap">{{ $s['tgl_ajuan'] }}</td>

                    <td class="px-6 py-4">
                        @php
                            $badge = match($s['status']) {
                                'approved' => 'bg-emerald-100 text-emerald-700',
                                'rejected' => 'bg-red-100 text-red-600',
                                'pending'  => 'bg-amber-100 text-amber-600',
                                default    => 'bg-slate-100 text-slate-500',
                            };
                            $icon = match($s['status']) {
                                'approved' => '✓',
                                'rejected' => '✕',
                                'pending'  => '⏳',
                                default    => '?',
                            };
                            $label = match($s['status']) {
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'pending'  => 'Menunggu',
                                default    => 'Unknown',
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold {{ $badge }}
                                     {{ $s['status'] === 'pending' ? 'animate-pulse' : '' }}">
                            {{ $icon }} {{ $label }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-slate-500 text-xs max-w-45 truncate" title="{{ $s['catatan'] }}">
                        {{ $s['catatan'] === '-' ? '—' : $s['catatan'] }}
                    </td>

                    <td class="px-6 py-4 text-center">
                        @if($s['status'] === 'pending')
                            <button
                            class="btn-cancel-temp px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold transition-colors"
                                data-id="{{ $s['id'] }}"
                                data-action="{{ route('organization.cancel', $s['id']) }}">
                                Batalkan
                            </button>
                        @elseif($s['status'] === 'rejected')
                            <a href="{{ route('organization.create') }}"
                               class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-semibold transition-colors">
                                Ajukan Ulang
                            </a>
                        @else
                            <span class="text-slate-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-2 text-slate-400">
                            <span class="text-4xl">📋</span>
                            <p class="font-semibold text-slate-500">Belum ada pengajuan</p>
                            <p class="text-xs">Klik tombol "Ajukan Perwakilan Baru" untuk memulai.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>{{-- /main card --}}


{{-- ── MODAL KONFIRMASI BATALKAN ───────────────────────────────────────── --}}
<div id="modal-cancel"
     class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-sm mx-4 animate-fade-in">

        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-2xl mb-4">⚠️</div>
        <h3 class="font-bold text-lg text-slate-800 mb-1">Batalkan Pengajuan?</h3>
        <p class="text-sm text-slate-500 mb-6">
            Pengajuan yang dibatalkan tidak dapat dikembalikan. Anda bisa mengajukan ulang setelahnya.
        </p>

        <form id="form-cancel" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="button" id="btn-close-modal"
                    class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition text-sm">
                    Tidak
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 active:scale-95 text-white font-semibold transition text-sm">
                    Ya, Batalkan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {

    // ── Filter tabel ────────────────────────────────────────────────────
    $('.filter-btn').on('click', function () {
        const filter = $(this).data('filter');

        $('.filter-btn')
            .removeClass('bg-slate-800 text-white shadow-sm')
            .addClass('bg-slate-100 text-slate-500');

        $(this)
            .removeClass('bg-slate-100 text-slate-500')
            .addClass('bg-slate-800 text-white shadow-sm');

        $('.org-row').each(function () {
            const match = filter === 'all' || $(this).data('status') === filter;
            $(this).toggle(match);
        });
    });

    // ── Buka modal ──────────────────────────────────────────────────────
    $('.btn-cancel').on('click', function () {
        const action = $(this).data('action');
        $('#form-cancel').attr('action', action);
        $('#modal-cancel').removeClass('hidden').addClass('flex');
    });

    // ── Tutup modal ─────────────────────────────────────────────────────
    $('#btn-close-modal, #modal-cancel').on('click', function (e) {
        if (e.target.id === 'btn-close-modal' || e.target.id === 'modal-cancel') {
            $('#modal-cancel').removeClass('flex').addClass('hidden');
        }
    });

    // ── Tombol Batalkan ─────────────────────────────────────────────────
    $(document).on('click', '.btn-cancel-temp', function () {
        const id = $(this).data('id');
        const action = $(this).data('action');
        
        Swal.fire({
            title: 'Batalkan Pengajuan?',
            text: 'Pengajuan yang dibatalkan tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: action,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() {
                        Swal.fire('Dibatalkan!', 'Pengajuan berhasil dibatalkan.', 'success');
                        location.reload();
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                    }
                });
            }
        });
    });

});
</script>
@endpush

@endsection