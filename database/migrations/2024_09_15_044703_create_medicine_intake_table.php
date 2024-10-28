<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicineIntakeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicine_intake', function (Blueprint $table) {
            $table->id();
            $table->string('id_number'); // id_number field
            $table->string('medicine_name');
            $table->string('dosage');
            $table->time('intake_time')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps(); // created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicine_intake');
    }
}
