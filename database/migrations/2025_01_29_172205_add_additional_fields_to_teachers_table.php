<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToTeachersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('teacher', function (Blueprint $table) {
            $table->string('father_name')->nullable()->after('role');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->string('contact_number')->nullable()->after('mother_name');
            $table->string('address')->nullable()->after('contact_number');
            $table->string('emergency_contact')->nullable()->after('address');
            $table->unsignedInteger('age')->nullable()->after('emergency_contact');
            $table->string('profile_picture')->nullable()->after('age');
            $table->date('birthdate')->nullable()->after('profile_picture');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('teacher', function (Blueprint $table) {
            $table->dropColumn([
                'father_name',
                'mother_name',
                'contact_number',
                'address',
                'emergency_contact',
                'age',
                'profile_picture',
                'birthdate',
            ]);
        });
    }
}
