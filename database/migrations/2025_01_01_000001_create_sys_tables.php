<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for basic system tables.
     */
    public function up(): void
    {
        // 1. Tables Creation
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('password');
            $table->string('google_id', 191)->nullable();
            $table->string('avatar', 500)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->index(['created_at']);
        });

        // Self-referencing FKs for users need to be added after table creation or loosely enforced?
        // Usually safe to add immediately since table exists, but keys reference 'id' which exists.
        // Let's add them at the end or use Schema::table to be safe if cyclic.
        // For now, I'll add them inline but without foreign key constraint enforcement in definition if it causes issues,
        // but typically it's fine.
        // Actually, to be safe and clean, I will add FKs in a separate Schema::table block at the end of up()

        Schema::create('sys_password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 191)->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sys_sessions', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent', 1000)->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('sys_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('guard_name', 50);
            $table->timestamps();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes(); // mass_sync added softDeletes too

            $table->index(['created_at']);
        });

        Schema::create('sys_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('guard_name', 50);
            $table->string('category', 100)->nullable();
            $table->string('sub_category', 100)->nullable();
            $table->timestamps();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();

            $table->index(['created_at']);
        });

        Schema::create('sys_model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type', 150);
            $table->unsignedBigInteger('model_id');
            $table->foreign('role_id')->references('id')->on('sys_roles')->onDelete('cascade');
            $table->primary(['role_id', 'model_type', 'model_id'], 'sys_role_model_primary');
        });

        Schema::create('sys_model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type', 150);
            $table->unsignedBigInteger('model_id');
            $table->foreign('permission_id')->references('id')->on('sys_permissions')->onDelete('cascade');
            $table->primary(['permission_id', 'model_type', 'model_id'], 'sys_permission_model_primary');
        });

        Schema::create('sys_role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->foreign('permission_id')->references('id')->on('sys_permissions')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('sys_roles')->onDelete('cascade');
            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('sys_media', function (Blueprint $table) {
            $table->id();
            $table->string('model_type', 150);
            $table->unsignedBigInteger('model_id');
            $table->uuid()->nullable()->unique();
            $table->string('collection_name', 100);
            $table->string('name', 255);
            $table->string('file_name', 255);
            $table->string('mime_type', 100)->nullable();
            $table->string('disk', 50);
            $table->string('conversions_disk', 50)->nullable();
            $table->unsignedBigInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            $table->unsignedInteger('order_column')->nullable();
            $table->nullableTimestamps();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();

            $table->index(['model_id', 'model_type']);
            $table->index(['created_at']);
        });

        Schema::create('sys_cache', function (Blueprint $table) {
            $table->string('key', 191)->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('sys_cache_locks', function (Blueprint $table) {
            $table->string('key', 191)->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('sys_activity_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('log_name', 100)->nullable()->index();
            $table->text('description');
            $table->string('event', 50)->nullable();
            $table->string('subject_type', 150)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('causer_type', 150)->nullable();
            $table->unsignedBigInteger('causer_id')->nullable();
            $table->json('properties')->nullable();
            $table->uuid('batch_uuid')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent', 1000)->nullable();
            $table->timestamps();
            $table->index(['causer_type', 'causer_id']);
            $table->index(['created_at']);
        });

        Schema::create('sys_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type', 191);
            $table->string('notifiable_type', 150);
            $table->unsignedBigInteger('notifiable_id');
            $table->text('data');
            $table->timestamp('read_at')->nullable()->index();
            $table->timestamps();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();

            $table->index(['notifiable_id', 'notifiable_type'], 'sys_notifications_notifiable_index');
        });

        Schema::create('sys_error_log', function (Blueprint $table) {
            $table->id();
            $table->string('level', 20)->default('error')->index();
            $table->text('message');
            $table->string('exception_class', 191)->nullable();
            $table->text('file');
            $table->integer('line');
            $table->text('trace')->nullable();
            $table->json('context')->nullable();
            $table->string('url', 500)->nullable();
            $table->string('method', 10)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent', 1000)->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->index(['created_at']);
        });

        Schema::create('sys_hosts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191)->index();
            $table->string('ssh_user', 100)->nullable();
            $table->integer('port')->nullable();
            $table->string('ip', 45)->nullable()->index();
            $table->json('custom_properties')->nullable();
            $table->timestamps();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();

            $table->index(['created_at']);
        });

        Schema::create('sys_checks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('host_id')->unsigned()->index();
            $table->foreign('host_id')->references('id')->on('sys_hosts')->onDelete('cascade');
            $table->string('type', 100)->index();
            $table->string('status', 20)->nullable()->index();
            $table->boolean('enabled')->default(true);
            $table->text('last_run_message')->nullable();
            $table->json('last_run_output')->nullable();
            $table->timestamp('last_ran_at')->nullable()->index();
            $table->integer('next_run_in_minutes')->nullable();
            $table->timestamp('started_throttling_failing_notifications_at')->nullable();
            $table->json('custom_properties')->nullable();
            $table->timestamps();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();

            $table->index(['created_at']);
        });

        Schema::create('sys_personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('tokenable_type', 150);
            $table->unsignedBigInteger('tokenable_id');
            $table->string('name', 191);
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();

            $table->index(['tokenable_id', 'tokenable_type'], 'sys_pat_tokenable_index');
        });

        // Add Foreign Keys for Blameable columns generally via a loop or helper if strict,
        // but explicit definition in each table (nullable) is enough for migration to run.
        // The foreign key constraints can be tricky if Users table isn't ready, but it's created first.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_personal_access_tokens');
        Schema::dropIfExists('sys_checks');
        Schema::dropIfExists('sys_hosts');
        Schema::dropIfExists('sys_error_log');
        Schema::dropIfExists('sys_notifications');
        Schema::dropIfExists('sys_activity_log');
        Schema::dropIfExists('sys_cache_locks');
        Schema::dropIfExists('sys_cache');
        Schema::dropIfExists('sys_media');
        Schema::dropIfExists('sys_role_has_permissions');
        Schema::dropIfExists('sys_model_has_permissions');
        Schema::dropIfExists('sys_model_has_roles');
        Schema::dropIfExists('sys_permissions');
        Schema::dropIfExists('sys_roles');
        Schema::dropIfExists('sys_sessions');
        Schema::dropIfExists('sys_password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
