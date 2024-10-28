<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDownloadedToDentalExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dental_examinations', function (Blueprint $table) {
            $table->boolean('is_downloaded')->default(false); // Add the column here
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dental_examinations', function (Blueprint $table) {
            $table->dropColumn('is_downloaded'); // Drop the column if the migration is rolled back
        });
    }
}
