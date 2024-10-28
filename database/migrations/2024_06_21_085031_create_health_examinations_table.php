<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthExaminationsTable extends Migration
{
    public function up()
    {
        Schema::create('health_examinations', function (Blueprint $table) {
            $table->id();
            $table->string('id_number'); // Use id_number instead of user_id
            $table->foreign('id_number')->references('id_number')->on('users')->onDelete('cascade'); // Foreign key constraint
            $table->string('school_year');
            $table->json('health_examination_picture')->nullable();
            $table->json('xray_picture')->nullable(); // Storing multiple X-ray pictures as JSON array
            $table->json('lab_result_picture')->nullable(); // Storing multiple Lab Result pictures as JSON array
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_examinations');
    }
}
