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
            $table->string('id_number'); // Match this exactly with the `id_number` field in `users`
            $table->foreign('id_number')->references('id_number')->on('users')->onDelete('cascade'); // Foreign key to users table
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
            $table->string('medical_condition');
            $table->json('medicines'); // Store as JSON
            $table->string('profile_picture');
            $table->string('health_documents')->nullable();
            $table->boolean('is_approved')->default(false); // Approval status, default to false
            $table->boolean('is_current')->default(true); // Indicates if the record is the current one
            $table->date('record_date')->default(DB::raw('CURRENT_DATE')); // Default to the current date
            $table->timestamps(); // This will include 'created_at' and 'updated_at' fields
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_records');
    }


    public function medicineIntakes()
    {
        return $this->hasMany(MedicineIntake::class, 'id_number', 'id_number');
    }
}
