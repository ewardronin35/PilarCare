<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSvgPathInToothHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tooth_histories', function (Blueprint $table) {
            // Change 'svg_path' from VARCHAR to TEXT
            $table->text('svg_path')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tooth_histories', function (Blueprint $table) {
            // Revert 'svg_path' back to VARCHAR(255)
            $table->string('svg_path', 255)->change();
        });
    }
}
