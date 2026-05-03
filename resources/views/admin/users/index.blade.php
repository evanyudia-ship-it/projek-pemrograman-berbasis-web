@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page_title', 'Manajemen User')
@section('page_subtitle', 'Kelola akun admin, validator, dosen, mahasiswa, dan organisasi')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5 mb-6">

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Total User</p>
        <h3 class="text-3xl font-extrabold mt-2">128</h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Admin</p>
        <h3 class="text-3xl font-extrabold mt-2 text-slate-700">3</h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Dosen</p>
        <h3 class="text-3xl font-extrabold mt-2 text-indigo-600">18</h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Mahasiswa</p>
        <h3 class="text-3xl font-extrabold mt-2 text-blue-600">96</h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Organisasi</p>
        <h3 class="text-3xl font-extrabold mt-2 text-emerald-600">11</h3>
    </div>

</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="p-6 border-b border-slate-200">

        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

            <div>
                <h3 class="font-bold text-lg">Daftar User</h3>
                <p class="text-sm text-slate-500">
                    Pantau status akun, role, verifikasi, dan reputation point
                </p>
            </div>

            <div class="flex flex-col md:flex-row gap-3">
                <input type="text"
                       id="searchUser"
                       class="rounded-xl"
                       placeholder="Cari nama/email...">

                <select id="filterRole" class="rounded-xl">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="validator">Validator</option>
                    <option value="dosen">Dosen</option>
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="organisasi">Organisasi</option>
                </select>

                <select id="filterStatus" class="rounded-xl">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="pending">Menunggu</option>
                    <option value="inactive">Nonaktif</option>
                    <option value="banned">Banned</option>
                </select>

                <button id="btnOpenUserModal"
                        class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                    + Tambah User
                </button>
            </div>

        </div>

    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="text-left px-6 py-4">User</th>
                    <th class="text-left px-6 py-4">NIM/NIP/Kode</th>
                    <th class="text-left px-6 py-4">Role</th>
                    <th class="text-left px-6 py-4">Status</th>
                    <th class="text-left px-6 py-4">Reputation</th>
                    <th class="text-left px-6 py-4">Verifikasi</th>
                    <th class="text-center px-6 py-4">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100" id="userTable">

                <tr class="user-row"
                    data-search="admin kampus admin@kampus.ac.id adm001"
                    data-role="admin"
                    data-status="active">

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold">
                                A
                            </div>
                            <div>
                                <p class="font-bold">Admin Kampus</p>
                                <p class="text-xs text-slate-500">admin@kampus.ac.id</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">ADM001</td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                            Admin
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                            Aktif
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-blue-600">100</span>
                            <div class="w-20 h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full w-full bg-blue-600"></div>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-emerald-600 font-bold">Terverifikasi</span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button class="btn-edit px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                                Edit
                            </button>
                            <button class="btn-reset px-3 py-2 rounded-lg bg-yellow-100 text-yellow-700 font-semibold">
                                Reset
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="user-row"
                    data-search="pak budi dosen@kampus.ac.id dsn001"
                    data-role="dosen"
                    data-status="active">

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                                D
                            </div>
                            <div>
                                <p class="font-bold">Pak Budi</p>
                                <p class="text-xs text-slate-500">dosen@kampus.ac.id</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">DSN001</td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">
                            Dosen
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                            Aktif
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-blue-600">92</span>
                            <div class="w-20 h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full w-[92%] bg-blue-600"></div>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-emerald-600 font-bold">Terverifikasi</span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button class="btn-edit px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                                Edit
                            </button>
                            <button class="btn-disable px-3 py-2 rounded-lg bg-red-100 text-red-700 font-semibold">
                                Nonaktifkan
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="user-row"
                    data-search="mahasiswa demo mahasiswa@kampus.ac.id 20260001"
                    data-role="mahasiswa"
                    data-status="active">

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                                M
                            </div>
                            <div>
                                <p class="font-bold">Mahasiswa Demo</p>
                                <p class="text-xs text-slate-500">mahasiswa@kampus.ac.id</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">20260001</td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                            Mahasiswa
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                            Aktif
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-blue-600">85</span>
                            <div class="w-20 h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full w-[85%] bg-blue-600"></div>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-emerald-600 font-bold">Terverifikasi</span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button class="btn-edit px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                                Edit
                            </button>
                            <button class="btn-disable px-3 py-2 rounded-lg bg-red-100 text-red-700 font-semibold">
                                Nonaktifkan
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="user-row"
                    data-search="bem kampus bem@kampus.ac.id org001"
                    data-role="organisasi"
                    data-status="pending">

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold">
                                O
                            </div>
                            <div>
                                <p class="font-bold">BEM Kampus</p>
                                <p class="text-xs text-slate-500">bem@kampus.ac.id</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">ORG001</td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                            Organisasi
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">
                            Menunggu
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-blue-600">78</span>
                            <div class="w-20 h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full w-[78%] bg-blue-600"></div>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-yellow-600 font-bold">Perlu Verifikasi</span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button class="btn-edit px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                                Edit
                            </button>
                            <button class="btn-verify px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 font-semibold">
                                Verifikasi
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="user-row"
                    data-search="tono mahasiswa tono@kampus.ac.id 20260002"
                    data-role="mahasiswa"
                    data-status="banned">

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full bg-red-600 text-white flex items-center justify-center font-bold">
                                T
                            </div>
                            <div>
                                <p class="font-bold">Tono Mahasiswa</p>
                                <p class="text-xs text-slate-500">tono@kampus.ac.id</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">20260002</td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                            Mahasiswa
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                            Banned
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-red-600">12</span>
                            <div class="w-20 h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full w-[12%] bg-red-600"></div>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-emerald-600 font-bold">Terverifikasi</span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button class="btn-edit px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                                Edit
                            </button>
                            <button class="btn-activate px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 font-semibold">
                                Aktifkan
                            </button>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>

