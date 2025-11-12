<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLabIdToLogPenggunaanLabsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('log_penggunaan_labs', function (Blueprint $table) {
            $table->foreignId('lab_id')->constrained('labs', 'lab_id')->after('kegiatan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('log_penggunaan_labs', function (Blueprint $table) {
            $table->dropForeign(['lab_id']);
            $table->dropColumn('lab_id');
        });
    }
}