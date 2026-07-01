@extends('layouts.app')

@section('title', 'Level Reputasi - Smart Classroom')
@section('page_title', 'Level Reputasi')
@section('page_subtitle', 'Kelola level dan batas poin reputasi user')

@section('content')

<div class="max-w-5xl mx-auto font-sora space-y-6">

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

    <div class="bg-gradient-to-br from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800 rounded-2xl p-6 md:p-8 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-3xl">
                    🏆
                </div>
                <div>
                    <p class="text-xs font-semibold text-white/70 uppercase tracking-widest">Manajemen Reputasi</p>
                    <h1 class="text-2xl md:text-3xl font-bold mt-0.5">Level Reputasi</h1>
                    <p class="text-sm text-white/80 mt-1">Kelola level dan batas poin reputasi user</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 rounded-xl bg-white/20 backdrop-blur text-sm font-semibold border border-white/10 flex items-center gap-2">
                    <i class="fas fa-layer-group"></i> {{ $levels->count() }} Level
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($levels as $level)
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden hover:shadow-md transition group">
            {{-- Header Card --}}
            <div class="px-6 py-4 flex items-center justify-between" style="border-left: 4px solid {{ $level->color ?? '#6366f1' }}">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl" style="background-color: {{ $level->color ?? '#6366f1' }}20">
                        @if($level->name == 'Trusted User')
                            🌟
                        @elseif($level->name == 'Normal')
                            ⭐
                        @elseif($level->name == 'Dibatasi')
                            ⚠️
                        @elseif($level->name == 'Diblokir')
                            🚫
                        @else
                            🏅
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-lg">{{ $level->name }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Poin: {{ $level->min_points }} - {{ $level->max_points ?? '∞' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: {{ $level->color ?? '#6366f1' }}20; color: {{ $level->color ?? '#6366f1' }}">
                        <i class="fas fa-circle" style="color: {{ $level->color ?? '#6366f1' }}"></i>
                        {{ $level->min_points }}-{{ $level->max_points ?? '∞' }}
                    </span>
                </div>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('admin.reputation.levels.update', $level->id) }}" class="p-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    {{-- Nama Level --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            <i class="fas fa-tag text-indigo-500 mr-1"></i> Nama Level
                        </label>
                        <input type="text" name="name" value="{{ $level->name }}"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               required>
                    </div>

                    {{-- Min Poin --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            <i class="fas fa-arrow-up text-emerald-500 mr-1"></i> Min Poin
                        </label>
                        <input type="number" name="min_points" value="{{ $level->min_points }}"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               required min="0">
                    </div>

                    {{-- Max Poin --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            <i class="fas fa-arrow-down text-red-500 mr-1"></i> Max Poin
                        </label>
                        <input type="number" name="max_points" value="{{ $level->max_points }}"
                               placeholder="∞ (Unlimited)"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>

                    {{-- Warna + Action --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            <i class="fas fa-palette text-purple-500 mr-1"></i> Warna
                        </label>
                        <div class="flex gap-2">
                            <input type="color" name="color" value="{{ $level->color ?? '#6366f1' }}"
                                   class="h-10 w-14 rounded-xl border border-slate-200 dark:border-slate-600 cursor-pointer">
                            <button type="submit"
                                    class="flex-1 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition flex items-center justify-center gap-1.5 shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30">
                                <i class="fas fa-save text-xs"></i> Update
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endforeach
    </div>

    <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-700/30 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0">
                <i class="fas fa-info-circle text-lg"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 dark:text-white text-sm">Tentang Level Reputasi</h4>
                <div class="text-sm text-slate-600 dark:text-slate-400 mt-1 leading-relaxed space-y-1">
                    <p>• <strong>Min Poin</strong>: Batas bawah poin untuk level ini</p>
                    <p>• <strong>Max Poin</strong>: Batas atas poin untuk level ini (kosongkan jika tidak ada batas)</p>
                    <p>• <strong>Warna</strong>: Digunakan untuk visualisasi level di dashboard user</p>
                    <p>• Level akan ditentukan berdasarkan poin reputasi user secara otomatis</p>
                </div>
                <div class="mt-3 flex flex-wrap gap-3">
                    @foreach($levels as $level)
                    <span class="px-3 py-1 rounded-full text-xs font-medium" style="background-color: {{ $level->color ?? '#6366f1' }}20; color: {{ $level->color ?? '#6366f1' }}">
                        {{ $level->name }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>


<style>
/* Smooth transition for color inputs */
input[type="color"]::-webkit-color-swatch-wrapper {
    padding: 2px;
}
input[type="color"]::-webkit-color-swatch {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}
.dark input[type="color"]::-webkit-color-swatch {
    border-color: #475569;
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
    // VALIDASI FORM - Min Points tidak boleh lebih besar dari Max Points
    // ================================================================
    $('form').on('submit', function(e) {
        const $form = $(this);
        const minPoints = parseInt($form.find('input[name="min_points"]').val()) || 0;
        const maxPoints = $form.find('input[name="max_points"]').val();

        if (maxPoints !== '' && parseInt(maxPoints) < minPoints) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Range',
                text: 'Min Points tidak boleh lebih besar dari Max Points!',
                confirmButtonColor: '#6366f1'
            });
        }
    });

    // ================================================================
    // PREVIEW COLOR
    // ================================================================
    $('input[type="color"]').on('input', function() {
        const $card = $(this).closest('.group');
        const color = $(this).val();
        $card.find('.border-l-4').css('border-left-color', color);
        $card.find('.rounded-xl.flex.items-center.justify-center.text-2xl').css('background-color', color + '20');
        $card.find('.px-3.py-1.rounded-full').css('background-color', color + '20').css('color', color);
    });

});
</script>
@endpush

@endsection
