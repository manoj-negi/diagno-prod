<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Labprofile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_profile', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lab_id');
            $table->string('profile_name')->unique();
            $table->text('description');
            $table->timestamps();

            // Add the foreign key constraint
            $table->foreign('lab_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lab_profile', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['lab_id']);
        });
        Schema::dropIfExists('lab_profile');
    }
}
