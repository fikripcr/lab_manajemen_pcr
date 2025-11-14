<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesForPerformance extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Add indexes to commonly queried columns

        // users table
        Schema::table('users', function (Blueprint $table) {
            $table->index(['email'], 'users_email_index');
        });

        // jadwals table
        Schema::table('jadwals', function (Blueprint $table) {
            $table->index(['semester_id'], 'jadwals_semester_id_index');
            $table->index(['mata_kuliah_id'], 'jadwals_mata_kuliah_id_index');
            $table->index(['dosen_id'], 'jadwals_dosen_id_index');
            $table->index(['lab_id'], 'jadwals_lab_id_index');
            $table->index(['hari', 'jam_mulai', 'jam_selesai'], 'jadwals_time_index');
        });

        // pc_assignments table
        Schema::table('pc_assignments', function (Blueprint $table) {
            $table->index(['user_id'], 'pc_assignments_user_id_index');
            $table->index(['jadwal_id'], 'pc_assignments_jadwal_id_index');
            $table->index(['lab_id'], 'pc_assignments_lab_id_index');
            $table->index(['is_active'], 'pc_assignments_is_active_index');
        });

        // log_penggunaan_pcs table
        Schema::table('log_penggunaan_pcs', function (Blueprint $table) {
            $table->index(['pc_assignment_id'], 'log_penggunaan_pcs_assignment_id_index');
            $table->index(['user_id'], 'log_penggunaan_pcs_user_id_index');
            $table->index(['jadwal_id'], 'log_penggunaan_pcs_jadwal_id_index');
            $table->index(['lab_id'], 'log_penggunaan_pcs_lab_id_index');
            $table->index(['waktu_isi'], 'log_penggunaan_pcs_waktu_isi_index');
        });

        // log_penggunaan_labs table
        Schema::table('log_penggunaan_labs', function (Blueprint $table) {
            $table->index(['kegiatan_id'], 'log_penggunaan_labs_kegiatan_id_index');
            $table->index(['lab_id'], 'log_penggunaan_labs_lab_id_index');
            $table->index(['waktu_isi'], 'log_penggunaan_labs_waktu_isi_index');
        });

        // kegiatans table
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->index(['lab_id'], 'kegiatans_lab_id_index');
            $table->index(['penyelenggara_id'], 'kegiatans_penyelenggara_id_index');
            $table->index(['tanggal'], 'kegiatans_tanggal_index');
            $table->index(['status'], 'kegiatans_status_index');
        });

        // inventaris table
        Schema::table('inventaris', function (Blueprint $table) {
            $table->index(['lab_id'], 'inventaris_lab_id_index');
        });

        // laporan_kerusakan table
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->index(['inventaris_id'], 'laporan_kerusakan_inventaris_id_index');
            $table->index(['teknisi_id'], 'laporan_kerusakan_teknisi_id_index');
            $table->index(['status'], 'laporan_kerusakan_status_index');
        });

        // request_software table
        Schema::table('request_software', function (Blueprint $table) {
            $table->index(['dosen_id'], 'request_software_dosen_id_index');
            $table->index(['status'], 'request_software_status_index');
        });

        // mata_kuliahs table - no indexes needed since lab_id was removed
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Drop indexes - wrap in try-catch to handle cases where index might not exist
        Schema::table('users', function (Blueprint $table) {
            // Drop role_id index if it exists (in case it was created in previous migrations)
            try {
                $table->dropIndex(['users_users_role_id_index_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            // Drop role_id index with alternate name if it exists
            try {
                $table->dropIndex(['users_role_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            // Drop email index
            try {
                $table->dropIndex(['users_email_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('jadwals', function (Blueprint $table) {
            try {
                $table->dropIndex(['jadwals_semester_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['jadwals_mata_kuliah_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['jadwals_dosen_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['jadwals_lab_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['jadwals_time_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('pc_assignments', function (Blueprint $table) {
            try {
                $table->dropIndex(['pc_assignments_user_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['pc_assignments_jadwal_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['pc_assignments_lab_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['pc_assignments_is_active_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('log_penggunaan_pcs', function (Blueprint $table) {
            try {
                $table->dropIndex(['log_penggunaan_pcs_assignment_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['log_penggunaan_pcs_user_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['log_penggunaan_pcs_jadwal_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['log_penggunaan_pcs_lab_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['log_penggunaan_pcs_waktu_isi_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('log_penggunaan_labs', function (Blueprint $table) {
            try {
                $table->dropIndex(['log_penggunaan_labs_kegiatan_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['log_penggunaan_labs_lab_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['log_penggunaan_labs_waktu_isi_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('kegiatans', function (Blueprint $table) {
            try {
                $table->dropIndex(['kegiatans_lab_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['kegiatans_penyelenggara_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['kegiatans_tanggal_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['kegiatans_status_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('inventaris', function (Blueprint $table) {
            try {
                $table->dropIndex(['inventaris_lab_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            try {
                $table->dropIndex(['laporan_kerusakan_inventaris_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['laporan_kerusakan_teknisi_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['laporan_kerusakan_status_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        Schema::table('request_software', function (Blueprint $table) {
            try {
                $table->dropIndex(['request_software_dosen_id_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            try {
                $table->dropIndex(['request_software_status_index']);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        });

        // mata_kuliahs table - no indexes to drop since lab_id was removed
    }
}