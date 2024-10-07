
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDentalRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('dental_records', function (Blueprint $table) {
            $table->id();
            $table->string('dental_record_id');// Reference to id_number from students/teachers/staff
            $table->string('id_number')->unique();// Reference to id_number from students/teachers/staff
            $table->string('user_type'); // Store the type (student, teacher, or staff)
            $table->string('patient_name');
            $table->string('grade_section');
            $table->timestamps(); // Created at and updated at timestamp
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('dental_records');
    }
}

