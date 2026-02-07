<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('indikator_orgunit', function (Blueprint $table) {
            if (! Schema::hasColumn('indikator_orgunit', 'target')) {
                $table->string('target', 255)->nullable()->after('org_unit_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('indikator_orgunit', function (Blueprint $table) {
            if (Schema::hasColumn('indikator_orgunit', 'target')) {
                $table->dropColumn('target');
            }
        });
    }
};
