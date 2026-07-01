@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')
@section('page_subtitle', 'Kelola informasi akun dan password')

@section('content')

<div class="max-w-6xl mx-auto font-sora space-y-6">

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 font-semibold text-sm flex items-center gap-3">
        <span class="text-lg">✅</span>
        {{ session('success') }}
        <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-500 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 font-semibold text-sm flex items-center gap-3">
        <span class="text-lg">❌</span>
        {{ session('error') }}
        <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm">
        <ul class="list-disc ml-5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ============================================================ --}}
        {{-- SIDEBAR: PROFIL --}}
        {{-- ============================================================ --}}
        <div class="xl:col-span-1">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 text-center sticky top-24">

                {{-- Avatar --}}
                <div class="relative inline-block">
                    <div class="w-28 h-28 rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 text-white flex items-center justify-center text-5xl font-bold mx-auto shadow-lg ring-4 ring-indigo-100 dark:ring-indigo-900/50">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="absolute bottom-1 right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-800 flex items-center justify-center">
                        <i class="fas fa-check text-[8px] text-white"></i>
                    </div>
                </div>

                <h3 class="text-xl font-extrabold text-slate-800 dark:text-white mt-4">
                    {{ $user->name }}
                </h3>

                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ $user->email }}
                </p>

                <div class="mt-4 flex justify-center gap-2 flex-wrap">
                    <span class="px-3 py-1.5 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-xs font-bold flex items-center gap-1">
                        <i class="fas fa-user-tag text-[10px]"></i>
                        {{ ucfirst($user->role) }}
                    </span>

                    <span class="px-3 py-1.5 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-xs font-bold flex items-center gap-1">
                        <i class="fas fa-circle text-[6px] text-emerald-500"></i>
                        {{ ucfirst($user->status ?? 'active') }}
                    </span>

                    <span class="px-3 py-1.5 rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 text-xs font-bold flex items-center gap-1">
                        <i class="fas fa-star text-[10px]"></i>
                        {{ $user->reputation_points ?? 0 }}
                    </span>
                </div>

                <div class="mt-6 text-left space-y-3 text-sm divide-y divide-slate-100 dark:divide-slate-700">
                    <div class="flex justify-between gap-3 pt-1">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fas fa-id-card text-indigo-400 w-4"></i> NIM
                        </span>
                        <span class="font-semibold text-slate-800 dark:text-white">{{ $user->nim ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between gap-3 pt-3">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fas fa-id-badge text-indigo-400 w-4"></i> NIDN
                        </span>
                        <span class="font-semibold text-slate-800 dark:text-white">{{ $user->nidn ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between gap-3 pt-3">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fas fa-phone text-indigo-400 w-4"></i> No. HP
                        </span>
                        <span class="font-semibold text-slate-800 dark:text-white">{{ $user->phone ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between gap-3 pt-3">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fas fa-university text-indigo-400 w-4"></i> Fakultas
                        </span>
                        <span class="font-semibold text-slate-800 dark:text-white text-right">
                            {{ $user->faculty->name ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between gap-3 pt-3">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-indigo-400 w-4"></i> Bergabung
                        </span>
                        <span class="font-semibold text-slate-800 dark:text-white">
                            {{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '-' }}
                        </span>
                    </div>
                </div>

                {{-- Reputation Progress --}}
                @php
                    $rp = $user->reputation_points ?? 0;
                    $rpColor = $rp >= 80 ? 'emerald' : ($rp >= 50 ? 'blue' : ($rp >= 30 ? 'amber' : 'red'));
                @endphp
                <div class="mt-6 p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50">
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-slate-500 dark:text-slate-400">Reputasi</span>
                        <span class="font-bold text-{{ $rpColor }}-600 dark:text-{{ $rpColor }}-400">{{ $rp }}/100</span>
                    </div>
                    <div class="h-2 bg-slate-200 dark:bg-slate-600 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-{{ $rpColor }}-400 to-{{ $rpColor }}-500 rounded-full transition-all duration-700"
                             style="width: {{ min($rp, 100) }}%"></div>
                    </div>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5 text-center">
                        @if($rp >= 80)
                            <i class="fas fa-crown text-amber-400 mr-1"></i> Trusted User
                        @elseif($rp >= 50)
                            <i class="fas fa-user-check text-emerald-400 mr-1"></i> Normal
                        @elseif($rp >= 30)
                            <i class="fas fa-exclamation-triangle text-amber-400 mr-1"></i> Dibatasi
                        @else
                            <i class="fas fa-ban text-red-400 mr-1"></i> Diblokir
                        @endif
                    </p>
                </div>

            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- MAIN: EDIT PROFIL & PASSWORD --}}
        {{-- ============================================================ --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- EDIT PROFIL --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <i class="fas fa-user-edit text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white">Edit Profil</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Email akun tidak dapat diubah setelah akun dibuat</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('profile.update') }}" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                <i class="fas fa-user text-indigo-400 mr-1.5"></i> Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                <i class="fas fa-envelope text-indigo-400 mr-1.5"></i> Email / Gmail
                            </label>
                            <input type="email"
                                   value="{{ $user->email }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 cursor-not-allowed"
                                   readonly>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i> Email tidak bisa diubah
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                <i class="fas fa-id-card text-indigo-400 mr-1.5"></i> NIM
                            </label>
                            <input type="text"
                                   name="nim"
                                   value="{{ old('nim', $user->nim) }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                   placeholder="Masukkan NIM">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                <i class="fas fa-id-badge text-indigo-400 mr-1.5"></i> NIDN
                            </label>
                            <input type="text"
                                   name="nidn"
                                   value="{{ old('nidn', $user->nidn) }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                   placeholder="Masukkan NIDN">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                <i class="fas fa-phone text-indigo-400 mr-1.5"></i> No. HP
                            </label>
                            <input type="text"
                                   name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                   placeholder="Contoh: 081234567890">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                <i class="fas fa-university text-indigo-400 mr-1.5"></i> Fakultas
                            </label>
                            <select name="faculty_id"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="">— Tidak ada —</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}"
                                        {{ old('faculty_id', $user->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                        {{ $faculty->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2 flex justify-end pt-2 border-t border-slate-200 dark:border-slate-700">
                            <button type="submit"
                                    class="px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold transition shadow-md hover:shadow-lg flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- GANTI PASSWORD --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-950/30 dark:to-orange-950/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                            <i class="fas fa-lock text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white">Ganti Password</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Masukkan password lama dan password baru</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('profile.password') }}" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @csrf
                        @method('PUT')

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                <i class="fas fa-key text-amber-400 mr-1.5"></i> Password Lama <span class="text-red-500">*</span>
                            </label>
                            <input type="password"
                                   name="current_password"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                                   placeholder="Masukkan password lama" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                <i class="fas fa-key text-amber-400 mr-1.5"></i> Password Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="password"
                                   name="password"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                                   placeholder="Minimal 6 karakter" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                <i class="fas fa-check-double text-amber-400 mr-1.5"></i> Konfirmasi Password Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                                   placeholder="Ulangi password baru" required>
                        </div>

                        <div class="md:col-span-2 flex justify-end pt-2 border-t border-slate-200 dark:border-slate-700">
                            <button type="submit"
                                    class="px-6 py-3 rounded-xl bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-semibold transition shadow-md hover:shadow-lg flex items-center gap-2">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection
