<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeethTable extends Migration
{
    public function up()
    {
        Schema::create('teeth', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dental_record_id');
            $table->string('tooth_number'); // E.g., '11', '12', etc.
            $table->string('status'); // E.g., 'Healthy', 'Decayed', etc.
            $table->text('notes')->nullable(); // Additional notes about the tooth
            $table->text('svg_path')->nullable();
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraint
            $table->foreign('dental_record_id')->references('id')->on('dental_records')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('teeth');
    }
}
