<?php
namespace Database\Seeders;

use App\Models\Pmb\Jalur;
use App\Models\Pmb\JenisDokumen;
use App\Models\Pmb\Periode;
use App\Models\Pmb\SyaratDokumenJalur;
use App\Models\Shared\StrukturOrganisasi as OrgUnit;
use Illuminate\Database\Seeder;

class MainPmbSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Periode
        $periode = Periode::updateOrCreate(
            ['nama_periode' => 'Seleksi Mahasiswa Baru 2024/2025'],
            [
                'tanggal_mulai'   => now(),
                'tanggal_selesai' => now()->addMonths(6),
                'is_aktif'        => true,
            ]
        );

        // 2. Jalur
        $jalurReguler = Jalur::updateOrCreate(
            ['nama_jalur' => 'Reguler'],
            [
                'biaya_pendaftaran' => 350000,
                'is_aktif'          => true,
            ]
        );

        $jalurPrestasi = Jalur::updateOrCreate(
            ['nama_jalur' => 'Prestasi'],
            [
                'biaya_pendaftaran' => 0,
                'is_aktif'          => true,
            ]
        );

        // 3. Prodi (Ensure OrgUnits exist)
        // MainPemutuSeeder might have created some, but we ensure all PMB prodis exist here.
        $prodisData = [
            ['nama_prodi' => 'Teknik Informatika', 'kode_prodi' => 'TI', 'fakultas' => 'Ilmu Komputer'],
            ['nama_prodi' => 'Sistem Informasi', 'kode_prodi' => 'SI', 'fakultas' => 'Ilmu Komputer'],
            ['nama_prodi' => 'Teknik Elektro', 'kode_prodi' => 'TE', 'fakultas' => 'Teknik'],
            ['nama_prodi' => 'Akuntansi', 'kode_prodi' => 'AKT', 'fakultas' => 'Ekonomi'],
        ];

        // Ensure Root exists if not
        $pcr = OrgUnit::firstOrCreate(['code' => 'PCR'], ['name' => 'Politeknik Caltex Riau', 'type' => 'Institusi', 'level' => 1]);

        $prodiOrgUnits = [];

        foreach ($prodisData as $data) {
            // Create Faculty/Jurusan first (simplified mapping for seeder)
            $jurusanCode = 'JUR-' . strtoupper(substr($data['fakultas'], 0, 3));
            $jurusan     = OrgUnit::firstOrCreate(
                ['code' => $jurusanCode],
                [
                    'name'      => 'Jurusan ' . $data['fakultas'],
                    'type'      => 'Jurusan',
                    'level'     => 2,
                    'parent_id' => $pcr->orgunit_id,
                ]
            );

            // Create Prodi
            $prodi = OrgUnit::firstOrCreate(
                ['code' => $data['kode_prodi']],
                [
                    'name'      => $data['nama_prodi'],
                    'type'      => 'Prodi',
                    'level'     => 3,
                    'parent_id' => $jurusan->orgunit_id,
                ]
            );
            $prodiOrgUnits[] = $prodi;
        }

        // 4. Jenis Dokumen
        $dokIjazah   = JenisDokumen::firstOrCreate(['nama_dokumen' => 'Ijazah SMA/SMK']);
        $dokKK       = JenisDokumen::firstOrCreate(['nama_dokumen' => 'Kartu Keluarga']);
        $dokFoto     = JenisDokumen::firstOrCreate(['nama_dokumen' => 'Pas Foto 4x6']);
        $dokPrestasi = JenisDokumen::firstOrCreate(['nama_dokumen' => 'Sertifikat Prestasi']);

        // 5. Syarat Dokumen Jalur
        SyaratDokumenJalur::firstOrCreate(['jalur_id' => $jalurReguler->jalur_id, 'jenis_dokumen_id' => $dokIjazah->jenis_dokumen_id]);
        SyaratDokumenJalur::firstOrCreate(['jalur_id' => $jalurReguler->jalur_id, 'jenis_dokumen_id' => $dokKK->jenis_dokumen_id]);
        SyaratDokumenJalur::firstOrCreate(['jalur_id' => $jalurReguler->jalur_id, 'jenis_dokumen_id' => $dokFoto->jenis_dokumen_id]);

        SyaratDokumenJalur::firstOrCreate(['jalur_id' => $jalurPrestasi->jalur_id, 'jenis_dokumen_id' => $dokIjazah->jenis_dokumen_id]);
        SyaratDokumenJalur::firstOrCreate(['jalur_id' => $jalurPrestasi->jalur_id, 'jenis_dokumen_id' => $dokKK->jenis_dokumen_id]);
        SyaratDokumenJalur::firstOrCreate(['jalur_id' => $jalurPrestasi->jalur_id, 'jenis_dokumen_id' => $dokFoto->jenis_dokumen_id]);
        SyaratDokumenJalur::firstOrCreate(['jalur_id' => $jalurPrestasi->jalur_id, 'jenis_dokumen_id' => $dokPrestasi->jenis_dokumen_id]);

        // 6. Sesi Ujian (Needed for exam flow)
        $sesi1 = \App\Models\Pmb\SesiUjian::updateOrCreate(
            ['nama_sesi' => 'Gelombang 1 - Sesi Pagi', 'periode_id' => $periode->periode_id],
            [
                'waktu_mulai'   => now()->addDays(7)->setTime(8, 0),
                'waktu_selesai' => now()->addDays(7)->setTime(10, 0),
                'lokasi'        => 'Lab Komputer 1',
                'kuota'         => 100,
            ]
        );

        $sesi2 = \App\Models\Pmb\SesiUjian::updateOrCreate(
            ['nama_sesi' => 'Gelombang 1 - Sesi Siang', 'periode_id' => $periode->periode_id],
            [
                'waktu_mulai'   => now()->addDays(7)->setTime(13, 0),
                'waktu_selesai' => now()->addDays(7)->setTime(15, 0),
                'lokasi'        => 'Lab Komputer 2',
                'kuota'         => 100,
            ]
        );

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

            // Create Camaba with user_id relationship
            $existingCamaba = \App\Models\Pmb\Camaba::where('user_id', $user->id)->first();
            
            if (!$existingCamaba) {
                \App\Models\Pmb\Camaba::create([
                    'user_id'          => $user->id,
                    'nik'              => $faker->unique()->numerify('14##############'),
                    'nama'             => $user->name,
                    'email'            => $email,
                    'no_hp'            => $faker->phoneNumber,
                    'tempat_lahir'     => $faker->city,
                    'tanggal_lahir'    => $faker->date('Y-m-d', '2006-01-01'),
                    'jenis_kelamin'    => $faker->randomElement(['L', 'P']),
                    'alamat'           => $faker->address,
                    'angkatan'         => date('Y'),
                    'created_by'       => 'system',
                ]);
            }

            // Create Pendaftaran using the user
            \App\Models\Pmb\Camaba::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nik'              => $existingCamaba?->nik ?? $faker->unique()->numerify('14##############'),
                    'no_hp'            => $faker->phoneNumber,
                    'tempat_lahir'     => $faker->city,
                    'tanggal_lahir'    => $faker->date('Y-m-d', '2006-01-01'),
                    'jenis_kelamin'    => $faker->randomElement(['L', 'P']),
                    'alamat_lengkap'   => $faker->address,
                    'asal_sekolah'     => 'SMA ' . $faker->company,
                    'nisn'             => $faker->numerify('00########'),
                    'nama_ibu_kandung' => $faker->name('female'),
                ]
            );

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
            $pendaftaran = \App\Models\Pmb\Pendaftaran::updateOrCreate(
                ['user_id' => $user->id, 'periode_id' => $periode->periode_id],
                [
                    'no_pendaftaran' => 'REG-2024-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'jalur_id'       => $jalur->jalur_id,
                    'status_terkini' => $status,
                    'waktu_daftar'   => $faker->dateTimeBetween('-1 month', 'now'),
                ]
            );

                                              // Pilihan Prodi
            $prodi1 = $prodiOrgUnits[$i % 4]; // Distribute evenly
            $prodi2 = $prodiOrgUnits[($i + 1) % 4];

            \App\Models\Pmb\PilihanProdi::updateOrCreate(
                ['pendaftaran_id' => $pendaftaran->pendaftaran_id, 'urutan' => 1],
                ['orgunit_id' => $prodi1->orgunit_id]
            );

            \App\Models\Pmb\PilihanProdi::updateOrCreate(
                ['pendaftaran_id' => $pendaftaran->pendaftaran_id, 'urutan' => 2],
                ['orgunit_id' => $prodi2->orgunit_id]
            );

            // Simulation Logic based on Status

            // 1. Payment (Formulir)
            if ($status != 'Draft') {
                \App\Models\Pmb\Pembayaran::updateOrCreate(
                    ['pendaftaran_id' => $pendaftaran->pendaftaran_id, 'jenis_bayar' => 'Formulir'],
                    [
                        'jumlah_bayar'      => $jalur->biaya_pendaftaran,
                        'bukti_bayar_path'  => 'dummy/bukti_bayar.jpg',
                        'status_verifikasi' => ($status == 'Menunggu_Verifikasi_Bayar') ? 'Pending' : 'Lunas',
                        'verifikator_id'    => ($status != 'Menunggu_Verifikasi_Bayar') ? 1 : null, // Admin ID 1
                        'waktu_bayar'       => now()->subDays(rand(1, 20)),
                    ]
                );
            }

            // 2. Upload Documents
            if (! in_array($status, ['Draft', 'Menunggu_Verifikasi_Bayar'])) {
                $docs = \App\Models\Pmb\SyaratDokumenJalur::where('jalur_id', $jalur->jalur_id)->get();
                foreach ($docs as $doc) {
                    \App\Models\Pmb\DokumenUpload::updateOrCreate(
                        ['pendaftaran_id' => $pendaftaran->pendaftaran_id, 'jenis_dokumen_id' => $doc->jenis_dokumen_id],
                        [
                            'path_file'         => 'dummy/doc_' . $doc->jenis_dokumen_id . '.pdf',
                            'status_verifikasi' => ($status == 'Menunggu_Verifikasi_Berkas') ? 'Pending' : 'Valid',
                            'waktu_upload'      => now()->subDays(rand(1, 15)),
                        ]
                    );
                }
            }

            // 3. Exam
            if (! in_array($status, ['Draft', 'Menunggu_Verifikasi_Bayar', 'Menunggu_Verifikasi_Berkas', 'Revisi_Berkas'])) {
                $sesi  = $sesiList[$i % 2];
                $nilai = $faker->randomFloat(2, 40, 100);

                \App\Models\Pmb\PesertaUjian::updateOrCreate(
                    ['pendaftaran_id' => $pendaftaran->pendaftaran_id],
                    [
                        'sesi_id'          => $sesi->sesiujian_id,
                        'username_cbt'     => 'usercbt' . ($user->id), // Use user ID for uniqueness
                        'password_cbt'     => 'passcbt',
                        'nilai_akhir'      => ($status == 'Siap_Ujian') ? null : $nilai,
                        'status_kehadiran' => ($status != 'Siap_Ujian'),
                    ]
                );
            }

            // 4. Final Decision & Re-registration
            // Note: Decisions should match orgunit_id of the chosen prodi
            if (in_array($status, ['Lulus', 'Daftar_Ulang'])) {
                // Update PilihanProdi decision
                \App\Models\Pmb\PilihanProdi::where('pendaftaran_id', $pendaftaran->pendaftaran_id)
                    ->where('urutan', 1)
                    ->update(['keputusan_admin' => 'Disetujui', 'rekomendasi_sistem' => 'Lulus']);

                // Assign Accepted Prodi (OrgUnit)
                $pendaftaran->orgunit_diterima_id = $prodi1->orgunit_id;

                if ($status == 'Daftar_Ulang') {
                    $pendaftaran->nim_final = '2024' . $prodi1->code . str_pad($i, 3, '0', STR_PAD_LEFT);

                    // Daftar Ulang Payment
                    \App\Models\Pmb\Pembayaran::updateOrCreate(
                        ['pendaftaran_id' => $pendaftaran->pendaftaran_id, 'jenis_bayar' => 'Daftar_Ulang'],
                        [
                            'jumlah_bayar'      => 5000000,
                            'bukti_bayar_path'  => 'dummy/daftar_ulang.jpg',
                            'status_verifikasi' => 'Lunas',
                            'waktu_bayar'       => now(),
                        ]
                    );
                }
                $pendaftaran->save();
            } elseif ($status == 'Tidak_Lulus') {
                \App\Models\Pmb\PilihanProdi::where('pendaftaran_id', $pendaftaran->pendaftaran_id)
                    ->update(['keputusan_admin' => 'Ditolak', 'rekomendasi_sistem' => 'Gagal']);
            }
        }
    }
}
