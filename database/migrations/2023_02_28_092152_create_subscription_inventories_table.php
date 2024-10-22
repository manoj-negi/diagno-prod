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
        if (!Schema::hasTable('subscription_inventories')) {
            Schema::create('subscription_inventories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
                $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->text('transection_id')->nullable();
                $table->string('payer_id')->nullable();
                $table->string('payer_email')->nullable();
                $table->float('amount', 10, 2)->nullable();
                $table->string('currency')->nullable();
                $table->string('pay_status')->nullable();
                $table->boolean('status')->default(0);
                $table->timestamp('start_date')->nullable();
                $table->timestamp('end_date')->nullable();
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
        Schema::dropIfExists('subscription_inventories');
    }
};
