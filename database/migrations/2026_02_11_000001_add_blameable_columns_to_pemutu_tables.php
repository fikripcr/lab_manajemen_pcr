<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add created_by and updated_by columns to pemutu tables that don't have them
        Schema::table('pemutu_label_types', function (Blueprint $table) {
            if (!Schema::hasColumn('pemutu_label_types', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
                $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('pemutu_label_types', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            }
        });

        Schema::table('pemutu_label', function (Blueprint $table) {
            if (!Schema::hasColumn('pemutu_label', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
                $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('pemutu_label', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            }
        });

        Schema::table('pemutu_dok_sub', function (Blueprint $table) {
            if (!Schema::hasColumn('pemutu_dok_sub', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
                $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('pemutu_dok_sub', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            }
        });

        Schema::table('pemutu_indikator', function (Blueprint $table) {
            if (!Schema::hasColumn('pemutu_indikator', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
                $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('pemutu_indikator', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            }
        });

        Schema::table('pemutu_personil', function (Blueprint $table) {
            if (!Schema::hasColumn('pemutu_personil', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
                $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('pemutu_personil', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        // Remove columns if needed
        Schema::table('pemutu_label_types', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('pemutu_label', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('pemutu_dok_sub', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('pemutu_indikator', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('pemutu_personil', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
};
