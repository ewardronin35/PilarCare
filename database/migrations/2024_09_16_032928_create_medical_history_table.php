<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_history', function (Blueprint $table) {
            $table->id();
            $table->string('id_number'); // Foreign key to link with medical_records
            $table->string('past_illness');
            $table->string('chronic_conditions');
            $table->string('surgical_history');
            $table->string('family_medical_history');
            $table->string('allergies');
            $table->string('medical_condition');
            $table->string('approval_status')->default('pending'); // Tracks if the record is approved
            $table->string('health_documents')->nullable(); // Store the picture path
            $table->date('record_date')->default(now()); // Date of the record creation
            $table->timestamps();
            
            $table->foreign('id_number')->references('id_number')->on('medical_records')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_history');
    }
};
