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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable();
            $table->string('title');
            $table->string('language');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->date('date_published')->nullable();
            $table->integer('pages');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
};
