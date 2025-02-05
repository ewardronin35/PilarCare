<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('id_number')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('grade_or_course');
            $table->string('semester'); // Removed 'after'
            $table->string('father_name'); // Removed 'after'
            $table->string('mother_name'); // Removed 'after'
            $table->string('contact_number'); // Removed 'after'
            $table->string('emergency_contact_number'); // Removed 'after'
            $table->string('address'); // Removed 'after'
            $table->boolean('approved')->default(0); // Kept at the end for clarity
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
