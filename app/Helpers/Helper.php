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
        $defaultUrl = asset('img/no-image.jpg'); // Fallback default

        if (!$model->hasMedia($collection)) {
            // 1. Tidak ada media di DB -> gunakan fallback default
            return $defaultUrl;
        }

        $media = $model->getFirstMedia($collection);

        if (!$media) {
            // 2. Media tidak ditemukan -> gunakan fallback default
            return $defaultUrl;
        }

        // Check if the actual file exists on disk
        $mediaPath = storage_path('app/public/' . $media->file_path);
        if (!file_exists($mediaPath)) {
            // 3. File media tidak ditemukan di disk -> gunakan fallback default
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

        // Cek jika ini adalah format waktu murni (HH:ii tanpa tanggal)
        if (is_string($tanggal) && preg_match('/^\d{2}:\d{2}$/', $tanggal)) {
            // Jika hanya format HH:MM, tampilkan sebagai waktu saja
            return $tanggal;
        }

        // Konversi ke Carbon jika bukan Carbon instance
        if (!($tanggal instanceof Carbon)) {
            $tanggal = Carbon::parse($tanggal);
        }

        // Jika hanya waktu (tanpa tanggal valid), tampilkan sebagai waktu saja
        $formatted = $tanggal->format('Y-m-d H:i:s');
        if ($formatted === $tanggal->format('0000-00-00 H:i:s')) {
            // Ini adalah nilai waktu tanpa tanggal, tampilkan hanya jam
            return $tanggal->format('H:i');
        }

        // Jika hanya tanggal (00:00:00), tampilkan sebagai tanggal saja
        if ($tanggal->format('H:i:s') === '00:00:00') {
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

            $tanggalNum = $tanggal->format('d');
            $bulan = $bulanList[$tanggal->format('F')];
            $tahun = $tanggal->format('Y');

            return "$tanggalNum $bulan $tahun";
        }

        // Tampilkan tanggal dan waktu lengkap
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
        $waktu = $tanggal->format('H:i');

        return "$hari, $tanggalNum $bulan $tahun $waktu";
    }
}

if (! function_exists('formatWaktuSaja')) {
    /**
     * Format hanya waktu ke format HH:ii (tanpa tanggal)
     *
     * @param mixed $waktu
     * @return string
     */
    function formatWaktuSaja($waktu)
    {
        if (!$waktu) {
            return '-';
        }

        // Cek jika ini adalah format waktu murni (HH:ii tanpa tanggal)
        if (is_string($waktu) && preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $waktu)) {
            // Jika hanya format HH:MM atau HH:MM:SS, tampilkan hanya jam dan menit
            $parts = explode(':', $waktu);
            return $parts[0] . ':' . $parts[1];
        }

        // Konversi ke Carbon jika bukan Carbon instance
        if (!($waktu instanceof Carbon)) {
            $waktu = Carbon::parse($waktu);
        }

        // Kembalikan hanya format jam:menit
        return $waktu->format('H:i');
    }
}

if (! function_exists('generateKodeInventaris')) {
    /**
     * Generate unique kode inventaris
     *
     * @param int $labId
     * @param int $inventarisId
     * @return string
     */
    function generateKodeInventaris($labId, $inventarisId)
    {
        $lab = \App\Models\Lab::find($labId);
        $inventaris = \App\Models\Inventaris::find($inventarisId);

        if (!$lab || !$inventaris) {
            return null;
        }

        $labCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $lab->name ?? ''), 0, 3));
        $invCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $inventaris->nama_alat ?? ''), 0, 3));

        // Ambil jumlah inventaris yang sudah ada di lab ini untuk urutan
        $count = \App\Models\LabInventaris::where('lab_id', $labId)
                    ->where('inventaris_id', $inventarisId)
                    ->count() + 1;

        return sprintf('%s-%s-%04d', $labCode, $invCode, $count);
    }
}
