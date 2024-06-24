<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');  // Assume 'user_id' should store 'id_number' from 'users' table
            $table->string('title');
            $table->text('message');
            $table->timestamp('scheduled_time')->useCurrent();  // Default to current time if not provided
            $table->timestamps();

            // Ensure 'users' table migration is run first or 'users' table already has 'id_number'
            $table->foreign('user_id')->references('id_number')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
