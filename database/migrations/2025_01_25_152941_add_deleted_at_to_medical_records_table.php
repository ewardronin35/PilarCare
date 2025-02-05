<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToMedicalRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->softDeletes(); // Adds a nullable 'deleted_at' TIMESTAMP column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Drops the 'deleted_at' column
        });
    }
}
