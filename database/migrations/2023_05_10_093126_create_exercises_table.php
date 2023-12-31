<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->string('id');
            $table->string('user_id');
            $table->string('name');
            $table->integer('duration_minutes');
            $table->integer('calories_per_hour');
            $table->integer('total_calories');
            $table->dateTime('date');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exercises');
    }
}
