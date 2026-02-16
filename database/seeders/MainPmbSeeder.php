<?php
namespace Database\Seeders;

use App\Models\Pmb\Jalur;
use App\Models\Pmb\JenisDokumen;
use App\Models\Pmb\Periode;
use App\Models\Pmb\Prodi;
use App\Models\Pmb\SyaratDokumenJalur;
use Illuminate\Database\Seeder;

class MainPmbSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Periode
        $periode = Periode::create([
            'nama_periode'    => 'Seleksi Mahasiswa Baru 2024/2025',
            'tanggal_mulai'   => now(),
            'tanggal_selesai' => now()->addMonths(6),
            'is_aktif'        => true,
        ]);

        // 2. Jalur
        // 2. Jalur
        $jalurReguler = Jalur::create([
            'nama_jalur'        => 'Reguler',
            'biaya_pendaftaran' => 350000,
            'is_aktif'          => true,
        ]);

        $jalurPrestasi = Jalur::create([
            'nama_jalur'        => 'Prestasi',
            'biaya_pendaftaran' => 0,
            'is_aktif'          => true,
        ]);

        // 3. Prodi
        // 3. Prodi
        $prodis = [
            ['nama_prodi' => 'Teknik Informatika', 'kode_prodi' => 'TI', 'fakultas' => 'Ilmu Komputer'],
            ['nama_prodi' => 'Sistem Informasi', 'kode_prodi' => 'SI', 'fakultas' => 'Ilmu Komputer'],
            ['nama_prodi' => 'Teknik Elektro', 'kode_prodi' => 'TE', 'fakultas' => 'Teknik'],
            ['nama_prodi' => 'Akuntansi', 'kode_prodi' => 'AKT', 'fakultas' => 'Ekonomi'],
        ];

        foreach ($prodis as $prodi) {
            Prodi::create($prodi);
        }

        // 4. Jenis Dokumen
        $dokIjazah   = JenisDokumen::create(['nama_dokumen' => 'Ijazah SMA/SMK']);
        $dokKK       = JenisDokumen::create(['nama_dokumen' => 'Kartu Keluarga']);
        $dokFoto     = JenisDokumen::create(['nama_dokumen' => 'Pas Foto 4x6']);
        $dokPrestasi = JenisDokumen::create(['nama_dokumen' => 'Sertifikat Prestasi']);

        // 5. Syarat Dokumen Jalur
        SyaratDokumenJalur::create(['jalur_id' => $jalurReguler->id, 'jenis_dokumen_id' => $dokIjazah->id]);
        SyaratDokumenJalur::create(['jalur_id' => $jalurReguler->id, 'jenis_dokumen_id' => $dokKK->id]);
        SyaratDokumenJalur::create(['jalur_id' => $jalurReguler->id, 'jenis_dokumen_id' => $dokFoto->id]);

        SyaratDokumenJalur::create(['jalur_id' => $jalurPrestasi->id, 'jenis_dokumen_id' => $dokIjazah->id]);
        SyaratDokumenJalur::create(['jalur_id' => $jalurPrestasi->id, 'jenis_dokumen_id' => $dokKK->id]);
        SyaratDokumenJalur::create(['jalur_id' => $jalurPrestasi->id, 'jenis_dokumen_id' => $dokFoto->id]);
        SyaratDokumenJalur::create(['jalur_id' => $jalurPrestasi->id, 'jenis_dokumen_id' => $dokPrestasi->id]);

        // 6. Sesi Ujian (Needed for exam flow)
        $sesi1 = \App\Models\Pmb\SesiUjian::create([
            'periode_id'    => $periode->id,
            'nama_sesi'     => 'Gelombang 1 - Sesi Pagi',
            'waktu_mulai'   => now()->addDays(7)->setTime(8, 0),
            'waktu_selesai' => now()->addDays(7)->setTime(10, 0),
            'lokasi'        => 'Lab Komputer 1',
            'kuota'         => 100,
        ]);

        $sesi2 = \App\Models\Pmb\SesiUjian::create([
            'periode_id'    => $periode->id,
            'nama_sesi'     => 'Gelombang 1 - Sesi Siang',
            'waktu_mulai'   => now()->addDays(7)->setTime(13, 0),
            'waktu_selesai' => now()->addDays(7)->setTime(15, 0),
            'lokasi'        => 'Lab Komputer 2',
            'kuota'         => 100,
        ]);

        $sesiList = [$sesi1, $sesi2];

        // 7. Generate 300 Camaba
        $this->command->info('Generating 300 Camaba data...');
        $faker = \Faker\Factory::create('id_ID');

        $statuses = [
            'Draft', 'Menunggu_Verifikasi_Bayar', 'Menunggu_Verifikasi_Berkas',
            'Revisi_Berkas', 'Siap_Ujian', 'Selesai_Ujian', 'Lulus', 'Tidak_Lulus', 'Daftar_Ulang',
        ];

        for ($i = 0; $i < 300; $i++) {
            // Create User
            $email = 'camaba' . ($i + 1) . '@pmb.test';
            $user  = \App\Models\User::firstOrCreate(
                ['email' => $email],
                [
                    'name'              => $faker->name,
                    'password'          => \Illuminate\Support\Facades\Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('mahasiswa'); // Assuming 'mahasiswa' role implies student/candidate

            // Create Profil
            \App\Models\Pmb\ProfilMahasiswa::create([
                'user_id'          => $user->id,
                'nik'              => $faker->unique()->numerify('14##############'),
                'no_hp'            => $faker->phoneNumber,
                'tempat_lahir'     => $faker->city,
                'tanggal_lahir'    => $faker->date('Y-m-d', '2006-01-01'),
                'jenis_kelamin'    => $faker->randomElement(['L', 'P']),
                'alamat_lengkap'   => $faker->address,
                'asal_sekolah'     => 'SMA ' . $faker->company,
                'nisn'             => $faker->numerify('00########'),
                'nama_ibu_kandung' => $faker->name('female'),
            ]);

            // Determine Flow Status
            // Weighted random to have more data in later stages
            $status = $faker->randomElement([
                'Draft',
                'Menunggu_Verifikasi_Bayar',
                'Menunggu_Verifikasi_Berkas', 'Menunggu_Verifikasi_Berkas',
                'Siap_Ujian',
                'Selesai_Ujian',
                'Lulus', 'Lulus',
                'Tidak_Lulus',
                'Daftar_Ulang', 'Daftar_Ulang', 'Daftar_Ulang',
            ]);

            $jalur = ($i % 10 < 3) ? $jalurPrestasi : $jalurReguler;

            // Create Pendaftaran
            $pendaftaran = \App\Models\Pmb\Pendaftaran::create([
                'no_pendaftaran' => 'REG-2024-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'user_id'        => $user->id,
                'periode_id'     => $periode->id,
                'jalur_id'       => $jalur->id,
                'status_terkini' => $status,
                'waktu_daftar'   => $faker->dateTimeBetween('-1 month', 'now'),
            ]);

                                       // Pilihan Prodi
            $prodi1 = $prodis[$i % 4]; // Distribute evenly
            $prodi2 = $prodis[($i + 1) % 4];

            \App\Models\Pmb\PilihanProdi::create([
                'pendaftaran_id' => $pendaftaran->id,
                'prodi_id'       => \App\Models\Pmb\Prodi::where('kode_prodi', $prodi1['kode_prodi'])->first()->id,
                'urutan'         => 1,
            ]);

            \App\Models\Pmb\PilihanProdi::create([
                'pendaftaran_id' => $pendaftaran->id,
                'prodi_id'       => \App\Models\Pmb\Prodi::where('kode_prodi', $prodi2['kode_prodi'])->first()->id,
                'urutan'         => 2,
            ]);

            // Simulation Logic based on Status

            // 1. Payment (Formulir)
            if ($status != 'Draft') {
                \App\Models\Pmb\Pembayaran::create([
                    'pendaftaran_id'    => $pendaftaran->id,
                    'jenis_bayar'       => 'Formulir',
                    'jumlah_bayar'      => $jalur->biaya_pendaftaran,
                    'bukti_bayar_path'  => 'dummy/bukti_bayar.jpg',
                    'status_verifikasi' => ($status == 'Menunggu_Verifikasi_Bayar') ? 'Pending' : 'Lunas',
                    'verifikator_id'    => ($status != 'Menunggu_Verifikasi_Bayar') ? 1 : null, // Admin ID 1
                    'waktu_bayar'       => now()->subDays(rand(1, 20)),
                ]);
            }

            // 2. Upload Documents
            if (! in_array($status, ['Draft', 'Menunggu_Verifikasi_Bayar'])) {
                $docs = \App\Models\Pmb\SyaratDokumenJalur::where('jalur_id', $jalur->id)->get();
                foreach ($docs as $doc) {
                    \App\Models\Pmb\DokumenUpload::create([
                        'pendaftaran_id'    => $pendaftaran->id,
                        'jenis_dokumen_id'  => $doc->jenis_dokumen_id,
                        'path_file'         => 'dummy/doc_' . $doc->jenis_dokumen_id . '.pdf',
                        'status_verifikasi' => ($status == 'Menunggu_Verifikasi_Berkas') ? 'Pending' : 'Valid',
                        'waktu_upload'      => now()->subDays(rand(1, 15)),
                    ]);
                }
            }

            // 3. Exam
            if (! in_array($status, ['Draft', 'Menunggu_Verifikasi_Bayar', 'Menunggu_Verifikasi_Berkas', 'Revisi_Berkas'])) {
                $sesi  = $sesiList[$i % 2];
                $nilai = $faker->randomFloat(2, 40, 100);

                \App\Models\Pmb\PesertaUjian::create([
                    'pendaftaran_id'   => $pendaftaran->id,
                    'sesi_id'          => $sesi->id,
                    'username_cbt'     => 'usercbt' . ($i + 1),
                    'password_cbt'     => 'passcbt',
                    'nilai_akhir'      => ($status == 'Siap_Ujian') ? null : $nilai,
                    'status_kehadiran' => ($status != 'Siap_Ujian'),
                ]);
            }

            // 4. Final Decision & Re-registration
            if (in_array($status, ['Lulus', 'Daftar_Ulang'])) {
                // Update PilihanProdi decision
                \App\Models\Pmb\PilihanProdi::where('pendaftaran_id', $pendaftaran->id)
                    ->where('urutan', 1)
                    ->update(['keputusan_admin' => 'Disetujui', 'rekomendasi_sistem' => 'Lulus']);

                // Assign Accepted Prodi
                $pendaftaran->prodi_diterima_id = \App\Models\Pmb\Prodi::where('kode_prodi', $prodi1['kode_prodi'])->first()->id;

                if ($status == 'Daftar_Ulang') {
                    $pendaftaran->nim_final = '2024' . $prodi1['kode_prodi'] . str_pad($i, 3, '0', STR_PAD_LEFT);

                    // Daftar Ulang Payment
                    \App\Models\Pmb\Pembayaran::create([
                        'pendaftaran_id'    => $pendaftaran->id,
                        'jenis_bayar'       => 'Daftar_Ulang',
                        'jumlah_bayar'      => 5000000,
                        'bukti_bayar_path'  => 'dummy/daftar_ulang.jpg',
                        'status_verifikasi' => 'Lunas',
                        'waktu_bayar'       => now(),
                    ]);
                }
                $pendaftaran->save();
            } elseif ($status == 'Tidak_Lulus') {
                \App\Models\Pmb\PilihanProdi::where('pendaftaran_id', $pendaftaran->id)
                    ->update(['keputusan_admin' => 'Ditolak', 'rekomendasi_sistem' => 'Gagal']);
            }
        }
    }
}
