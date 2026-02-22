<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Cbt{
/**
 * @property int $jadwal_ujian_id
 * @property int $paket_id
 * @property string $nama_kegiatan
 * @property \Illuminate\Support\Carbon $waktu_mulai
 * @property \Illuminate\Support\Carbon $waktu_selesai
 * @property string|null $token_ujian
 * @property bool $is_token_aktif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_jadwal_ujian_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Cbt\PaketUjian $paket
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cbt\PesertaBerhak> $pesertaBerhak
 * @property-read int|null $peserta_berhak_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cbt\RiwayatUjianSiswa> $riwayatSiswa
 * @property-read int|null $riwayat_siswa_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian whereIsTokenAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian whereJadwalUjianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian whereNamaKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian wherePaketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian whereTokenUjian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian whereWaktuMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian whereWaktuSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalUjian withoutTrashed()
 */
	class JadwalUjian extends \Eloquent {}
}

namespace App\Models\Cbt{
/**
 * @property int $jawaban_siswa_id
 * @property int $riwayat_id
 * @property int $soal_id
 * @property int|null $opsi_dipilih_id
 * @property string|null $jawaban_esai
 * @property bool $is_ragu
 * @property numeric $nilai_didapat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $encrypted_jawaban_siswa_id
 * @property-read \App\Models\Cbt\OpsiJawaban|null $opsi
 * @property-read \App\Models\Cbt\RiwayatUjianSiswa $riwayat
 * @property-read \App\Models\Cbt\Soal $soal
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JawabanSiswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JawabanSiswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JawabanSiswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JawabanSiswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JawabanSiswa whereIsRagu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JawabanSiswa whereJawabanEsai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JawabanSiswa whereJawabanSiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JawabanSiswa whereNilaiDidapat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JawabanSiswa whereOpsiDipilihId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JawabanSiswa whereRiwayatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JawabanSiswa whereSoalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JawabanSiswa whereUpdatedAt($value)
 */
	class JawabanSiswa extends \Eloquent {}
}

namespace App\Models\Cbt{
/**
 * @property int $komposisi_paket_id
 * @property int $paket_id
 * @property int $soal_id
 * @property int $urutan_tampil
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $encrypted_komposisi_paket_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Cbt\PaketUjian $paket
 * @property-read \App\Models\Cbt\Soal $soal
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KomposisiPaket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KomposisiPaket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KomposisiPaket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KomposisiPaket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KomposisiPaket whereKomposisiPaketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KomposisiPaket wherePaketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KomposisiPaket whereSoalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KomposisiPaket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KomposisiPaket whereUrutanTampil($value)
 */
	class KomposisiPaket extends \Eloquent {}
}

namespace App\Models\Cbt{
/**
 * @property int $log_pelanggaran_id
 * @property int $riwayat_id
 * @property string $jenis_pelanggaran
 * @property \Illuminate\Support\Carbon $waktu_kejadian
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $encrypted_log_pelanggaran_id
 * @property-read \App\Models\Cbt\RiwayatUjianSiswa $riwayatUjianSiswa
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPelanggaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPelanggaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPelanggaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPelanggaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPelanggaran whereJenisPelanggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPelanggaran whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPelanggaran whereLogPelanggaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPelanggaran whereRiwayatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPelanggaran whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPelanggaran whereWaktuKejadian($value)
 */
	class LogPelanggaran extends \Eloquent {}
}

namespace App\Models\Cbt{
/**
 * @property int $mata_uji_id
 * @property string $nama_mata_uji
 * @property string $tipe Pemisah konteks penggunaan
 * @property int|null $durasi_menit
 * @property string|null $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_mata_uji_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cbt\Soal> $soal
 * @property-read int|null $soal_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataUji newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataUji newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataUji onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataUji query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataUji whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataUji whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataUji whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataUji whereDurasiMenit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataUji whereMataUjiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataUji whereNamaMataUji($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataUji whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataUji whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataUji withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataUji withoutTrashed()
 */
	class MataUji extends \Eloquent {}
}

namespace App\Models\Cbt{
/**
 * @property int $opsi_jawaban_id
 * @property int $soal_id
 * @property string $label A, B, C, D, E
 * @property string|null $teks_jawaban
 * @property string|null $media_url
 * @property bool $is_kunci_jawaban
 * @property int $bobot_nilai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_opsi_jawaban_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Cbt\Soal $soal
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban whereBobotNilai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban whereIsKunciJawaban($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban whereMediaUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban whereOpsiJawabanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban whereSoalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban whereTeksJawaban($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpsiJawaban withoutTrashed()
 */
	class OpsiJawaban extends \Eloquent {}
}

namespace App\Models\Cbt{
/**
 * @property int $paket_ujian_id
 * @property string $nama_paket
 * @property string $tipe_paket
 * @property int $total_soal
 * @property int $total_durasi_menit
 * @property bool $is_acak_soal
 * @property bool $is_acak_opsi
 * @property int $kk_nilai_minimal
 * @property int $dibuat_oleh
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_paket_ujian_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cbt\JadwalUjian> $jadwal
 * @property-read int|null $jadwal_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cbt\KomposisiPaket> $komposisi
 * @property-read int|null $komposisi_count
 * @property-read \App\Models\User $pembuat
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian whereDibuatOleh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian whereIsAcakOpsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian whereIsAcakSoal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian whereKkNilaiMinimal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian whereNamaPaket($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian wherePaketUjianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian whereTipePaket($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian whereTotalDurasiMenit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian whereTotalSoal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaketUjian withoutTrashed()
 */
	class PaketUjian extends \Eloquent {}
}

namespace App\Models\Cbt{
/**
 * @property int $peserta_berhak_id
 * @property int $jadwal_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $encrypted_peserta_berhak_id
 * @property-read \App\Models\Cbt\JadwalUjian $jadwal
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaBerhak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaBerhak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaBerhak query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaBerhak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaBerhak whereJadwalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaBerhak wherePesertaBerhakId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaBerhak whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaBerhak whereUserId($value)
 */
	class PesertaBerhak extends \Eloquent {}
}

namespace App\Models\Cbt{
/**
 * @property int $riwayat_ujian_id
 * @property int $jadwal_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $waktu_mulai
 * @property \Illuminate\Support\Carbon|null $waktu_selesai
 * @property int|null $sisa_waktu_terakhir Snapshot detik tersisa jika crash
 * @property numeric $nilai_akhir
 * @property string $status
 * @property string|null $ip_address
 * @property string|null $browser_info
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_riwayat_ujian_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Cbt\JadwalUjian $jadwal
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cbt\JawabanSiswa> $jawaban
 * @property-read int|null $jawaban_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cbt\LogPelanggaran> $logPelanggaran
 * @property-read int|null $log_pelanggaran_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa whereBrowserInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa whereJadwalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa whereNilaiAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa whereRiwayatUjianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa whereSisaWaktuTerakhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa whereWaktuMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa whereWaktuSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatUjianSiswa withoutTrashed()
 */
	class RiwayatUjianSiswa extends \Eloquent {}
}