{{-- MODAL TAMBAH / EDIT USER --}}
<div id="userModal"
     class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden">

        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg" id="modalTitle">Tambah User</h3>
                <p class="text-sm text-slate-500">Form data pengguna sistem</p>
            </div>

            <button id="btnCloseUserModal"
                    class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200">
                ✕
            </button>
        </div>

        <form action="#" method="POST" id="userForm" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="text-sm font-semibold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" id="formName" class="mt-2 w-full rounded-xl" placeholder="Nama user">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Email</label>
                    <input type="email" name="email" id="formEmail" class="mt-2 w-full rounded-xl" placeholder="email@kampus.ac.id">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">NIM / NIP / Kode</label>
                    <input type="text" name="identity_number" id="formIdentity" class="mt-2 w-full rounded-xl" placeholder="Contoh: 20260001">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">No. HP</label>
                    <input type="text" name="phone" id="formPhone" class="mt-2 w-full rounded-xl" placeholder="08123456789">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Role</label>
                    <select name="role" id="formRole" class="mt-2 w-full rounded-xl">
                        <option value="">Pilih role</option>
                        <option value="admin">Admin</option>
                        <option value="validator">Validator</option>
                        <option value="dosen">Dosen</option>
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="organisasi">Organisasi</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Status</label>
                    <select name="status" id="formStatus" class="mt-2 w-full rounded-xl">
                        <option value="active">Aktif</option>
                        <option value="pending">Menunggu Verifikasi</option>
                        <option value="inactive">Nonaktif</option>
                        <option value="banned">Banned</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Password</label>
                    <input type="password" name="password" class="mt-2 w-full rounded-xl" placeholder="Password">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="mt-2 w-full rounded-xl" placeholder="Ulangi password">
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-700">Catatan Admin</label>
                    <textarea name="note" rows="3" class="mt-2 w-full rounded-xl" placeholder="Catatan tambahan jika diperlukan"></textarea>
                </div>

            </div>

            <div class="mt-6 flex flex-col md:flex-row gap-3 md:justify-end">
                <button type="button"
                        id="btnCancelUserModal"
                        class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold">
                    Batal
                </button>

                <button type="submit"
                        class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                    Simpan User
                </button>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
    function filterUsers() {
        let keyword = $('#searchUser').val().toLowerCase();
        let role = $('#filterRole').val();
        let status = $('#filterStatus').val();

        $('.user-row').each(function () {
            let searchData = $(this).data('search');
            let rowRole = $(this).data('role');
            let rowStatus = $(this).data('status');

            let matchKeyword = searchData.includes(keyword);
            let matchRole = role === '' || rowRole === role;
            let matchStatus = status === '' || rowStatus === status;

            if (matchKeyword && matchRole && matchStatus) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    $('#searchUser').on('keyup', filterUsers);
    $('#filterRole').on('change', filterUsers);
    $('#filterStatus').on('change', filterUsers);

    $('#btnOpenUserModal').on('click', function () {
        $('#modalTitle').text('Tambah User');
        $('#userForm')[0].reset();
        $('#userModal').removeClass('hidden');
    });

    $('#btnCloseUserModal, #btnCancelUserModal').on('click', function () {
        $('#userModal').addClass('hidden');
    });

    $('#userModal').on('click', function (e) {
        if (e.target.id === 'userModal') {
            $('#userModal').addClass('hidden');
        }
    });

    $('.btn-edit').on('click', function () {
        $('#modalTitle').text('Edit User');

        let row = $(this).closest('tr');
        let name = row.find('td:first p.font-bold').text();
        let email = row.find('td:first p.text-xs').text();
        let identity = row.find('td:eq(1)').text().trim();
        let role = row.data('role');
        let status = row.data('status');

        $('#formName').val(name);
        $('#formEmail').val(email);
        $('#formIdentity').val(identity);
        $('#formRole').val(role);
        $('#formStatus').val(status);

        $('#userModal').removeClass('hidden');
    });

    $('.btn-disable').on('click', function () {
        if (confirm('Nonaktifkan user ini?')) {
            alert('User dinonaktifkan. Ini masih GUI, belum terhubung database.');
        }
    });

    $('.btn-activate').on('click', function () {
        if (confirm('Aktifkan kembali user ini?')) {
            alert('User berhasil diaktifkan. Ini masih GUI, belum terhubung database.');
        }
    });

    $('.btn-reset').on('click', function () {
        if (confirm('Reset password user ini?')) {
            alert('Password berhasil di-reset. Ini masih GUI.');
        }
    });

    $('.btn-verify').on('click', function () {
        if (confirm('Verifikasi user/organisasi ini?')) {
            alert('User berhasil diverifikasi. Ini masih GUI.');
        }
    });

    $('#userForm').on('submit', function (e) {
        e.preventDefault();

        alert('Data user berhasil disimpan. Ini masih GUI, belum masuk database.');
        $('#userModal').addClass('hidden');
    });
</script>
@endpush

@endsection
