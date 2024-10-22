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
        if (!Schema::hasTable('mail_templates')) {
            Schema::create('mail_templates', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('mail_key');
                $table->string('mail_subject');
                $table->text('content');
                $table->boolean('status')->default(0)->comment('1=>active,0=>inactive');
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
        Schema::dropIfExists('mail_templates');
    }
};