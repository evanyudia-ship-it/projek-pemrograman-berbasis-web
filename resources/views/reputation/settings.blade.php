@extends('layouts.app')

@section('title', 'Pengaturan Reputasi - Smart Classroom')
@section('page_title', 'Pengaturan Reputasi')
@section('page_subtitle', 'Atur poin untuk setiap aksi di sistem')

@section('content')

<div class="max-w-4xl mx-auto font-sora space-y-6">

    {{-- ============================================================ --}}
    {{-- FLASH MESSAGES --}}
    -- ============================================================ --}}
    @if(session('success'))
    <div id="flashMsg" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm font-semibold shadow-sm">
        <span class="text-lg">✅</span>
        {{ session('success') }}
        <button onclick="document.getElementById('flashMsg').remove()" class="ml-auto text-emerald-500 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-200 font-bold">✕</button>
    </div>
    @endif

    @if(session('error'))
    <div id="flashMsg" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm font-semibold shadow-sm">
        <span class="text-lg">❌</span>
        {{ session('error') }}
        <button onclick="document.getElementById('flashMsg').remove()" class="ml-auto text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200 font-bold">✕</button>
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm font-semibold shadow-sm">
        <div class="flex items-start gap-3">
            <span class="text-lg">❌</span>
            <div>
                <p class="font-semibold">Terjadi kesalahan:</p>
                <ul class="list-disc ml-5 mt-1 text-sm font-normal">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- HEADER --}}
    -- ============================================================ --}}
    <div class="bg-gradient-to-br from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800 rounded-2xl p-6 md:p-8 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-3xl">
                    ⚙️
                </div>
                <div>
                    <p class="text-xs font-semibold text-white/70 uppercase tracking-widest">Manajemen Reputasi</p>
                    <h1 class="text-2xl md:text-3xl font-bold mt-0.5">Pengaturan Reputasi</h1>
                    <p class="text-sm text-white/80 mt-1">Atur poin untuk setiap aksi di sistem</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 rounded-xl bg-white/20 backdrop-blur text-sm font-semibold border border-white/10 flex items-center gap-2">
                    <i class="fas fa-sliders-h"></i> {{ $settings->count() }} Pengaturan
                </span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- STATISTICS CARDS --}}
    -- ============================================================ --}}
    @php
        $totalReward = $settings->where('type', 'reward')->sum('points');
        $totalPenalty = $settings->where('type', 'penalty')->sum('points');
        $maxPoints = $settings->max('points');
        $minPoints = $settings->min('points');
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Settings --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Aksi</p>
                    <p class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $settings->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition">
                    <i class="fas fa-list-check text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">aksi yang diatur</span>
            </div>
        </div>

        {{-- Total Reward --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Reward</p>
                    <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">+{{ $totalReward }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition">
                    <i class="fas fa-star text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">total poin reward</span>
            </div>
        </div>

        {{-- Total Penalty --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Penalty</p>
                    <p class="text-2xl font-extrabold text-red-600 dark:text-red-400 mt-1">{{ $totalPenalty }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400 group-hover:scale-110 transition">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-red-600 dark:text-red-400 font-medium">total poin penalty</span>
            </div>
        </div>

        {{-- Range Poin --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Range Poin</p>
                    <p class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ $minPoints }} - {{ $maxPoints }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition">
                    <i class="fas fa-arrows-left-right text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">minimal - maksimal</span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SETTINGS FORM --}}
    -- ============================================================ --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <i class="fas fa-sliders-h text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white">Daftar Pengaturan Poin</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Atur nilai poin untuk setiap aksi di sistem</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.reputation.settings.update') }}">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-1">
                @foreach($settings as $setting)
                <div class="flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition group border-b border-slate-100 dark:border-slate-700 last:border-0">
                    {{-- Nama & Deskripsi --}}
                    <div class="flex items-center gap-4 flex-1">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg
                            {{ $setting->type === 'reward' ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400' }}">
                            <i class="fas {{ $setting->type === 'reward' ? 'fa-plus' : 'fa-minus' }}"></i>
                        </div>
                        <div>
                            <label class="font-semibold text-slate-800 dark:text-white text-sm cursor-pointer">
                                {{ $setting->name }}
                            </label>
                            @if($setting->description)
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $setting->description }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Input Poin --}}
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="settings[{{ $loop->index }}][id]" value="{{ $setting->id }}">
                        <div class="relative">
                            <input type="number" name="settings[{{ $loop->index }}][points]" value="{{ $setting->points }}"
                                   min="0"
                                   class="w-28 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-2.5 text-sm text-center outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                   {{ $setting->type === 'reward' ? 'focus:ring-emerald-500 focus:border-emerald-500' : 'focus:ring-red-500 focus:border-red-500' }}">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 dark:text-slate-500">poin</span>
                        </div>
                        <span class="text-xs font-medium px-3 py-1 rounded-full {{ $setting->type === 'reward' ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300' : 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300' }}">
                            {{ $setting->type === 'reward' ? 'Reward' : 'Penalty' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                    <i class="fas fa-info-circle text-indigo-500"></i>
                    <span>Nilai <strong class="text-slate-700 dark:text-slate-300">0</strong> berarti aksi tidak memberikan perubahan poin</span>
                </div>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30 flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

    {{-- ============================================================ --}}
    {{-- INFO CARD --}}
    -- ============================================================ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Tip Reward --}}
        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/20 dark:to-teal-950/20 rounded-2xl border border-emerald-200 dark:border-emerald-800 p-5">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                    <i class="fas fa-star text-sm"></i>
                </div>
                <div>
                    <h4 class="font-bold text-emerald-700 dark:text-emerald-300 text-sm">Reward</h4>
                    <p class="text-xs text-emerald-600/80 dark:text-emerald-400/80 mt-0.5 leading-relaxed">
                        Poin reward diberikan untuk aksi positif seperti booking yang disetujui, check-in tepat waktu, dan menyelesaikan booking.
                    </p>
                </div>
            </div>
        </div>

        {{-- Tip Penalty --}}
        <div class="bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-950/20 dark:to-rose-950/20 rounded-2xl border border-red-200 dark:border-red-800 p-5">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-red-100 dark:bg-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
                    <i class="fas fa-exclamation-triangle text-sm"></i>
                </div>
                <div>
                    <h4 class="font-bold text-red-700 dark:text-red-300 text-sm">Penalty</h4>
                    <p class="text-xs text-red-600/80 dark:text-red-400/80 mt-0.5 leading-relaxed">
                        Poin penalty diberikan untuk aksi negatif seperti no-show, pembatalan mendadak, dan pelanggaran aturan.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ============================================================ --}}
{{-- STYLES --}}
-- ============================================================ --}}
<style>
/* Hapus spinner pada input number */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type=number] {
    -moz-appearance: textfield;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {

    // ================================================================
    // AUTO-DISMISS FLASH MESSAGE
    // ================================================================
    const flashMsg = document.getElementById('flashMsg');
    if (flashMsg) {
        setTimeout(() => {
            flashMsg.style.transition = 'opacity 0.5s';
            flashMsg.style.opacity = '0';
            setTimeout(() => flashMsg.remove(), 500);
        }, 5000);
    }

    // ================================================================
    // VALIDASI - Poin tidak boleh negatif
    // ================================================================
    $('input[type="number"]').on('blur', function() {
        const val = parseInt($(this).val()) || 0;
        if (val < 0) {
            $(this).val(0);
            Swal.fire({
                icon: 'warning',
                title: 'Poin Minimal 0',
                text: 'Nilai poin tidak boleh negatif.',
                confirmButtonColor: '#6366f1',
                timer: 2000,
                timerProgressBar: true
            });
        }
    });

    // ================================================================
    // PREVIEW PERUBAHAN (Optional)
    // ================================================================
    $('input[type="number"]').on('input', function() {
        const $row = $(this).closest('.flex.items-center.justify-between');
        const val = parseInt($(this).val()) || 0;
        const isReward = $row.find('.text-xs.font-medium').text().includes('Reward');

        // Highlight perubahan
        if (val !== parseInt($(this).data('original') || $(this).val())) {
            $(this).addClass('ring-2 ring-amber-400');
        } else {
            $(this).removeClass('ring-2 ring-amber-400');
        }

        // Simpan original value
        if (!$(this).data('original')) {
            $(this).data('original', $(this).val());
        }
    });

    // ================================================================
    // KONFIRMASI SEBELUM SUBMIT
    // ================================================================
    $('form').on('submit', function(e) {
        const $form = $(this);
        let hasChanges = false;

        $form.find('input[type="number"]').each(function() {
            const original = $(this).data('original');
            const current = $(this).val();
            if (original !== undefined && original !== current) {
                hasChanges = true;
            }
        });

        if (hasChanges) {
            // Tidak perlu konfirmasi, langsung submit
            // Tapi tambahkan feedback loading
            const $btn = $form.find('button[type="submit"]');
            $btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);
        }
    });

});
</script>
@endpush

@endsection
