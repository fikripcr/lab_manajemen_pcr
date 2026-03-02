<?php

if (! function_exists('hrDiffFields')) {
    function hrDiffFields($before, $new): array
    {
        if (! $new) {
            return [];
        }

        // Fields to always skip (technical / internal)
        $skip = [
            'created_at', 'updated_at', 'deleted_at',
            'created_by', 'updated_by', 'deleted_by',
            'before_id', 'latest_riwayatapproval_id',
            'face_encoding', 'file_foto', 'file_ttd_digital',
            // ID foreign keys — derived from subject itself, no need to show raw ID
            'pegawai_id',
        ];

        // Human-readable labels
        $labels = [
            'nip'                   => 'NIP',
            'nidn'                  => 'NIDN',
            'nama'                  => 'Nama Lengkap',
            'inisial'               => 'Inisial',
            'email'                 => 'Email',
            'jenis_kelamin'         => 'Jenis Kelamin',
            'tempat_lahir'          => 'Tempat Lahir',
            'tgl_lahir'             => 'Tanggal Lahir',
            'alamat'                => 'Alamat',
            'telp'                  => 'No. Telepon',
            'no_hp'                 => 'No. HP',
            'no_ktp'                => 'No. KTP',
            'status_nikah'          => 'Status Nikah',
            'agama'                 => 'Agama',
            'no_kk'                 => 'No. KK',
            'gelar_depan'           => 'Gelar Depan',
            'gelar_belakang'        => 'Gelar Belakang',
            'nama_buku'             => 'Nama di Buku',
            'no_rekening'           => 'No. Rekening',
            'bank_pegawai'          => 'Bank',
            'npwp'                  => 'NPWP',
            'status_cuti'           => 'Status Cuti',
            'absen_pin'             => 'PIN Absen',
            'bidang_ilmu'           => 'Bidang Ilmu',
            'jenis_perubahan'       => 'Jenis Perubahan',
            'orgunit_posisi_id'     => 'Posisi',
            'orgunit_departemen_id' => 'Departemen',
            // Keluarga
            'hubungan'              => 'Hubungan',
            'nama_lengkap'          => 'Nama Lengkap',
            // Pendidikan
            'jenjang'               => 'Jenjang Pendidikan',
            'jurusan'               => 'Jurusan/Prodi',
            'perguruan_tinggi'      => 'Perguruan Tinggi',
            'tgl_ijazah'            => 'Tanggal Ijazah',
            'gelar'                 => 'Gelar',
            // Status Pegawai
            'status_pegawai_id'     => 'Status Kepegawaian',
            'tmt'                   => 'TMT',
            'sk_nomor'              => 'No. SK',
            'sk_tanggal'            => 'Tgl. SK',
            // Inpassing
            'golongan_inpassing_id' => 'Golongan Inpassing',
            // Jabatan Fungsional
            'jabatan_fungsional_id' => 'Jabatan Fungsional',
            // General
            'status'                => 'Status',
            'keterangan'            => 'Keterangan',
        ];

        // Enum / code value maps — make raw values human-readable
        $valueMaps = [
            'jenis_kelamin' => ['L' => 'Laki-laki', 'P' => 'Perempuan'],
            'status_nikah'  => [
                'Belum Menikah' => 'Belum Menikah',
                'Menikah'       => 'Menikah',
                'Cerai'         => 'Cerai',
            ],
            'hubungan'      => [
                'Suami'     => 'Suami',
                'Istri'     => 'Istri',
                'Anak'      => 'Anak',
                'Orang Tua' => 'Orang Tua',
            ],
        ];

        // Date fields — format to d M Y
        $dateFields = [
            'tgl_lahir', 'tgl_ijazah', 'tmt', 'sk_tanggal',
            'tgl_mulai', 'tgl_selesai', 'tgl_efektif',
        ];

        $formatValue = function ($field, $value) use ($valueMaps, $dateFields) {
            if ($value === null || $value === '') {
                return $value;
            }
            // Enum map
            if (isset($valueMaps[$field][$value])) {
                return $valueMaps[$field][$value];
            }
            // Date formatting
            if (in_array($field, $dateFields) || str_ends_with($field, '_at') || str_ends_with($field, '_tanggal')) {
                try {
                    return \Carbon\Carbon::parse($value)->translatedFormat('d M Y');
                } catch (\Exception $e) {
                    return $value;
                }
            }
            return $value;
        };

        $fillable = $new->getFillable();
        $diffs    = [];

        foreach ($fillable as $field) {
            if (in_array($field, $skip) || $field === $new->getKeyName()) {
                continue;
            }

            $oldVal = $before ? $before->{$field} : null;
            $newVal = $new->{$field};

            // Skip empty in new addition context
            if (! $before && ($newVal === null || $newVal === '')) {
                continue;
            }

            $label   = $labels[$field] ?? ucwords(str_replace('_', ' ', $field));
            $changed = (string) $oldVal !== (string) $newVal;

            $diffs[] = [
                'field'   => $field,
                'label'   => $label,
                'old'     => $formatValue($field, $oldVal),
                'new'     => $formatValue($field, $newVal),
                'changed' => $changed,
            ];
        }

        return $diffs;
    }
}

