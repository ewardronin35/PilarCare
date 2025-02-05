<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('id_number');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('grade_course')->nullable(); // New column for Grade
            $table->string('section')->nullable(); // New column for Section
            $table->integer('pain_assessment');
            $table->text('sickness_description');
            $table->string('role'); 
            $table->string('medicine_given')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
}
