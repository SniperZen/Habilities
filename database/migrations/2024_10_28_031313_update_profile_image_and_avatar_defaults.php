<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProfileImageAndAvatarDefaults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Update profile_image default path
            $table->string('profile_image')->default('users-avatar/default_image.jpg')->change();
            
            // Update existing avatar column default (from Chatify)
            $table->string('avatar')->default('default_image.jpg')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert profile_image to original default
            $table->string('profile_image')->default('profile-images/default_image.jpg')->change();
            
            // Revert avatar to Chatify's original default (if any)
            $table->string('avatar')->default(null)->change();
        });
    }
}
