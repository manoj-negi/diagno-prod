<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->float('wallet', 8, 2)->default(0.00);
            $table->string('postal_code')->nullable();
            $table->string('gst')->nullable();
            $table->string('number')->nullable();
            $table->string('refer_code', 35)->nullable();
            $table->string('age')->nullable();
            $table->string('sex', 50)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=>active,0=>deactive');
            $table->tinyInteger('is_profile')->default(0)->comment('0=>not updated profile,1=> update profile');
            $table->string('profile_image')->nullable();
            $table->string('address')->nullable();
            $table->string('dob')->nullable();
            $table->enum('is_approved', ['approved', 'pending', 'rejected'])->default('pending');
            $table->string('otp')->nullable();
            $table->string('home_collection')->nullable();
            $table->bigInteger('otp_expire')->nullable();
            $table->integer('plan_id')->nullable();
            $table->timestamp('plan_start_date')->nullable();
            $table->timestamp('plan_expire_date')->nullable();
            $table->text('fcm_token')->nullable();
            $table->rememberToken();
            $table->string('city_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('hospital_category')->nullable();
            $table->integer('is_hospital')->nullable();
            $table->timestamps();
            $table->integer('is_patient')->nullable();
            $table->string('hospital_logo')->nullable();
            $table->text('hospital_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
