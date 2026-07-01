@extends('layouts.app')

@section('title', 'Manajemen Banding - Smart Classroom')
@section('page_title', 'Manajemen Banding')
@section('page_subtitle', 'Kelola banding dari user yang dibanned')

@section('content')

<div class="max-w-7xl mx-auto font-sora space-y-6">

    {{-- ============================================================ --}}
    {{-- FLASH MESSAGES --}}
    {{-- ============================================================ --}}
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

    {{-- ============================================================ --}}
    {{-- STATISTICS CARDS --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Banding</p>
                    <p class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition">
                    <i class="fas fa-clipboard-list text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">total pengajuan banding</span>
            </div>
        </div>

        {{-- Pending --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Menunggu</p>
                    <p class="text-2xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
            @if($stats['pending'] > 0)
            <div class="mt-2">
                <span class="text-xs text-amber-600 dark:text-amber-400 animate-pulse inline-flex items-center gap-1">
                    <i class="fas fa-circle text-[6px]"></i> perlu ditinjau
                </span>
            </div>
            @endif
        </div>

        {{-- Approved --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Disetujui</p>
                    <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['approved'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">user di-unban</span>
            </div>
        </div>

        {{-- Rejected --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Ditolak</p>
                    <p class="text-2xl font-extrabold text-red-600 dark:text-red-400 mt-1">{{ $stats['rejected'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400 group-hover:scale-110 transition">
                    <i class="fas fa-times-circle text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-red-600 dark:text-red-400 font-medium">user tetap banned</span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- APPEALS TABLE --}}
    {{-- ============================================================ --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <i class="fas fa-list-ul text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white">Daftar Banding</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Kelola banding dari user yang dibanned</p>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-4 font-semibold">User</th>
                        <th class="text-left px-6 py-4 font-semibold">Pesan Banding</th>
                        <th class="text-left px-6 py-4 font-semibold">Status</th>
                        <th class="text-left px-6 py-4 font-semibold">Tanggal</th>
                        <th class="text-center px-6 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($appeals as $appeal)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                        {{-- User --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($appeal->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 dark:text-white">{{ $appeal->user->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $appeal->user->email }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                                        <span class="inline-flex items-center gap-1">
                                            <i class="fas fa-user-tag text-[10px]"></i>
                                            {{ ucfirst($appeal->user->role) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- Message --}}
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                <p class="text-slate-700 dark:text-slate-300 line-clamp-2 leading-relaxed">
                                    {{ $appeal->message }}
                                </p>
                                @if(strlen($appeal->message) > 100)
                                <span class="text-xs text-slate-400 dark:text-slate-500">...</span>
                                @endif
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            @if($appeal->status == 'pending')
                                <span class="px-3 py-1.5 rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 text-xs font-bold inline-flex items-center gap-1.5">
                                    <i class="fas fa-clock"></i> Menunggu
                                </span>
                            @elseif($appeal->status == 'approved')
                                <span class="px-3 py-1.5 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-xs font-bold inline-flex items-center gap-1.5">
                                    <i class="fas fa-check-circle"></i> Disetujui
                                </span>
                            @else
                                <span class="px-3 py-1.5 rounded-full bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 text-xs font-bold inline-flex items-center gap-1.5">
                                    <i class="fas fa-times-circle"></i> Ditolak
                                </span>
                            @endif

                            @if($appeal->status != 'pending' && $appeal->response)
                            <button type="button" class="block mt-1 text-xs text-indigo-600 dark:text-indigo-400 hover:underline" onclick="showResponse('{{ addslashes($appeal->response) }}')">
                                <i class="fas fa-comment"></i> Lihat respon
                            </button>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td class="px-6 py-4">
                            <p class="text-slate-600 dark:text-slate-400 text-xs font-medium">
                                {{ $appeal->created_at->translatedFormat('d M Y') }}
                            </p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">
                                {{ $appeal->created_at->translatedFormat('H:i') }}
                            </p>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-1.5 flex-wrap">
                                <a href="{{ route('admin.appeals.show', $appeal->id) }}"
                                   class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 text-xs font-semibold transition flex items-center gap-1"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i> Detail
                                </a>

                                @if($appeal->status == 'pending')
                                <form method="POST" action="{{ route('admin.appeals.approve', $appeal->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 hover:bg-emerald-200 dark:hover:bg-emerald-800 text-emerald-700 dark:text-emerald-300 text-xs font-semibold transition flex items-center gap-1"
                                            onclick="return confirm('Setujui banding dari {{ $appeal->user->name }}? User akan di-unban.')"
                                            title="Setujui Banding">
                                        <i class="fas fa-check"></i> Setujui
                                    </button>
                                </form>

                                <button onclick="showRejectModal('{{ $appeal->id }}', '{{ addslashes($appeal->user->name) }}')"
                                        class="px-3 py-1.5 rounded-lg bg-red-100 dark:bg-red-900/50 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 text-xs font-semibold transition flex items-center gap-1"
                                        title="Tolak Banding">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-4xl mb-4">
                                    <i class="fas fa-clipboard-list text-slate-400 dark:text-slate-500"></i>
                                </div>
                                <p class="font-semibold text-slate-600 dark:text-slate-400">Belum ada banding</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Tidak ada pengajuan banding dari user</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($appeals->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
            {{ $appeals->links() }}
        </div>
        @endif
    </div>

</div>

{{-- ============================================================ --}}
{{-- MODAL REJECT --}}
{{-- ============================================================ --}}
<div id="rejectModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md animate-fade-in-up">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-950/30 dark:to-rose-950/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-white">Tolak Banding</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400" id="rejectUserInfo">Berikan alasan penolakan</p>
                </div>
            </div>
        </div>

        <form id="rejectForm" method="POST" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="response" id="rejectReason" rows="4"
                    class="mt-2 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition resize-none"
                    placeholder="Jelaskan alasan penolakan banding..."></textarea>
                <p class="text-xs text-red-500 mt-1 hidden" id="reasonError">
                    <i class="fas fa-exclamation-circle mr-1"></i> Alasan wajib diisi.
                </p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()"
                    class="flex-1 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition shadow-sm flex items-center justify-center gap-2">
                    <i class="fas fa-times"></i> Tolak Banding
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL RESPONSE --}}
{{-- ============================================================ --}}
<div id="responseModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md animate-fade-in-up">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <i class="fas fa-comment text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 dark:text-white">Respon Admin</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Alasan keputusan banding</p>
                    </div>
                </div>
                <button onclick="closeResponseModal()" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 font-bold transition text-slate-500 dark:text-slate-400">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="p-6">
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/30 border border-slate-200 dark:border-slate-700">
                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed" id="responseText"></p>
            </div>
            <div class="mt-4 flex justify-end">
                <button onclick="closeResponseModal()"
                    class="px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-semibold text-sm transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- STYLES --}}
{{-- ============================================================ --}}
<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
.animate-fade-in-up {
    animation: fadeInUp 0.25s ease-out forwards;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
// ================================================================
// REJECT MODAL
// ================================================================
function showRejectModal(id, name) {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
    document.getElementById('rejectForm').action = '{{ route("admin.appeals.reject", ":id") }}'.replace(':id', id);
    document.getElementById('rejectUserInfo').textContent = 'Tolak banding dari ' + name;
    document.getElementById('rejectReason').value = '';
    document.getElementById('reasonError').classList.add('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
    document.body.style.overflow = '';
}

// Click outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});

// Validate reject form
document.getElementById('rejectForm').addEventListener('submit', function(e) {
    const reason = document.getElementById('rejectReason').value.trim();
    if (!reason) {
        e.preventDefault();
        document.getElementById('reasonError').classList.remove('hidden');
        document.getElementById('rejectReason').focus();
    }
});

// ================================================================
// RESPONSE MODAL
// ================================================================
function showResponse(response) {
    document.getElementById('responseText').textContent = response;
    document.getElementById('responseModal').classList.remove('hidden');
    document.getElementById('responseModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeResponseModal() {
    document.getElementById('responseModal').classList.add('hidden');
    document.getElementById('responseModal').classList.remove('flex');
    document.body.style.overflow = '';
}

document.getElementById('responseModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeResponseModal();
    }
});

// ================================================================
// ESC KEY
// ================================================================
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRejectModal();
        closeResponseModal();
    }
});

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
</script>

@endsection
