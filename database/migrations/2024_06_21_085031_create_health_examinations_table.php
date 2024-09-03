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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('school_year');
            $table->string('health_examination_picture');
            $table->json('xray_pictures')->nullable(); // Storing multiple X-ray pictures as JSON array
            $table->json('lab_result_pictures')->nullable(); // Storing multiple Lab Result pictures as JSON array
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_examinations');
    }
}
