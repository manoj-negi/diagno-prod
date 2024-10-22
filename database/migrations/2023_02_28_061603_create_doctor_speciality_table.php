<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('doctor_speciality')) {
            Schema::create('doctor_speciality', function (Blueprint $table) {
                $table->unsignedBigInteger('doctor_id');
                $table->unsignedBigInteger('speciality_id');
                $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('speciality_id')->references('id')->on('specialities')->onDelete('cascade');
            });
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_speciality');
    }
};