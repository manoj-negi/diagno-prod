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
        if (!Schema::hasTable('disease_symptom_test')) {
            Schema::create('disease_symptom_test', function (Blueprint $table) {
                $table->id();
                $table->foreignId('disease_symptom_id')->references('id')->on('disease_symptom')->onDelete('cascade');
                $table->foreignId('medical_test_id')->references('id')->on('medical_tests')->onDelete('cascade');
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
        Schema::dropIfExists('disease_symptom_test');
    }
};