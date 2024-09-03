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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key to users table

            $table->string('name');
            $table->date('birthdate');
            $table->integer('age');
            $table->string('address');
            $table->string('personal_contact_number');
            $table->string('emergency_contact_number');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('past_illness');
            $table->string('chronic_conditions');
            $table->string('surgical_history');
            $table->string('family_medical_history');
            $table->string('allergies');
            $table->json('medicines'); // Store as JSON
            $table->string('profile_picture')->nullable(); // Store the picture path
            $table->timestamps(); // This will include 'created_at' and 'updated_at' fields
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
}
