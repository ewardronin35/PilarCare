<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birthdate');
            $table->integer('age');
            $table->string('address');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('medical_illness');
            $table->string('allergies');
            $table->string('pediatrician');
            $table->json('medicines'); // Store as JSON
            $table->json('physical_examination'); // Store as JSON
            $table->string('consent_signature');
            $table->date('consent_date');
            $table->string('contact_no');
            $table->string('picture_path'); // Store the picture path
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
}
