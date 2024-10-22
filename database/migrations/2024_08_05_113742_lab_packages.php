<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Labpackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labs_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lab_id');
            $table->unsignedBigInteger('lab_profile_id');
            $table->string('package_name')->unique();
            $table->text('details');
            $table->decimal('price', 10, 2);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            // Add foreign key constraints
            $table->foreign('lab_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lab_profile_id')->references('id')->on('lab_profile')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('labs_packages', function (Blueprint $table) {
            // Drop the foreign key constraints
            $table->dropForeign(['lab_id']);
            $table->dropForeign(['lab_profile_id']);
        });
        Schema::dropIfExists('labs_packages');
    }
}
