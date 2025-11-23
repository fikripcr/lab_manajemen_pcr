<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for system table indexes.
     */
    public function up(): void
    {
        // Add indexes to sys_roles table
        Schema::table('sys_roles', function (Blueprint $table) {
            $table->index(['created_at']);
        });

        // Add indexes to sys_permissions table
        Schema::table('sys_permissions', function (Blueprint $table) {
            $table->index(['created_at']);
        });

        // Add indexes to sys_model_has_roles table
        Schema::table('sys_model_has_roles', function (Blueprint $table) {
            $table->index(['model_type']);
        });

        // Add indexes to sys_model_has_permissions table
        Schema::table('sys_model_has_permissions', function (Blueprint $table) {
            $table->index(['model_type']);
        });

        // Add indexes to sys_sessions table (already has user_id and last_activity from original migration)
        Schema::table('sys_sessions', function (Blueprint $table) {
            $table->index(['user_id']);
            $table->index(['last_activity']);
        });

        // Add indexes to sys_activity_log table
        Schema::table('sys_activity_log', function (Blueprint $table) {
            $table->index(['log_name']);
            $table->index(['causer_type', 'causer_id']);
            $table->index(['created_at']);
        });

        // Add indexes to sys_notifications table
        Schema::table('sys_notifications', function (Blueprint $table) {
            $table->index([ 'notifiable_id']);
            $table->index(['read_at']);
        });

        // Add indexes to sys_error_log table
        Schema::table('sys_error_log', function (Blueprint $table) {
            $table->index(['level']);
            $table->index(['created_at']);
            $table->index(['user_id']);
        });

        // Add indexes to sys_hosts table
        Schema::table('sys_hosts', function (Blueprint $table) {
            $table->index(['name']);
            $table->index(['ip']);
            $table->index(['created_at']);
        });

        // Add indexes to sys_checks table
        Schema::table('sys_checks', function (Blueprint $table) {
            $table->index(['host_id']);
            $table->index(['type']);
            $table->index(['status']);
            $table->index(['last_ran_at']);
            $table->index(['created_at']);
        });

        // Add indexes to sys_media table
        Schema::table('sys_media', function (Blueprint $table) {
            $table->index(['model_type']);
            $table->index(['collection_name']);
            $table->index(['created_at']);
        });

        // Add indexes to users table for system operations
        Schema::table('users', function (Blueprint $table) {
            $table->index(['created_at']);
            $table->index(['email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from sys_roles table
        Schema::table('sys_roles', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        // Remove indexes from sys_permissions table
        Schema::table('sys_permissions', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        // Remove indexes from sys_model_has_roles table
        Schema::table('sys_model_has_roles', function (Blueprint $table) {
            $table->dropIndex(['model_type']);
        });

        // Remove indexes from sys_model_has_permissions table
        Schema::table('sys_model_has_permissions', function (Blueprint $table) {
            $table->dropIndex(['model_type']);
        });

        // Remove indexes from sys_sessions table
        Schema::table('sys_sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['last_activity']);
        });

        // Remove indexes from sys_activity_log table
        Schema::table('sys_activity_log', function (Blueprint $table) {
            $table->dropIndex(['log_name']);
            $table->dropIndex(['subject_type', 'subject_id']);
            $table->dropIndex(['causer_type', 'causer_id']);
            $table->dropIndex(['created_at']);
        });

        // Remove indexes from sys_notifications table
        Schema::table('sys_notifications', function (Blueprint $table) {
            $table->dropIndex(['notifiable_type', 'notifiable_id']);
            $table->dropIndex(['read_at']);
        });

        // Remove indexes from sys_error_log table
        Schema::table('sys_error_log', function (Blueprint $table) {
            $table->dropIndex(['level']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['exception_class']);
        });

        // Remove indexes from sys_hosts table
        Schema::table('sys_hosts', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['ip']);
            $table->dropIndex(['created_at']);
        });

        // Remove indexes from sys_checks table
        Schema::table('sys_checks', function (Blueprint $table) {
            $table->dropIndex(['host_id']);
            $table->dropIndex(['type']);
            $table->dropIndex(['status']);
            $table->dropIndex(['enabled']);
            $table->dropIndex(['last_ran_at']);
            $table->dropIndex(['created_at']);
        });

        // Remove indexes from sys_media table
        Schema::table('sys_media', function (Blueprint $table) {
            $table->dropIndex(['model_type', 'model_id']);
            $table->dropIndex(['collection_name']);
            $table->dropIndex(['created_at']);
        });

        // Remove indexes from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['email']);
        });
    }
};
