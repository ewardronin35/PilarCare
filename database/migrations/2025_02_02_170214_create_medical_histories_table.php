<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();
            // Link to the current medical record (for example, by medical_record_id)
            $table->unsignedBigInteger('medical_record_id');
            // Copy the fields you need from the MedicalRecord table:
            $table->string('name');
            $table->date('birthdate')->nullable();
            $table->integer('age')->nullable();
            $table->string('address')->nullable();
            $table->string('personal_contact_number')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('past_illness')->nullable();
            $table->string('chronic_conditions')->nullable();
            $table->string('surgical_history')->nullable();
            $table->string('family_medical_history')->nullable();
            $table->string('allergies')->nullable();
            $table->string('medical_condition')->nullable();
            $table->json('medicines')->nullable();
            $table->json('health_documents')->nullable();
            $table->string('profile_picture')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamp('record_date')->nullable();
            $table->timestamps();
            
            // Add foreign key constraint if desired:
            $table->foreign('medical_record_id')->references('id')->on('medical_records')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_histories');
    }
}
