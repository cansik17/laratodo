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
        Schema::create('note_categories_pivots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("note_id");
            $table->unsignedBigInteger("category_id");
            $table->foreign('note_id')->references('id')->on('notes')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('note_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('note_categories_pivots');
    }
};
