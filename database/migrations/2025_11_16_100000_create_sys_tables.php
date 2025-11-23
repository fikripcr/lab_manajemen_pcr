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
        // Users table (not prefixed with sys_)
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->timestamp('expired_at')->nullable();
                $table->string('password');
                $table->string('google_id')->nullable();
                $table->string('avatar')->nullable();
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes(); // Added for soft deletes
            });
        }

        // Password resets table
        if (! Schema::hasTable('sys_password_reset_tokens')) {
            Schema::create('sys_password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        // Sessions table
        if (! Schema::hasTable('sys_sessions')) {
            Schema::create('sys_sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity');
            });
        }

        // Sys Roles table (from spatie/laravel-permission) - using sys_ prefix
        if (! Schema::hasTable('sys_roles')) {
            Schema::create('sys_roles', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('guard_name');
                $table->timestamps();
            });
        }

        // Sys Permissions table (from spatie/laravel-permission) - using sys_ prefix
        if (! Schema::hasTable('sys_permissions')) {
            Schema::create('sys_permissions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('guard_name');
                $table->string('category')->nullable();
                $table->string('sub_category')->nullable();
                $table->timestamps();
            });
        }

        // Sys Model has roles table (from spatie/laravel-permission) - using sys_ prefix
        if (! Schema::hasTable('sys_model_has_roles')) {
            Schema::create('sys_model_has_roles', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->string('model_type');
                $table->unsignedBigInteger('model_id'); // Changed from uuid to integer

                $table->foreign('role_id')->references('id')->on('sys_roles')->onDelete('cascade');
                $table->primary(['role_id', 'model_type', 'model_id']);
            });
        }

        // Sys Model has permissions table (from spatie/laravel-permission) - using sys_ prefix
        if (! Schema::hasTable('sys_model_has_permissions')) {
            Schema::create('sys_model_has_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('permission_id');
                $table->string('model_type');
                $table->unsignedBigInteger('model_id'); // Changed from uuid to integer

                $table->foreign('permission_id')->references('id')->on('sys_permissions')->onDelete('cascade');
                $table->primary(['permission_id', 'model_type', 'model_id']);
            });
        }

        // Sys Role has permissions table (from spatie/laravel-permission) - using sys_ prefix
        if (! Schema::hasTable('sys_role_has_permissions')) {
            Schema::create('sys_role_has_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('permission_id');
                $table->unsignedBigInteger('role_id');

                $table->foreign('permission_id')->references('id')->on('sys_permissions')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('sys_roles')->onDelete('cascade');

                $table->primary(['permission_id', 'role_id']);
            });
        }

        // Media table for Laravel Media Library with correct structure for Spatie
        if (! Schema::hasTable('sys_media')) {
            Schema::create('sys_media', function (Blueprint $table) {
                $table->id();

                $table->morphs('model');
                $table->uuid()->nullable()->unique();
                $table->string('collection_name');
                $table->string('name');
                $table->string('file_name');
                $table->string('mime_type')->nullable();
                $table->string('disk');
                $table->string('conversions_disk')->nullable();
                $table->unsignedBigInteger('size');
                $table->json('manipulations');
                $table->json('custom_properties');
                $table->json('generated_conversions');
                $table->json('responsive_images');
                $table->unsignedInteger('order_column')->nullable();

                $table->nullableTimestamps();
            });

        }

        Schema::create('sys_cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('sys_cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        // Activity log table with sys_ prefix
        if (! Schema::hasTable('sys_activity_log')) {
            Schema::create('sys_activity_log', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('log_name')->nullable();
                $table->string('description');
                $table->string('event')->nullable();
                $table->string('subject_type')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->string('causer_type')->nullable();
                $table->unsignedBigInteger('causer_id')->nullable();
                $table->json('properties')->nullable();
                $table->uuid('batch_uuid')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();
            });
        }

        // Sys Notifications table with sys_ prefix
        if (! Schema::hasTable('sys_notifications')) {
            Schema::create('sys_notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // Create sys_error_log table
        if (! Schema::hasTable('sys_error_log')) {
            Schema::create('sys_error_log', function (Blueprint $table) {
                $table->id();
                $table->string('level')->default('error'); // error, warning, info, etc.
                $table->text('message');
                $table->string('exception_class')->nullable();
                $table->text('file');
                $table->integer('line');
                $table->text('trace')->nullable();   // Full stack trace
                $table->json('context')->nullable(); // Additional context data
                $table->string('url')->nullable();
                $table->string('method')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->unsignedBigInteger('user_id')->nullable(); // User that encountered the error
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(! Schema::hasTable('sys_hosts')) {
            Schema::create('sys_hosts', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('ssh_user')->nullable();
                $table->integer('port')->nullable();
                $table->string('ip')->nullable();
                $table->json('custom_properties')->nullable();
                $table->timestamps();
            });
        }

        if(! Schema::hasTable('sys_checks')) {
            Schema::create('sys_checks', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('host_id')->unsigned();
                $table->foreign('host_id')->references('id')->on('sys_hosts')->onDelete('cascade');
                $table->string('type');
                $table->string('status')->nullable();
                $table->boolean('enabled')->default(true);
                $table->text('last_run_message')->nullable();
                $table->json('last_run_output')->nullable();
                $table->timestamp('last_ran_at')->nullable();
                $table->integer('next_run_in_minutes')->nullable();
                $table->timestamp('started_throttling_failing_notifications_at')->nullable();
                $table->json('custom_properties')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_role_has_permissions');
        Schema::dropIfExists('sys_model_has_permissions');
        Schema::dropIfExists('sys_model_has_roles');
        Schema::dropIfExists('sys_permissions');
        Schema::dropIfExists('sys_roles');
        Schema::dropIfExists('sys_media');
        Schema::dropIfExists('sys_sessions');
        Schema::dropIfExists('sys_password_reset_tokens');
        Schema::dropIfExists('sys_activity_log');
        Schema::dropIfExists('sys_error_log');
        Schema::dropIfExists('sys_notifications');
        Schema::dropIfExists('sys_cache');
        Schema::dropIfExists('sys_cache_locks');
        Schema::dropIfExists('sys_hosts');
        Schema::dropIfExists('sys_checks');
        Schema::dropIfExists('users');
    }
};
