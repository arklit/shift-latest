<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('information_pages', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(0);
            $table->integer('sort')->default(0);
            $table->string('type');
            $table->string('name');
            $table->string('title');
            $table->string('code');
            $table->text('text');
            $table->text('announce');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('image_outer');
            $table->text('image_inner');
            $table->text('uri');
            $table->string('template');
            $table->json('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('information_pages');
    }
};
