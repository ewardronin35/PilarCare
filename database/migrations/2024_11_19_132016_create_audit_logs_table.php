<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('module'); // e.g., 'Appointments', 'Complaints'
            $table->string('action'); // e.g., 'Created', 'Updated', 'Deleted', 'Login'
            $table->text('description'); // Detailed description of the action
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // User who performed the action
            $table->timestamps(); // Includes 'created_at' for timestamp
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
}
