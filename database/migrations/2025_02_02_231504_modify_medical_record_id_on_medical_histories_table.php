<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMedicalRecordIdOnMedicalHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('medical_histories', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign(['medical_record_id']);

            // Change the column type to string. Adjust the length as needed.
            $table->string('medical_record_id')->change();

            // Optionally, re-add the foreign key constraint so that the string field references the id_number in the medical_records table.
            // Ensure that the referenced column is a string as well.
            $table->foreign('medical_record_id')
                  ->references('id_number')->on('medical_records')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('medical_histories', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['medical_record_id']);

            // Change the column back to unsignedBigInteger
            $table->unsignedBigInteger('medical_record_id')->change();

            // Re-add the original foreign key constraint (if needed)
            $table->foreign('medical_record_id')
                  ->references('id')->on('medical_records')
                  ->onDelete('cascade');
        });
    }
}

