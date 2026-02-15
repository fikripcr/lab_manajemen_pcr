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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('hr_lembur_pegawai');
        Schema::dropIfExists('hr_lembur');

        // Tabel 1: hr_lembur (Main overtime table)
        Schema::create('hr_lembur', function (Blueprint $table) {
            $table->id('lembur_id');

            // Pengusul & Detail
            $table->unsignedBigInteger('pengusul_id')->comment('ID pegawai yang mengusulkan');
            $table->string('judul', 255)->comment('Judul/ringkasan lembur');
            $table->text('uraian_pekerjaan')->nullable()->comment('Deskripsi detail pekerjaan');
            $table->text('alasan')->nullable()->comment('Alasan lembur');

            // Waktu Pelaksanaan
            $table->date('tgl_pelaksanaan')->comment('Tanggal pelaksanaan lembur');
            $table->time('jam_mulai')->comment('Jam mulai lembur');
            $table->time('jam_selesai')->comment('Jam selesai lembur');
            $table->integer('durasi_menit')->nullable()->comment('Durasi dalam menit (auto-calculated)');

            // Pembayaran
            $table->boolean('is_dibayar')->default(true)->comment('Apakah lembur dibayar?');
            $table->string('metode_bayar', 50)->nullable()->comment('Metode pembayaran: uang, cuti_pengganti, tidak_dibayar');
            $table->decimal('nominal_per_jam', 10, 2)->nullable()->comment('Nominal per jam jika dibayar');

            // Approval Integration (menggunakan hr_riwayat_approval)
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable()->index()
                ->comment('FK ke hr_riwayat_approval untuk tracking approval');

            // Audit Trail
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            // Indexes untuk performa (composite index)
            $table->index(['pengusul_id', 'tgl_pelaksanaan']);

            // Foreign Keys
            $table->foreign('pengusul_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
            // Note: FK to hr_riwayat_approval removed - relationship exists but no FK constraint
            // $table->foreign('latest_riwayatapproval_id')->references('riwayatapproval_id')->on('hr_riwayat_approval')->onDelete('set null');
        });

        // Tabel 2: hr_lembur_pegawai (Pegawai yang ikut lembur)
        Schema::create('hr_lembur_pegawai', function (Blueprint $table) {
            $table->id('lemburpegawai_id');

            // Relasi
            $table->unsignedBigInteger('lembur_id')->index()->comment('FK ke hr_lembur');
            $table->unsignedBigInteger('pegawai_id')->index()->comment('FK ke hr_pegawai');

            // Perhitungan Khusus (opsional per pegawai)
            $table->decimal('override_nominal', 10, 2)->nullable()->comment('Override nominal jika berbeda dari lembur utama');
            $table->text('catatan')->nullable()->comment('Catatan khusus untuk pegawai ini');

            // Audit Trail
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            // Unique constraint - satu pegawai tidak bisa didaftarkan 2x untuk lembur yang sama
            $table->unique(['lembur_id', 'pegawai_id', 'deleted_at'], 'unique_lembur_pegawai');

            // Foreign Keys
            $table->foreign('lembur_id')->references('lembur_id')->on('hr_lembur')->onDelete('cascade');
            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_lembur_pegawai');
        Schema::dropIfExists('hr_lembur');
    }
};
