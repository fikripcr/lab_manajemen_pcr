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
        // 1. Create Events Table
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('judul_event', 200);
            $table->string('jenis_event', 100)->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('lokasi', 200)->nullable();
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('pic_user_id')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Create Event Tamu Table
        Schema::create('event_tamus', function (Blueprint $table) {
            $table->id('eventtamu_id');
            $table->unsignedBigInteger('event_id');
            $table->string('nama_tamu', 150);
            $table->string('instansi', 150)->nullable();
            $table->string('jabatan', 150)->nullable();
            $table->string('kontak', 100)->nullable();
            $table->string('tujuan', 200)->nullable();
            $table->datetime('waktu_datang')->nullable();
            $table->string('foto_url', 255)->nullable();
            $table->string('ttd_url', 255)->nullable();
            $table->text('keterangan')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
        });

        // 3. Create Event Team Table (Polymorphic Committee)
        Schema::create('event_teams', function (Blueprint $table) {
            $table->id('eventteam_id');
            $table->unsignedBigInteger('event_id');
            $table->nullableMorphs('memberable'); // can link to User, Mahasiswa, Personil
            $table->string('name')->nullable();   // fallback/cache if not linked to a model
            $table->string('role')->nullable();
            $table->boolean('is_pic')->default(false);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
        });

        // 4. Rename Rapat Tables
        if (Schema::hasTable('pemutu_rapat')) {
            Schema::rename('pemutu_rapat', 'event_rapat');
        }
        if (Schema::hasTable('pemutu_rapat_agenda')) {
            Schema::rename('pemutu_rapat_agenda', 'event_rapat_agenda');
        }
        if (Schema::hasTable('pemutu_rapat_entitas')) {
            Schema::rename('pemutu_rapat_entitas', 'event_rapat_entitas');
        }
        if (Schema::hasTable('pemutu_rapat_peserta')) {
            Schema::rename('pemutu_rapat_peserta', 'event_rapat_peserta');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_teams');
        Schema::dropIfExists('event_tamus');
        Schema::dropIfExists('events');

        // Rename back
        if (Schema::hasTable('event_rapat')) {
            Schema::rename('event_rapat', 'pemutu_rapat');
        }
        if (Schema::hasTable('event_rapat_agenda')) {
            Schema::rename('event_rapat_agenda', 'pemutu_rapat_agenda');
        }
        if (Schema::hasTable('event_rapat_entitas')) {
            Schema::rename('event_rapat_entitas', 'pemutu_rapat_entitas');
        }
        if (Schema::hasTable('event_rapat_peserta')) {
            Schema::rename('event_rapat_peserta', 'pemutu_rapat_peserta');
        }
    }
};
