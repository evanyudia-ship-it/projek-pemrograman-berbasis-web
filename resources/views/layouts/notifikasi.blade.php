@php
    $role = session('user_role', 'mahasiswa');

    $defaultNotifications = [
        'mahasiswa' => [
            [
                'icon' => '✅',
                'title' => 'Akun Berhasil Diverifikasi',
                'message' => 'Akun Anda sudah berhasil diverifikasi dan dapat digunakan untuk melakukan booking ruangan.',
                'time' => 'Baru saja',
                'type' => 'success',
                'read' => false,
            ],
            [
                'icon' => '⏳',
                'title' => 'Booking Menunggu Persetujuan',
                'message' => 'Booking ruangan berhasil diajukan dan sedang menunggu persetujuan validator.',
                'time' => '5 menit lalu',
                'type' => 'warning',
                'read' => false,
            ],
            [
                'icon' => '✅',
                'title' => 'Booking Disetujui',
                'message' => 'Booking ruangan Anda telah disetujui oleh validator.',
                'time' => '15 menit lalu',
                'type' => 'success',
                'read' => false,
            ],
            [
                'icon' => '❌',
                'title' => 'Booking Ditolak',
                'message' => 'Booking ruangan ditolak. Silakan periksa alasan penolakan pada halaman data booking.',
                'time' => '30 menit lalu',
                'type' => 'danger',
                'read' => true,
            ],
            [
                'icon' => '⌛',
                'title' => 'Booking Expired',
                'message' => 'Booking dibatalkan karena tidak mendapatkan respons dalam waktu 1×24 jam.',
                'time' => '1 jam lalu',
                'type' => 'danger',
                'read' => true,
            ],
            [
                'icon' => '⏰',
                'title' => 'Pengingat Jadwal Ruangan',
                'message' => 'Jadwal penggunaan ruangan akan dimulai dalam 30 menit.',
                'time' => 'Hari ini',
                'type' => 'info',
                'read' => false,
            ],
            [
                'icon' => '📍',
                'title' => 'Pengingat Check-in',
                'message' => 'Silakan lakukan check-in sebelum batas waktu berakhir.',
                'time' => 'Hari ini',
                'type' => 'warning',
                'read' => false,
            ],
            [
                'icon' => '🚫',
                'title' => 'Booking No-show',
                'message' => 'Booking dibatalkan karena Anda tidak melakukan check-in.',
                'time' => 'Kemarin',
                'type' => 'danger',
                'read' => true,
            ],
            [
                'icon' => '⭐',
                'title' => 'Reputasi Bertambah +5',
                'message' => 'Poin reputasi bertambah +5 karena berhasil melakukan check-in.',
                'time' => 'Kemarin',
                'type' => 'success',
                'read' => true,
            ],
            [
                'icon' => '⭐',
                'title' => 'Reputasi Bertambah +2',
                'message' => 'Poin reputasi bertambah +2 karena penggunaan ruangan berjalan dengan baik.',
                'time' => 'Kemarin',
                'type' => 'success',
                'read' => true,
            ],
            [
                'icon' => '⚠️',
                'title' => 'Reputasi Berkurang -15',
                'message' => 'Poin reputasi berkurang -15 karena No-show.',
                'time' => '2 hari lalu',
                'type' => 'danger',
                'read' => true,
            ],
            [
                'icon' => '⚠️',
                'title' => 'Peringatan Reputasi',
                'message' => 'Poin reputasi Anda sudah mendekati batas BAN. Harap menjaga kedisiplinan penggunaan ruangan.',
                'time' => '2 hari lalu',
                'type' => 'warning',
                'read' => true,
            ],
            [
                'icon' => '🔒',
                'title' => 'Akun Dibatasi',
                'message' => 'Akun Anda dinonaktifkan atau dibatasi oleh administrator.',
                'time' => 'Sistem',
                'type' => 'danger',
                'read' => true,
            ],
        ],

        'admin' => [
            [
                'icon' => '📥',
                'title' => 'Pengajuan Booking Baru',
                'message' => 'Terdapat pengajuan booking baru yang menunggu persetujuan.',
                'time' => 'Baru saja',
                'type' => 'warning',
                'read' => false,
            ],
            [
                'icon' => '⌛',
                'title' => 'Batas Waktu Hampir Habis',
                'message' => 'Pengajuan booking akan segera melewati batas waktu 1×24 jam.',
                'time' => '10 menit lalu',
                'type' => 'danger',
                'read' => false,
            ],
            [
                'icon' => '✅',
                'title' => 'Booking Disetujui',
                'message' => 'Booking berhasil disetujui oleh validator.',
                'time' => '20 menit lalu',
                'type' => 'success',
                'read' => true,
            ],
            [
                'icon' => '❌',
                'title' => 'Booking Ditolak',
                'message' => 'Booking berhasil ditolak oleh validator.',
                'time' => '35 menit lalu',
                'type' => 'danger',
                'read' => true,
            ],
            [
                'icon' => '⌛',
                'title' => 'Booking Expired',
                'message' => 'Booking otomatis expired karena tidak direspons dalam waktu 1×24 jam.',
                'time' => '1 jam lalu',
                'type' => 'danger',
                'read' => true,
            ],
            [
                'icon' => '↩️',
                'title' => 'Booking Dibatalkan Pengguna',
                'message' => 'Pengguna membatalkan booking sebelum diproses.',
                'time' => '2 jam lalu',
                'type' => 'info',
                'read' => true,
            ],
            [
                'icon' => '🏫',
                'title' => 'Perubahan Data Ruangan',
                'message' => 'Terdapat perubahan jadwal atau data ruangan yang memengaruhi booking yang sedang diproses.',
                'time' => 'Hari ini',
                'type' => 'warning',
                'read' => true,
            ],
        ],

        'superadmin' => [
            [
                'icon' => '👤',
                'title' => 'Pengguna Baru',
                'message' => 'Terdapat pengguna baru yang melakukan registrasi.',
                'time' => 'Baru saja',
                'type' => 'info',
                'read' => false,
            ],
            [
                'icon' => '🏢',
                'title' => 'Organisasi Baru',
                'message' => 'Terdapat akun organisasi atau Ormawa baru yang berhasil dibuat.',
                'time' => '10 menit lalu',
                'type' => 'success',
                'read' => false,
            ],
            [
                'icon' => '✏️',
                'title' => 'Data Organisasi Diperbarui',
                'message' => 'Data akun organisasi berhasil diperbarui.',
                'time' => '30 menit lalu',
                'type' => 'info',
                'read' => true,
            ],
            [
                'icon' => '🔒',
                'title' => 'Akun Pengguna Dinonaktifkan',
                'message' => 'Akun pengguna telah dinonaktifkan oleh Super Admin.',
                'time' => '1 jam lalu',
                'type' => 'danger',
                'read' => true,
            ],
        ],
    ];

    $notifications = session('notifications', $defaultNotifications[$role] ?? []);
    $unreadCount = collect($notifications)->where('read', false)->count();