namespace App\Models\Cbt{
/**
 * @property int $soal_id
 * @property int $mata_uji_id
 * @property string $tipe_soal
 * @property string $konten_pertanyaan Bisa HTML/Rich Text
 * @property string|null $media_url Gambar/Audio jika ada
 * @property string $tingkat_kesulitan
 * @property bool $is_aktif
 * @property int $dibuat_oleh
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_soal_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Cbt\MataUji $mataUji
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cbt\OpsiJawaban> $opsiJawaban
 * @property-read int|null $opsi_jawaban_count
 * @property-read \App\Models\User $pembuat
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal whereDibuatOleh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal whereIsAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal whereKontenPertanyaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal whereMataUjiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal whereMediaUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal whereSoalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal whereTingkatKesulitan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal whereTipeSoal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soal withoutTrashed()
 */
	class Soal extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $feedback_id
 * @property int $layanan_id
 * @property int $rating
 * @property string|null $feedback
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_feedback_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Eoffice\Layanan $layanan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereFeedbackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereLayananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback withoutTrashed()
 */
	class Feedback extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $jenislayanan_id
 * @property string $nama_layanan
 * @property string $kategori
 * @property string|null $bidang_terkait
 * @property int $batas_pengerjaan
 * @property bool $is_diskusi
 * @property bool $is_fitur_keterlibatan
 * @property string|null $jenis_khusus
 * @property array<array-key, mixed>|null $only_show_on
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Eoffice\JenisLayananDisposisi> $disposisis
 * @property-read int|null $disposisis_count
 * @property-read mixed $encrypted_jenislayanan_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Eoffice\JenisLayananIsian> $isians
 * @property-read int|null $isians_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Eoffice\Layanan> $layanans
 * @property-read int|null $layanans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Eoffice\JenisLayananPeriode> $periodes
 * @property-read int|null $periodes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Eoffice\JenisLayananPic> $pics
 * @property-read int|null $pics_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereBatasPengerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereBidangTerkait($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereIsDiskusi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereIsFiturKeterlibatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereJenisKhusus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereJenislayananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereNamaLayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereOnlyShowOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan withoutTrashed()
 */
	class JenisLayanan extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $jldisposisi_id
 * @property int $jenislayanan_id
 * @property int $seq
 * @property string|null $model Posisi/JabatanStruktural/Lainnya
 * @property string|null $value
 * @property string|null $text
 * @property bool $is_notify_email
 * @property int $batas_pengerjaan
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_jldisposisi_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Eoffice\JenisLayanan $jenisLayanan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi whereBatasPengerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi whereIsNotifyEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi whereJenislayananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi whereJldisposisiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi whereSeq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananDisposisi withoutTrashed()
 */
	class JenisLayananDisposisi extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $jlisian_id
 * @property int $jenislayanan_id
 * @property int $kategoriisian_id
 * @property int $seq
 * @property bool $is_required
 * @property bool $is_show_on_validasi
 * @property string $fill_by Pemohon/Disposisi 1/Disposisi 2/Sistem
 * @property string|null $rule
 * @property string|null $info_tambahan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_jlisian_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Eoffice\JenisLayanan $jenisLayanan
 * @property-read \App\Models\Eoffice\KategoriIsian $kategoriIsian
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian whereFillBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian whereInfoTambahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian whereIsShowOnValidasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian whereJenislayananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian whereJlisianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian whereKategoriisianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian whereRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian whereSeq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananIsian withoutTrashed()
 */
	class JenisLayananIsian extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $jlperiode_id
 * @property int $jenislayanan_id
 * @property \Illuminate\Support\Carbon $tgl_mulai
 * @property \Illuminate\Support\Carbon $tgl_selesai
 * @property string|null $tahun_ajaran
 * @property string|null $semester
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_jlperiode_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Eoffice\JenisLayanan $jenisLayanan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode whereJenislayananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode whereJlperiodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode whereTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode whereTglMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode whereTglSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPeriode withoutTrashed()
 */
	class JenisLayananPeriode extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $jlpic_id
 * @property int $jenislayanan_id
 * @property int $user_id
 * @property string|null $expired
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_jlpic_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Eoffice\JenisLayanan $jenisLayanan
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic whereExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic whereJenislayananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic whereJlpicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayananPic withoutTrashed()
 */
	class JenisLayananPic extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $kategoriisian_id
 * @property string $nama_isian
 * @property string $type text/date/file/select
 * @property array<array-key, mixed>|null $type_value
 * @property string|null $keterangan_isian
 * @property string|null $alias_on_document
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_kategoriisian_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian whereAliasOnDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian whereKategoriisianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian whereKeteranganIsian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian whereNamaIsian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian whereTypeValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriIsian withoutTrashed()
 */
	class KategoriIsian extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $kategoriperusahaan_id
 * @property string $nama_kategori
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_kategoriperusahaan_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Eoffice\Perusahaan> $perusahaan
 * @property-read int|null $perusahaan_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriPerusahaan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriPerusahaan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriPerusahaan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriPerusahaan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriPerusahaan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriPerusahaan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriPerusahaan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriPerusahaan whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriPerusahaan whereKategoriperusahaanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriPerusahaan whereNamaKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriPerusahaan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriPerusahaan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriPerusahaan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriPerusahaan withoutTrashed()
 */
	class KategoriPerusahaan extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $layanan_id
 * @property string $no_layanan
 * @property int $jenislayanan_id
 * @property string|null $pengusul_nama
 * @property string|null $pengusul_nim
 * @property string|null $pengusul_prodi
 * @property string|null $pengusul_email
 * @property string|null $pengusul_inisial
 * @property string|null $pengusul_jabstruktural
 * @property int|null $pic_awal
 * @property int|null $pic_pengganti
 * @property string|null $keterangan
 * @property array<array-key, mixed>|null $disposisi_info
 * @property array<array-key, mixed>|null $disposisi_list
 * @property int|null $latest_layananstatus_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Eoffice\LayananDiskusi> $diskusi
 * @property-read int|null $diskusi_count
 * @property-read \App\Models\Eoffice\Feedback|null $feedback
 * @property-read mixed $encrypted_layanan_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Eoffice\LayananIsian> $isians
 * @property-read int|null $isians_count
 * @property-read \App\Models\Eoffice\JenisLayanan $jenisLayanan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Eoffice\LayananKeterlibatan> $keterlibatan
 * @property-read int|null $keterlibatan_count
 * @property-read \App\Models\Eoffice\LayananStatus|null $latestStatus
 * @property-read \App\Models\Eoffice\LayananPeriode|null $periode
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Eoffice\LayananStatus> $statuses
 * @property-read int|null $statuses_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan whereDisposisiInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan whereDisposisiList($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan whereJenislayananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan whereLatestLayananstatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan whereLayananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan whereNoLayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan wherePengusulEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan wherePengusulInisial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan wherePengusulJabstruktural($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan wherePengusulNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan wherePengusulNim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan wherePengusulProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan wherePicAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan wherePicPengganti($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Layanan withoutTrashed()
 */
	class Layanan extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $layanandiskusi_id
 * @property int $layanan_id
 * @property int $user_id
 * @property string $pesan
 * @property string|null $file_lampiran
 * @property string|null $status_pengirim
 * @property string|null $created_by_email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_layanandiskusi_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Eoffice\Layanan $layanan
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi whereCreatedByEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi whereFileLampiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi whereLayananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi whereLayanandiskusiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi wherePesan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi whereStatusPengirim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananDiskusi withoutTrashed()
 */
	class LayananDiskusi extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $layananisian_id
 * @property int $layanan_id
 * @property string $nama_isian
 * @property string|null $isi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_layananisian_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Eoffice\Layanan $layanan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian whereIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian whereLayananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian whereLayananisianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian whereNamaIsian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananIsian withoutTrashed()
 */
	class LayananIsian extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $layananketerlibatan_id
 * @property int $layanan_id
 * @property int $user_id
 * @property string|null $peran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_layananketerlibatan_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Eoffice\Layanan $layanan
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan whereLayananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan whereLayananketerlibatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan wherePeran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananKeterlibatan withoutTrashed()
 */
	class LayananKeterlibatan extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $layananperiode_id
 * @property int $layanan_id
 * @property int $jlperiode_id
 * @property \Illuminate\Support\Carbon|null $tgl_mulai
 * @property \Illuminate\Support\Carbon|null $tgl_selesai
 * @property string|null $tahun_ajaran
 * @property string|null $semester
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_layananperiode_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Eoffice\JenisLayananPeriode $jenisLayananPeriode
 * @property-read \App\Models\Eoffice\Layanan $layanan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode whereJlperiodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode whereLayananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode whereLayananperiodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode whereTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode whereTglMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode whereTglSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPeriode withoutTrashed()
 */
	class LayananPeriode extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $layananstatus_id
 * @property int $layanan_id
 * @property string $status_layanan
 * @property string|null $keterangan
 * @property string|null $file_lampiran
 * @property array<array-key, mixed>|null $disposisi_info
 * @property \Illuminate\Support\Carbon|null $done_at
 * @property string|null $done_duration
 * @property string|null $done_by_email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_layananstatus_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Eoffice\Layanan $layanan
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus whereDisposisiInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus whereDoneAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus whereDoneByEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus whereDoneDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus whereFileLampiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus whereLayananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus whereLayananstatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus whereStatusLayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananStatus withoutTrashed()
 */
	class LayananStatus extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * Alias for backward compatibility.
 * 
 * The canonical Mahasiswa model is now App\Models\Shared\Mahasiswa.
 * All new code should use App\Models\Shared\Mahasiswa directly.
 *
 * @property string $mahasiswa_id
 * @property int|null $user_id
 * @property string $nim
 * @property string $nama
 * @property string $email
 * @property int|null $orgunit_id
 * @property string|null $jenis_kelamin
 * @property string|null $tempat_lahir
 * @property string|null $tanggal_lahir
 * @property string|null $agama
 * @property string|null $kewarganegaraan
 * @property string|null $no_hp
 * @property string|null $alamat
 * @property string|null $angkatan
 * @property string|null $foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_mahasiswa_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Shared\StrukturOrganisasi|null $prodi
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAngkatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereKewarganegaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereMahasiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereOrgunitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa withoutTrashed()
 */
	class Mahasiswa extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * Alias for backward compatibility.
 * 
 * The canonical Pegawai model is now App\Models\Shared\Pegawai.
 * All new code should use App\Models\Shared\Pegawai directly.
 *
 * @property int $pegawai_id
 * @property int|null $latest_riwayatdatadiri_id
 * @property int|null $latest_riwayatstatpegawai_id
 * @property int|null $latest_riwayatstataktifitas_id
 * @property int|null $latest_riwayatinpassing_id
 * @property int|null $latest_riwayatpendidikan_id
 * @property int|null $latest_riwayatjabfungsional_id
 * @property int|null $latest_riwayatjabstruktural_id
 * @property int|null $latest_riwayatpenugasan_id
 * @property int|null $atasan1
 * @property int|null $atasan2
 * @property string|null $photo Employee photo for face recognition
 * @property string|null $face_encoding Face encoding data for face matching
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \App\Models\Shared\Pegawai|null $atasanDua
 * @property-read \App\Models\Shared\Pegawai|null $atasanSatu
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\FilePegawai> $files
 * @property-read int|null $files_count
 * @property-read mixed $email
 * @property-read mixed $encrypted_pegawai_id
 * @property-read mixed $hashid
 * @property-read mixed $inisial
 * @property-read mixed $nama
 * @property-read mixed $nip
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatDataDiri> $historyDataDiri
 * @property-read int|null $history_data_diri_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatInpassing> $historyInpassing
 * @property-read int|null $history_inpassing_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatJabFungsional> $historyJabFungsional
 * @property-read int|null $history_jab_fungsional_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatJabStruktural> $historyJabStruktural
 * @property-read int|null $history_jab_struktural_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatPenugasan> $historyPenugasan
 * @property-read int|null $history_penugasan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatStatAktifitas> $historyStatAktifitas
 * @property-read int|null $history_stat_aktifitas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatStatPegawai> $historyStatPegawai
 * @property-read int|null $history_stat_pegawai_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\Keluarga> $keluarga
 * @property-read int|null $keluarga_count
 * @property-read \App\Models\Hr\RiwayatDataDiri|null $latestDataDiri
 * @property-read \App\Models\Hr\RiwayatInpassing|null $latestInpassing
 * @property-read \App\Models\Hr\RiwayatJabFungsional|null $latestJabatanFungsional
 * @property-read \App\Models\Hr\RiwayatJabStruktural|null $latestJabatanStruktural
 * @property-read \App\Models\Hr\RiwayatPendidikan|null $latestPendidikan
 * @property-read \App\Models\Hr\RiwayatPenugasan|null $latestPenugasan
 * @property-read \App\Models\Hr\RiwayatStatAktifitas|null $latestStatusAktifitas
 * @property-read \App\Models\Hr\RiwayatStatPegawai|null $latestStatusPegawai
 * @property-read \App\Models\Shared\StrukturOrganisasi|null $orgUnit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\PengembanganDiri> $pengembanganDiri
 * @property-read int|null $pengembangan_diri_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatPendidikan> $riwayatPendidikan
 * @property-read int|null $riwayat_pendidikan_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereAtasan1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereAtasan2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereFaceEncoding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatdatadiriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatinpassingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatjabfungsionalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatjabstrukturalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatpendidikanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatpenugasanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatstataktifitasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatstatpegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai withoutTrashed()
 */
	class Pegawai extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $perusahaan_id
 * @property int $kategoriperusahaan_id
 * @property string $nama_perusahaan
 * @property string|null $alamat
 * @property string|null $kota
 * @property string|null $telp
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_perusahaan_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Eoffice\KategoriPerusahaan $kategori
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereKategoriperusahaanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereNamaPerusahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan wherePerusahaanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereTelp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan withoutTrashed()
 */
	class Perusahaan extends \Eloquent {}
}

namespace App\Models\Eoffice{
/**
 * @property int $tanggaltidakhadir_id
 * @property string $jenis_ketidakhadiran
 * @property \Illuminate\Support\Carbon $tgl
 * @property string|null $keterangan
 * @property array<array-key, mixed>|null $additional_info
 * @property bool|null $is_full_day
 * @property string|null $waktu_mulai
 * @property string|null $waktu_selesai
 * @property string|null $model Layanan
 * @property int|null $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_tanggaltidakhadir_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereAdditionalInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereIsFullDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereJenisKetidakhadiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereTanggaltidakhadirId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereTgl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereWaktuMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir whereWaktuSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakHadir withoutTrashed()
 */
	class TanggalTidakHadir extends \Eloquent {}
}

namespace App\Models\Event{
/**
 * @property int $event_id
 * @property string $judul_event
 * @property string|null $jenis_event
 * @property \Illuminate\Support\Carbon $tanggal_mulai
 * @property \Illuminate\Support\Carbon|null $tanggal_selesai
 * @property string|null $lokasi
 * @property string|null $deskripsi
 * @property int|null $pic_user_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_event_id
 * @property-read mixed $hashid
 * @property mixed $jenis_kegiatan
 * @property mixed $judul_kegiatan
 * @property-read \App\Models\User|null $pic
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event\EventTamu> $tamus
 * @property-read int|null $tamus_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event\EventTeam> $teams
 * @property-read int|null $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereJenisEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereJudulEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event wherePicUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withoutTrashed()
 */
	class Event extends \Eloquent {}
}

namespace App\Models\Event{
/**
 * @property int $eventtamu_id
 * @property int $event_id
 * @property string $nama_tamu
 * @property string|null $instansi
 * @property string|null $jabatan
 * @property string|null $kontak
 * @property string|null $tujuan
 * @property \Illuminate\Support\Carbon|null $waktu_datang
 * @property string|null $foto_url
 * @property string|null $ttd_url
 * @property string|null $keterangan
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Event\Event $event
 * @property-read mixed $encrypted_eventtamu_id
 * @property-read mixed $hashid
 * @property-read mixed $photo_url
 * @property-read mixed $signature_url
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Sys\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereEventtamuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereFotoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereInstansi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereKontak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereNamaTamu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereTtdUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereTujuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu whereWaktuDatang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTamu withoutTrashed()
 */
	class EventTamu extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models\Event{
/**
 * @property int $eventteam_id
 * @property int $event_id
 * @property string|null $memberable_type
 * @property int|null $memberable_id
 * @property string|null $name
 * @property string|null $role
 * @property int $is_pic
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Event\Event $event
 * @property-read mixed $display_name
 * @property-read mixed $encrypted_eventteam_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $memberable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam whereEventteamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam whereIsPic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam whereMemberableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam whereMemberableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTeam withoutTrashed()
 */
	class EventTeam extends \Eloquent {}
}

namespace App\Models\Event{
/**
 * @property int $rapat_id
 * @property string $jenis_rapat
 * @property string $judul_kegiatan
 * @property \Illuminate\Support\Carbon $tgl_rapat
 * @property \Illuminate\Support\Carbon $waktu_mulai
 * @property \Illuminate\Support\Carbon $waktu_selesai
 * @property string $tempat_rapat
 * @property int|null $ketua_user_id
 * @property int|null $notulen_user_id
 * @property int|null $author_user_id
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event\RapatAgenda> $agendas
 * @property-read int|null $agendas_count
 * @property-read \App\Models\User|null $author_user
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event\RapatEntitas> $entitas
 * @property-read int|null $entitas_count
 * @property-read mixed $encrypted_rapat_id
 * @property-read mixed $hashid
 * @property-read \App\Models\User|null $ketua_user
 * @property-read \App\Models\User|null $notulen_user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event\RapatPeserta> $pesertas
 * @property-read int|null $pesertas_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereAuthorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereJenisRapat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereJudulKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereKetuaUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereNotulenUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereRapatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereTempatRapat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereTglRapat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereWaktuMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereWaktuSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat withoutTrashed()
 */
	class Rapat extends \Eloquent {}
}

namespace App\Models\Event{
/**
 * @property int $rapatagenda_id
 * @property int $rapat_id
 * @property string $judul_agenda
 * @property string $isi
 * @property int $seq
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_rapatagenda_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Event\Rapat $rapat
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatAgenda newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatAgenda newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatAgenda query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatAgenda whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatAgenda whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatAgenda whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatAgenda whereIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatAgenda whereJudulAgenda($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatAgenda whereRapatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatAgenda whereRapatagendaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatAgenda whereSeq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatAgenda whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatAgenda whereUpdatedBy($value)
 */
	class RapatAgenda extends \Eloquent {}
}

namespace App\Models\Event{
/**
 * @property int $rapatentitas_id
 * @property int $rapat_id
 * @property string $model
 * @property int $model_id
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_rapatentitas_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Event\Rapat $rapat
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatEntitas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatEntitas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatEntitas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatEntitas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatEntitas whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatEntitas whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatEntitas whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatEntitas whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatEntitas whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatEntitas whereRapatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatEntitas whereRapatentitasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatEntitas whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatEntitas whereUpdatedBy($value)
 */
	class RapatEntitas extends \Eloquent {}
}

namespace App\Models\Event{
/**
 * @property int $rapatpeserta_id
 * @property int $rapat_id
 * @property int $user_id
 * @property string $jabatan
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $waktu_hadir
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_rapatpeserta_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Event\Rapat $rapat
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatPeserta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatPeserta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatPeserta query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatPeserta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatPeserta whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatPeserta whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatPeserta whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatPeserta whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatPeserta whereRapatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatPeserta whereRapatpesertaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatPeserta whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatPeserta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatPeserta whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatPeserta whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RapatPeserta whereWaktuHadir($value)
 */
	class RapatPeserta extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttCancel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttCancel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttCancel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttCancel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttCancel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttCancel withoutTrashed()
 */
	class AttCancel extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property-read mixed $encrypted_att_device_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttDevice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttDevice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttDevice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttDevice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttDevice withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttDevice withoutTrashed()
 */
	class AttDevice extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttLog onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttLog withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttLog withoutTrashed()
 */
	class AttLog extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $filepegawai_id
 * @property int $pegawai_id
 * @property int $jenisfile_id
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_filepegawai_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Hr\JenisFile $jenisFile
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Sys\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Hr\Pegawai $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai whereFilepegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai whereJenisfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilePegawai withoutTrashed()
 */
	class FilePegawai extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models\Hr{
/**
 * @property int $gol_inpassing_id
 * @property string|null $nama_pangkat
 * @property string|null $golongan
 * @property string|null $ruang
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_gol_inpassing_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing whereGolInpassingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing whereGolongan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing whereNamaPangkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing whereRuang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GolonganInpassing withoutTrashed()
 */
	class GolonganInpassing extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $indisipliner_id
 * @property int|null $jenisindisipliner_id
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $tgl_indisipliner
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_indisipliner_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\IndisiplinerPegawai> $indisiplinerPegawai
 * @property-read int|null $indisipliner_pegawai_count
 * @property-read \App\Models\Hr\JenisIndisipliner|null $jenisIndisipliner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\Pegawai> $pegawai
 * @property-read int|null $pegawai_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner filterByYear($year)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner whereIndisiplinerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner whereJenisindisiplinerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner whereTglIndisipliner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indisipliner withoutTrashed()
 */
	class Indisipliner extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $indispegawai_id
 * @property int|null $indisipliner_id
 * @property int|null $pegawai_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_indispegawai_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Hr\Indisipliner|null $indisipliner
 * @property-read \App\Models\Hr\Pegawai|null $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndisiplinerPegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndisiplinerPegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndisiplinerPegawai onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndisiplinerPegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndisiplinerPegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndisiplinerPegawai whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndisiplinerPegawai whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndisiplinerPegawai whereIndisiplinerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndisiplinerPegawai whereIndispegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndisiplinerPegawai wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndisiplinerPegawai whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndisiplinerPegawai whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndisiplinerPegawai withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndisiplinerPegawai withoutTrashed()
 */
	class IndisiplinerPegawai extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $jabfungsional_id
 * @property string $kode_jabatan
 * @property string $jabfungsional
 * @property bool $is_active
 * @property int|null $tunjangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_jabfungsional_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional whereJabfungsional($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional whereJabfungsionalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional whereKodeJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional whereTunjangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanFungsional withoutTrashed()
 */
	class JabatanFungsional extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPmk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPmk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPmk onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPmk query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPmk withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPmk withoutTrashed()
 */
	class JadwalPmk extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalWfh newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalWfh newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalWfh onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalWfh query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalWfh withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalWfh withoutTrashed()
 */
	class JadwalWfh extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $jenisfile_id
 * @property string $jenisfile
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_jenisfile_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisFile onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisFile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisFile whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisFile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisFile whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisFile whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisFile whereJenisfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisFile whereJenisfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisFile whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisFile withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisFile withoutTrashed()
 */
	class JenisFile extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $jenisindisipliner_id
 * @property string $jenis_indisipliner
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_jenisindisipliner_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\Indisipliner> $indisipliner
 * @property-read int|null $indisipliner_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIndisipliner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIndisipliner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIndisipliner onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIndisipliner query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIndisipliner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIndisipliner whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIndisipliner whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIndisipliner whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIndisipliner whereJenisIndisipliner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIndisipliner whereJenisindisiplinerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIndisipliner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIndisipliner whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIndisipliner withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIndisipliner withoutTrashed()
 */
	class JenisIndisipliner extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $jenisizin_id
 * @property string $nama
 * @property string|null $kategori
 * @property int|null $max_hari
 * @property string|null $pemilihan_waktu
 * @property string|null $urutan_approval
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_jenisizin_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\Perizinan> $perizinan
 * @property-read int|null $perizinan_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin whereJenisizinId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin whereMaxHari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin wherePemilihanWaktu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin whereUrutanApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIzin withoutTrashed()
 */
	class JenisIzin extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $jenis_shift_id
 * @property string $jenis_shift
 * @property string|null $jam_masuk
 * @property string|null $jam_pulang
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_jenis_shift_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift whereJamMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift whereJamPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift whereJenisShift($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift whereJenisShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisShift withoutTrashed()
 */
	class JenisShift extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $keluarga_id
 * @property int $pegawai_id
 * @property string|null $nama
 * @property string|null $hubungan
 * @property \Illuminate\Support\Carbon|null $tgl_lahir
 * @property string|null $jenis_kelamin
 * @property int|null $latest_riwayatapproval_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \App\Models\Hr\RiwayatApproval|null $approval
 * @property-read mixed $encrypted_keluarga_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Hr\Pegawai|null $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga whereHubungan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga whereKeluargaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga whereLatestRiwayatapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga whereTglLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluarga withoutTrashed()
 */
	class Keluarga extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $lembur_id
 * @property int $pengusul_id ID pegawai yang mengusulkan
 * @property string $judul Judul/ringkasan lembur
 * @property string|null $uraian_pekerjaan Deskripsi detail pekerjaan
 * @property string|null $alasan Alasan lembur
 * @property \Illuminate\Support\Carbon $tgl_pelaksanaan Tanggal pelaksanaan lembur
 * @property \Illuminate\Support\Carbon $jam_mulai Jam mulai lembur
 * @property \Illuminate\Support\Carbon $jam_selesai Jam selesai lembur
 * @property int|null $durasi_menit Durasi dalam menit (auto-calculated)
 * @property bool $is_dibayar Apakah lembur dibayar?
 * @property string|null $metode_bayar Metode pembayaran: uang, cuti_pengganti, tidak_dibayar
 * @property numeric|null $nominal_per_jam Nominal per jam jika dibayar
 * @property int|null $latest_riwayatapproval_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatApproval> $approvals
 * @property-read int|null $approvals_count
 * @property-read mixed $encrypted_lembur_id
 * @property-read mixed $hashid
 * @property-read string $status_approval
 * @property-read float $total_bayar
 * @property-read \App\Models\Hr\RiwayatApproval|null $latestApproval
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\LemburPegawai> $lemburPegawais
 * @property-read int|null $lembur_pegawais_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\Pegawai> $pegawais
 * @property-read int|null $pegawais_count
 * @property-read \App\Models\Hr\Pegawai $pengusul
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur byDateRange($startDate, $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur byPengusul(int $pengusulId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur byStatus(string $status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereAlasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereDurasiMenit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereIsDibayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereLatestRiwayatapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereLemburId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereMetodeBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereNominalPerJam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur wherePengusulId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereTglPelaksanaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereUraianPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur withoutTrashed()
 */
	class Lembur extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $lemburpegawai_id
 * @property int $lembur_id
 * @property int $pegawai_id
 * @property numeric|null $override_nominal
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_lemburpegawai_id
 * @property-read mixed $hashid
 * @property-read float $nominal_efektif
 * @property-read float $total_bayar
 * @property-read \App\Models\Hr\Lembur $lembur
 * @property-read \App\Models\Hr\Pegawai $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai byLembur(int $lemburId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai byPegawai(int $pegawaiId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai whereLemburId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai whereLemburpegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai whereOverrideNominal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburPegawai withoutTrashed()
 */
	class LemburPegawai extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburWaktu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburWaktu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburWaktu onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburWaktu query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburWaktu withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburWaktu withoutTrashed()
 */
	class LemburWaktu extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburWaktuPegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburWaktuPegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburWaktuPegawai onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburWaktuPegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburWaktuPegawai withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LemburWaktuPegawai withoutTrashed()
 */
	class LemburWaktuPegawai extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPekerjaan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPekerjaan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPekerjaan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPekerjaan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPekerjaan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPekerjaan withoutTrashed()
 */
	class LogPekerjaan extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPrestasiTahunan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPrestasiTahunan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPrestasiTahunan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPrestasiTahunan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPrestasiTahunan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPrestasiTahunan withoutTrashed()
 */
	class NilaiPrestasiTahunan extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * Alias for backward compatibility.
 * 
 * The canonical model is now App\Models\Shared\StrukturOrganisasi.
 * All new code should use App\Models\Shared\StrukturOrganisasi directly.
 *
 * @property int $orgunit_id
 * @property int|null $parent_id
 * @property string $name
 * @property string|null $code
 * @property string|null $type
 * @property int $level
 * @property int $seq
 * @property int $sort_order
 * @property bool $is_active
 * @property string|null $description
 * @property int|null $successor_id
 * @property int|null $auditee_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shared\StrukturOrganisasi> $activeChildren
 * @property-read int|null $active_children_count
 * @property-read \App\Models\User|null $auditee
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shared\StrukturOrganisasi> $children
 * @property-read int|null $children_count
 * @property-read mixed $encrypted_org_unit_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pemutu\Indikator> $indikators
 * @property-read int|null $indikators_count
 * @property-read \App\Models\Shared\StrukturOrganisasi|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shared\Pegawai> $pegawai
 * @property-read int|null $pegawai_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shared\Personil> $personils
 * @property-read int|null $personils_count
 * @property-read \App\Models\Shared\StrukturOrganisasi|null $predecessor
 * @property-read \App\Models\Shared\StrukturOrganisasi|null $successor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereAuditeeUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereOrgunitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereSeq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereSuccessorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit withoutTrashed()
 */
	class OrgUnit extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * Alias for backward compatibility.
 * 
 * The canonical Pegawai model is now App\Models\Shared\Pegawai.
 * All new code should use App\Models\Shared\Pegawai directly.
 *
 * @property int $pegawai_id
 * @property int|null $latest_riwayatdatadiri_id
 * @property int|null $latest_riwayatstatpegawai_id
 * @property int|null $latest_riwayatstataktifitas_id
 * @property int|null $latest_riwayatinpassing_id
 * @property int|null $latest_riwayatpendidikan_id
 * @property int|null $latest_riwayatjabfungsional_id
 * @property int|null $latest_riwayatjabstruktural_id
 * @property int|null $latest_riwayatpenugasan_id
 * @property int|null $atasan1
 * @property int|null $atasan2
 * @property string|null $photo Employee photo for face recognition
 * @property string|null $face_encoding Face encoding data for face matching
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \App\Models\Shared\Pegawai|null $atasanDua
 * @property-read \App\Models\Shared\Pegawai|null $atasanSatu
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\FilePegawai> $files
 * @property-read int|null $files_count
 * @property-read mixed $email
 * @property-read mixed $encrypted_pegawai_id
 * @property-read mixed $hashid
 * @property-read mixed $inisial
 * @property-read mixed $nama
 * @property-read mixed $nip
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatDataDiri> $historyDataDiri
 * @property-read int|null $history_data_diri_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatInpassing> $historyInpassing
 * @property-read int|null $history_inpassing_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatJabFungsional> $historyJabFungsional
 * @property-read int|null $history_jab_fungsional_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatJabStruktural> $historyJabStruktural
 * @property-read int|null $history_jab_struktural_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatPenugasan> $historyPenugasan
 * @property-read int|null $history_penugasan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatStatAktifitas> $historyStatAktifitas
 * @property-read int|null $history_stat_aktifitas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatStatPegawai> $historyStatPegawai
 * @property-read int|null $history_stat_pegawai_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\Keluarga> $keluarga
 * @property-read int|null $keluarga_count
 * @property-read \App\Models\Hr\RiwayatDataDiri|null $latestDataDiri
 * @property-read \App\Models\Hr\RiwayatInpassing|null $latestInpassing
 * @property-read \App\Models\Hr\RiwayatJabFungsional|null $latestJabatanFungsional
 * @property-read \App\Models\Hr\RiwayatJabStruktural|null $latestJabatanStruktural
 * @property-read \App\Models\Hr\RiwayatPendidikan|null $latestPendidikan
 * @property-read \App\Models\Hr\RiwayatPenugasan|null $latestPenugasan
 * @property-read \App\Models\Hr\RiwayatStatAktifitas|null $latestStatusAktifitas
 * @property-read \App\Models\Hr\RiwayatStatPegawai|null $latestStatusPegawai
 * @property-read \App\Models\Shared\StrukturOrganisasi|null $orgUnit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\PengembanganDiri> $pengembanganDiri
 * @property-read int|null $pengembangan_diri_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatPendidikan> $riwayatPendidikan
 * @property-read int|null $riwayat_pendidikan_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereAtasan1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereAtasan2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereFaceEncoding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatdatadiriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatinpassingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatjabfungsionalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatjabstrukturalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatpendidikanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatpenugasanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatstataktifitasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatstatpegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai withoutTrashed()
 */
	class Pegawai extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiShift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiShift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiShift onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiShift query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiShift withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiShift withoutTrashed()
 */
	class PegawaiShift extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $pengembangandiri_id
 * @property int $pegawai_id
 * @property string $jenis_kegiatan
 * @property string $nama_kegiatan
 * @property string|null $nama_penyelenggara
 * @property string|null $peran
 * @property \Illuminate\Support\Carbon $tgl_mulai
 * @property \Illuminate\Support\Carbon|null $tgl_selesai
 * @property \Illuminate\Support\Carbon|null $berlaku_hingga
 * @property int $tahun
 * @property string|null $keterangan
 * @property int|null $latest_riwayatapproval_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \App\Models\Hr\RiwayatApproval|null $approval
 * @property-read mixed $encrypted_pengembangandiri_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Hr\Pegawai|null $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri whereBerlakuHingga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri whereJenisKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri whereLatestRiwayatapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri whereNamaKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri whereNamaPenyelenggara($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri wherePengembangandiriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri wherePeran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri whereTglMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri whereTglSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembanganDiri withoutTrashed()
 */
	class PengembanganDiri extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $perizinan_id
 * @property int|null $jenisizin_id
 * @property int|null $pengusul
 * @property string|null $pekerjaan_ditinggalkan
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $tgl_awal
 * @property \Illuminate\Support\Carbon|null $tgl_akhir
 * @property int|null $latest_riwayatapproval_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatApproval> $approvalHistory
 * @property-read int|null $approval_history_count
 * @property-read mixed $encrypted_perizinan_id
 * @property-read mixed $hashid
 * @property-read mixed $status
 * @property-read \App\Models\Hr\JenisIzin|null $jenisIzin
 * @property-read \App\Models\Hr\Keluarga|null $keluarga
 * @property-read \App\Models\Hr\RiwayatApproval|null $latestApproval
 * @property-read \App\Models\Hr\Pegawai|null $pengusulPegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan whereJenisizinId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan whereLatestRiwayatapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan wherePekerjaanDitinggalkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan wherePengusul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan wherePerizinanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan whereTglAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan whereTglAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perizinan withoutTrashed()
 */
	class Perizinan extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $presensi_id
 * @property int|null $pegawai_id
 * @property \Illuminate\Support\Carbon $tanggal
 * @property \Illuminate\Support\Carbon|null $check_in_time
 * @property \Illuminate\Support\Carbon|null $check_out_time
 * @property numeric|null $check_in_latitude
 * @property numeric|null $check_in_longitude
 * @property string|null $check_in_address
 * @property string|null $check_in_photo Photo path for check-in face verification
 * @property numeric|null $check_out_latitude
 * @property numeric|null $check_out_longitude
 * @property string|null $check_out_address
 * @property string|null $check_out_photo Photo path for check-out face verification
 * @property numeric|null $check_in_distance Distance from office in meters
 * @property numeric|null $check_out_distance Distance from office in meters
 * @property bool $check_in_face_verified Face verification status for check-in
 * @property bool $check_out_face_verified Face verification status for check-out
 * @property string|null $status
 * @property int|null $duration_minutes Total working minutes
 * @property int|null $overtime_minutes Overtime minutes
 * @property int|null $late_minutes Late arrival minutes
 * @property int|null $shift_id
 * @property string|null $notes
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_presensi_id
 * @property-read mixed $formatted_check_in_time
 * @property-read mixed $formatted_check_out_time
 * @property-read mixed $formatted_duration
 * @property-read mixed $hashid
 * @property-read mixed $status_badge
 * @property-read \App\Models\Hr\Pegawai|null $pegawai
 * @property-read \App\Models\Hr\JenisShift|null $shift
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi byDateRange($startDate, $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi byMonth($year, $month)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi byPegawai($pegawaiId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi byStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi complete()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi today()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCheckInAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCheckInDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCheckInFaceVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCheckInLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCheckInLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCheckInPhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCheckInTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCheckOutAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCheckOutDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCheckOutFaceVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCheckOutLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCheckOutLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCheckOutPhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCheckOutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereLateMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereOvertimeMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi wherePresensiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi withCheckIn()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi withCheckOut()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi withoutTrashed()
 */
	class Presensi extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $riwayatapproval_id
 * @property string|null $model
 * @property int|null $model_id
 * @property string|null $status
 * @property string|null $pejabat
 * @property string|null $jenis_jabatan
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_riwayatapproval_id
 * @property-read mixed $hashid
 * @property-read mixed $pegawai
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $subject
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval whereJenisJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval wherePejabat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval whereRiwayatapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatApproval withoutTrashed()
 */
	class RiwayatApproval extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatAtasan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatAtasan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatAtasan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatAtasan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatAtasan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatAtasan withoutTrashed()
 */
	class RiwayatAtasan extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $riwayatdatadiri_id
 * @property int $pegawai_id
 * @property string|null $nip
 * @property string|null $email
 * @property string|null $nama
 * @property string|null $inisial
 * @property string|null $jenis_kelamin
 * @property string|null $tempat_lahir
 * @property string|null $tgl_lahir
 * @property string|null $alamat
 * @property string|null $no_hp
 * @property string|null $status_nikah
 * @property string|null $agama
 * @property string|null $nidn
 * @property int|null $orgunit_departemen_id
 * @property int|null $orgunit_posisi_id
 * @property int|null $latest_riwayatapproval_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \App\Models\Hr\RiwayatApproval|null $approval
 * @property-read \App\Models\Hr\OrgUnit|null $departemen
 * @property-read mixed $encrypted_riwayatdatadiri_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Hr\Pegawai|null $pegawai
 * @property-read \App\Models\Hr\OrgUnit|null $posisi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereInisial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereLatestRiwayatapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereNidn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereOrgunitDepartemenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereOrgunitPosisiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereRiwayatdatadiriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereStatusNikah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereTglLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDataDiri withoutTrashed()
 */
	class RiwayatDataDiri extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDiskusi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDiskusi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDiskusi onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDiskusi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDiskusi withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatDiskusi withoutTrashed()
 */
	class RiwayatDiskusi extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $riwayatinpassing_id
 * @property int $pegawai_id
 * @property int|null $before_id
 * @property int|null $gol_inpassing_id
 * @property string|null $no_sk
 * @property \Illuminate\Support\Carbon|null $tgl_sk
 * @property \Illuminate\Support\Carbon|null $tmt
 * @property int $masa_kerja_tahun
 * @property int $masa_kerja_bulan
 * @property numeric $gaji_pokok
 * @property string|null $file_sk
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read RiwayatInpassing|null $after
 * @property-read \App\Models\Hr\RiwayatApproval|null $approval
 * @property-read RiwayatInpassing|null $before
 * @property-read mixed $encrypted_riwayatinpassing_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Hr\GolonganInpassing|null $golonganInpassing
 * @property-read \App\Models\Hr\Pegawai|null $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereBeforeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereFileSk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereGajiPokok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereGolInpassingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereMasaKerjaBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereMasaKerjaTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereNoSk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereRiwayatinpassingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereTglSk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereTmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatInpassing withoutTrashed()
 */
	class RiwayatInpassing extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $riwayatjabfungsional_id
 * @property int $pegawai_id
 * @property int|null $jabfungsional_id
 * @property \Illuminate\Support\Carbon|null $tmt
 * @property string|null $no_sk_internal
 * @property int|null $latest_riwayatapproval_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read RiwayatJabFungsional|null $after
 * @property-read \App\Models\Hr\RiwayatApproval|null $approval
 * @property-read RiwayatJabFungsional|null $before
 * @property-read mixed $encrypted_riwayatjabfungsional_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Hr\JabatanFungsional|null $jabatanFungsional
 * @property-read \App\Models\Hr\Pegawai|null $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional whereJabfungsionalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional whereLatestRiwayatapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional whereNoSkInternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional whereRiwayatjabfungsionalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional whereTmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabFungsional withoutTrashed()
 */
	class RiwayatJabFungsional extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $riwayatjabstruktural_id
 * @property int $pegawai_id
 * @property int|null $org_unit_id
 * @property string|null $no_sk
 * @property \Illuminate\Support\Carbon|null $tgl_awal
 * @property \Illuminate\Support\Carbon|null $tgl_akhir
 * @property int|null $latest_riwayatapproval_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read RiwayatJabStruktural|null $after
 * @property-read \App\Models\Hr\RiwayatApproval|null $approval
 * @property-read RiwayatJabStruktural|null $before
 * @property-read mixed $encrypted_riwayatjabstruktural_id
 * @property-read mixed $hashid
 * @property-read mixed $nama_jabatan
 * @property-read \App\Models\Hr\OrgUnit|null $orgUnit
 * @property-read \App\Models\Hr\Pegawai|null $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural whereLatestRiwayatapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural whereNoSk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural whereOrgUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural whereRiwayatjabstrukturalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural whereTglAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural whereTglAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatJabStruktural withoutTrashed()
 */
	class RiwayatJabStruktural extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $riwayatpendidikan_id
 * @property int $pegawai_id
 * @property string|null $jenjang_pendidikan
 * @property string|null $nama_pt
 * @property int|null $thn_lulus
 * @property string|null $bidang_ilmu
 * @property \Illuminate\Support\Carbon|null $tgl_ijazah
 * @property int|null $latest_riwayatapproval_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read RiwayatPendidikan|null $after
 * @property-read \App\Models\Hr\RiwayatApproval|null $approval
 * @property-read RiwayatPendidikan|null $before
 * @property-read mixed $encrypted_riwayatpendidikan_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Hr\Pegawai|null $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan whereBidangIlmu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan whereJenjangPendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan whereLatestRiwayatapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan whereNamaPt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan whereRiwayatpendidikanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan whereTglIjazah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan whereThnLulus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendidikan withoutTrashed()
 */
	class RiwayatPendidikan extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $riwayatpenugasan_id
 * @property int $pegawai_id
 * @property int|null $org_unit_id
 * @property \Illuminate\Support\Carbon $tgl_mulai
 * @property \Illuminate\Support\Carbon|null $tgl_selesai
 * @property \Illuminate\Support\Carbon|null $tgl_sk
 * @property string|null $no_sk
 * @property string|null $jabatan
 * @property string|null $keterangan
 * @property string $status
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \App\Models\User|null $approvedBy
 * @property-read mixed $encrypted_riwayatpenugasan_id
 * @property-read mixed $hashid
 * @property-read mixed $is_active
 * @property-read \App\Models\Hr\OrgUnit|null $orgUnit
 * @property-read \App\Models\Hr\Pegawai|null $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereNoSk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereOrgUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereRiwayatpenugasanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereTglMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereTglSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereTglSk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPenugasan withoutTrashed()
 */
	class RiwayatPenugasan extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatProddep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatProddep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatProddep onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatProddep query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatProddep withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatProddep withoutTrashed()
 */
	class RiwayatProddep extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $riwayatstataktifitas_id
 * @property int $pegawai_id
 * @property int|null $statusaktifitas_id
 * @property \Illuminate\Support\Carbon|null $tmt
 * @property int|null $latest_riwayatapproval_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read RiwayatStatAktifitas|null $after
 * @property-read \App\Models\Hr\RiwayatApproval|null $approval
 * @property-read RiwayatStatAktifitas|null $before
 * @property-read mixed $encrypted_riwayatstataktifitas_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Hr\Pegawai|null $pegawai
 * @property-read \App\Models\Hr\StatusAktifitas|null $statusAktifitas
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas whereLatestRiwayatapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas whereRiwayatstataktifitasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas whereStatusaktifitasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas whereTmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatAktifitas withoutTrashed()
 */
	class RiwayatStatAktifitas extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $riwayatstatpegawai_id
 * @property int $pegawai_id
 * @property int|null $before_id
 * @property int|null $statuspegawai_id
 * @property \Illuminate\Support\Carbon|null $tmt
 * @property \Illuminate\Support\Carbon|null $tgl_akhir
 * @property string|null $no_sk
 * @property int|null $latest_riwayatapproval_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read RiwayatStatPegawai|null $after
 * @property-read \App\Models\Hr\RiwayatApproval|null $approval
 * @property-read RiwayatStatPegawai|null $before
 * @property-read mixed $encrypted_riwayatstatpegawai_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Hr\Pegawai|null $pegawai
 * @property-read \App\Models\Hr\StatusPegawai|null $statusPegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai whereBeforeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai whereLatestRiwayatapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai whereNoSk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai whereRiwayatstatpegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai whereStatuspegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai whereTglAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai whereTmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatPegawai withoutTrashed()
 */
	class RiwayatStatPegawai extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatusKuliah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatusKuliah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatusKuliah onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatusKuliah query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatusKuliah withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatStatusKuliah withoutTrashed()
 */
	class RiwayatStatusKuliah extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $statusaktifitas_id
 * @property string $kode_status
 * @property string $nama_status
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_statusaktifitas_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas whereKodeStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas whereNamaStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas whereStatusaktifitasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusAktifitas withoutTrashed()
 */
	class StatusAktifitas extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $statuspegawai_id
 * @property string $kode_status
 * @property string $nama_status
 * @property string|null $organisasi
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_statuspegawai_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai whereKodeStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai whereNamaStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai whereOrganisasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai whereStatuspegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPegawai withoutTrashed()
 */
	class StatusPegawai extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $tanggallibur_id
 * @property int|null $tahun
 * @property \Illuminate\Support\Carbon|null $tgl_libur
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_tanggallibur_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur whereTanggalliburId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur whereTglLibur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalLibur withoutTrashed()
 */
	class TanggalLibur extends \Eloquent {}
}

namespace App\Models\Hr{
/**
 * @property int $tidakmasuk_id
 * @property int $perizinan_id
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_tidakmasuk_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk wherePerizinanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk whereTidakmasukId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanggalTidakMasuk withoutTrashed()
 */
	class TanggalTidakMasuk extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property int $inventaris_id
 * @property string $nama_alat
 * @property string $jenis_alat
 * @property string $kondisi_terakhir
 * @property \Illuminate\Support\Carbon|null $tanggal_pengecekan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_inventaris_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\LabInventaris> $labInventaris
 * @property-read int|null $lab_inventaris_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\Lab> $labs
 * @property-read int|null $labs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\LaporanKerusakan> $laporanKerusakans
 * @property-read int|null $laporan_kerusakans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereInventarisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereJenisAlat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereKondisiTerakhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereNamaAlat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereTanggalPengecekan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris withoutTrashed()
 */
	class Inventaris extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property string $jadwal_kuliah_id
 * @property int $semester_id
 * @property int $mata_kuliah_id
 * @property int $dosen_id
 * @property int $lab_id
 * @property string $hari
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \App\Models\User $dosen
 * @property-read mixed $encrypted_jadwal_kuliah_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Lab\Lab $lab
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\LogPenggunaanPc> $logPenggunaanPcs
 * @property-read int|null $log_penggunaan_pcs_count
 * @property-read \App\Models\Lab\MataKuliah $mataKuliah
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\PcAssignment> $pcAssignments
 * @property-read int|null $pc_assignments_count
 * @property-read \App\Models\Lab\Semester $semester
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah whereDosenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah whereHari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah whereJadwalKuliahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah whereLabId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah whereMataKuliahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah whereSemesterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalKuliah withoutTrashed()
 */
	class JadwalKuliah extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property int $kegiatan_id
 * @property int $lab_id
 * @property int $penyelenggara_id
 * @property string $nama_kegiatan
 * @property string $deskripsi
 * @property \Illuminate\Support\Carbon $tanggal
 * @property \Illuminate\Support\Carbon $jam_mulai
 * @property \Illuminate\Support\Carbon $jam_selesai
 * @property string $status
 * @property int|null $latest_riwayatapproval_id
 * @property string|null $dokumentasi_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\LabRiwayatApproval> $approvals
 * @property-read int|null $approvals_count
 * @property-read mixed $encrypted_kegiatan_id
 * @property-read mixed $encrypted_lab_id
 * @property-read mixed $encrypted_penyelenggara_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Lab\Lab $lab
 * @property-read \App\Models\Lab\LabRiwayatApproval|null $latestApproval
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\LogPenggunaanLab> $logPenggunaanLabs
 * @property-read int|null $log_penggunaan_labs_count
 * @property-read \App\Models\User $penyelenggara
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereDokumentasiPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereLabId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereLatestRiwayatapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereNamaKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan wherePenyelenggaraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan withoutTrashed()
 */
	class Kegiatan extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property string $lab_id
 * @property string $name
 * @property string|null $location
 * @property int|null $capacity
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_lab_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\Inventaris> $inventaris
 * @property-read int|null $inventaris_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\JadwalKuliah> $jadwals
 * @property-read int|null $jadwals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\Kegiatan> $kegiatans
 * @property-read int|null $kegiatans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\LabInventaris> $labInventaris
 * @property-read int|null $lab_inventaris_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\LabMedia> $labMedia
 * @property-read int|null $lab_media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\LabTeam> $labTeams
 * @property-read int|null $lab_teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\LogPenggunaanLab> $logPenggunaanLabs
 * @property-read int|null $log_penggunaan_labs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\LogPenggunaanPc> $logPenggunaanPcs
 * @property-read int|null $log_penggunaan_pcs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\MataKuliah> $mataKuliahs
 * @property-read int|null $mata_kuliahs_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Sys\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\PcAssignment> $pcAssignments
 * @property-read int|null $pc_assignments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab whereLabId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lab withoutTrashed()
 */
	class Lab extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models\Lab{
/**
 * @property int $inventaris_penempatan_id
 * @property int $inventaris_id
 * @property int $lab_id
 * @property string $kode_inventaris
 * @property string|null $no_series
 * @property \Illuminate\Support\Carbon|null $tanggal_penempatan
 * @property \Illuminate\Support\Carbon|null $tanggal_penghapusan
 * @property string $status
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_inventaris_id
 * @property-read mixed $encrypted_inventaris_penempatan_id
 * @property-read mixed $encrypted_lab_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Lab\Inventaris $inventaris
 * @property-read \App\Models\Lab\Lab $lab
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris whereInventarisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris whereInventarisPenempatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris whereKodeInventaris($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris whereLabId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris whereNoSeries($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris whereTanggalPenempatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris whereTanggalPenghapusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabInventaris withoutTrashed()
 */
	class LabInventaris extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property-read mixed $encrypted_lab_id
 * @property-read mixed $encrypted_lab_media_id
 * @property-read mixed $encrypted_media_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Lab\Lab|null $lab
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabMedia onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabMedia withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabMedia withoutTrashed()
 */
	class LabMedia extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property int $riwayatapproval_id
 * @property string|null $model
 * @property int|null $model_id
 * @property string|null $status
 * @property string|null $pejabat
 * @property string|null $jenis_jabatan
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $approvalable
 * @property-read mixed $encrypted_riwayatapproval_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval whereJenisJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval wherePejabat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval whereRiwayatapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabRiwayatApproval withoutTrashed()
 */
	class LabRiwayatApproval extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property int $lab_team_id
 * @property int $lab_id
 * @property int $user_id
 * @property string|null $jabatan
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $tanggal_mulai
 * @property \Illuminate\Support\Carbon|null $tanggal_selesai
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_lab_id
 * @property-read mixed $encrypted_lab_team_id
 * @property-read mixed $encrypted_user_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Lab\Lab $lab
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam whereLabId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam whereLabTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTeam withoutTrashed()
 */
	class LabTeam extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property int $laporan_kerusakan_id
 * @property int $inventaris_id
 * @property int $teknisi_id
 * @property string $deskripsi_kerusakan
 * @property string $status
 * @property string|null $catatan_perbaikan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \App\Models\User|null $createdBy
 * @property-read mixed $encrypted_inventaris_id
 * @property-read mixed $encrypted_laporan_kerusakan_id
 * @property-read mixed $encrypted_teknisi_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Lab\Inventaris $inventaris
 * @property-read \App\Models\User $teknisi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan whereCatatanPerbaikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan whereDeskripsiKerusakan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan whereInventarisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan whereLaporanKerusakanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan whereTeknisiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerusakan withoutTrashed()
 */
	class LaporanKerusakan extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property int $log_penggunaan_labs_id
 * @property int $kegiatan_id
 * @property int $lab_id
 * @property string|null $nama_peserta
 * @property string|null $email_peserta
 * @property string|null $npm_peserta
 * @property int|null $nomor_pc
 * @property string|null $kondisi
 * @property string|null $catatan_umum
 * @property \Illuminate\Support\Carbon $waktu_isi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_kegiatan_id
 * @property-read mixed $encrypted_lab_id
 * @property-read mixed $encrypted_log_penggunaan_labs_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Lab\Kegiatan $kegiatan
 * @property-read \App\Models\Lab\Lab $lab
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereCatatanUmum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereEmailPeserta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereKondisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereLabId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereLogPenggunaanLabsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereNamaPeserta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereNomorPc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereNpmPeserta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab whereWaktuIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanLab withoutTrashed()
 */
	class LogPenggunaanLab extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property int $log_penggunaan_pcs_id
 * @property int $pc_assignment_id
 * @property int $user_id
 * @property int $jadwal_id
 * @property int $lab_id
 * @property string $status
 * @property \Illuminate\Support\Carbon $waktu_isi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_jadwal_id
 * @property-read mixed $encrypted_lab_id
 * @property-read mixed $encrypted_log_penggunaan_pcs_id
 * @property-read mixed $encrypted_user_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Lab\JadwalKuliah $jadwal
 * @property-read \App\Models\Lab\Lab $lab
 * @property-read \App\Models\Lab\PcAssignment $pcAssignment
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc whereJadwalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc whereLabId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc whereLogPenggunaanPcsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc wherePcAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc whereWaktuIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPenggunaanPc withoutTrashed()
 */
	class LogPenggunaanPc extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * Alias for backward compatibility.
 * 
 * The canonical Mahasiswa model is now App\Models\Shared\Mahasiswa.
 * All new code should use App\Models\Shared\Mahasiswa directly.
 *
 * @property string $mahasiswa_id
 * @property int|null $user_id
 * @property string $nim
 * @property string $nama
 * @property string $email
 * @property int|null $orgunit_id
 * @property string|null $jenis_kelamin
 * @property string|null $tempat_lahir
 * @property string|null $tanggal_lahir
 * @property string|null $agama
 * @property string|null $kewarganegaraan
 * @property string|null $no_hp
 * @property string|null $alamat
 * @property string|null $angkatan
 * @property string|null $foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_mahasiswa_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Shared\StrukturOrganisasi|null $prodi
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAngkatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereKewarganegaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereMahasiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereOrgunitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa withoutTrashed()
 */
	class Mahasiswa extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property int $mata_kuliah_id
 * @property string $kode_mk
 * @property string $nama_mk
 * @property int $sks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_mata_kuliah_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\JadwalKuliah> $jadwals
 * @property-read int|null $jadwals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\RequestSoftware> $requestSoftwares
 * @property-read int|null $request_softwares_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah whereKodeMk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah whereMataKuliahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah whereNamaMk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah whereSks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataKuliah withoutTrashed()
 */
	class MataKuliah extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array<array-key, mixed> $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Model $notifiable
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification read()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification unread()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification withoutTrashed()
 */
	class Notification extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property int $pc_assignment_id
 * @property int $user_id
 * @property int $jadwal_id
 * @property int $lab_id
 * @property string $pc_name
 * @property string|null $keterangan
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_jadwal_id
 * @property-read mixed $encrypted_lab_id
 * @property-read mixed $encrypted_pc_assignment_id
 * @property-read mixed $encrypted_user_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Lab\JadwalKuliah $jadwal
 * @property-read \App\Models\Lab\Lab $lab
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\LogPenggunaanPc> $logPenggunaanPcs
 * @property-read int|null $log_penggunaan_pcs_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment whereJadwalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment whereLabId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment wherePcAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment wherePcName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PcAssignment withoutTrashed()
 */
	class PcAssignment extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * Alias for backward compatibility.
 * 
 * The canonical Pengumuman model is now App\Models\Shared\Pengumuman.
 * All new code should use App\Models\Shared\Pengumuman directly.
 *
 * @property string $pengumuman_id
 * @property int $penulis_id
 * @property string $judul
 * @property string $isi
 * @property string $jenis
 * @property bool $is_published
 * @property string|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $attachments_url
 * @property-read mixed $cover_medium_url
 * @property-read mixed $cover_small_url
 * @property-read mixed $cover_url
 * @property-read mixed $encrypted_pengumuman_id
 * @property-read mixed $hashid
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Sys\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User $penulis
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman wherePengumumanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman wherePenulisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman withoutTrashed()
 */
	class Pengumuman extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property int $periodsoftreq_id
 * @property int $semester_id
 * @property string $nama_periode
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_periodsoftreq_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Lab\Semester $semester
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\RequestSoftware> $softwareRequests
 * @property-read int|null $software_requests_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest whereNamaPeriode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest wherePeriodsoftreqId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest whereSemesterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodSoftRequest withoutTrashed()
 */
	class PeriodSoftRequest extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * Alias for backward compatibility.
 * 
 * The canonical Personil model is now App\Models\Shared\Personil.
 * All new code should use App\Models\Shared\Personil directly.
 *
 * @property int $personil_id
 * @property int|null $user_id
 * @property int|null $org_unit_id
 * @property string $nama
 * @property string|null $email
 * @property string|null $nip
 * @property string|null $posisi
 * @property string|null $tipe outsource, vendor_staff, etc.
 * @property string|null $vendor Nama perusahaan vendor/penyedia
 * @property string|null $ttd_digital
 * @property bool $status_aktif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_personil_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Shared\StrukturOrganisasi|null $orgUnit
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereOrgUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil wherePersonilId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil wherePosisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereStatusAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereTtdDigital($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereVendor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil withoutTrashed()
 */
	class Personil extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property int $request_software_id
 * @property int|null $periodsoftreq_id
 * @property int $dosen_id
 * @property string $nama_software
 * @property string|null $versi
 * @property string|null $url_download
 * @property string $deskripsi
 * @property string $status
 * @property int|null $latest_riwayatapproval_id
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\LabRiwayatApproval> $approvals
 * @property-read int|null $approvals_count
 * @property-read \App\Models\User $dosen
 * @property-read mixed $encrypted_dosen_id
 * @property-read mixed $encrypted_request_software_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Lab\LabRiwayatApproval|null $latestApproval
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\MataKuliah> $mataKuliahs
 * @property-read int|null $mata_kuliahs_count
 * @property-read \App\Models\Lab\PeriodSoftRequest|null $period
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware whereDosenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware whereLatestRiwayatapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware whereNamaSoftware($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware wherePeriodsoftreqId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware whereRequestSoftwareId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware whereUrlDownload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware whereVersi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestSoftware withoutTrashed()
 */
	class RequestSoftware extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property string $semester_id
 * @property string $tahun_ajaran
 * @property string $semester
 * @property string $start_date
 * @property string $end_date
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_semester_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\JadwalKuliah> $jadwals
 * @property-read int|null $jadwals_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereSemesterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester withoutTrashed()
 */
	class Semester extends \Eloquent {}
}

namespace App\Models\Lab{
/**
 * @property int $surat_bebas_lab_id
 * @property int $student_id
 * @property string $status
 * @property int|null $latest_riwayatapproval_id
 * @property string|null $file_path
 * @property string|null $remarks
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\LabRiwayatApproval> $approvals
 * @property-read int|null $approvals_count
 * @property-read \App\Models\User|null $approver
 * @property-read mixed $encrypted_surat_bebas_lab_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Lab\LabRiwayatApproval|null $latestApproval
 * @property-read \App\Models\User $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab whereLatestRiwayatapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab whereSuratBebasLabId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratBebasLab withoutTrashed()
 */
	class SuratBebasLab extends \Eloquent {}
}

namespace App\Models\Pemutu{
/**
 * @property int $doksub_id
 * @property int $dok_id
 * @property string $judul
 * @property string|null $isi
 * @property int|null $seq
 * @property bool $is_hasilkan_indikator
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pemutu\Dokumen> $childDokumens
 * @property-read int|null $child_dokumens_count
 * @property-read \App\Models\Pemutu\Dokumen $dokumen
 * @property-read mixed $encrypted_doksub_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pemutu\Indikator> $indikators
 * @property-read int|null $indikators_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub whereDokId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub whereDoksubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub whereIsHasilkanIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub whereIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub whereSeq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokSub withoutTrashed()
 */
	class DokSub extends \Eloquent {}
}

namespace App\Models\Pemutu{
/**
 * @property int $dok_id
 * @property int|null $parent_id
 * @property int|null $parent_doksub_id
 * @property string|null $jenis
 * @property int $level
 * @property int $seq
 * @property string $judul
 * @property string|null $isi
 * @property string|null $kode
 * @property int|null $periode
 * @property bool $std_is_staging
 * @property string|null $std_amirtn_id
 * @property int|null $std_jeniskriteria_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pemutu\DokumenApproval> $approvals
 * @property-read int|null $approvals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Dokumen> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pemutu\DokSub> $dokSubs
 * @property-read int|null $dok_subs_count
 * @property-read mixed $encrypted_dok_id
 * @property-read mixed $hashid
 * @property-read Dokumen|null $parent
 * @property-read \App\Models\Pemutu\DokSub|null $parentDokSub
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereDokId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereParentDoksubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen wherePeriode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereSeq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereStdAmirtnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereStdIsStaging($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereStdJeniskriteriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen withoutTrashed()
 */
	class Dokumen extends \Eloquent {}
}

namespace App\Models\Pemutu{
/**
 * @property int $dokapproval_id
 * @property int $dok_id
 * @property string|null $proses
 * @property int|null $pegawai_id
 * @property string|null $jabatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Shared\Pegawai|null $approver
 * @property-read \App\Models\Pemutu\Dokumen $dokumen
 * @property-read mixed $encrypted_dokapproval_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pemutu\DokumenApprovalStatus> $statuses
 * @property-read int|null $statuses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval whereDokId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval whereDokapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval whereProses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApproval withoutTrashed()
 */
	class DokumenApproval extends \Eloquent {}
}

namespace App\Models\Pemutu{
/**
 * @property int $dokstatusapproval_id
 * @property int $dokapproval_id
 * @property string $status_approval
 * @property string|null $komentar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Pemutu\DokumenApproval $approval
 * @property-read mixed $encrypted_dokstatusapproval_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus whereDokapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus whereDokstatusapprovalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus whereKomentar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus whereStatusApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenApprovalStatus withoutTrashed()
 */
	class DokumenApprovalStatus extends \Eloquent {}
}

namespace App\Models\Pemutu{
/**
 * @property int $indikator_id
 * @property int|null $parent_id
 * @property string $type
 * @property string|null $no_indikator
 * @property string|null $indikator
 * @property string|null $target
 * @property string|null $jenis_indikator
 * @property string|null $jenis_data
 * @property string|null $periode_jenis
 * @property string|null $periode_mulai
 * @property string|null $periode_selesai
 * @property string|null $unit_ukuran
 * @property string|null $keterangan
 * @property int|null $seq
 * @property string|null $level_risk
 * @property string|null $origin_from
 * @property string|null $hash
 * @property int|null $peningkat_nonaktif_indik
 * @property int|null $is_new_indik_after_peningkatan
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Indikator> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pemutu\DokSub> $dokSubs
 * @property-read int|null $dok_subs_count
 * @property-read mixed $encrypted_indikator_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pemutu\Label> $labels
 * @property-read int|null $labels_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shared\StrukturOrganisasi> $orgUnits
 * @property-read int|null $org_units_count
 * @property-read Indikator|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pemutu\IndikatorPegawai> $pegawai
 * @property-read int|null $pegawai_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereIndikatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereIsNewIndikAfterPeningkatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereJenisData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereJenisIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereLevelRisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereNoIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereOriginFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator wherePeningkatNonaktifIndik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator wherePeriodeJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator wherePeriodeMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator wherePeriodeSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereSeq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereUnitUkuran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Indikator withoutTrashed()
 */
	class Indikator extends \Eloquent {}
}

namespace App\Models\Pemutu{
/**
 * @property int $indikator_pegawai_id
 * @property int $pegawai_id
 * @property int $indikator_id
 * @property int|null $periode_kpi_id
 * @property int $year
 * @property string $semester
 * @property numeric|null $weight
 * @property numeric|null $target_value
 * @property string|null $realization
 * @property numeric|null $score
 * @property string|null $attachment
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_indikator_pegawai_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Pemutu\Indikator $indikator
 * @property-read \App\Models\Shared\Pegawai $pegawai
 * @property-read \App\Models\Pemutu\PeriodeKpi|null $periodeKpi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereIndikatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereIndikatorPegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai wherePeriodeKpiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereRealization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereTargetValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai whereYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorPegawai withoutTrashed()
 */
	class IndikatorPegawai extends \Eloquent {}
}

namespace App\Models\Pemutu{
/**
 * @property int $label_id
 * @property int $type_id
 * @property string $name
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $color
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_label_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Pemutu\LabelType $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label whereLabelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Label withoutTrashed()
 */
	class Label extends \Eloquent {}
}

namespace App\Models\Pemutu{
/**
 * @property int $labeltype_id
 * @property string $name
 * @property string|null $description
 * @property string $color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_labeltype_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pemutu\Label> $labels
 * @property-read int|null $labels_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType whereLabeltypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabelType withoutTrashed()
 */
	class LabelType extends \Eloquent {}
}

namespace App\Models\Pemutu{
/**
 * Alias for backward compatibility.
 * 
 * The canonical model is now App\Models\Shared\StrukturOrganisasi.
 * All new code should use App\Models\Shared\StrukturOrganisasi directly.
 *
 * @property int $orgunit_id
 * @property int|null $parent_id
 * @property string $name
 * @property string|null $code
 * @property string|null $type
 * @property int $level
 * @property int $seq
 * @property int $sort_order
 * @property bool $is_active
 * @property string|null $description
 * @property int|null $successor_id
 * @property int|null $auditee_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shared\StrukturOrganisasi> $activeChildren
 * @property-read int|null $active_children_count
 * @property-read \App\Models\User|null $auditee
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shared\StrukturOrganisasi> $children
 * @property-read int|null $children_count
 * @property-read mixed $encrypted_org_unit_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pemutu\Indikator> $indikators
 * @property-read int|null $indikators_count
 * @property-read \App\Models\Shared\StrukturOrganisasi|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shared\Pegawai> $pegawai
 * @property-read int|null $pegawai_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shared\Personil> $personils
 * @property-read int|null $personils_count
 * @property-read \App\Models\Shared\StrukturOrganisasi|null $predecessor
 * @property-read \App\Models\Shared\StrukturOrganisasi|null $successor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereAuditeeUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereOrgunitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereSeq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereSuccessorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrgUnit withoutTrashed()
 */
	class OrgUnit extends \Eloquent {}
}

namespace App\Models\Pemutu{
/**
 * @property int $periode_kpi_id
 * @property string $nama
 * @property string $semester
 * @property string $tahun_akademik
 * @property int $tahun
 * @property \Illuminate\Support\Carbon $tanggal_mulai
 * @property \Illuminate\Support\Carbon $tanggal_selesai
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_periode_kpi_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pemutu\IndikatorPegawai> $kpiAssignments
 * @property-read int|null $kpi_assignments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi wherePeriodeKpiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi whereTahunAkademik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeKpi withoutTrashed()
 */
	class PeriodeKpi extends \Eloquent {}
}

namespace App\Models\Pemutu{
/**
 * @property int $periodespmi_id
 * @property int $periode
 * @property string $jenis_periode
 * @property \Illuminate\Support\Carbon|null $penetapan_awal
 * @property \Illuminate\Support\Carbon|null $penetapan_akhir
 * @property \Illuminate\Support\Carbon|null $ed_awal
 * @property \Illuminate\Support\Carbon|null $ed_akhir
 * @property \Illuminate\Support\Carbon|null $ami_awal
 * @property \Illuminate\Support\Carbon|null $ami_akhir
 * @property \Illuminate\Support\Carbon|null $pengendalian_awal
 * @property \Illuminate\Support\Carbon|null $pengendalian_akhir
 * @property \Illuminate\Support\Carbon|null $peningkatan_awal
 * @property \Illuminate\Support\Carbon|null $peningkatan_akhir
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_periodespmi_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi whereAmiAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi whereAmiAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi whereEdAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi whereEdAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi whereJenisPeriode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi wherePenetapanAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi wherePenetapanAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi wherePengendalianAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi wherePengendalianAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi wherePeningkatanAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi wherePeningkatanAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi wherePeriode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi wherePeriodespmiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeSpmi withoutTrashed()
 */
	class PeriodeSpmi extends \Eloquent {}
}

namespace App\Models\Pemutu{
/**
 * @property int $tim_mutu_id
 * @property int $periodespmi_id
 * @property int $org_unit_id
 * @property int $pegawai_id
 * @property string $role
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_tim_mutu_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Shared\StrukturOrganisasi $orgUnit
 * @property-read \App\Models\Shared\Pegawai $pegawai
 * @property-read \App\Models\Pemutu\PeriodeSpmi $periode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu forPeriode($periodeId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu forUnit($unitId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu whereOrgUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu wherePeriodespmiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu whereTimMutuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimMutu withoutTrashed()
 */
	class TimMutu extends \Eloquent {}
}

namespace App\Models\Pmb{
/**
 * @property int $dokumenupload_id
 * @property int $pendaftaran_id
 * @property int $jenis_dokumen_id
 * @property string $path_file
 * @property string $status_verifikasi
 * @property string|null $catatan_revisi
 * @property int|null $verifikator_id
 * @property string|null $waktu_upload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_dokumenupload_id
 * @property-read mixed $hashid
 * @property-read mixed $is_verified
 * @property-read \App\Models\Pmb\JenisDokumen $jenisDokumen
 * @property-read \App\Models\Pmb\Pendaftaran $pendaftaran
 * @property-read \App\Models\User|null $verifikator
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload whereCatatanRevisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload whereDokumenuploadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload whereJenisDokumenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload wherePathFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload wherePendaftaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload whereStatusVerifikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload whereVerifikatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload whereWaktuUpload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenUpload withoutTrashed()
 */
	class DokumenUpload extends \Eloquent {}
}

namespace App\Models\Pmb{
/**
 * @property int $jalur_id
 * @property string $nama_jalur Contoh: Reguler, Prestasi, KIP-K
 * @property numeric $biaya_pendaftaran
 * @property int $is_aktif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_jalur_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pmb\SyaratDokumenJalur> $syaratDokumen
 * @property-read int|null $syarat_dokumen_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jalur newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jalur newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jalur onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jalur query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jalur whereBiayaPendaftaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jalur whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jalur whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jalur whereIsAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jalur whereJalurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jalur whereNamaJalur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jalur whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jalur withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jalur withoutTrashed()
 */
	class Jalur extends \Eloquent {}
}

namespace App\Models\Pmb{
/**
 * @property int $jenis_dokumen_id
 * @property string $nama_dokumen KTP, Ijazah, Sertifikat, Raport
 * @property string|null $tipe_file pdf, jpg, png
 * @property int $max_size_kb
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_jenis_dokumen_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen whereJenisDokumenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen whereMaxSizeKb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen whereNamaDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen whereTipeFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen withoutTrashed()
 */
	class JenisDokumen extends \Eloquent {}
}

namespace App\Models\Pmb{
/**
 * @property int $pembayaran_id
 * @property int $pendaftaran_id
 * @property string $jenis_bayar
 * @property numeric $jumlah_bayar
 * @property string|null $bukti_bayar_path
 * @property string $status_verifikasi
 * @property int|null $verifikator_id
 * @property string|null $waktu_bayar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_pembayaran_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Pmb\Pendaftaran $pendaftaran
 * @property-read \App\Models\User|null $verifikator
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereBuktiBayarPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereJenisBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereJumlahBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran wherePembayaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran wherePendaftaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereStatusVerifikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereVerifikatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereWaktuBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran withoutTrashed()
 */
	class Pembayaran extends \Eloquent {}
}

namespace App\Models\Pmb{
/**
 * @property int $pendaftaran_id
 * @property string $no_pendaftaran Format: REG-2025-XXXX
 * @property int $user_id
 * @property int $periode_id
 * @property int $jalur_id
 * @property string $status_terkini
 * @property string|null $nim_final
 * @property int|null $orgunit_diterima_id
 * @property string|null $waktu_daftar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pmb\DokumenUpload> $dokumenUpload
 * @property-read int|null $dokumen_upload_count
 * @property-read mixed $encrypted_pendaftaran_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Pmb\Jalur $jalur
 * @property-read \App\Models\Shared\StrukturOrganisasi|null $orgUnitDiterima
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pmb\Pembayaran> $pembayaran
 * @property-read int|null $pembayaran_count
 * @property-read \App\Models\Pmb\Periode $periode
 * @property-read \App\Models\Pmb\PesertaUjian|null $pesertaUjian
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pmb\PilihanProdi> $pilihanProdi
 * @property-read int|null $pilihan_prodi_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pmb\RiwayatPendaftaran> $riwayat
 * @property-read int|null $riwayat_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran whereJalurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran whereNimFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran whereNoPendaftaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran whereOrgunitDiterimaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran wherePendaftaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran wherePeriodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran whereStatusTerkini($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran whereWaktuDaftar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftaran withoutTrashed()
 */
	class Pendaftaran extends \Eloquent {}
}

namespace App\Models\Pmb{
/**
 * @property int $periode_id
 * @property string $nama_periode Contoh: 2025/2026 Ganjil
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property int $is_aktif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_periode_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Periode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Periode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Periode onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Periode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Periode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Periode whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Periode whereIsAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Periode whereNamaPeriode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Periode wherePeriodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Periode whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Periode whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Periode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Periode withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Periode withoutTrashed()
 */
	class Periode extends \Eloquent {}
}

namespace App\Models\Pmb{
/**
 * @property int $pesertaujian_id
 * @property int $pendaftaran_id
 * @property int $sesi_id
 * @property string|null $username_cbt
 * @property string|null $password_cbt
 * @property numeric|null $nilai_akhir
 * @property int $status_kehadiran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_pesertaujian_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Pmb\Pendaftaran $pendaftaran
 * @property-read \App\Models\Pmb\SesiUjian $sesi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian whereNilaiAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian wherePasswordCbt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian wherePendaftaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian wherePesertaujianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian whereSesiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian whereStatusKehadiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian whereUsernameCbt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesertaUjian withoutTrashed()
 */
	class PesertaUjian extends \Eloquent {}
}

namespace App\Models\Pmb{
/**
 * @property int $pilihanprodi_id
 * @property int $pendaftaran_id
 * @property int $orgunit_id
 * @property int $urutan
 * @property string|null $rekomendasi_sistem
 * @property string|null $keputusan_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_pilihanprodi_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Shared\StrukturOrganisasi $orgUnit
 * @property-read \App\Models\Pmb\Pendaftaran $pendaftaran
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilihanProdi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilihanProdi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilihanProdi onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilihanProdi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilihanProdi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilihanProdi whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilihanProdi whereKeputusanAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilihanProdi whereOrgunitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilihanProdi wherePendaftaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilihanProdi wherePilihanprodiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilihanProdi whereRekomendasiSistem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilihanProdi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilihanProdi whereUrutan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilihanProdi withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilihanProdi withoutTrashed()
 */
	class PilihanProdi extends \Eloquent {}
}

namespace App\Models\Pmb{
/**
 * @property int $profilmahasiswa_id
 * @property int $user_id
 * @property string $nik
 * @property string|null $no_hp
 * @property string|null $tempat_lahir
 * @property string|null $tanggal_lahir
 * @property string|null $jenis_kelamin
 * @property string|null $alamat_lengkap
 * @property string|null $asal_sekolah
 * @property string|null $nisn
 * @property string|null $nama_ibu_kandung
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_profilmahasiswa_id
 * @property-read mixed $hashid
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa whereAlamatLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa whereAsalSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa whereNamaIbuKandung($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa whereNisn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa whereProfilmahasiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilMahasiswa withoutTrashed()
 */
	class ProfilMahasiswa extends \Eloquent {}
}

namespace App\Models\Pmb{
/**
 * @property int $riwayatpendaftaran_id
 * @property int $pendaftaran_id
 * @property string $status_baru
 * @property string|null $keterangan
 * @property int $user_pelaku_id
 * @property string $waktu_kejadian
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $encrypted_riwayatpendaftaran_id
 * @property-read mixed $hashid
 * @property-read \App\Models\User $pelaku
 * @property-read \App\Models\Pmb\Pendaftaran $pendaftaran
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendaftaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendaftaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendaftaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendaftaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendaftaran whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendaftaran wherePendaftaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendaftaran whereRiwayatpendaftaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendaftaran whereStatusBaru($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendaftaran whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendaftaran whereUserPelakuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatPendaftaran whereWaktuKejadian($value)
 */
	class RiwayatPendaftaran extends \Eloquent {}
}

namespace App\Models\Pmb{
/**
 * @property int $sesiujian_id
 * @property int $periode_id
 * @property string $nama_sesi
 * @property \Illuminate\Support\Carbon $waktu_mulai
 * @property \Illuminate\Support\Carbon $waktu_selesai
 * @property string|null $lokasi
 * @property int $kuota
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_sesiujian_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Pmb\Periode $periode
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pmb\PesertaUjian> $peserta
 * @property-read int|null $peserta_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian whereKuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian whereNamaSesi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian wherePeriodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian whereSesiujianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian whereWaktuMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian whereWaktuSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesiUjian withoutTrashed()
 */
	class SesiUjian extends \Eloquent {}
}

namespace App\Models\Pmb{
/**
 * @property int $syaratdokumenjalur_id
 * @property int $jalur_id
 * @property int $jenis_dokumen_id
 * @property int $is_wajib
 * @property string|null $keterangan_khusus
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_syaratdokumenjalur_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Pmb\Jalur $jalur
 * @property-read \App\Models\Pmb\JenisDokumen $jenisDokumen
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratDokumenJalur newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratDokumenJalur newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratDokumenJalur onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratDokumenJalur query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratDokumenJalur whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratDokumenJalur whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratDokumenJalur whereIsWajib($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratDokumenJalur whereJalurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratDokumenJalur whereJenisDokumenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratDokumenJalur whereKeteranganKhusus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratDokumenJalur whereSyaratdokumenjalurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratDokumenJalur whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratDokumenJalur withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratDokumenJalur withoutTrashed()
 */
	class SyaratDokumenJalur extends \Eloquent {}
}

namespace App\Models\Shared{
/**
 * @property int $id
 * @property string $question
 * @property string $answer
 * @property string|null $category
 * @property int $seq
 * @property bool $is_active
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_faq_id
 * @property-read mixed $hashid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ whereSeq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FAQ withoutTrashed()
 */
	class FAQ extends \Eloquent {}
}

namespace App\Models\Shared{
/**
 * @property string $mahasiswa_id
 * @property int|null $user_id
 * @property string $nim
 * @property string $nama
 * @property string $email
 * @property int|null $orgunit_id
 * @property string|null $jenis_kelamin
 * @property string|null $tempat_lahir
 * @property string|null $tanggal_lahir
 * @property string|null $agama
 * @property string|null $kewarganegaraan
 * @property string|null $no_hp
 * @property string|null $alamat
 * @property string|null $angkatan
 * @property string|null $foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_mahasiswa_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Shared\StrukturOrganisasi|null $prodi
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAngkatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereKewarganegaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereMahasiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereOrgunitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa withoutTrashed()
 */
	class Mahasiswa extends \Eloquent {}
}

namespace App\Models\Shared{
/**
 * @property int $pegawai_id
 * @property int|null $latest_riwayatdatadiri_id
 * @property int|null $latest_riwayatstatpegawai_id
 * @property int|null $latest_riwayatstataktifitas_id
 * @property int|null $latest_riwayatinpassing_id
 * @property int|null $latest_riwayatpendidikan_id
 * @property int|null $latest_riwayatjabfungsional_id
 * @property int|null $latest_riwayatjabstruktural_id
 * @property int|null $latest_riwayatpenugasan_id
 * @property int|null $atasan1
 * @property int|null $atasan2
 * @property string|null $photo Employee photo for face recognition
 * @property string|null $face_encoding Face encoding data for face matching
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read Pegawai|null $atasanDua
 * @property-read Pegawai|null $atasanSatu
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\FilePegawai> $files
 * @property-read int|null $files_count
 * @property-read mixed $email
 * @property-read mixed $encrypted_pegawai_id
 * @property-read mixed $hashid
 * @property-read mixed $inisial
 * @property-read mixed $nama
 * @property-read mixed $nip
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatDataDiri> $historyDataDiri
 * @property-read int|null $history_data_diri_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatInpassing> $historyInpassing
 * @property-read int|null $history_inpassing_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatJabFungsional> $historyJabFungsional
 * @property-read int|null $history_jab_fungsional_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatJabStruktural> $historyJabStruktural
 * @property-read int|null $history_jab_struktural_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatPenugasan> $historyPenugasan
 * @property-read int|null $history_penugasan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatStatAktifitas> $historyStatAktifitas
 * @property-read int|null $history_stat_aktifitas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatStatPegawai> $historyStatPegawai
 * @property-read int|null $history_stat_pegawai_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\Keluarga> $keluarga
 * @property-read int|null $keluarga_count
 * @property-read \App\Models\Hr\RiwayatDataDiri|null $latestDataDiri
 * @property-read \App\Models\Hr\RiwayatInpassing|null $latestInpassing
 * @property-read \App\Models\Hr\RiwayatJabFungsional|null $latestJabatanFungsional
 * @property-read \App\Models\Hr\RiwayatJabStruktural|null $latestJabatanStruktural
 * @property-read \App\Models\Hr\RiwayatPendidikan|null $latestPendidikan
 * @property-read \App\Models\Hr\RiwayatPenugasan|null $latestPenugasan
 * @property-read \App\Models\Hr\RiwayatStatAktifitas|null $latestStatusAktifitas
 * @property-read \App\Models\Hr\RiwayatStatPegawai|null $latestStatusPegawai
 * @property-read \App\Models\Shared\StrukturOrganisasi|null $orgUnit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\PengembanganDiri> $pengembanganDiri
 * @property-read int|null $pengembangan_diri_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hr\RiwayatPendidikan> $riwayatPendidikan
 * @property-read int|null $riwayat_pendidikan_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereAtasan1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereAtasan2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereFaceEncoding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatdatadiriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatinpassingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatjabfungsionalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatjabstrukturalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatpendidikanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatpenugasanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatstataktifitasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLatestRiwayatstatpegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai withoutTrashed()
 */
	class Pegawai extends \Eloquent {}
}

namespace App\Models\Shared{
/**
 * @property string $pengumuman_id
 * @property int $penulis_id
 * @property string $judul
 * @property string $isi
 * @property string $jenis
 * @property bool $is_published
 * @property string|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $attachments_url
 * @property-read mixed $cover_medium_url
 * @property-read mixed $cover_small_url
 * @property-read mixed $cover_url
 * @property-read mixed $encrypted_pengumuman_id
 * @property-read mixed $hashid
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Sys\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User $penulis
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman wherePengumumanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman wherePenulisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengumuman withoutTrashed()
 */
	class Pengumuman extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models\Shared{
/**
 * @property int $personil_id
 * @property int|null $user_id
 * @property int|null $org_unit_id
 * @property string $nama
 * @property string|null $email
 * @property string|null $nip
 * @property string|null $posisi
 * @property string|null $tipe outsource, vendor_staff, etc.
 * @property string|null $vendor Nama perusahaan vendor/penyedia
 * @property string|null $ttd_digital
 * @property bool $status_aktif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_personil_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Shared\StrukturOrganisasi|null $orgUnit
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereOrgUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil wherePersonilId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil wherePosisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereStatusAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereTtdDigital($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil whereVendor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personil withoutTrashed()
 */
	class Personil extends \Eloquent {}
}

namespace App\Models\Shared{
/**
 * @property int $menu_id
 * @property int|null $parent_id
 * @property string $title
 * @property string $type
 * @property string|null $url
 * @property string|null $route
 * @property int|null $page_id
 * @property string $position
 * @property string $target
 * @property int $sequence
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PublicMenu> $children
 * @property-read int|null $children_count
 * @property-read mixed $encrypted_menu_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Shared\PublicPage|null $page
 * @property-read PublicMenu|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu whereSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicMenu withoutTrashed()
 */
	class PublicMenu extends \Eloquent {}
}

namespace App\Models\Shared{
/**
 * @property int $page_id
 * @property string $title
 * @property string $slug
 * @property string|null $content
 * @property string|null $meta_desc
 * @property string|null $meta_keywords
 * @property int $is_published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $encrypted_page_id
 * @property-read mixed $hashid
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Sys\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage whereMetaDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicPage withoutTrashed()
 */
	class PublicPage extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models\Shared{
/**
 * @property int $id
 * @property string $image_url
 * @property string|null $title
 * @property string|null $caption
 * @property string|null $link
 * @property int $seq
 * @property bool $is_active
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_slideshow_id
 * @property-read mixed $hashid
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Sys\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow whereCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow whereSeq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slideshow withoutTrashed()
 */
	class Slideshow extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models\Shared{
/**
 * @property int $orgunit_id
 * @property int|null $parent_id
 * @property string $name
 * @property string|null $code
 * @property string|null $type
 * @property int $level
 * @property int $seq
 * @property int $sort_order
 * @property bool $is_active
 * @property string|null $description
 * @property int|null $successor_id
 * @property int|null $auditee_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, StrukturOrganisasi> $activeChildren
 * @property-read int|null $active_children_count
 * @property-read \App\Models\User|null $auditee
 * @property-read \Illuminate\Database\Eloquent\Collection<int, StrukturOrganisasi> $children
 * @property-read int|null $children_count
 * @property-read mixed $encrypted_org_unit_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pemutu\Indikator> $indikators
 * @property-read int|null $indikators_count
 * @property-read StrukturOrganisasi|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shared\Pegawai> $pegawai
 * @property-read int|null $pegawai_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shared\Personil> $personils
 * @property-read int|null $personils_count
 * @property-read StrukturOrganisasi|null $predecessor
 * @property-read StrukturOrganisasi|null $successor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereAuditeeUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereOrgunitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereSeq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereSuccessorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StrukturOrganisasi withoutTrashed()
 */
	class StrukturOrganisasi extends \Eloquent {}
}

namespace App\Models\Survei{
/**
 * @property int $id
 * @property int $survei_id
 * @property string|null $judul_halaman
 * @property int $urutan
 * @property string|null $deskripsi_halaman
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $encrypted_halaman_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Survei\Pertanyaan> $pertanyaan
 * @property-read int|null $pertanyaan_count
 * @property-read \App\Models\Survei\Survei $survei
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Halaman newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Halaman newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Halaman query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Halaman whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Halaman whereDeskripsiHalaman($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Halaman whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Halaman whereJudulHalaman($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Halaman whereSurveiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Halaman whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Halaman whereUrutan($value)
 */
	class Halaman extends \Eloquent {}
}

namespace App\Models\Survei{
/**
 * @property int $id
 * @property int $pengisian_id
 * @property int $pertanyaan_id
 * @property string|null $nilai_teks
 * @property int|null $nilai_angka
 * @property \Illuminate\Support\Carbon|null $nilai_tanggal
 * @property array<array-key, mixed>|null $nilai_json
 * @property int|null $opsi_id
 * @property string $dibuat_pada
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Survei\Opsi|null $opsi
 * @property-read \App\Models\Survei\Pengisian $pengisian
 * @property-read \App\Models\Survei\Pertanyaan $pertanyaan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jawaban newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jawaban newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jawaban query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jawaban whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jawaban whereDibuatPada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jawaban whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jawaban whereNilaiAngka($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jawaban whereNilaiJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jawaban whereNilaiTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jawaban whereNilaiTeks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jawaban whereOpsiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jawaban wherePengisianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jawaban wherePertanyaanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jawaban whereUpdatedAt($value)
 */
	class Jawaban extends \Eloquent {}
}

namespace App\Models\Survei{
/**
 * @property int $id
 * @property int $survei_id
 * @property int $pertanyaan_pemicu_id
 * @property string $operator
 * @property string $nilai_pemicu
 * @property string $aksi
 * @property int|null $target_halaman_id
 * @property int|null $target_pertanyaan_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Survei\Pertanyaan $pertanyaanPemicu
 * @property-read \App\Models\Survei\Survei $survei
 * @property-read \App\Models\Survei\Halaman|null $targetHalaman
 * @property-read \App\Models\Survei\Pertanyaan|null $targetPertanyaan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logika newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logika newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logika query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logika whereAksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logika whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logika whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logika whereNilaiPemicu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logika whereOperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logika wherePertanyaanPemicuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logika whereSurveiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logika whereTargetHalamanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logika whereTargetPertanyaanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logika whereUpdatedAt($value)
 */
	class Logika extends \Eloquent {}
}

namespace App\Models\Survei{
/**
 * @property int $id
 * @property int $pertanyaan_id
 * @property string $label
 * @property string|null $nilai_tersimpan
 * @property int $bobot_skor
 * @property int $urutan
 * @property int|null $next_pertanyaan_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $encrypted_opsi_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Survei\Pertanyaan|null $nextPertanyaan
 * @property-read \App\Models\Survei\Pertanyaan $pertanyaan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opsi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opsi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opsi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opsi whereBobotSkor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opsi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opsi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opsi whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opsi whereNextPertanyaanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opsi whereNilaiTersimpan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opsi wherePertanyaanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opsi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opsi whereUrutan($value)
 */
	class Opsi extends \Eloquent {}
}

namespace App\Models\Survei{
/**
 * @property int $id
 * @property int $survei_id
 * @property int|null $user_id
 * @property string|null $entitas_target_type
 * @property int|null $entitas_target_id
 * @property string $status
 * @property \Illuminate\Support\Carbon $waktu_mulai
 * @property \Illuminate\Support\Carbon|null $waktu_selesai
 * @property string|null $ip_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $entitasTarget
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Survei\Jawaban> $jawaban
 * @property-read int|null $jawaban_count
 * @property-read \App\Models\Survei\Survei $survei
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengisian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengisian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengisian query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengisian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengisian whereEntitasTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengisian whereEntitasTargetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengisian whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengisian whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengisian whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengisian whereSurveiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengisian whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengisian whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengisian whereWaktuMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengisian whereWaktuSelesai($value)
 */
	class Pengisian extends \Eloquent {}
}

namespace App\Models\Survei{
/**
 * @property int $id
 * @property int $survei_id
 * @property int $halaman_id
 * @property string $teks_pertanyaan
 * @property string|null $bantuan_teks
 * @property string $tipe
 * @property array<array-key, mixed>|null $config_json
 * @property bool $wajib_diisi
 * @property int $urutan
 * @property int|null $next_pertanyaan_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $encrypted_pertanyaan_id
 * @property-read mixed $hashid
 * @property-read \App\Models\Survei\Halaman $halaman
 * @property-read \App\Models\Survei\Logika|null $logika
 * @property-read Pertanyaan|null $nextPertanyaan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Survei\Opsi> $opsi
 * @property-read int|null $opsi_count
 * @property-read \App\Models\Survei\Survei $survei
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pertanyaan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pertanyaan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pertanyaan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pertanyaan whereBantuanTeks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pertanyaan whereConfigJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pertanyaan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pertanyaan whereHalamanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pertanyaan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pertanyaan whereNextPertanyaanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pertanyaan whereSurveiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pertanyaan whereTeksPertanyaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pertanyaan whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pertanyaan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pertanyaan whereUrutan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pertanyaan whereWajibDiisi($value)
 */
	class Pertanyaan extends \Eloquent {}
}

namespace App\Models\Survei{
/**
 * @property int $id
 * @property int $survei_id
 * @property int|null $pertanyaan_id
 * @property string $model_type
 * @property int $model_id
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $model
 * @property-read \App\Models\Survei\Survei $survei
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RelasiKonteks newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RelasiKonteks newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RelasiKonteks query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RelasiKonteks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RelasiKonteks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RelasiKonteks whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RelasiKonteks whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RelasiKonteks whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RelasiKonteks wherePertanyaanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RelasiKonteks whereSurveiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RelasiKonteks whereUpdatedAt($value)
 */
	class RelasiKonteks extends \Eloquent {}
}

namespace App\Models\Survei{
/**
 * @property int $id
 * @property string $judul
 * @property string|null $deskripsi
 * @property string $slug
 * @property string|null $periode
 * @property string $mode
 * @property string $target_role
 * @property bool $is_aktif
 * @property bool $wajib_login
 * @property bool $bisa_isi_ulang
 * @property \Illuminate\Support\Carbon|null $tanggal_mulai
 * @property \Illuminate\Support\Carbon|null $tanggal_selesai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_survei_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Survei\Halaman> $halaman
 * @property-read int|null $halaman_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Survei\Pengisian> $pengisian
 * @property-read int|null $pengisian_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Survei\Pertanyaan> $pertanyaan
 * @property-read int|null $pertanyaan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Survei\RelasiKonteks> $relasiKonteks
 * @property-read int|null $relasi_konteks_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei whereBisaIsiUlang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei whereIsAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei wherePeriode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei whereTargetRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei whereWajibLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survei withoutTrashed()
 */
	class Survei extends \Eloquent {}
}

namespace App\Models\Sys{
/**
 * @property int $id
 * @property string|null $log_name
 * @property string $description
 * @property string|null $event
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string|null $causer_type
 * @property int|null $causer_id
 * @property array<array-key, mixed>|null $properties
 * @property string|null $batch_uuid
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|null $causer
 * @property-read \Illuminate\Support\Collection $changes
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Model|null $subject
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity causedBy(\Illuminate\Database\Eloquent\Model $causer)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity forBatch(string $batchUuid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity forEvent(string $event)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity forSubject(\Illuminate\Database\Eloquent\Model $subject)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity hasBatch()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity inLog(...$logNames)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereBatchUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereCauserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereCauserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereLogName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereUserAgent($value)
 */
	class Activity extends \Eloquent {}
}

namespace App\Models\Sys{
/**
 * @property int $id
 * @property string $level
 * @property string $message
 * @property string|null $exception_class
 * @property string $file
 * @property int $line
 * @property array<array-key, mixed>|null $trace
 * @property array<array-key, mixed>|null $context
 * @property string|null $url
 * @property string|null $method
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read mixed $formatted_trace
 * @property-read mixed $hashid
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog dateRange($startDate, $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog level($level)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereContext($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereExceptionClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereTrace($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ErrorLog withoutTrashed()
 */
	class ErrorLog extends \Eloquent {}
}

namespace App\Models\Sys{
/**
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string|null $uuid
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property string|null $conversions_disk
 * @property int $size
 * @property array<array-key, mixed> $manipulations
 * @property array<array-key, mixed> $custom_properties
 * @property array<array-key, mixed> $generated_conversions
 * @property array<array-key, mixed> $responsive_images
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $extension
 * @property-read mixed $hashid
 * @property-read mixed $human_readable_size
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $model
 * @property-read mixed $original_url
 * @property-read mixed $preview_url
 * @property-read mixed $type
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, static> all($columns = ['*'])
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereCollectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereConversionsDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereCustomProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereGeneratedConversions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereManipulations($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereResponsiveImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media withoutTrashed()
 */
	class Media extends \Eloquent {}
}

namespace App\Models\Sys{
/**
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array<array-key, mixed> $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notifiable
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification read()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification unread()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification withoutTrashed()
 */
	class Notification extends \Eloquent {}
}

namespace App\Models\Sys{
/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property string|null $category
 * @property string|null $sub_category
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sys\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereSubCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutTrashed()
 */
	class Permission extends \Eloquent implements \Spatie\Searchable\Searchable {}
}

namespace App\Models\Sys{
/**
 * @property int $id
 * @property string $tokenable_type
 * @property int $tokenable_id
 * @property string $name
 * @property string $token
 * @property array<array-key, mixed>|null $abilities
 * @property \Illuminate\Support\Carbon|null $last_used_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $tokenable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereAbilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereTokenableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereTokenableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken withoutTrashed()
 */
	class PersonalAccessToken extends \Eloquent {}
}

namespace App\Models\Sys{
/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $encrypted_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sys\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutTrashed()
 */
	class Role extends \Eloquent implements \Spatie\Searchable\Searchable {}
}

namespace App\Models\Sys{
/**
 * @property int $id
 * @property int $host_id
 * @property string $type
 * @property string|null $status
 * @property int $enabled
 * @property string|null $last_run_message
 * @property array<array-key, mixed>|null $last_run_output
 * @property \Illuminate\Support\Carbon|null $last_ran_at
 * @property int|null $next_run_in_minutes
 * @property \Illuminate\Support\Carbon|null $started_throttling_failing_notifications_at
 * @property array<array-key, mixed>|null $custom_properties
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $hashid
 * @property-read string $latest_run_diff
 * @property-read string $next_run_diff
 * @property-read string $status_as_emoji
 * @property-read string $summary
 * @property-read \App\Models\Sys\ServerMonitorHost $host
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck enabled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck healthy()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck unhealthy()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereCustomProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereHostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereLastRanAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereLastRunMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereLastRunOutput($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereNextRunInMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereStartedThrottlingFailingNotificationsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorCheck withoutTrashed()
 */
	class ServerMonitorCheck extends \Eloquent {}
}

namespace App\Models\Sys{
/**
 * @property int $id
 * @property string $name
 * @property string|null $ssh_user
 * @property int|null $port
 * @property string|null $ip
 * @property array<array-key, mixed>|null $custom_properties
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sys\ServerMonitorCheck> $checks
 * @property-read int|null $checks_count
 * @property-read \Illuminate\Support\Collection $enabled_checks
 * @property-read mixed $hashid
 * @property-read string $health_as_emoji
 * @property-read string $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost whereCustomProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost whereSshUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServerMonitorHost withoutTrashed()
 */
	class ServerMonitorHost extends \Eloquent {}
}

namespace App\Models\Sys{
/**
 * @property int|null $total_users
 * @property int|null $new_users_7days
 * @property int|null $total_roles
 * @property int|null $total_permissions
 * @property int|null $today_activities
 * @property int|null $activities_7days
 * @property int|null $total_activities
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SysDashboardView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SysDashboardView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SysDashboardView query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SysDashboardView whereActivities7days($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SysDashboardView whereNewUsers7days($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SysDashboardView whereTodayActivities($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SysDashboardView whereTotalActivities($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SysDashboardView whereTotalPermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SysDashboardView whereTotalRoles($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SysDashboardView whereTotalUsers($value)
 */
	class SysDashboardView extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $expired_at
 * @property string $password
 * @property string|null $google_id
 * @property string|null $avatar
 * @property int|null $pegawai_id Link to Shared Pegawai ID
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sys\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read mixed $avatar_medium_url
 * @property-read mixed $avatar_small_url
 * @property-read mixed $avatar_url
 * @property-read mixed $encrypted_id
 * @property-read mixed $hashid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\Kegiatan> $kegiatans
 * @property-read int|null $kegiatans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\LaporanKerusakan> $laporanKerusakans
 * @property-read int|null $laporan_kerusakans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\LogPenggunaanPc> $logPenggunaanPcs
 * @property-read int|null $log_penggunaan_pcs_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Sys\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \App\Models\Sys\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\PcAssignment> $pcAssignments
 * @property-read int|null $pc_assignments_count
 * @property-read \App\Models\Shared\Pegawai|null $pegawai
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sys\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\Pmb\ProfilMahasiswa|null $profilPmb
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sys\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lab\RequestSoftware> $softwareRequests
 * @property-read int|null $software_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sys\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \App\Models\Sys\Notification> $unreadNotifications
 * @property-read int|null $unread_notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent implements \Spatie\MediaLibrary\HasMedia, \Spatie\Searchable\Searchable {}
}