if (! function_exists('hrStatusBadge')) {
    /**
     * Get badge HTML for HR statuses
     *
     * @param string $status
     * @param string $module (perizinan, lembur, approval)
     * @return string
     */
    function hrStatusBadge($status, $module = 'perizinan')
    {
        $status = strtolower(trim($status));

        if ($module === 'lembur') {
            $badges = [
                'diajukan' => 'bg-warning',
                'pending'  => 'bg-warning',
                'approved' => 'bg-success',
                'rejected' => 'bg-danger',
            ];
            $color = $badges[$status] ?? 'bg-secondary';
            return '<span class="badge ' . $color . ' text-white">' . ucfirst($status) . '</span>';
        }

        // Default for perizinan/general
        $badges = [
            'draft'    => 'bg-secondary-lt',
            'diajukan' => 'bg-warning',
            'pending'  => 'bg-warning-lt',
            'approved' => 'bg-success-lt',
            'rejected' => 'bg-danger-lt',
        ];
        $color     = $badges[$status] ?? 'bg-secondary-lt';
        $textColor = str_contains($color, '-lt') ? '' : 'text-white';

        return '<span class="badge ' . $color . ' ' . $textColor . '">' . ucfirst($status) . '</span>';
    }
}

if (! function_exists('hrModelLabel')) {
    /**
     * Convert model class string to a human-readable label
     *
     * @param string $modelClass
     * @return string
     */
    function hrModelLabel($modelClass)
    {
        if (! $modelClass) {
            return '-';
        }

        try {
            $shortName = (new \ReflectionClass($modelClass))->getShortName();
            // Optional: convert camelCase to space separated names if needed
            // e.g. RiwayatPendidikan -> Riwayat Pendidikan
            return preg_replace('/(?<!^)[A-Z]/', ' $0', $shortName);
        } catch (\Exception $e) {
            return $modelClass;
        }
    }
}

if (! function_exists('hrDateRange')) {
    /**
     * Format a date range string
     *
     * @param mixed $start
     * @param mixed $end
     * @param string $format
     * @return string
     */
    function hrDateRange($start, $end, $format = 'd/m/Y')
    {
        $startStr = $start instanceof \DateTimeInterface  ? $start->format($format) : ($start ?? '-');
        $endStr   = $end instanceof \DateTimeInterface  ? $end->format($format) : ($end ?? '-');

        return $startStr . ' s/d ' . $endStr;
    }
}

if (! function_exists('hrPegawaiName')) {
    /**
     * Format pegawai name (Inisial - Nama)
     *
     * @param mixed $pegawai
     * @return string
     */
    function hrPegawaiName($pegawai)
    {
        if (! $pegawai) {
            return 'N/A';
        }

        $dataDiri = $pegawai->latestDataDiri ?? null;
        if ($dataDiri) {
            return ($dataDiri->inisial ? $dataDiri->inisial . ' - ' : '') . $dataDiri->nama;
        }

        return $pegawai->nama ?? 'N/A';
    }
}
