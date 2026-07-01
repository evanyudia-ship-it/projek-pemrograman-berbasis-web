<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Smart Classroom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body{ font-family:'Inter',sans-serif; background:#EEF1F6; }
        .font-heading{ font-family:'Poppins',sans-serif; }

        .panel-blue{
            background: linear-gradient(160deg, #1D4ED8 0%, #1E3A8A 100%);
        }

        .field-input{
            width:100%;
            padding:0.85rem 1.1rem;
            border-radius:0.75rem;
            border:1px solid #E2E8F0;
            background:#F8FAFC;
            outline:none;
            transition:all .15s ease;
            font-size: 0.95rem;
        }
        .field-input:focus{
            border-color:#1D4ED8;
            background:#fff;
            box-shadow:0 0 0 4px rgba(29,78,216,0.12);
        }
        .field-input.error{
            border-color:#EF4444;
            background:#FEF2F2;
        }

        .btn-register{
            background:#1D4ED8;
            transition: all .15s ease;
            padding: 0.9rem 1.5rem;
        }
        .btn-register:hover{ background:#1739AD; }
        .btn-register:active{ transform:scale(0.98); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden grid md:grid-cols-[2fr_3fr]">

        <!-- LEFT PANEL -->
        <div class="panel-blue text-white p-10 flex flex-col">
            <div class="flex flex-col items-center text-center mb-8">
                <div class="w-24 h-24 rounded-full bg-white/10 border border-white/30 flex items-center justify-center mb-5">
                    <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center">
                        <img src="{{ asset('images/logo-undiksha-white.png') }}" alt="Logo Undiksha" class="w-12 h-12 object-contain">
                    </div>
                </div>
                <p class="font-heading text-sm tracking-widest text-blue-100 uppercase">SMART CLASSROOM</p>
                <p class="text-blue-200 text-sm">Sistem Booking Ruangan • Undiksha</p>
            </div>

            <h2 class="font-heading text-2xl font-bold text-center mb-4">Tentang Aplikasi</h2>
            <div class="text-blue-100 leading-relaxed text-center mb-8 text-[15px]">
                Smart Classroom adalah sistem informasi manajemen peminjaman ruangan berbasis web yang dirancang untuk memudahkan sivitas akademika dalam mengelola jadwal dan ketersediaan ruang.
            </div>

            <h3 class="font-heading text-lg font-semibold mb-4">Fitur Unggulan</h3>
            <div class="space-y-4">
                <div class="flex gap-3">
                    <span class="text-2xl">📅</span>
                    <div>
                        <strong>Booking Online</strong><br>
                        <span class="text-blue-200 text-sm">Ajukan peminjaman ruang kapan saja.</span>
                    </div>
                </div>
                <div class="flex gap-3">
                    <span class="text-2xl">✅</span>
                    <div>
                        <strong>Approval Terpadu</strong><br>
                        <span class="text-blue-200 text-sm">Proses persetujuan yang cepat.</span>
                    </div>
                </div>
                <div class="flex gap-3">
                    <span class="text-2xl">⭐</span>
                    <div>
                        <strong>Sistem Reputasi</strong><br>
                        <span class="text-blue-200 text-sm">Poin dan level pengguna.</span>
                    </div>
                </div>
            </div>

            <div class="mt-auto pt-8 text-center text-blue-200 text-sm">
                Versi 1.0 • Dikembangkan untuk Undiksha
            </div>
        </div>

        <!-- REGISTER FORM -->
        <div class="p-12 lg:p-16 flex flex-col justify-center">
            <div class="max-w-md mx-auto w-full">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 mx-auto mb-4 bg-[#1D4ED8] rounded-2xl flex items-center justify-center">
                        <img src="{{ asset('images/logo-undiksha.png') }}" alt="Logo Undiksha" class="w-10 h-10">
                    </div>
                    <h1 class="font-heading text-2xl font-bold text-slate-800">Daftar Akun Baru</h1>
                    <p class="text-slate-500 mt-1">Silakan isi data diri Anda</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 text-sm flex items-center gap-3">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 text-red-700 px-5 py-4 text-sm flex items-center gap-3">
                        ❌ {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 text-red-700 px-5 py-4 text-sm">
                        <ul class="list-disc ml-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.process') }}" class="space-y-6" id="registerForm">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="field-input @error('name') error @enderror"
                                   placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="field-input @error('email') error @enderror"
                                   placeholder="contoh@email.com" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                            <select name="role" id="role" class="field-input @error('role') error @enderror" required>
                                <option value="">Pilih Role</option>
                                <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>🎓 Mahasiswa</option>
                                <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>👨‍🏫 Dosen</option>
                                <option value="organisasi" {{ old('role') == 'organisasi' ? 'selected' : '' }}>🏛️ Organisasi</option>
                            </select>
                        </div>
                        <div>
                            <label id="nimLabel" class="block text-sm font-medium text-slate-700 mb-1.5">NIM / NIDN</label>
                            <input type="text" id="nim_nip" name="nim_nip" value="{{ old('nim_nip') }}"
                                   class="field-input @error('nim_nip') error @enderror">
                            <p id="nimHelp" class="text-xs text-slate-500 mt-1.5"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">No. HP</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                   class="field-input @error('phone') error @enderror"
                                   placeholder="081234567890">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Fakultas</label>
                            <select name="faculty_id" class="field-input @error('faculty_id') error @enderror">
                                <option value="">Pilih Fakultas</option>
                                @isset($faculties)
                                    @foreach($faculties as $faculty)
                                        <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                            {{ $faculty->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                       class="field-input pr-12 @error('password') error @enderror"
                                       placeholder="Minimal 8 karakter" required>
                                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" onclick="togglePassword('password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="strengthContainer" class="mt-2">
                                <div id="strengthBar" class="h-1 rounded-full bg-gray-200"></div>
                                <p id="strengthText" class="text-xs text-slate-500 mt-1"></p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="field-input pr-12" placeholder="Ulangi password" required>
                                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" onclick="togglePassword('password_confirmation', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p id="confirmMatch" class="text-xs mt-1.5 min-h-[20px]"></p>
                        </div>
                    </div>

                    <button type="submit" id="registerButton"
                            class="btn-register w-full rounded-2xl text-white font-semibold text-lg flex items-center justify-center gap-2">
                        <i class="fas fa-user-plus"></i> Daftar Akun
                    </button>
                </form>

                <div class="text-center mt-8">
                    <p class="text-slate-500">Sudah punya akun?</p>
                    <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline mt-1 inline-block">
                        Login sekarang →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script tetap sama seperti sebelumnya (update label, password strength, dll)
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const identifierInput = document.getElementById('nim_nip');
            const label = document.getElementById('nimLabel');
            const helpText = document.getElementById('nimHelp');

            function updateIdentifierField() {
                const role = roleSelect.value;
                if (role === 'mahasiswa') {
                    label.innerHTML = 'NIM <span class="text-red-500">*</span>';
                    identifierInput.placeholder = 'Masukkan NIM mahasiswa';
                    identifierInput.required = true;
                    helpText.textContent = 'Masukkan NIM mahasiswa Anda';
                } else if (role === 'dosen') {
                    label.innerHTML = 'NIDN <span class="text-red-500">*</span>';
                    identifierInput.placeholder = 'Masukkan NIDN dosen';
                    identifierInput.required = true;
                    helpText.textContent = 'Masukkan NIDN dosen Anda';
                } else if (role === 'organisasi') {
                    label.textContent = 'Nama Organisasi';
                    identifierInput.placeholder = 'Nama organisasi (opsional)';
                    identifierInput.required = false;
                    helpText.textContent = 'Opsional';
                } else {
                    label.textContent = 'NIM / NIDN';
                    identifierInput.placeholder = 'NIM / NIDN';
                    identifierInput.required = false;
                    helpText.textContent = 'Diisi sesuai role';
                }
            }

            roleSelect.addEventListener('change', updateIdentifierField);
            updateIdentifierField();

            window.togglePassword = function(inputId, btn) {
                const input = document.getElementById(inputId);
                const icon = btn.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            };

            // Password strength & confirm match script (sama seperti sebelumnya)
            // ... (bisa copy dari kode sebelumnya)
        });
    </script>
</body>
</html>
