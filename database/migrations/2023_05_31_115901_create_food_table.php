<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->float('serving_size');
            $table->float('calories')->nullable();
            $table->float('proteins')->nullable();
            $table->float('fats')->nullable();
            $table->float('carbs')->nullable();
            $table->string('image')->nullable();
            $table->integer('price');
            $table->integer('stock');
            $table->date('date');
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
        Schema::dropIfExists('food');
    }
}
