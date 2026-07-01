@extends('layouts.app')

@section('title', 'Detail Banding')
@section('page_title', 'Detail Banding')
@section('page_subtitle', 'Informasi lengkap banding user')

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

        {{-- ===== NAV BACK ===== --}}
        <div class="mb-6">
            <a href="{{ route('admin.appeals.index') }}"
               class="text-sm text-slate-500 hover:text-slate-700 transition flex items-center gap-2">
                ← Kembali ke Daftar Banding
            </a>
        </div>

        {{-- ===== HEADER ===== --}}
        <div class="flex items-start justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-xl font-bold text-slate-800">Detail Banding</h3>
                <p class="text-sm text-slate-500">
                    Diajukan oleh: <strong>{{ $appeal->user->name }}</strong> ({{ $appeal->user->email }})
                </p>
            </div>
            <div>
                @if($appeal->status == 'pending')
                    <span class="px-4 py-2 rounded-full bg-amber-100 text-amber-700 text-sm font-bold">⏳ Menunggu</span>
                @elseif($appeal->status == 'approved')
                    <span class="px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-sm font-bold">✅ Disetujui</span>
                @else
                    <span class="px-4 py-2 rounded-full bg-red-100 text-red-700 text-sm font-bold">❌ Ditolak</span>
                @endif
            </div>
        </div>

        {{-- ===== INFO USER ===== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <p class="text-xs text-slate-400">Nama</p>
                <p class="font-semibold text-slate-800">{{ $appeal->user->name }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Email</p>
                <p class="font-semibold text-slate-800">{{ $appeal->user->email }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Role</p>
                <p class="font-semibold text-slate-800">{{ ucfirst($appeal->user->role) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Reputasi Saat Ini</p>
                <p class="font-semibold text-slate-800">{{ $appeal->user->reputation_points ?? 0 }} poin</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Tanggal Diajukan</p>
                <p class="font-semibold text-slate-800">{{ $appeal->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
            @if($appeal->processed_at)
            <div>
                <p class="text-xs text-slate-400">Tanggal Diproses</p>
                <p class="font-semibold text-slate-800">{{ $appeal->processed_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
            @endif
        </div>

        {{-- ===== PESAN BANDING ===== --}}
        <div class="mt-6">
            <p class="text-xs text-slate-400 mb-2">Pesan Banding</p>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <p class="text-slate-700 whitespace-pre-wrap">{{ $appeal->message }}</p>
            </div>
        </div>

        {{-- ===== RESPON ADMIN ===== --}}
        @if($appeal->admin_response)
        <div class="mt-4">
            <p class="text-xs text-slate-400 mb-2">Respon Admin</p>
            <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                <p class="text-slate-700 whitespace-pre-wrap">{{ $appeal->admin_response }}</p>
            </div>
            @if($appeal->processedBy)
            <p class="text-xs text-slate-400 mt-2">
                Diproses oleh: {{ $appeal->processedBy->name }}
            </p>
            @endif
        </div>
        @endif

        {{-- ===== AKSI ADMIN (Jika masih pending) ===== --}}
        @if($appeal->status == 'pending')
        <div class="mt-6 pt-4 border-t border-slate-200">
            <div class="flex flex-wrap gap-3">
                <form method="POST" action="{{ route('admin.appeals.approve', $appeal->id) }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition shadow-sm">
                        ✅ Setujui Banding
                    </button>
                </form>

                <button onclick="showRejectModal()"
                        class="px-5 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition shadow-sm">
                    ❌ Tolak Banding
                </button>

                <a href="{{ route('admin.appeals.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition">
                    Kembali
                </a>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- ===== MODAL REJECT ===== --}}
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h4 class="font-bold text-lg mb-2">Tolak Banding</h4>
        <p class="text-sm text-slate-500 mb-4">Berikan alasan penolakan banding ini.</p>
        <form id="rejectForm" method="POST" action="{{ route('admin.appeals.reject', $appeal->id) }}">
            @csrf
            <textarea name="response" rows="4"
                class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-red-500"
                placeholder="Alasan penolakan..." required></textarea>
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="closeRejectModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold text-sm transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold text-sm transition">
                    Ya, Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
    document.body.style.overflow = '';
}

document.addEventListener('click', function(e) {
    if (e.target.id === 'rejectModal') {
        closeRejectModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRejectModal();
    }
});
</script>

@endsection
