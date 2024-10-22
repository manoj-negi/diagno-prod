<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('hospital_id')->default(0);
            $table->string('report_image', 255)->nullable();
            $table->string('report_title', 255)->nullable();
            $table->string('test_type', 50)->nullable();
            $table->date('date');
            $table->string('time', 50)->nullable();
            $table->string('prescription_file', 255)->nullable();
            $table->integer('member_id')->nullable();
            $table->string('patient_address', 300)->nullable();
            $table->tinyInteger('report_hard_copy')->default(0);
            $table->enum('payment_mode', ['online', 'cash']);
            $table->enum('transaction_status', ['pending', 'failed', 'success'])->default('pending');
            $table->string('transaction_id', 255)->nullable();
            $table->float('booking_amount', 8, 2)->nullable();
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->decimal('discounted_amount', 10, 2)->nullable();
            $table->string('status', 50)->default('pending');
            $table->string('invoice', 255)->nullable();
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('offer_id')->references('offer_id')->on('offers')->onDelete('set null');

            // Add indexes
            $table->index('doctor_id');
            $table->index('patient_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Drop the foreign key constraints
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['offer_id']);
        });
        Schema::dropIfExists('appointments');
    }
}
