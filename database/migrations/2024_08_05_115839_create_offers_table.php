<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->bigIncrements('offer_id');
            $table->string('title', 255)->notNull();
            $table->text('description')->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->enum('status', ['active', 'inactive', 'expired'])->notNull();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('offer_code', 50)->nullable();
            $table->enum('offer_type', ['percentage', 'fixed_amount'])->notNull();
            $table->decimal('maximum_discount', 10, 2)->nullable();
            $table->decimal('minimum_purchase_amount', 10, 2)->nullable();
            $table->enum('applicable_to', ['all_users', 'new_users', 'existing_users'])->notNull();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
