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
        // Add indexes to commonly queried columns on existing tables

        // users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasIndex('users', 'users_email_index')) {
                $table->index(['email'], 'users_email_index');
            }
        });

        // jadwal_kuliah table
        Schema::table('jadwal_kuliah', function (Blueprint $table) {
            if (!Schema::hasIndex('jadwal_kuliah', 'jadwal_kuliah_semester_id_index')) {
                $table->index(['semester_id'], 'jadwal_kuliah_semester_id_index');
            }
            if (!Schema::hasIndex('jadwal_kuliah', 'jadwal_kuliah_mata_kuliah_id_index')) {
                $table->index(['mata_kuliah_id'], 'jadwal_kuliah_mata_kuliah_id_index');
            }
            if (!Schema::hasIndex('jadwal_kuliah', 'jadwal_kuliah_dosen_id_index')) {
                $table->index(['dosen_id'], 'jadwal_kuliah_dosen_id_index');
            }
            if (!Schema::hasIndex('jadwal_kuliah', 'jadwal_kuliah_lab_id_index')) {
                $table->index(['lab_id'], 'jadwal_kuliah_lab_id_index');
            }
            if (!Schema::hasIndex('jadwal_kuliah', 'jadwal_kuliah_time_index')) {
                $table->index(['hari', 'jam_mulai', 'jam_selesai'], 'jadwal_kuliah_time_index');
            }
        });

        // pc_assignments table
        Schema::table('pc_assignments', function (Blueprint $table) {
            if (!Schema::hasIndex('pc_assignments', 'pc_assignments_user_id_index')) {
                $table->index(['user_id'], 'pc_assignments_user_id_index');
            }
            if (!Schema::hasIndex('pc_assignments', 'pc_assignments_jadwal_id_index')) {
                $table->index(['jadwal_id'], 'pc_assignments_jadwal_id_index');
            }
            if (!Schema::hasIndex('pc_assignments', 'pc_assignments_lab_id_index')) {
                $table->index(['lab_id'], 'pc_assignments_lab_id_index');
            }
            if (!Schema::hasIndex('pc_assignments', 'pc_assignments_is_active_index')) {
                $table->index(['is_active'], 'pc_assignments_is_active_index');
            }
        });

        // log_penggunaan_pcs table
        Schema::table('log_penggunaan_pcs', function (Blueprint $table) {
            if (!Schema::hasIndex('log_penggunaan_pcs', 'log_penggunaan_pcs_assignment_id_index')) {
                $table->index(['pc_assignment_id'], 'log_penggunaan_pcs_assignment_id_index');
            }
            if (!Schema::hasIndex('log_penggunaan_pcs', 'log_penggunaan_pcs_user_id_index')) {
                $table->index(['user_id'], 'log_penggunaan_pcs_user_id_index');
            }
            if (!Schema::hasIndex('log_penggunaan_pcs', 'log_penggunaan_pcs_jadwal_id_index')) {
                $table->index(['jadwal_id'], 'log_penggunaan_pcs_jadwal_id_index');
            }
            if (!Schema::hasIndex('log_penggunaan_pcs', 'log_penggunaan_pcs_lab_id_index')) {
                $table->index(['lab_id'], 'log_penggunaan_pcs_lab_id_index');
            }
            if (!Schema::hasIndex('log_penggunaan_pcs', 'log_penggunaan_pcs_waktu_isi_index')) {
                $table->index(['waktu_isi'], 'log_penggunaan_pcs_waktu_isi_index');
            }
        });

        // log_penggunaan_labs table
        Schema::table('log_penggunaan_labs', function (Blueprint $table) {
            if (!Schema::hasIndex('log_penggunaan_labs', 'log_penggunaan_labs_kegiatan_id_index')) {
                $table->index(['kegiatan_id'], 'log_penggunaan_labs_kegiatan_id_index');
            }
            if (!Schema::hasIndex('log_penggunaan_labs', 'log_penggunaan_labs_lab_id_index')) {
                $table->index(['lab_id'], 'log_penggunaan_labs_lab_id_index');
            }
            if (!Schema::hasIndex('log_penggunaan_labs', 'log_penggunaan_labs_waktu_isi_index')) {
                $table->index(['waktu_isi'], 'log_penggunaan_labs_waktu_isi_index');
            }
        });

        // kegiatans table
        Schema::table('kegiatans', function (Blueprint $table) {
            if (!Schema::hasIndex('kegiatans', 'kegiatans_lab_id_index')) {
                $table->index(['lab_id'], 'kegiatans_lab_id_index');
            }
            if (!Schema::hasIndex('kegiatans', 'kegiatans_penyelenggara_id_index')) {
                $table->index(['penyelenggara_id'], 'kegiatans_penyelenggara_id_index');
            }
            if (!Schema::hasIndex('kegiatans', 'kegiatans_tanggal_index')) {
                $table->index(['tanggal'], 'kegiatans_tanggal_index');
            }
            if (!Schema::hasIndex('kegiatans', 'kegiatans_status_index')) {
                $table->index(['status'], 'kegiatans_status_index');
            }
        });

        // inventaris table
        Schema::table('inventaris', function (Blueprint $table) {
            if (!Schema::hasIndex('inventaris', 'inventaris_lab_id_index')) {
                $table->index(['lab_id'], 'inventaris_lab_id_index');
            }
        });

        // laporan_kerusakan table
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            if (!Schema::hasIndex('laporan_kerusakan', 'laporan_kerusakan_inventaris_id_index')) {
                $table->index(['inventaris_id'], 'laporan_kerusakan_inventaris_id_index');
            }
            if (!Schema::hasIndex('laporan_kerusakan', 'laporan_kerusakan_teknisi_id_index')) {
                $table->index(['teknisi_id'], 'laporan_kerusakan_teknisi_id_index');
            }
            if (!Schema::hasIndex('laporan_kerusakan', 'laporan_kerusakan_status_index')) {
                $table->index(['status'], 'laporan_kerusakan_status_index');
            }
        });

        // request_software table
        Schema::table('request_software', function (Blueprint $table) {
            if (!Schema::hasIndex('request_software', 'request_software_dosen_id_index')) {
                $table->index(['dosen_id'], 'request_software_dosen_id_index');
            }
            if (!Schema::hasIndex('request_software', 'request_software_status_index')) {
                $table->index(['status'], 'request_software_status_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes using try-catch to handle cases where index might not exist
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropIndex('users_email_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('jadwal_kuliah', function (Blueprint $table) {
            try {
                $table->dropIndex('jadwal_kuliah_semester_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('jadwal_kuliah_mata_kuliah_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('jadwal_kuliah_dosen_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('jadwal_kuliah_lab_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('jadwal_kuliah_time_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('pc_assignments', function (Blueprint $table) {
            try {
                $table->dropIndex('pc_assignments_user_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('pc_assignments_jadwal_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('pc_assignments_lab_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('pc_assignments_is_active_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('log_penggunaan_pcs', function (Blueprint $table) {
            try {
                $table->dropIndex('log_penggunaan_pcs_assignment_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('log_penggunaan_pcs_user_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('log_penggunaan_pcs_jadwal_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('log_penggunaan_pcs_lab_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('log_penggunaan_pcs_waktu_isi_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('log_penggunaan_labs', function (Blueprint $table) {
            try {
                $table->dropIndex('log_penggunaan_labs_kegiatan_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('log_penggunaan_labs_lab_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('log_penggunaan_labs_waktu_isi_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('kegiatans', function (Blueprint $table) {
            try {
                $table->dropIndex('kegiatans_lab_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('kegiatans_penyelenggara_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('kegiatans_tanggal_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('kegiatans_status_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('inventaris', function (Blueprint $table) {
            try {
                $table->dropIndex('inventaris_lab_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            try {
                $table->dropIndex('laporan_kerusakan_inventaris_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('laporan_kerusakan_teknisi_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('laporan_kerusakan_status_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('request_software', function (Blueprint $table) {
            try {
                $table->dropIndex('request_software_dosen_id_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex('request_software_status_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });
    }
};