<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. TABEL PROJECTS
        Schema::create('pr_projects', function (Blueprint $table) {
            $table->id('project_id'); // Custom Primary Key

            // $table->foreignId('workspace_id')->constrained('workspaces', 'workspace_id')->cascadeOnDelete(); // Aktifkan jika pakai sistem SaaS multitenant

            $table->string('project_name', 200);
            $table->text('project_desc')->nullable();

            // Mode proyek
            $table->boolean('is_agile')->default(false);

            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['planning', 'active', 'completed', 'on_hold'])->default('planning');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. TABEL PROJECT MEMBERS (Tim & Visibilitas)
        Schema::create('pr_project_members', function (Blueprint $table) {
            $table->id('project_member_id');
            $table->foreignId('project_id')->constrained('pr_projects', 'project_id')->cascadeOnDelete();

            // Refer to system users table (uses default 'id')
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->enum('role', ['leader', 'member'])->default('member');
            $table->string('alias_position', 100)->nullable(); // Misal: "Scrum Master", "Frontend Dev"
            $table->decimal('rate_per_hour', 15, 2)->nullable(); // Opsional: jika ingin hitung cost berdasarkan jam kerja

            // Cegah duplikasi member di project yang sama
            $table->unique(['project_id', 'user_id']);

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['project_id'], 'pr_project_members_project_idx');
            $table->index(['user_id'], 'pr_project_members_user_idx');
        });

        // 3. TABEL PROJECT PHASES (Dimensi Ruang/Scope)
        Schema::create('pr_project_phases', function (Blueprint $table) {
            $table->id('project_phase_id');
            $table->foreignId('project_id')->constrained('pr_projects', 'project_id')->cascadeOnDelete();

            $table->string('phase_name', 200);
            $table->text('phase_desc')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['project_id'], 'pr_project_phases_project_idx');
        });

        // 4. TABEL PROJECT SPRINTS (Dimensi Waktu/Agile)
        Schema::create('pr_project_sprints', function (Blueprint $table) {
            $table->id('project_sprint_id');
            $table->foreignId('project_id')->constrained('pr_projects', 'project_id')->cascadeOnDelete();

            $table->string('sprint_name', 200);
            $table->text('sprint_desc')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['pending', 'active', 'completed'])->default('pending');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['project_id'], 'pr_project_sprints_project_idx');
        });

        // 5. TABEL PROJECT TASKS (Pusat Aktivitas - WBS)
        Schema::create('pr_project_tasks', function (Blueprint $table) {
            $table->id('project_task_id');
            $table->foreignId('project_id')->constrained('pr_projects', 'project_id')->cascadeOnDelete();

            // Bisa masuk ke Phase tertentu, Sprint tertentu, atau Keduanya (Agile Matrix)
            $table->foreignId('project_phase_id')->nullable()->constrained('pr_project_phases', 'project_phase_id')->nullOnDelete();
            $table->foreignId('project_sprint_id')->nullable()->constrained('pr_project_sprints', 'project_sprint_id')->nullOnDelete();

            // Di-assign ke member yang mana? (Merujuk ke users.id)
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();

            // Parent task (subtasks)
            $table->foreignId('parent_id')->nullable()->constrained('pr_project_tasks', 'project_task_id')->nullOnDelete();

            $table->string('task_title', 200);
            $table->text('task_desc')->nullable();
            $table->enum('status', ['todo', 'in_progress', 'review', 'done'])->default('todo');

            $table->unsignedTinyInteger('weight')->default(1); // Bobot task
            $table->unsignedInteger('hours_worked')->default(0); // Tracking waktu

            // Ordering and priority
            $table->unsignedInteger('seq')->default(0)->comment('Ordering sequence within list');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->date('due_date')->nullable();

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for common queries
            $table->index(['project_id'], 'pr_project_tasks_project_idx');
            $table->index(['assignee_id'], 'pr_project_tasks_assignee_idx');
            $table->index(['project_phase_id'], 'pr_project_tasks_phase_idx');
            $table->index(['project_sprint_id'], 'pr_project_tasks_sprint_idx');
            $table->index(['parent_id'], 'pr_project_tasks_parent_idx');
            $table->index(['seq'], 'pr_project_tasks_seq_idx');
            $table->index(['priority'], 'pr_project_tasks_priority_idx');
            $table->index(['due_date'], 'pr_project_tasks_due_idx');
        });

        // 6. TABEL PROJECT COSTS (Bottom-Up Costing)
        Schema::create('pr_project_costs', function (Blueprint $table) {
            $table->id('project_cost_id');
            $table->foreignId('project_id')->constrained('pr_projects', 'project_id')->cascadeOnDelete();

            // Jika biaya nempel di task spesifik (Bottom-Up). Jika Null, berarti biaya general Project.
            $table->foreignId('project_task_id')->nullable()->constrained('pr_project_tasks', 'project_task_id')->nullOnDelete();

            // Siapa yang menginput/mengajukan biaya ini?
            $table->foreignId('author_id')->constrained('users');

            $table->text('cost_desc');
            $table->enum('cost_type', ['in_cash', 'in_kind']); // Uang tunai atau barang/jasa bernilai
            $table->decimal('amount', 15, 2);
            $table->date('cost_date')->nullable();
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending'); // default pending

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['project_id'], 'pr_project_costs_project_idx');
            $table->index(['project_task_id'], 'pr_project_costs_task_idx');
            $table->index(['approval_status'], 'pr_project_costs_approval_idx');
        });
    }

    public function down(): void
    {
        // Drop dengan urutan terbalik dari atas untuk mencegah error constraint
        Schema::dropIfExists('pr_project_costs');
        Schema::dropIfExists('pr_project_tasks');
        Schema::dropIfExists('pr_project_sprints');
        Schema::dropIfExists('pr_project_phases');
        Schema::dropIfExists('pr_project_members');
        Schema::dropIfExists('pr_projects');
    }
};
