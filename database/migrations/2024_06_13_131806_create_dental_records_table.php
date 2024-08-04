<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDentalRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('dental_records', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->date('date_of_birth');
            $table->text('treatment');
            $table->string('dentist');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dental_records');
    }
}