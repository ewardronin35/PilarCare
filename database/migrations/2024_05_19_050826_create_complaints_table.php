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
            $table->integer('age');
            $table->date('birthdate');
            $table->string('year');
            $table->string('contact_number');
            $table->integer('pain_assessment');
            $table->text('sickness_description');
            $table->string('status')->default('In progress');
            $table->string('role'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
}
