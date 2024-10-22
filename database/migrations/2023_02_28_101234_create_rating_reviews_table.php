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
        if (!Schema::hasTable('rating_reviews')) {
            Schema::create('rating_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreignId('doctor_id')->references('id')->on('users')->onDelete('cascade');
                $table->integer('ratings');
                $table->text('review')->nullable();
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
        Schema::dropIfExists('rating_reviews');
    }
};