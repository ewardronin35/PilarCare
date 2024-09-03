<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('dental_examinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key for the user (student)
            $table->date('date_of_examination');
            $table->string('grade_section');
            $table->string('lastname');
            $table->string('firstname');
            $table->date('birthdate');
            $table->integer('age');
            
            // Fields for oral conditions
            $table->boolean('carries_free')->default(false);
            $table->boolean('poor_oral_hygiene')->default(false);
            $table->boolean('gum_infection')->default(false);
            $table->boolean('restorable_caries')->default(false);
            $table->string('other_condition')->nullable();
    
            // Fields for recommendations
            $table->boolean('personal_attention')->default(false);
            $table->boolean('oral_prophylaxis')->default(false);
            $table->boolean('fluoride_application')->default(false);
            $table->boolean('gum_treatment')->default(false);
            $table->boolean('ortho_consultation')->default(false);
            $table->string('sealant_tooth')->nullable();
            $table->string('filling_tooth')->nullable();
            $table->string('extraction_tooth')->nullable();
            $table->string('endodontic_tooth')->nullable();
            $table->string('radiograph_tooth')->nullable();
            $table->string('prosthesis_tooth')->nullable();
            $table->boolean('medical_clearance')->default(false);
            $table->string('other_recommendation')->nullable();
    
            $table->timestamps();
    
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
    