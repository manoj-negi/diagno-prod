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
        if (!Schema::hasTable('avaliablities')) {
            Schema::create('avaliablities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('doctor_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreignId('hospital_id')->references('id')->on('hospitals')->onDelete('cascade');
                $table->tinyInteger('week_day')->comment('0=>sunday,1=>monday,...6=>saturday');
                $table->time('start_time');
                $table->time('end_time');
                $table->boolean('status')->default(1);
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
        Schema::dropIfExists('avaliablities');
    }
};