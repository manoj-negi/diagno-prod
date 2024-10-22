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
        if (!Schema::hasTable('question_for_patients')) {
            Schema::create('question_for_patients', function (Blueprint $table) {
                $table->id();
                $table->text('question');
                $table->unsignedBigInteger('speciality_id')->default(0);
                $table->foreign('speciality_id')->references('id')->on('specialities')->onDelete('cascade');
                $table->boolean('status')->default(1);
                $table->bigInteger('types_of_consultation_id')->nullable();
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
        Schema::dropIfExists('question_for_patients');
    }
};