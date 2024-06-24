<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('id_number')->unique(); // Unique identifier, will be the primary key
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('contact_number', 12);
            $table->string('gender');
            $table->string('role');
            $table->string('parent_id')->nullable(); // Nullable parent_id
            $table->string('student_type')->nullable();
            $table->string('program')->nullable();
            $table->string('year_level')->nullable();
            $table->string('year_section')->nullable();
            $table->string('bed_type')->nullable();
            $table->string('section')->nullable();
            $table->string('grade')->nullable();
            $table->string('teacher_type')->nullable();
            $table->string('staff_role')->nullable();
            $table->timestamps();
            $table->rememberToken();

            // Index for id_number column
            $table->index('id_number');

            // Foreign key constraint for parent_id referencing id_number on users table
            $table->foreign('parent_id')->references('id_number')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
