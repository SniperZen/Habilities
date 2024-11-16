<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFeedbackTableContentColumn extends Migration
{
    public function up()
    {
        Schema::table('feedback', function (Blueprint $table) {
            // Change content column to longText to store formatted content
            $table->longText('content')->change();
        });
    }

    public function down()
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->text('content')->change();
        });
    }
}
