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
        // Add indexes to foreign keys that are commonly used in JOINs
        // Note: Only adding indexes for foreign keys that don't already exist

        // For tables in the main migration, foreign keys don't automatically get indexes
        Schema::table('jadwal_kuliah', function (Blueprint $table) {
            $table->index(['semester_id']);
            $table->index(['mata_kuliah_id']);
            $table->index(['dosen_id']);
            $table->index(['lab_id']);
        });

        Schema::table('pc_assignments', function (Blueprint $table) {
            $table->index(['user_id']);
            $table->index(['jadwal_id']);
            $table->index(['lab_id']);
        });

        Schema::table('log_penggunaan_pcs', function (Blueprint $table) {
            $table->index(['pc_assignment_id']);
            $table->index(['user_id']);
            $table->index(['jadwal_id']);
            $table->index(['lab_id']);
            $table->index(['waktu_isi']); // Commonly used for ordering in logs
        });

        Schema::table('kegiatans', function (Blueprint $table) {
            $table->index(['lab_id']);
            $table->index(['penyelenggara_id']);
            $table->index(['tanggal']); // For date-based queries
        });

        Schema::table('log_penggunaan_labs', function (Blueprint $table) {
            $table->index(['kegiatan_id']);
            $table->index(['lab_id']);
            $table->index(['waktu_isi']); // For date-based queries and ordering
        });

        Schema::table('request_software', function (Blueprint $table) {
            $table->index(['dosen_id']);
            $table->index(['status']); // Commonly used for filtering
        });

        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->index(['inventaris_id']);
            $table->index(['teknisi_id']);
            $table->index(['status']); // Commonly used for filtering
        });

        Schema::table('pengumuman', function (Blueprint $table) {
            $table->index(['penulis_id']);
            $table->index(['jenis']); // For filtering between pengumuman and berita
            $table->index(['is_published']); // For filtering published items
            $table->index(['published_at']); // For ordering by publication date
        });

        // Add indexes to users table
        Schema::table('users', function (Blueprint $table) {
            $table->index(['nim']); // For student lookups
            $table->index(['nip']); // For employee lookups
        });

        // Add indexes to semesters table
        Schema::table('semesters', function (Blueprint $table) {
            $table->index(['is_active']); // For getting current semester
        });

        // lab_inventaris already has indexes on ['inventaris_id', 'lab_id'] and 'kode_inventaris'
        // lab_teams already has a unique constraint on ['lab_id', 'user_id'] but individual indexes still useful

        Schema::table('lab_teams', function (Blueprint $table) {
            // The unique constraint covers ['lab_id', 'user_id'] but separate indexes are still useful
            // for queries filtering on only one of these columns
            $table->index(['lab_id']);
            $table->index(['user_id']);
        });

        // For sys_activity_log, sys_notifications, and sys_sessions,
        // morphs() and explicit indexes were already added in original migrations
        Schema::table('sys_activity_log', function (Blueprint $table) {
            // 'subject_type', 'subject_id' already indexed by morphs()
            $table->index(['causer_type', 'causer_id']); // For querying activities by specific user types
            $table->index(['created_at']); // For ordering by activity time
        });

        Schema::table('sys_notifications', function (Blueprint $table) {
            // 'notifiable_type', 'notifiable_id' already indexed by morphs()
            $table->index(['read_at']); // For filtering read/unread notifications
        });

        // sys_sessions already has indexes on 'user_id' and 'last_activity' from original migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from jadwal_kuliah table
        Schema::table('jadwal_kuliah', function (Blueprint $table) {
            $table->dropIndex(['jadwal_kuliah_semester_id_index']);
            $table->dropIndex(['jadwal_kuliah_mata_kuliah_id_index']);
            $table->dropIndex(['jadwal_kuliah_dosen_id_index']);
            $table->dropIndex(['jadwal_kuliah_lab_id_index']);
        });

        // Remove indexes from pc_assignments table
        Schema::table('pc_assignments', function (Blueprint $table) {
            $table->dropIndex(['pc_assignments_user_id_index']);
            $table->dropIndex(['pc_assignments_jadwal_id_index']);
            $table->dropIndex(['pc_assignments_lab_id_index']);
        });

        // Remove indexes from log_penggunaan_pcs table
        Schema::table('log_penggunaan_pcs', function (Blueprint $table) {
            $table->dropIndex(['log_penggunaan_pcs_pc_assignment_id_index']);
            $table->dropIndex(['log_penggunaan_pcs_user_id_index']);
            $table->dropIndex(['log_penggunaan_pcs_jadwal_id_index']);
            $table->dropIndex(['log_penggunaan_pcs_lab_id_index']);
            $table->dropIndex(['log_penggunaan_pcs_waktu_isi_index']);
        });

        // Remove indexes from kegiatans table
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropIndex(['kegiatans_lab_id_index']);
            $table->dropIndex(['kegiatans_penyelenggara_id_index']);
            $table->dropIndex(['kegiatans_tanggal_index']);
        });

        // Remove indexes from log_penggunaan_labs table
        Schema::table('log_penggunaan_labs', function (Blueprint $table) {
            $table->dropIndex(['log_penggunaan_labs_kegiatan_id_index']);
            $table->dropIndex(['log_penggunaan_labs_lab_id_index']);
            $table->dropIndex(['log_penggunaan_labs_waktu_isi_index']);
        });

        // Remove indexes from request_software table
        Schema::table('request_software', function (Blueprint $table) {
            $table->dropIndex(['request_software_dosen_id_index']);
            $table->dropIndex(['request_software_status_index']);
        });

        // Remove indexes from laporan_kerusakan table
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->dropIndex(['laporan_kerusakan_inventaris_id_index']);
            $table->dropIndex(['laporan_kerusakan_teknisi_id_index']);
            $table->dropIndex(['laporan_kerusakan_status_index']);
        });

        // Remove indexes from pengumuman table
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->dropIndex(['pengumuman_penulis_id_index']);
            $table->dropIndex(['pengumuman_jenis_index']);
            $table->dropIndex(['pengumuman_is_published_index']);
            $table->dropIndex(['pengumuman_published_at_index']);
        });

        // Remove indexes from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['users_nim_index']);
            $table->dropIndex(['users_nip_index']);
        });

        // Remove indexes from semesters table
        Schema::table('semesters', function (Blueprint $table) {
            $table->dropIndex(['semesters_is_active_index']);
        });

        // Remove indexes from lab_teams table
        Schema::table('lab_teams', function (Blueprint $table) {
            $table->dropIndex(['lab_teams_lab_id_index']);
            $table->dropIndex(['lab_teams_user_id_index']);
        });

        // Remove indexes from sys_activity_log table
        Schema::table('sys_activity_log', function (Blueprint $table) {
            $table->dropIndex(['sys_activity_log_causer_type_causer_id_index']);
            $table->dropIndex(['sys_activity_log_created_at_index']);
        });

        // Remove indexes from sys_notifications table
        Schema::table('sys_notifications', function (Blueprint $table) {
            $table->dropIndex(['sys_notifications_read_at_index']);
        });

        // sys_sessions indexes were from original migration, no need to drop in this migration
    }
};
