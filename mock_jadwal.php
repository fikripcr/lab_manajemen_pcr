<?php
use App\Models\Cbt\JadwalUjian;
use App\Models\Cbt\PaketUjian;

$paket = PaketUjian::first();
if (! $paket) {
    echo "NO_PAKET\n";
    exit;
}

$jadwal = JadwalUjian::create([
    'paket_id'       => $paket->paket_ujian_id,
    'nama_kegiatan'  => 'Ujian Testing Timer - ' . time(),
    'waktu_mulai'    => now(),
    'waktu_selesai'  => now()->addMinutes(2),
    'durasi_menit'   => 2,
    'token_ujian'    => 'TEST1',
    'is_token_aktif' => true,
    'keterangan'     => 'Testing JS Timer Auto Submit',
]);

echo "JADWAL_HASHID:" . $jadwal->hashid . "\n";
