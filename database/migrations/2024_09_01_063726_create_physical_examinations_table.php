<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhysicalExaminationsTable extends Migration
{
    public function up()
    {
        Schema::create('physical_examinations', function (Blueprint $table) {
            $table->id();
            $table->string('id_number'); // Replacing user_id with id_number
            $table->float('height');
            $table->float('weight');
            $table->string('vision');
            $table->text('remarks')->nullable();
            $table->boolean('md_approved')->default(false); // MD approval status
            $table->string('picture')->nullable(); // Adding the column for picture upload

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_number')->references('id_number')->on('information')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('physical_examinations');
    }
}
