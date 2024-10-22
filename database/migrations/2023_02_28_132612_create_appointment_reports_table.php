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
        if (!Schema::hasTable('appointment_reports')) {
            Schema::create('appointment_reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->references('id')->on('users')->onDelete('cascade');
                $table->string('report_image');
                 $table->string('report_title');
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
        Schema::dropIfExists('appointment_reports');
    }
};
