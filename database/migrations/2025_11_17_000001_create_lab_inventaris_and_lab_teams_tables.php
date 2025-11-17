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
        // Create lab_inventaris table
        Schema::create('lab_inventaris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaris_id')->constrained('inventaris', 'inventaris_id')->onDelete('cascade');
            $table->foreignId('lab_id')->constrained('labs', 'lab_id')->onDelete('cascade');
            $table->string('kode_inventaris')->unique(); // Format: LAB-INV-XXXX
            $table->string('no_series')->nullable(); // Nomor seri atau kode tambahan
            $table->timestamp('tanggal_penempatan')->nullable();
            $table->timestamp('tanggal_penghapusan')->nullable();
            $table->string('status')->default('active'); // active, moved, inactive
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->index(['inventaris_id', 'lab_id']); // Index for faster joins
            $table->index('kode_inventaris');
        });
        
        // Create lab_teams table
        Schema::create('lab_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs', 'lab_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('jabatan')->nullable(); // PIC, Teknisi, dll
            $table->boolean('is_active')->default(true);
            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
            
            $table->unique(['lab_id', 'user_id']); // One user per lab
        });
        
        // Modify inventaris table - remove lab_id
        Schema::table('inventaris', function (Blueprint $table) {
            $table->dropForeign(['lab_id']);
            $table->dropColumn('lab_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore lab_id to inventaris table
        Schema::table('inventaris', function (Blueprint $table) {
            $table->foreignId('lab_id')->constrained('labs', 'lab_id');
        });
        
        // Drop lab_teams table
        Schema::dropIfExists('lab_teams');
        
        // Drop lab_inventaris table
        Schema::dropIfExists('lab_inventaris');
    }
};