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
            $table->string('user_id');
            $table->string('title');
            $table->text('message');
            $table->timestamp('scheduled_time')->useCurrent();
            $table->timestamps();

            // Ensure the 'users' table has 'id_number' and create the foreign key constraint
            $table->foreign('user_id')->references('id_number')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
