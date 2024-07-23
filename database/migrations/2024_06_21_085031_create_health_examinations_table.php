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
            $table->string('health_examination_picture');
            $table->string('xray_picture')->nullable();
            $table->string('lab_result_picture')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_examinations');
    }
}
