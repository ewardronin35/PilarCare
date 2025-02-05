<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEnrollmentsStudentIdToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::table('enrollments', function (Blueprint $table) {
            // Drop existing foreign key constraint if it exists
            $table->dropForeign(['student_id']);

            // Change 'student_id' column from integer to string
            $table->string('student_id')->change();

            // Re-add foreign key constraint referencing 'students.id_number'
            $table->foreign('student_id')
                  ->references('id_number')
                  ->on('students')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::table('enrollments', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['student_id']);

            // Change 'student_id' column back to integer
            $table->unsignedBigInteger('student_id')->change();

            // Re-add foreign key constraint referencing 'students.id' if applicable
            // If 'students.id_number' was originally not a foreign key, adjust accordingly
            $table->foreign('student_id')
                  ->references('id')
                  ->on('students')
                  ->onDelete('cascade');
        });
    }
}
