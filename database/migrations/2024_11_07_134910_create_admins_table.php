<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::create('admins', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key 'id'
            $table->string('id_number')->unique(); // Unique Admin ID Number
            $table->string('name'); // Admin's Full Name
            $table->timestamps(); // 'created_at' and 'updated_at' columns
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
