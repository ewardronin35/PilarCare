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
            $table->string('dental_record_id'); // Assuming 'id_number' in dental_records is a string
            $table->integer('tooth_number'); // Changed from string to integer
            $table->enum('status', ['Healthy', 'Decayed', 'Missing']); // Using ENUM for status
            $table->text('notes')->nullable(); // Additional notes about the tooth
            $table->text('svg_path')->nullable();
            $table->json('dental_pictures')->nullable(); // Path to dental pictures
            $table->boolean('is_current')->default(true); // Indicates if this is the current record
            $table->boolean('is_approved')->default(false); // Indicates if the record is approved
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraint
            $table->foreign('dental_record_id')
                  ->references('id_number')
                  ->on('dental_records')
                  ->onDelete('cascade');

            // Composite Unique Constraint
            $table->unique(['dental_record_id', 'tooth_number'], 'unique_dental_record_tooth');
        });
    }

    public function down()
    {
        Schema::table('teeth', function (Blueprint $table) {
            // Drop unique constraint first
            $table->dropUnique('unique_dental_record_tooth');
        });

        Schema::dropIfExists('teeth');
    }
}
