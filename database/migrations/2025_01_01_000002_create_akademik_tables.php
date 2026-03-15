<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Akademik module.
     */
    public function up(): void
    {
        // =====================================================================
        // 1. Semesters (Moved from Lab)
        // =====================================================================
        Schema::create('akademik_semesters', function (Blueprint $table) {
            $table->id('semester_id');
            $table->string('tahun_ajaran', 50);
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        // =====================================================================
        // 2. Mata Kuliahs (Moved from Lab)
        // =====================================================================
        Schema::create('akademik_mata_kuliahs', function (Blueprint $table) {
            $table->id('mata_kuliah_id');
            $table->string('kode_mk', 50);
            $table->string('nama_mk', 191);
            $table->integer('sks');
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        // =====================================================================
        // 3. Mahasiswa (unified from lab_mahasiswa + eoffice_mahasiswa)
        // =====================================================================
        Schema::create('akademik_mahasiswa', function (Blueprint $table) {
            $table->id('mahasiswa_id');
            $table->unsignedBigInteger('user_id')->nullable()->comment('Foreign key to users table');
            $table->string('nim')->unique();
            $table->string('nama');
            $table->string('email')->unique();
            $table->unsignedBigInteger('orgunit_id')->nullable();

            // Demographic fields
            $table->string('jenis_kelamin', 20)->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama', 30)->nullable();
            $table->string('kewarganegaraan', 50)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('angkatan', 4)->nullable();
            $table->string('foto')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('orgunit_id')->references('orgunit_id')->on('hr_struktur_organisasi')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('akademik_mahasiswa');
        Schema::dropIfExists('akademik_mata_kuliahs');
        Schema::dropIfExists('akademik_semesters');
        Schema::enableForeignKeyConstraints();
    }
};
