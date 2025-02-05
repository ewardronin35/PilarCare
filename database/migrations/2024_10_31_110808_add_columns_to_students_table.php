<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('education_level')->after('last_name'); // elementary, JHS, SHS, college
            $table->string('enrollment_status')->default('inactive')->after('education_level'); // 'active', 'inactive'
            $table->boolean('is_scholar')->default(false)->after('enrollment_status'); // 'true' for scholars
            $table->string('course_major')->nullable()->after('grade_or_course'); // for SHS and college, nullable for other levels
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
            $table->dropColumn(['education_level', 'enrollment_status', 'is_scholar', 'course_major']);
        });
    }
}
