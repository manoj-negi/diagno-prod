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
        if (!Schema::hasTable('plan_subscription')) {
            Schema::create('plan_subscription', function (Blueprint $table) {
                $table->unsignedBigInteger('plan_id');
                $table->unsignedBigInteger('subscription_id');
                $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
                $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');

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
        Schema::dropIfExists('plan_subscription');
    }
};