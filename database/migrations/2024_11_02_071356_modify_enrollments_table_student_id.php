<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyEnrollmentsTableStudentId extends Migration
{
    public function up()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // Drop existing foreign key constraint
            if (Schema::hasColumn('enrollments', 'student_id')) {
                $table->dropForeign(['student_id']);
            }
        });

        // Update existing records to use 'id_number' instead of 'id'
        DB::table('enrollments')->get()->each(function ($enrollment) {
            $student = DB::table('students')->where('id', $enrollment->student_id)->first();
            if ($student) {
                DB::table('enrollments')
                    ->where('id', $enrollment->id)
                    ->update(['student_id' => $student->id_number]);
            }
        });

        Schema::table('enrollments', function (Blueprint $table) {
            // Change 'student_id' type to string if not already
            $table->string('student_id', 7)->change();

            // Add foreign key constraint to 'students.id_number'
            $table->foreign('student_id')
                  ->references('id_number')
                  ->on('students')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['student_id']);
        });

        // Update existing records to use 'id' instead of 'id_number'
        DB::table('enrollments')->get()->each(function ($enrollment) {
            $student = DB::table('students')->where('id_number', $enrollment->student_id)->first();
            if ($student) {
                DB::table('enrollments')
                    ->where('id', $enrollment->id)
                    ->update(['student_id' => $student->id]);
            }
        });

        Schema::table('enrollments', function (Blueprint $table) {
            // Change 'student_id' type back to unsigned big integer
            $table->unsignedBigInteger('student_id')->change();

            // Add foreign key constraint to 'students.id'
            $table->foreign('student_id')
                  ->references('id')
                  ->on('students')
                  ->onDelete('cascade');
        });
    }
}
