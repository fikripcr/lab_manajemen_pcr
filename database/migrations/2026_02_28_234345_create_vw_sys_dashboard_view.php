<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sql = "CREATE OR REPLACE VIEW vw_sys_dashboard AS
            SELECT
                -- User Statistics
                (SELECT COUNT(*) FROM users WHERE deleted_at IS NULL) AS total_users,
                (SELECT COUNT(*) FROM users WHERE deleted_at IS NULL AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)) AS new_users_7days,

                -- Role Statistics
                (SELECT COUNT(*) FROM sys_roles) AS total_roles,

                -- Permission Statistics
                (SELECT COUNT(*) FROM sys_permissions) AS total_permissions,

                -- Activity Statistics
                (SELECT COUNT(*) FROM sys_activity_log WHERE created_at >= CURDATE()) AS today_activities,
                (SELECT COUNT(*) FROM sys_activity_log WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)) AS activities_7days,

                -- System Statistics
                (SELECT COUNT(*) FROM sys_activity_log) AS total_activities

            FROM DUAL";

        \Illuminate\Support\Facades\DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("DROP VIEW IF EXISTS vw_sys_dashboard");
    }
};
