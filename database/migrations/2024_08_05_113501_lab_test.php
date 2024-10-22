<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Labtest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labs_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lab_id');
            $table->string('test_name');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
            $table->string('description', 100)->nullable();

            // Add foreign key constraint
            $table->foreign('lab_id')->references('id')->on('users')->onDelete('cascade');

            // Add composite unique key on lab_id and test_name
            $table->unique(['lab_id', 'test_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('labs_tests', function (Blueprint $table) {
            // Drop the composite unique key
            $table->dropUnique(['lab_id', 'test_name']);

            // Drop the foreign key constraint
            $table->dropForeign(['lab_id']);
        });

        Schema::dropIfExists('labs_tests');
    }
}
