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
            $table->string('user_id'); // Assuming id_number is a string. If it's an integer, use $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('message');
            $table->string('role')->nullable();  // Add this line
            $table->timestamp('scheduled_time')->useCurrent();
            $table->boolean('is_opened')->default(false);
            $table->timestamps();


            // Assuming 'id_number' is the primary or unique key in 'users' table and is a string
            $table->foreign('user_id')->references('id_number')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
