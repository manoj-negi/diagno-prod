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
        if (!Schema::hasTable('consult_details')) {
            Schema::create('consult_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreignId('doctor_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreignId('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
                $table->bigInteger('disease_id')->nullable();
                $table->string('symptom_ids')->nullable()->comment('comma seprated');
                $table->string('medication_ids')->nullable()->comment('comma seprated');
                $table->text('medicine_taken')->nullable();
                $table->text('medicine_dosage')->nullable();
                $table->string('test_ids')->nullable()->comment('comma seprated');
                $table->boolean('status')->default(0);
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
        Schema::dropIfExists('consult_details');
    }
};