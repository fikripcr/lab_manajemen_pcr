<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Create consolidated Sys tables for GLOBAL system components:
     * - sys_approvals: Polymorphic approval workflow
     * - sys_periodes: Period/milestone management
     * 
     * Used across all modules: Pemutu, HR, PMB, Lab, Event, Eoffice, etc.
     */
    public function up(): void
    {
        // ==========================================
        // SYS APPROVALS - Global Approval System
        // ==========================================
        Schema::create('sys_approvals', function (Blueprint $table) {
            $table->id('sys_approval_id');
            
            // Polymorphic relationship
            $table->string('model'); // Class name (e.g., 'App\Models\Pemutu\Dokumen')
            $table->unsignedBigInteger('model_id');
            
            // Approver info
            $table->unsignedBigInteger('pegawai_id')->nullable()->index();
            $table->string('pejabat'); // Name of approver
            $table->string('jabatan')->nullable(); // Position
            
            // Approval status
            $table->enum('status', ['Draft', 'Pending', 'Approved', 'Rejected'])->default('Pending')->index();
            $table->text('catatan')->nullable(); // Notes/comments
            
            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            
            // Timestamps & Soft Deletes
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index(['model', 'model_id']); // Polymorphic index
        });

        // ==========================================
        // SYS PERIODES - Global Period Management
        // ==========================================
        Schema::create('sys_periodes', function (Blueprint $table) {
            $table->id('sys_periode_id');
            
            // Basic info
            $table->string('name'); // e.g., "SPMI 2026 - Akademik", "KPI Q1 2026"
            $table->string('type')->index(); // spmi, kpi, pmb, layanan, event, etc
            $table->integer('year')->nullable()->index();
            
            // Date range
            $table->date('start_date')->nullable()->index();
            $table->date('end_date')->nullable()->index();
            
            // Status
            $table->boolean('is_active')->default(false)->index();
            
            // Flexible metadata (JSON)
            $table->json('metadata')->nullable();
            
            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            
            // Timestamps & Soft Deletes
            $table->timestamps();
            $table->softDeletes();
            
            // Composite indexes
            $table->index(['type', 'year']);
            $table->index(['start_date', 'end_date']);
        });

        // ==========================================
        // SYS PERIODEABLES - Polymorphic Period Pivot
        // ==========================================
        Schema::create('sys_periodeables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sys_periode_id');
            $table->morphs('periodable'); // periodable_type, periodable_id
            $table->timestamps();
            
            $table->foreign('sys_periode_id')
                ->references('sys_periode_id')
                ->on('sys_periodes')
                ->onDelete('cascade');
            
            // Use shorter name for unique constraint
            $table->unique(['sys_periode_id', 'periodable_type', 'periodable_id'], 'periodeable_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_periodeables');
        Schema::dropIfExists('sys_periodes');
        Schema::dropIfExists('sys_approvals');
    }
};
