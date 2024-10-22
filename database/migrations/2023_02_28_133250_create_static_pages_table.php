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
        if (!Schema::hasTable('static_pages')) {
            Schema::create('static_pages', function (Blueprint $table) {
                $table->id();
                $table->text('title');
                $table->text('slug');
                $table->longText('content');
                $table->boolean('status')->default(1)->comment("1=>active,0=>deactive");
                $table->string('meta_keyword')->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
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
        Schema::dropIfExists('static_pages');
    }
};