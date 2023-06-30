<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNutritionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('nutrition_reports', function (Blueprint $table) {
        //     $table->string('id');
        //     $table->string('user_id');
        //     $table->date('date');
        //     $table->primary('id');
        //     $table->foreign('user_id')->references('id')->on('users');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nutrition_reports');
    }
}
