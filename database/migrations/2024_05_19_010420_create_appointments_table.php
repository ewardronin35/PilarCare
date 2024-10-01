<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('id_number');
            $table->string('patient_name');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('role');
            $table->string('doctor_id');
            $table->string('appointment_type'); // Add this line if it doesn't exist
            $table->string('status')->default('pending'); // Add status column with default value 'pending'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
