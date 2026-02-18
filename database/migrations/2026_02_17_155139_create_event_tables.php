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

        // 4. Create Rapat Tables (New Schema for Event Module)

        // 4.1 Main Table: event_rapat
        Schema::create('event_rapat', function (Blueprint $table) {
            $table->id('rapat_id');
            $table->string('jenis_rapat', 20);
            $table->string('judul_kegiatan', 100);
            $table->date('tgl_rapat');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('tempat_rapat', 200);
            $table->foreignId('ketua_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('notulen_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('author_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        // 4.2 Child Table: event_rapat_agenda
        Schema::create('event_rapat_agenda', function (Blueprint $table) {
            $table->id('rapatagenda_id');
            $table->foreignId('rapat_id')->constrained('event_rapat', 'rapat_id')->onDelete('cascade');
            $table->string('judul_agenda', 250);
            $table->text('isi');
            $table->integer('seq');
            $table->timestamps();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        // 4.3 Child Table: event_rapat_peserta
        Schema::create('event_rapat_peserta', function (Blueprint $table) {
            $table->id('rapatpeserta_id');
            $table->foreignId('rapat_id')->constrained('event_rapat', 'rapat_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('jabatan', 100);
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa'])->nullable();
            $table->timestamp('waktu_hadir')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        // 4.4 Child Table: event_rapat_entitas
        Schema::create('event_rapat_entitas', function (Blueprint $table) {
            $table->id('rapatentitas_id');
            $table->foreignId('rapat_id')->constrained('event_rapat', 'rapat_id')->onDelete('cascade');
            $table->string('model', 50);
            $table->unsignedBigInteger('model_id');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
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
        if (Schema::hasTable('event_rapat_peserta')) {
            Schema::dropIfExists('event_rapat_peserta');
        }
        if (Schema::hasTable('event_rapat_entitas')) {
            Schema::dropIfExists('event_rapat_entitas');
        }
        if (Schema::hasTable('event_rapat_agenda')) {
            Schema::dropIfExists('event_rapat_agenda');
        }
        if (Schema::hasTable('event_rapat')) {
            // Drop foreign key first if it exists, though dropIfExists usually handles it if it's the table itself.
            // But if the FK is named 'pemutu_rapat_author_user_id_foreign' it might be lingering.
            Schema::table('event_rapat', function (Blueprint $table) {
                $table->dropForeign(['author_user_id']); // or use the exact name if known
                                                         // Since exact name was in error: pemutu_rapat_author_user_id_foreign
                                                         // Let's try to drop it by name to be safe if we are keeping the table.
                                                         // But we want to DROP the table.
            });
            Schema::dropIfExists('event_rapat');
        }
    }
};
