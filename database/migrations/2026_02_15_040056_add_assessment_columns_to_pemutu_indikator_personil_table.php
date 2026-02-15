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
        Schema::table('pemutu_indikator_personil', function (Blueprint $table) {
            $table->text('realization')->nullable()->after('target_value');
            $table->decimal('score', 5, 2)->nullable()->after('realization');
            $table->string('attachment')->nullable()->after('score');
            $table->string('status')->default('draft')->after('attachment'); // draft, submitted, approved, rejected
            $table->text('notes')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemutu_indikator_personil', function (Blueprint $table) {
            $table->dropColumn(['realization', 'score', 'attachment', 'status', 'notes']);
        });
    }
};
