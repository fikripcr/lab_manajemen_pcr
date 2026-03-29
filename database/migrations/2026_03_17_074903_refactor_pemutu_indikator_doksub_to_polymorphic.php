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
        Schema::table('pemutu_indikator_doksub', function (Blueprint $table) {
            // Add source_type column
            $table->string('source_type')->after('indikdoksub_id')->nullable();

            // Rename indikator_id to source_id
            $table->renameColumn('indikator_id', 'source_id');
        });

        // Update existing records to use Indikator model as source
        DB::table('pemutu_indikator_doksub')->update([
            'source_type' => 'App\Models\Pemutu\Indikator',
        ]);

        Schema::table('pemutu_indikator_doksub', function (Blueprint $table) {
            $table->string('source_type')->nullable(false)->change();

            // Add index for polymorphic relationship
            $table->index(['source_type', 'source_id'], 'pemutu_indikator_doksub_source_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemutu_indikator_doksub', function (Blueprint $table) {
            $table->dropIndex('pemutu_indikator_doksub_source_index');
            $table->renameColumn('source_id', 'indikator_id');
            $table->dropColumn('source_type');
        });
    }
};
