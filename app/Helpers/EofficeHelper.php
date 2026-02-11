<?php

if (! function_exists('statusLayananColor')) {
    /**
     * Get color and text for status badge
     */
    function statusLayananColor($status)
    {
        $data = [
            'Diajukan'     => ['color' => 'primary', 'text' => 'Diajukan'],
            'Diproses'     => ['color' => 'warning', 'text' => 'Diproses'],
            'Disposisi'    => ['color' => 'info', 'text' => 'Disposisi'],
            'Direvisi'     => ['color' => 'danger', 'text' => 'Butuh Revisi'],
            'Ditangguhkan' => ['color' => 'danger', 'text' => 'Ditangguhkan'],
            'Selesai'      => ['color' => 'success', 'text' => 'Selesai'],
            'Ditolak'      => ['color' => 'danger', 'text' => 'Ditolak'],
            'Dibatalkan'   => ['color' => 'secondary', 'text' => 'Dibatalkan'],
        ];

        return $data[$status] ?? ['color' => 'secondary', 'text' => $status];
    }
}

if (! function_exists('hoursToWorkDay')) {
    /**
     * Convert hours to working days (assuming 8 hours per day)
     */
    function hoursToWorkDay($hours)
    {
        if (! $hours) {
            return 0;
        }

        return round($hours / 8, 1);
    }
}

if (! function_exists('secondsToTimeFormat')) {
    /**
     * Convert seconds to human readable time
     */
    function secondsToTimeFormat($seconds)
    {
        if ($seconds < 60) {
            return $seconds . " dtk";
        }

        if ($seconds < 3600) {
            return round($seconds / 60) . " mnt";
        }

        if ($seconds < 86400) {
            return round($seconds / 3600, 1) . " jam";
        }

        return round($seconds / 86400, 1) . " hari";
    }
}

if (! function_exists('jenisIsian')) {
    /**
     * Human readable name for isian type
     */
    function jenisIsian($type)
    {
        $types = [
            'text'     => 'Text',
            'textarea' => 'Textarea',
            'number'   => 'Angka',
            'date'     => 'Tanggal',
            'file'     => 'File/Lampiran',
            'select'   => 'Pilihan (Select)',
        ];
        return $types[$type] ?? $type;
    }
}
