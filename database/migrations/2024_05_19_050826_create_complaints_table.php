<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('id_number')->unique();
            $table->string('name');
            $table->integer('age');
            $table->date('birthdate');
            $table->text('health_history')->nullable();
            $table->string('year')->nullable();
            $table->string('section')->nullable();
            $table->string('contact_number');
            $table->integer('pain_assessment');
            $table->text('sickness_description');
            $table->string('status')->default('In progress');
            $table->string('role'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
}
