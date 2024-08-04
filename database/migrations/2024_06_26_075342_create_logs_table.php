<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); // The user who performed the action
            $table->string('action'); // The action performed
            $table->string('category'); // The category of the log (e.g., appointment, inventory)
            $table->text('details'); // Additional details about the action
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
