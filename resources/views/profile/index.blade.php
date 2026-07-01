@extends('layouts.app')

@section('title', 'Profil Saya - Smart Classroom')
@section('page_title', 'Profil Saya')
@section('page_subtitle', 'Kelola informasi akun dan password')

@section('content')

<div class="max-w-7xl mx-auto font-sora space-y-6">

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
                <p class="font-semibold">Terdapat kesalahan:</p>
                <ul class="list-disc ml-5 mt-1 text-sm font-normal space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-1">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 text-center sticky top-24">

                {{-- Avatar --}}
                <div class="relative inline-block group">
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-indigo-500 via-purple-500 to-blue-600 text-white flex items-center justify-center text-5xl font-bold mx-auto shadow-lg ring-4 ring-indigo-100 dark:ring-indigo-900/50 transition group-hover:scale-105">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="absolute bottom-2 right-2 w-6 h-6 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-800 flex items-center justify-center shadow-sm">
                        <i class="fas fa-check text-[8px] text-white"></i>
                    </div>
                    {{-- Hover tooltip --}}
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-slate-800 text-white text-[10px] px-2 py-0.5 rounded-full whitespace-nowrap">
                        <i class="fas fa-camera mr-1"></i> Ubah Foto
                    </div>
                </div>

                <h3 class="text-xl font-extrabold text-slate-800 dark:text-white mt-4">
                    {{ $user->name }}
                </h3>

                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ $user->email }}
                </p>

                {{-- Badges --}}
                <div class="mt-4 flex justify-center gap-2 flex-wrap">
                    <span class="px-3 py-1.5 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-xs font-bold inline-flex items-center gap-1.5">
                        <i class="fas fa-user-tag text-[10px]"></i>
                        {{ ucfirst($user->role) }}
                    </span>

                    <span class="px-3 py-1.5 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-xs font-bold inline-flex items-center gap-1.5">
                        <i class="fas fa-circle text-[6px] text-emerald-500"></i>
                        {{ ucfirst($user->status ?? 'active') }}
                    </span>

                    <span class="px-3 py-1.5 rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 text-xs font-bold inline-flex items-center gap-1.5">
                        <i class="fas fa-star text-[10px]"></i>
                        {{ $user->reputation_points ?? 0 }}
                    </span>
                </div>

                {{-- Info Detail --}}
                <div class="mt-6 text-left space-y-2 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fas fa-id-card text-indigo-400 w-4"></i> NIM
                        </span>
                        <span class="font-semibold text-slate-800 dark:text-white font-mono">{{ $user->nim ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fas fa-id-badge text-indigo-400 w-4"></i> NIDN
                        </span>
                        <span class="font-semibold text-slate-800 dark:text-white font-mono">{{ $user->nidn ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fas fa-phone text-indigo-400 w-4"></i> No. HP
                        </span>
                        <span class="font-semibold text-slate-800 dark:text-white">{{ $user->phone ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fas fa-university text-indigo-400 w-4"></i> Fakultas
                        </span>
                        <span class="font-semibold text-slate-800 dark:text-white text-right max-w-[140px] truncate">
                            {{ $user->faculty->name ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2">
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
                    $rpLabel = $rp >= 80 ? '🌟 Trusted User' : ($rp >= 50 ? '⭐ Normal' : ($rp >= 30 ? '⚠️ Dibatasi' : '🚫 Diblokir'));
                @endphp
                <div class="mt-6 p-4 rounded-xl bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-700/30 dark:to-slate-700/50 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Reputasi</span>
                        <span class="text-xs font-bold text-{{ $rpColor }}-600 dark:text-{{ $rpColor }}-400">{{ $rp }}/100</span>
                    </div>
                    <div class="h-2.5 bg-slate-200 dark:bg-slate-600 rounded-full overflow-hidden relative">
                        <div class="h-full bg-gradient-to-r from-{{ $rpColor }}-400 to-{{ $rpColor }}-500 rounded-full transition-all duration-1000"
                             style="width: {{ min($rp, 100) }}%"></div>
                        {{-- Indicator dot --}}
                        <div class="absolute top-1/2 -translate-y-1/2 w-4 h-4 rounded-full bg-white dark:bg-slate-800 border-2 border-{{ $rpColor }}-500 shadow-md"
                             style="left: calc({{ min($rp, 100) }}% - 8px);"></div>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 text-center font-medium">
                        {{ $rpLabel }}
                    </p>
                </div>

                {{-- Quick Actions --}}
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <a href="{{ route('bookings.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 text-xs font-semibold transition flex items-center justify-center gap-1.5">
                        <i class="fas fa-calendar-alt"></i> Booking
                    </a>
                    <a href="{{ route('reputation.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 text-xs font-semibold transition flex items-center justify-center gap-1.5">
                        <i class="fas fa-star"></i> Reputasi
                    </a>
                </div>

            </div>
        </div>

        <div class="xl:col-span-2 space-y-6">

            {{-- EDIT PROFIL --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                <i class="fas fa-user-edit text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-white">Edit Profil</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Perbarui informasi pribadi Anda</p>
                            </div>
                        </div>
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-700 px-3 py-1 rounded-full">
                            <i class="fas fa-lock mr-1"></i> Email tidak dapat diubah
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('profile.update') }}" id="profileForm" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                <i class="fas fa-user text-indigo-400 mr-1.5"></i> Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                                   required>
                        </div>

                        {{-- Email (Readonly) --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                <i class="fas fa-envelope text-indigo-400 mr-1.5"></i> Email
                            </label>
                            <div class="relative">
                                <input type="email"
                                       value="{{ $user->email }}"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-sm cursor-not-allowed"
                                       readonly>
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-emerald-500">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">
                                <i class="fas fa-info-circle mr-1 text-[10px]"></i> Email tidak dapat diubah
                            </p>
                        </div>

                        {{-- NIM --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                <i class="fas fa-id-card text-indigo-400 mr-1.5"></i> NIM
                            </label>
                            <input type="text"
                                   name="nim"
                                   value="{{ old('nim', $user->nim) }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                                   placeholder="Masukkan NIM">
                        </div>

                        {{-- NIDN --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                <i class="fas fa-id-badge text-indigo-400 mr-1.5"></i> NIDN
                            </label>
                            <input type="text"
                                   name="nidn"
                                   value="{{ old('nidn', $user->nidn) }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                                   placeholder="Masukkan NIDN">
                        </div>

                        {{-- No HP --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                <i class="fas fa-phone text-indigo-400 mr-1.5"></i> No. HP
                            </label>
                            <input type="text"
                                   name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                                   placeholder="Contoh: 081234567890">
                        </div>

                        {{-- Fakultas --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                <i class="fas fa-university text-indigo-400 mr-1.5"></i> Fakultas
                            </label>
                            <select name="faculty_id"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition appearance-none">
                                <option value="">— Tidak ada —</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}"
                                        {{ old('faculty_id', $user->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                        {{ $faculty->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Submit --}}
                        <div class="md:col-span-2 flex justify-end pt-4 border-t border-slate-200 dark:border-slate-700">
                            <button type="submit"
                                    class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold text-sm transition shadow-md hover:shadow-lg flex items-center gap-2">
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
                    <form method="POST" action="{{ route('profile.password') }}" id="passwordForm" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @csrf
                        @method('PUT')

                        {{-- Password Lama --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                <i class="fas fa-key text-amber-400 mr-1.5"></i> Password Lama <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password"
                                       id="current_password"
                                       name="current_password"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition pr-12"
                                       placeholder="Masukkan password lama" required>
                                <button type="button"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition"
                                        onclick="togglePassword('current_password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Password Baru --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                <i class="fas fa-key text-amber-400 mr-1.5"></i> Password Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password"
                                       id="new_password"
                                       name="password"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition pr-12"
                                       placeholder="Minimal 6 karakter" required>
                                <button type="button"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition"
                                        onclick="togglePassword('new_password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="mt-1.5">
                                <div class="h-1 rounded-full bg-slate-200 dark:bg-slate-600 overflow-hidden" id="strengthBar">
                                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-300" style="width:0%" id="strengthIndicator"></div>
                                </div>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5" id="strengthText">
                                    <i class="fas fa-info-circle text-[10px]"></i> Minimal 6 karakter
                                </p>
                            </div>
                        </div>

                        {{-- Konfirmasi --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                <i class="fas fa-check-double text-amber-400 mr-1.5"></i> Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition pr-12"
                                       placeholder="Ulangi password baru" required>
                                <button type="button"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition"
                                        onclick="togglePassword('password_confirmation', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5" id="confirmMatch">
                                <i class="fas fa-info-circle text-[10px]"></i> Masukkan ulang password baru
                            </p>
                        </div>

                        {{-- Submit --}}
                        <div class="md:col-span-2 flex justify-end pt-4 border-t border-slate-200 dark:border-slate-700">
                            <button type="submit"
                                    class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-semibold text-sm transition shadow-md hover:shadow-lg flex items-center gap-2">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>

<style>
.sticky {
    position: sticky;
    top: 24px;
}

/* Toggle password button */
.password-toggle {
    cursor: pointer;
    user-select: none;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {

    // ================================================================
    // TOGGLE PASSWORD
    // ================================================================
    window.togglePassword = function(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    };

    // ================================================================
    // PASSWORD STRENGTH INDICATOR
    // ================================================================
    const newPassword = document.getElementById('new_password');
    const strengthBar = document.getElementById('strengthIndicator');
    const strengthText = document.getElementById('strengthText');

    if (newPassword) {
        newPassword.addEventListener('input', function() {
            const val = this.value;
            const hasMinLength = val.length >= 6;
            const hasNumber = /\d/.test(val);
            const hasLetter = /[a-zA-Z]/.test(val);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(val);

            let strength = 0;
            let level = 'weak';
            let message = '';

            if (val.length === 0) {
                strengthBar.style.width = '0%';
                strengthBar.className = 'h-full bg-slate-200 dark:bg-slate-600 rounded-full transition-all duration-300';
                strengthText.innerHTML = '<i class="fas fa-info-circle text-[10px]"></i> Minimal 6 karakter';
                return;
            }

            if (hasMinLength) strength++;
            if (hasNumber) strength++;
            if (hasLetter) strength++;
            if (hasSpecial) strength++;

            if (strength <= 1) {
                level = 'weak';
                message = '🔴 Password lemah';
                strengthBar.className = 'h-full bg-red-500 rounded-full transition-all duration-300';
                strengthBar.style.width = '25%';
            } else if (strength === 2) {
                level = 'medium';
                message = '🟡 Password sedang';
                strengthBar.className = 'h-full bg-amber-500 rounded-full transition-all duration-300';
                strengthBar.style.width = '50%';
            } else if (strength === 3) {
                level = 'good';
                message = '🟢 Password baik';
                strengthBar.className = 'h-full bg-blue-500 rounded-full transition-all duration-300';
                strengthBar.style.width = '75%';
            } else {
                level = 'strong';
                message = '🟢 Password kuat';
                strengthBar.className = 'h-full bg-emerald-500 rounded-full transition-all duration-300';
                strengthBar.style.width = '100%';
            }

            strengthText.innerHTML = message + ' <span class="text-slate-400">(' + val.length + ' karakter)</span>';
        });
    }

    // ================================================================
    // CONFIRM PASSWORD MATCH
    // ================================================================
    const confirmPassword = document.getElementById('password_confirmation');
    const confirmMatch = document.getElementById('confirmMatch');

    if (confirmPassword && newPassword) {
        confirmPassword.addEventListener('input', function() {
            const pass = newPassword.value;

            if (this.value.length === 0) {
                confirmMatch.innerHTML = '<i class="fas fa-info-circle text-[10px]"></i> Masukkan ulang password baru';
                this.style.borderColor = '';
                this.style.boxShadow = '';
                return;
            }

            if (this.value === pass) {
                confirmMatch.innerHTML = '<i class="fas fa-check-circle text-emerald-500"></i> Password cocok';
                this.style.borderColor = '#10B981';
                this.style.boxShadow = '0 0 0 3px rgba(16,185,129,0.15)';
            } else {
                confirmMatch.innerHTML = '<i class="fas fa-exclamation-circle text-red-500"></i> Password tidak cocok';
                this.style.borderColor = '#EF4444';
                this.style.boxShadow = '0 0 0 3px rgba(239,68,68,0.15)';
            }
        });
    }

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
    // FORM SUBMIT VALIDATION
    // ================================================================
    $('#passwordForm').on('submit', function(e) {
        const pass = $('#new_password').val();
        const confirm = $('#password_confirmation').val();

        if (pass !== confirm) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Password Tidak Cocok',
                text: 'Password baru dan konfirmasi password harus sama.',
                confirmButtonColor: '#F59E0B'
            });
        }
    });

});
</script>
@endpush

@endsection
