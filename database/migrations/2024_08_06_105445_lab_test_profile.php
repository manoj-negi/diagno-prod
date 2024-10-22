<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labs_tests_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('labs_tests_id');
            $table->unsignedBigInteger('lab_profile_id');
            $table->unsignedBigInteger('lab_id');
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('labs_tests_id')->references('id')->on('labs_tests')->onDelete('cascade');
            $table->foreign('lab_profile_id')->references('id')->on('lab_profile')->onDelete('cascade');
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
        Schema::table('labs_tests_profiles', function (Blueprint $table) {
            // Drop the foreign key constraints
            $table->dropForeign(['labs_tests_id']);
            $table->dropForeign(['lab_profile_id']);
        });
        Schema::dropIfExists('labs_tests_profiles');
    }
};
