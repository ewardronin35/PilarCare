<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformationTable extends Migration
{
    public function up()
    {
        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->string('id_number')->unique();
            $table->string('parent_name_father')->nullable();
            $table->string('parent_name_mother')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('personal_contact_number')->nullable();
            $table->string('medical_history');
            $table->string('allergies');
            $table->string('medicines');
            $table->string('surgical_history');
            $table->string('chronic_conditions');
            $table->string('address');
            $table->date('birthdate');
            $table->string('profile_picture');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('informations');
    }
}
