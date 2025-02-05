<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToStaffTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->string('father_name', 255)->nullable()->after('last_name');
            $table->string('mother_name', 255)->nullable()->after('father_name');
            $table->string('contact_number', 200)->nullable()->after('mother_name');
            $table->string('address', 255)->nullable()->after('contact_number');
            $table->date('birthdate')->nullable()->after('address');
            $table->string('profile_picture', 255)->nullable()->after('birthdate');
            $table->string('emergency_contact', 200)->nullable()->after('profile_picture');
            $table->integer('age')->nullable()->after('emergency_contact'); // Optional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn([
                'father_name',
                'mother_name',
                'contact_number',
                'address',
                'birthdate',
                'profile_picture',
                'emergency_contact',
                'age',
            ]);
        });
    }
}
