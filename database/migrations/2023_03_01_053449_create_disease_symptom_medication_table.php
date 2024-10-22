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
        if (!Schema::hasTable('disease_symptom_medication')) {
            Schema::create('disease_symptom_medication', function (Blueprint $table) {
                $table->id();
                $table->foreignId('disease_symptom_id')->references('id')->on('disease_symptom')->onDelete('cascade');
                $table->foreignId('medication_id')->references('id')->on('medications')->onDelete('cascade');
                $table->integer('counter')->default(0);
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
        Schema::dropIfExists('disease_symptom_medication');
    }
};