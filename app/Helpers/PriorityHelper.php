<?php

namespace App\Helpers;

class PriorityHelper
{
    /**
     * Mapping jenis kegiatan ke priority level.
     */
    public static function getPriorities(): array
    {
        return [
            // HIGH
            'seminar_nasional' => [
                'label' => 'Seminar Nasional / Konferensi',
                'priority' => 'High',
                'description' => 'Acara institusi besar'
            ],
            'wisuda' => [
                'label' => 'Wisuda / Gladi Bersih',
                'priority' => 'High',
                'description' => 'Agenda resmi universitas'
            ],
            'kuliah_umum' => [
                'label' => 'Kuliah Umum / Guest Lecture',
                'priority' => 'High',
                'description' => 'Melibatkan pembicara khusus'
            ],
            'workshop_institusi' => [
                'label' => 'Workshop / Pelatihan Institusi',
                'priority' => 'High',
                'description' => 'Kegiatan pengembangan skala besar'
            ],

            // MEDIUM-HIGH
            'ujian' => [
                'label' => 'Ujian (UTS/UAS/Remedial)',
                'priority' => 'Medium-High',
                'description' => 'Kegiatan akademik terjadwal'
            ],
            'sidang_skripsi' => [
                'label' => 'Sidang Skripsi / Tesis',
                'priority' => 'Medium-High',
                'description' => 'Membutuhkan ruang khusus'
            ],
            'seminar_proposal' => [
                'label' => 'Seminar Proposal',
                'priority' => 'Medium-High',
                'description' => 'Agenda akademik'
            ],
            'praktikum' => [
                'label' => 'Praktikum',
                'priority' => 'Medium-High',
                'description' => 'Bergantung fasilitas'
            ],
            'kompetisi' => [
                'label' => 'Kompetisi / Lomba Akademik',
                'priority' => 'Medium-High',
                'description' => 'Event akademik'
            ],

            // MEDIUM
            'kuliah_reguler' => [
                'label' => 'Kuliah Reguler',
                'priority' => 'Medium',
                'description' => 'Aktivitas pembelajaran rutin'
            ],
            'rapat_fakultas' => [
                'label' => 'Rapat Fakultas / Jurusan',
                'priority' => 'Medium',
                'description' => 'Operasional kampus'
            ],
            'organisasi_mahasiswa' => [
                'label' => 'Organisasi Mahasiswa (BEM/HIMA/UKM)',
                'priority' => 'Medium',
                'description' => 'Kegiatan organisasi'
            ],
            'penelitian' => [
                'label' => 'Penelitian / Pengambilan Data',
                'priority' => 'Medium',
                'description' => 'Kegiatan akademik'
            ],
            'sosialisasi' => [
                'label' => 'Sosialisasi / Orientasi',
                'priority' => 'Medium',
                'description' => 'Kegiatan informasi'
            ],

            // LOW
            'diskusi_kelompok' => [
                'label' => 'Diskusi Kelompok',
                'priority' => 'Low',
                'description' => 'Belajar bersama'
            ],
            'bimbingan_kelompok' => [
                'label' => 'Bimbingan Kelompok',
                'priority' => 'Low',
                'description' => 'Konsultasi'
            ],
            'belajar_mandiri' => [
                'label' => 'Belajar Mandiri',
                'priority' => 'Low',
                'description' => 'Kebutuhan individu'
            ],
            'latihan_presentasi' => [
                'label' => 'Latihan Presentasi',
                'priority' => 'Low',
                'description' => 'Persiapan'
            ],
            'pertemuan_komunitas' => [
                'label' => 'Pertemuan Komunitas',
                'priority' => 'Low',
                'description' => 'Non-prioritas utama'
            ],
        ];
    }

    /**
     * Get priority level by activity type.
     */
    public static function getPriority(string $activityType): string
    {
        $priorities = self::getPriorities();
        return $priorities[$activityType]['priority'] ?? 'Medium';
    }

    /**
     * Get activity label by type.
     */
    public static function getLabel(?string $activityType): string
    {
        if ($activityType === null || $activityType === '') {
            return '-';
        }

        $priorities = self::getPriorities();
        return $priorities[$activityType]['label'] ?? $activityType;
    }

    /**
     * Get all activity types for dropdown.
     */
    public static function getActivityTypes(): array
    {
        $priorities = self::getPriorities();
        $types = [];
        foreach ($priorities as $key => $value) {
            $types[$key] = $value['label'];
        }
        return $types;
    }

    /**
     * Get activities grouped by priority.
     */
    public static function getActivitiesGroupedByPriority(): array
    {
        $priorities = self::getPriorities();
        $grouped = [];

        foreach ($priorities as $key => $value) {
            $priority = $value['priority'];
            if (!isset($grouped[$priority])) {
                $grouped[$priority] = [];
            }
            $grouped[$priority][$key] = $value['label'];
        }

        return $grouped;
    }

    /**
     * Get priority label.
     */
    public static function getPriorityLabel(string $priority): string
    {
        return match($priority) {
            'High' => 'Tinggi',
            'Medium-High' => 'Sedang Tinggi',
            'Medium' => 'Sedang',
            'Low' => 'Rendah',
            default => $priority,
        };
    }

    /**
     * Get priority color class.
     */
    public static function getPriorityColor(string $priority): string
    {
        return match($priority) {
            'High' => 'bg-red-100 text-red-700',
            'Medium-High' => 'bg-orange-100 text-orange-700',
            'Medium' => 'bg-yellow-100 text-yellow-700',
            'Low' => 'bg-blue-100 text-blue-700',
            default => 'bg-slate-100 text-slate-700',
        };
    }
}
