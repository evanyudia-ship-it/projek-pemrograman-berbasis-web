

<?php $__env->startSection('title', 'Ajukan Booking'); ?>
<?php $__env->startSection('page_title', 'Ajukan Booking Ruang'); ?>
<?php $__env->startSection('page_subtitle', 'Isi formulir untuk mengajukan penggunaan ruang kelas'); ?>

<?php $__env->startSection('content'); ?>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

        <form action="#" method="POST" id="bookingForm">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="text-sm font-semibold text-slate-700">Ruang</label>
                    <select id="room_id" name="room_id"
                            class="mt-2 w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih ruang</option>
                        <option value="1" data-capacity="40">R-201 - Ruang Kelas 201</option>
                        <option value="2" data-capacity="20">R-105 - Ruang Diskusi 105</option>
                        <option value="3" data-capacity="35">LAB-01 - Lab Komputer</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Jenis Booking</label>
                    <select name="booking_type" id="booking_type"
                            class="mt-2 w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih jenis</option>
                        <option value="belajar_kelompok">Belajar Kelompok</option>
                        <option value="kelas_pengganti">Kelas Pengganti</option>
                        <option value="rapat_organisasi">Rapat Organisasi</option>
                        <option value="event">Event</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-700">Judul Kegiatan</label>
                    <input type="text" name="title"
                           class="mt-2 w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: Diskusi Tugas Kelompok">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Tanggal</label>
                    <input type="date" name="date" id="date"
                           class="mt-2 w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Jumlah Peserta</label>
                    <input type="number" name="participant_count" id="participant_count"
                           class="mt-2 w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: 25">
                    <p id="capacityInfo" class="text-xs text-slate-500 mt-1"></p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Jam Mulai</label>
                    <input type="time" name="start_time" id="start_time"
                           class="mt-2 w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Jam Selesai</label>
                    <input type="time" name="end_time" id="end_time"
                           class="mt-2 w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    <p id="durationInfo" class="text-xs mt-1"></p>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-700">Tujuan Penggunaan</label>
                    <textarea name="purpose" rows="4"
                              class="mt-2 w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Jelaskan tujuan penggunaan ruang"></textarea>
                </div>

            </div>

            <div class="mt-6 flex flex-col md:flex-row gap-3 md:justify-end">
                <a href="<?php echo e(route('rooms.index')); ?>"
                   class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-center">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                    Ajukan Booking
                </button>
            </div>
        </form>

    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="font-bold text-lg mb-2">Aturan Booking</h3>
        <p class="text-sm text-slate-500 mb-5">Pastikan booking sesuai ketentuan sistem.</p>

        <div class="space-y-4 text-sm">
            <div class="p-4 rounded-xl bg-blue-50 border border-blue-100">
                <p class="font-bold text-blue-700">Mahasiswa</p>
                <p class="text-slate-600 mt-1">Maksimal booking 2 jam.</p>
            </div>

            <div class="p-4 rounded-xl bg-indigo-50 border border-indigo-100">
                <p class="font-bold text-indigo-700">Dosen</p>
                <p class="text-slate-600 mt-1">Maksimal booking 6 jam.</p>
            </div>

            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100">
                <p class="font-bold text-emerald-700">Organisasi</p>
                <p class="text-slate-600 mt-1">Wajib validasi penanggung jawab organisasi.</p>
            </div>

            <div class="p-4 rounded-xl bg-red-50 border border-red-100">
                <p class="font-bold text-red-700">Check-in</p>
                <p class="text-slate-600 mt-1">Wajib check-in maksimal 15 menit setelah jam mulai.</p>
            </div>
        </div>
    </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function calculateDuration() {
        let start = $('#start_time').val();
        let end = $('#end_time').val();

        if (!start || !end) {
            $('#durationInfo').text('');
            return;
        }

        let startTime = new Date('2000-01-01 ' + start);
        let endTime = new Date('2000-01-01 ' + end);

        let diff = (endTime - startTime) / 1000 / 60;

        if (diff <= 0) {
            $('#durationInfo')
                .removeClass('text-emerald-600')
                .addClass('text-red-600')
                .text('Jam selesai harus lebih besar dari jam mulai.');
            return;
        }

        let hours = diff / 60;

        $('#durationInfo')
            .removeClass('text-red-600')
            .addClass('text-emerald-600')
            .text('Durasi booking: ' + hours.toFixed(1) + ' jam.');
    }

    $('#start_time, #end_time').on('change', calculateDuration);

    $('#room_id').on('change', function () {
        let capacity = $('option:selected', this).data('capacity');

        if (capacity) {
            $('#capacityInfo').text('Kapasitas ruang: ' + capacity + ' orang.');
        } else {
            $('#capacityInfo').text('');
        }
    });

    $('#participant_count').on('keyup change', function () {
        let capacity = $('#room_id option:selected').data('capacity');
        let participant = parseInt($(this).val());

        if (capacity && participant > capacity) {
            $('#capacityInfo')
                .removeClass('text-slate-500')
                .addClass('text-red-600')
                .text('Jumlah peserta melebihi kapasitas ruang.');
        } else if (capacity) {
            $('#capacityInfo')
                .removeClass('text-red-600')
                .addClass('text-slate-500')
                .text('Kapasitas ruang: ' + capacity + ' orang.');
        }
    });

    $('#bookingForm').on('submit', function (e) {
        e.preventDefault();

        alert('GUI berhasil. Nanti bagian ini disambungkan ke controller booking.');
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Kara\Kuliah\Tugas Kuliah\Semester 4\Pemrograman Berbasis Web\Project\PROJECT\resources\views/bookings/create.blade.php ENDPATH**/ ?>