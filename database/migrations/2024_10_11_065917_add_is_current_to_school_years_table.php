<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCurrentToSchoolYearsTable extends Migration
{
    public function up()
    {
        Schema::table('school_years', function (Blueprint $table) {
            $table->boolean('is_current')->default(false)->after('year');
        });
    }

    public function down()
    {
        Schema::table('school_years', function (Blueprint $table) {
            $table->dropColumn('is_current');
        });
    }
}