@endphp

<div class="relative">
    <button type="button"
        id="notificationButton"
        class="hidden md:flex w-9 h-9 items-center justify-center rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-500 hover:text-blue-600 transition relative dark:bg-bg-muted dark:text-text-secondary dark:hover:bg-nav-hover-bg dark:hover:text-nav-hover-txt"
        aria-label="Notifikasi"
        title="Notifikasi">
        🔔

        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 min-w-5 h-5 px-1 rounded-full bg-red-500 text-white text-[10px] flex items-center justify-center font-bold">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <div id="notificationDropdown"
        class="hidden absolute right-0 mt-3 w-96 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 overflow-hidden dark:bg-bg-surface dark:border-border">

        <div class="px-4 py-3 border-b border-slate-100 dark:border-border flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-text-primary">
                    Notifikasi
                </h3>
                <p class="text-xs text-slate-400">
                    Role: {{ ucfirst($role) }}
                </p>
            </div>

            @if($unreadCount > 0)
                <span class="text-xs px-2 py-1 rounded-full bg-red-50 text-red-600 font-semibold">
                    {{ $unreadCount }} baru
                </span>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notif)
                @php
                    $typeClass = match($notif['type'] ?? 'info') {
                        'success' => 'bg-green-50 text-green-700',
                        'danger' => 'bg-red-50 text-red-700',
                        'warning' => 'bg-amber-50 text-amber-700',
                        default => 'bg-blue-50 text-blue-700',
                    };
                @endphp

                <div class="px-4 py-3 border-b border-slate-100 hover:bg-slate-50 dark:border-border dark:hover:bg-bg-muted {{ empty($notif['read']) ? 'bg-blue-50/40' : '' }}">
                    <div class="flex gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg {{ $typeClass }}">
                            {{ $notif['icon'] ?? '🔔' }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-sm font-semibold text-slate-800 dark:text-text-primary">
                                    {{ $notif['title'] ?? 'Notifikasi' }}
                                </p>

                                @if(empty($notif['read']))
                                    <span class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 shrink-0"></span>
                                @endif
                            </div>

                            <p class="text-xs text-slate-500 dark:text-text-secondary mt-0.5 leading-relaxed">
                                {{ $notif['message'] ?? '-' }}
                            </p>

                            <p class="text-[11px] text-slate-400 mt-1">
                                {{ $notif['time'] ?? 'Baru saja' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <div class="text-3xl mb-2">🔕</div>
                    <p class="text-sm font-semibold text-slate-600 dark:text-text-primary">
                        Belum ada notifikasi
                    </p>
                    <p class="text-xs text-slate-400">
                        Notifikasi aktivitas akan muncul di sini.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>
