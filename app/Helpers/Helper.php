<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

if (! function_exists('encryptId')) {
    /**
     * Encrypt an ID using Hashids
     *
     * @param int $id
     * @return string
     */
    function encryptId($id)
    {
        return app('hashids')->encode($id);
    }
}

if (! function_exists('decryptId')) {
    /**
     * Decrypt a Hashid to get the original ID
     *
     * @param string $hash
     * @param bool $throwException Whether to throw exception on failure
     * @return int|null
     */
    function decryptId($hash, $throwException = true)
    {
        if (! $hash) {
            if ($throwException) {
                abort(403, 'Data tidak ditemukan.');
            }
            return null;
        }

        $decoded = app('hashids')->decode($hash);

        if (empty($decoded)) {
            if ($throwException) {
                abort(403, 'Data tidak ditemukan.');
            }
            return null;
        }

        return $decoded[0];
    }
}

if (! function_exists('getVerifiedMediaUrl')) {
    function getVerifiedMediaUrl($model, $collection, $conversion = '')
    {
        $media      = $model->getFirstMedia($collection);
        $defaultUrl = asset('img/no-image.jpg'); // Fallback default

        if (!$model->hasMedia($collection)) {
            // 1. Tidak ada media di DB -> gunakan fallback default
            return $defaultUrl;
        }


        $url = $model->getFirstMediaUrl($collection, $conversion);

        return $url;
    }
}

if (! function_exists('formatTanggalIndo')) {
    /**
     * Format tanggal ke bahasa Indonesia
     *
     * @param mixed $tanggal
     * @return string
     */
    function formatTanggalIndo($tanggal)
    {
        if (!$tanggal) {
            return '-';
        }

        // Konversi ke Carbon jika bukan Carbon instance
        if (!($tanggal instanceof Carbon)) {
            $tanggal = Carbon::parse($tanggal);
        }

        $hariList = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $bulanList = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        $hari = $hariList[$tanggal->format('l')];
        $tanggalNum = $tanggal->format('d');
        $bulan = $bulanList[$tanggal->format('F')];
        $tahun = $tanggal->format('Y');

        // Jika format waktu (H:i) tidak kosong, berarti ada waktu yang harus ditampilkan
        $waktu = $tanggal->format('H:i');
        if ($waktu !== '00:00') {
            return "$hari, $tanggalNum $bulan $tahun $waktu";
        } else {
            // Jika tidak ada waktu, hanya tampilkan tanggal
            return "$tanggalNum $bulan $tahun";
        }
    }
}
