@extends('layouts.app')

@section('title', 'Manajemen User - Smart Classroom')
@section('page_title', 'Manajemen User')
@section('page_subtitle', 'Kelola akun super admin, admin, dosen, dan mahasiswa')

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
        {{-- Total Users --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total User</p>
                    <p class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $totalUsers ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition">
                    <i class="fas fa-users text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">Seluruh akun terdaftar</span>
            </div>
        </div>

        {{-- Admin --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Admin</p>
                    <p class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ $totalAdmin ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition">
                    <i class="fas fa-user-shield text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">Validator ruangan</span>
            </div>
        </div>

        {{-- Dosen --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Dosen</p>
                    <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $totalDosen ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition">
                    <i class="fas fa-chalkboard-user text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">Pengajar & pemohon</span>
            </div>
        </div>

        {{-- Mahasiswa --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Mahasiswa</p>
                    <p class="text-2xl font-extrabold text-purple-600 dark:text-purple-400 mt-1">{{ $totalMahasiswa ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition">
                    <i class="fas fa-graduation-cap text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">Pemohon ruangan</span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- USER TABLE --}}
    {{-- ============================================================ --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <i class="fas fa-list-ul text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Daftar User</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Filter berdasarkan nama, email, role, dan status akun
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.users.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30">
                    <i class="fas fa-user-plus"></i> Tambah User
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text"
                           id="userSearch"
                           placeholder="Cari nama, email, NIM, NIDN..."
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>

                <select id="userRoleFilter" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition appearance-none">
                    <option value="">Semua Role</option>
                    <option value="superadmin">👑 Super Admin</option>
                    <option value="admin">🛡️ Admin</option>
                    <option value="dosen">📚 Dosen</option>
                    <option value="mahasiswa">🎓 Mahasiswa</option>
                    <option value="organisasi">🏛️ Organisasi</option>
                </select>

                <select id="userStatusFilter" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition appearance-none">
                    <option value="">Semua Status</option>
                    <option value="pending">⏳ Pending</option>
                    <option value="active">✅ Aktif</option>
                    <option value="inactive">⛔ Nonaktif</option>
                    <option value="banned">🚫 Banned</option>
                </select>

                <div class="flex gap-2">
                    <button id="userFilterBtn" class="flex-1 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition flex items-center justify-center gap-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button id="userResetBtn" class="px-4 py-2.5 rounded-xl bg-slate-200 dark:bg-slate-600 hover:bg-slate-300 dark:hover:bg-slate-500 text-slate-700 dark:text-slate-300 text-sm font-semibold transition flex items-center justify-center gap-2">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-4 font-semibold">User</th>
                        <th class="text-left px-6 py-4 font-semibold">NIM / NIDN</th>
                        <th class="text-left px-6 py-4 font-semibold">Fakultas</th>
                        <th class="text-left px-6 py-4 font-semibold">Role</th>
                        <th class="text-left px-6 py-4 font-semibold">Status</th>
                        <th class="text-left px-6 py-4 font-semibold">Reputasi</th>
                        <th class="text-center px-6 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-700" id="userTableBody">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition user-row"
                        data-name="{{ strtolower($user->name) }}"
                        data-email="{{ strtolower($user->email) }}"
                        data-nim="{{ strtolower($user->nim ?? '') }}"
                        data-nidn="{{ strtolower($user->nidn ?? '') }}"
                        data-role="{{ $user->role }}"
                        data-status="{{ $user->status }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full {{ $user->status === 'banned' ? 'bg-red-500' : 'bg-indigo-600' }} text-white flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 dark:text-white {{ $user->status === 'banned' ? 'line-through text-red-500 dark:text-red-400' : '' }}">
                                        {{ $user->name }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-mono text-xs">
                            {{ $user->nim ?? $user->nidn ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                            {{ $user->faculty->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $roleColors = [
                                    'superadmin' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300',
                                    'admin' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                                    'dosen' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
                                    'mahasiswa' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
                                    'organisasi' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/50 dark:text-cyan-300',
                                ];
                                $roleIcon = [
                                    'superadmin' => '👑',
                                    'admin' => '🛡️',
                                    'dosen' => '📚',
                                    'mahasiswa' => '🎓',
                                    'organisasi' => '🏛️',
                                ];
                                $roleClass = $roleColors[$user->role] ?? 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300';
                                $icon = $roleIcon[$user->role] ?? '👤';
                            @endphp

                            <span class="px-3 py-1 rounded-full {{ $roleClass }} text-xs font-bold inline-flex items-center gap-1.5">
                                <span>{{ $icon }}</span> {{ ucfirst($user->role) }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $statusMap = [
                                    'active' => ['bg' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300', 'icon' => 'fa-check-circle'],
                                    'pending' => ['bg' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300', 'icon' => 'fa-clock'],
                                    'banned' => ['bg' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300', 'icon' => 'fa-ban'],
                                    'inactive' => ['bg' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300', 'icon' => 'fa-user-slash'],
                                ];
                                $statusStyle = $statusMap[$user->status] ?? $statusMap['inactive'];
                            @endphp

                            <span class="px-3 py-1 rounded-full {{ $statusStyle['bg'] }} text-xs font-bold inline-flex items-center gap-1.5">
                                <i class="fas {{ $statusStyle['icon'] }}"></i> {{ ucfirst($user->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $rp = $user->reputation_points ?? 0;
                                $rpColor = $rp >= 80 ? 'text-emerald-600 dark:text-emerald-400' : ($rp >= 50 ? 'text-blue-600 dark:text-blue-400' : ($rp >= 30 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400'));
                            @endphp
                            <span class="font-bold {{ $rpColor }}">
                                {{ $rp }}
                            </span>
                            @if($rp >= 80)
                            <span class="text-xs text-emerald-500">🌟</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-1.5 flex-wrap">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 text-xs font-semibold transition flex items-center gap-1">
                                    <i class="fas fa-eye"></i> Detail
                                </a>

                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="px-3 py-1.5 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 hover:bg-indigo-200 dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-300 text-xs font-semibold transition flex items-center gap-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-lg bg-red-100 dark:bg-red-900/50 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 text-xs font-semibold transition flex items-center gap-1">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-4xl mb-4">
                                    <i class="fas fa-user-slash text-slate-400 dark:text-slate-500"></i>
                                </div>
                                <p class="font-semibold text-slate-600 dark:text-slate-400">Belum ada data user</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Mulai dengan menambahkan user baru</p>
                                <a href="{{ route('admin.users.create') }}" class="mt-4 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30 inline-flex items-center gap-2">
                                    <i class="fas fa-user-plus"></i> Tambah User
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
$(document).ready(function() {

    // ──────────────────────────────────────────────────────────────
    // FILTER USERS
    // ──────────────────────────────────────────────────────────────
    function filterUsers() {
        const keyword = $('#userSearch').val().toLowerCase();
        const role = $('#userRoleFilter').val();
        const status = $('#userStatusFilter').val();

        let visibleCount = 0;

        $('.user-row').each(function() {
            const $row = $(this);
            const name = $row.data('name') || '';
            const email = $row.data('email') || '';
            const nim = $row.data('nim') || '';
            const nidn = $row.data('nidn') || '';
            const rowRole = $row.data('role') || '';
            const rowStatus = $row.data('status') || '';

            const matchSearch = keyword === '' ||
                name.includes(keyword) ||
                email.includes(keyword) ||
                nim.includes(keyword) ||
                nidn.includes(keyword);

            const matchRole = role === '' || rowRole === role;
            const matchStatus = status === '' || rowStatus === status;

            if (matchSearch && matchRole && matchStatus) {
                $row.show();
                visibleCount++;
            } else {
                $row.hide();
            }
        });

        // Show/hide empty state
        const $emptyRow = $('#userTableBody tr:not(.user-row)');
        if (visibleCount === 0 && $('.user-row').length > 0) {
            if ($('#no-results-row').length === 0) {
                $('#userTableBody').append(`
                    <tr id="no-results-row">
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-3xl mb-3">
                                    🔍
                                </div>
                                <p class="font-semibold text-slate-600 dark:text-slate-400">Tidak ada user yang sesuai</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Coba ubah filter pencarian</p>
                            </div>
                        </td>
                    </tr>
                `);
            }
        } else {
            $('#no-results-row').remove();
        }
    }

    // ──────────────────────────────────────────────────────────────
    // EVENT LISTENERS
    // ──────────────────────────────────────────────────────────────
    $('#userSearch').on('keyup', filterUsers);
    $('#userRoleFilter').on('change', filterUsers);
    $('#userStatusFilter').on('change', filterUsers);
    $('#userFilterBtn').on('click', filterUsers);

    $('#userResetBtn').on('click', function() {
        $('#userSearch').val('');
        $('#userRoleFilter').val('');
        $('#userStatusFilter').val('');
        filterUsers();
    });

    // ──────────────────────────────────────────────────────────────
    // AUTO-DISMISS FLASH MESSAGE
    // ──────────────────────────────────────────────────────────────
    const flashMsg = $('#flashMsg');
    if (flashMsg.length) {
        setTimeout(() => {
            flashMsg.fadeOut(400, function() {
                $(this).remove();
            });
        }, 4000);
    }

    // ──────────────────────────────────────────────────────────────
    // INITIAL FILTER
    // ──────────────────────────────────────────────────────────────
    filterUsers();

});
</script>
@endpush

@endsection
