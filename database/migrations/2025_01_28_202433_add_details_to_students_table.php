<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::table('students', function (Blueprint $table) {
            $table->date('birthdate')->nullable()->after('address');
            $table->string('profile_picture')->nullable()->after('birthdate'); // Store the file path or URL
        });
    }
 
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { 
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['address', 'birthdate', 'profile_picture']);
        });
    }
}
