<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDentistNameAndFindingsToDentalExaminationsTable extends Migration
{
    public function up()
    {
        Schema::table('dental_examinations', function (Blueprint $table) {
            $table->string('dentist_name')->nullable()->after('age');
            $table->text('findings')->nullable()->after('dentist_name');
        });
    }

    public function down()
    {
        Schema::table('dental_examinations', function (Blueprint $table) {
            $table->dropColumn(['dentist_name', 'findings']);
        });
    }
}
