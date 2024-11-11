<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Check if the column does not exist before adding it
            if (!Schema::hasColumn('appointments', 'appointment_date')) {
                $table->dateTime('appointment_date')->nullable(); // For the date and time of the appointment
            }

            // Check if the column does not exist before adding it
            if (!Schema::hasColumn('appointments', 'status')) {
                $table->string('status')->default('pending'); // To track the status (pending, accepted, declined)
            }
        });
    }
    
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Drop the columns if they exist
            if (Schema::hasColumn('appointments', 'appointment_date')) {
                $table->dropColumn('appointment_date');
            }

            if (Schema::hasColumn('appointments', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
