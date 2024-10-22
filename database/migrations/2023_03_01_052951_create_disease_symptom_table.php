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
        if (!Schema::hasTable('disease_symptom')) {
            Schema::create('disease_symptom', function (Blueprint $table) {
                $table->id();
                $table->foreignId('disease_id')->references('id')->on('diseases')->onDelete('cascade');
                $table->foreignId('symptom_id')->references('id')->on('symptoms')->onDelete('cascade');
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
        Schema::dropIfExists('disease_symptom');
    }
};