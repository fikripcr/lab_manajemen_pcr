<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            // E-Office
            'eoffice_feedback', 'eoffice_jenis_layanan', 'eoffice_jenis_layanan_disposisi',
            'eoffice_jenis_layanan_isian', 'eoffice_jenis_layanan_periode', 'eoffice_jenis_layanan_pic',
            'eoffice_kategori_isian', 'eoffice_kategori_perusahaan', 'eoffice_layanan',
            'eoffice_layanan_diskusi', 'eoffice_layanan_isian', 'eoffice_layanan_keterlibaten',
            'eoffice_layanan_periode', 'eoffice_layanan_status', 'eoffice_mahasiswa',
            'eoffice_pegawai', 'eoffice_perusahaan', 'eoffice_tanggal_tidak_hadir',

            // HR
            'hr_att_device', 'hr_golongan_inpassing', 'hr_indisipliner', 'hr_indisipliner_pegawai',
            'hr_jabatan_fungsional', 'hr_jenis_indisipliner', 'hr_jenis_izin', 'hr_jenis_shift',
            'hr_keluarga', 'hr_org_unit', 'hr_pegawai', 'hr_pengembangan_diri', 'hr_perizinan',
            'hr_presensi', 'hr_riwayat_approval', 'hr_riwayat_datadiri', 'hr_riwayat_inpassing',
            'hr_riwayat_jabfungsional', 'hr_riwayat_jabstruktural', 'hr_riwayat_pendidikan',
            'hr_riwayat_penugasan', 'hr_riwayat_stataktifitas', 'hr_riwayat_statpegawai',
            'hr_status_aktifitas', 'hr_status_pegawai', 'hr_tanggal_libur', 'hr_tanggal_tidak_masuk',

            // Lab
            'lab_inventarises', 'lab_jadwal_kuliah', 'lab_kegiatans', 'lab_labs',
            'lab_inventaris_penempatan', 'lab_media', 'lab_teams', 'lab_laporan_kerusakan',
            'lab_log_penggunaan_labs', 'lab_log_penggunaan_pcs', 'lab_mata_kuliahs',
            'lab_pc_assignments', 'lab_pengumuman', 'lab_request_software', 'lab_semesters',

            // Pemutu
            'pemutu_dok_sub', 'pemutu_dokumen', 'pemutu_indikator', 'pemutu_indikator_personil',
            'pemutu_label', 'pemutu_label_types', 'pemutu_org_unit', 'pemutu_personil',

            // Sys
            'sys_error_log', 'sys_media', 'sys_notifications', 'sys_permissions',
            'sys_personal_access_tokens', 'sys_roles', 'sys_checks', 'sys_hosts',
        ];

        foreach ($tables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                // Soft Deletes
                if (! Schema::hasColumn($tableName, 'deleted_at')) {
                    $table->softDeletes();
                }

                // Blameable (Userstamps)
                if (! Schema::hasColumn($tableName, 'created_by')) {
                    $table->unsignedBigInteger('created_by')->nullable()->after('created_at');
                    $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
                }

                if (! Schema::hasColumn($tableName, 'updated_by')) {
                    $table->unsignedBigInteger('updated_by')->nullable()->after('updated_at');
                    $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
                }

                if (! Schema::hasColumn($tableName, 'deleted_by')) {
                    $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');
                    $table->foreign('deleted_by')->references('id')->on('users')->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not implemented for mass sync to avoid accidental data loss of existing audit columns
    }
};
