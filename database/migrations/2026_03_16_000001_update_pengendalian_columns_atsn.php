<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemutu_indikator_orgunit', function (Blueprint $blueprint) {
            // Add superior review columns
            $blueprint->string('pengend_status_atsn', 20)->nullable()->after('pengend_status');
            $blueprint->text('pengend_analisis_atsn')->nullable()->after('pengend_analisis');
            $blueprint->string('pengend_important_matrix_atsn', 20)->nullable()->after('pengend_important_matrix');
            $blueprint->string('pengend_urgent_matrix_atsn', 20)->nullable()->after('pengend_urgent_matrix');

            // Drop redundant columns
            $blueprint->dropColumn(['pengend_target', 'pengend_penyesuaian']);
        });
    }

    public function down(): void
    {
        Schema::table('pemutu_indikator_orgunit', function (Blueprint $blueprint) {
            $blueprint->dropColumn([
                'pengend_status_atsn', 
                'pengend_analisis_atsn', 
                'pengend_important_matrix_atsn', 
                'pengend_urgent_matrix_atsn'
            ]);
            $blueprint->text('pengend_target')->nullable();
            $blueprint->text('pengend_penyesuaian')->nullable();
        });
    }
};
