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
        $tables = [
            'pemutu_rapat',
            'pemutu_rapat_agenda',
            'pemutu_rapat_peserta',
            'pemutu_rapat_entitas',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                if (! Schema::hasColumn($table->getTable(), 'created_by')) {
                    $table->unsignedBigInteger('created_by')->nullable()->after('updated_at');
                }
                if (! Schema::hasColumn($table->getTable(), 'updated_by')) {
                    $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                }
                if (! Schema::hasColumn($table->getTable(), 'deleted_by')) {
                    $table->unsignedBigInteger('deleted_by')->nullable()->after('updated_by');
                }

                // Ensure foreign keys if possible for the main table
                if ($table->getTable() === 'pemutu_rapat') {
                    $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
                    $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'pemutu_rapat',
            'pemutu_rapat_agenda',
            'pemutu_rapat_peserta',
            'pemutu_rapat_entitas',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                // Drop foreign keys first if they exist
                if ($table->getTable() === 'pemutu_rapat') {
                    $table->dropForeign(['created_by']);
                    $table->dropForeign(['updated_by']);
                }

                $table->dropColumn(['created_by', 'updated_by', 'deleted_by']);
            });
        }
    }
};
