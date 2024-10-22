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
        if (!Schema::hasTable('certificate_reports')) {
            Schema::create('certificate_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('appointment_id');
                $table->unsignedBigInteger('consult_id');
                $table->unsignedBigInteger('patient_id');
                $table->string('report');
                $table->string('certificate');
                $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
                $table->foreign('consult_id')->references('id')->on('consult_details')->onDelete('cascade');
                $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
                $table->timestamps();
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
        Schema::dropIfExists('certificate_reports');
    }
};