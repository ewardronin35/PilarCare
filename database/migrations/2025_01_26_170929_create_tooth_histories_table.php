<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToothHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('tooth_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tooth_id'); // Foreign key to teeth table
            $table->integer('tooth_number');
            $table->string('status');
            $table->text('notes')->nullable();
            $table->string('svg_path');
            $table->json('dental_pictures')->nullable();
            $table->boolean('is_current')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_new')->default(true);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('tooth_id')
                  ->references('id')
                  ->on('teeth')
                  ->onDelete('cascade');

            // Indexes for faster queries
            $table->index('tooth_id');
            $table->index('tooth_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tooth_histories');
    }
}
