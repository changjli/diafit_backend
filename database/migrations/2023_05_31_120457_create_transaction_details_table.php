<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->string('transaction_id');
            $table->string('food_id');
            $table->integer('food_quantity');
            $table->primary(['transaction_id', 'food_id']);
            $table->foreign('transaction_id')->references('id')->on('transaction_headers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('food_id')->references('id')->on('food')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('transaction_details');
    }
}
