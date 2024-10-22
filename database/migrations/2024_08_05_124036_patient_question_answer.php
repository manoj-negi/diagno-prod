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
        if (!Schema::hasTable('patient_question_answers')) {
            Schema::create('patient_question_answers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('question_id');
                $table->unsignedBigInteger('appointment_id');
                $table->text('answer')->nullable();
                $table->foreign('question_id')->references('id')->on('question_for_patients')->onDelete('cascade');
                $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
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
        Schema::dropIfExists('patient_question_answers');
    }
};