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
            $table->unsignedBigInteger('user_id');
            $table->float('height');
            $table->float('weight');
            $table->string('vision');
            $table->string('medicine_intake')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('md_approved')->default(false); // MD approval status
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('physical_examinations');
    }
}
