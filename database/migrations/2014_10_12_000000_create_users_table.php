<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('gender')->nullable();
            $table->integer('age')->nullable();
            $table->decimal('height')->nullable();
            $table->decimal('weight')->nullable();
            $table->decimal('weightGoal')->nullable();
            $table->decimal('nutritionGoal')->nullable();
            $table->decimal("exerciseGoal")->nullable();
            $table->decimal('glucoseGoal')->nullable();
            $table->string('address')->nullable();
            $table->string('image')->nullable();
            $table->string('password_reset_token')->nullable();
            $table->string('exp_date')->nullable();
            $table->string('role');
            $table->rememberToken();
            $table->timestamps();
            $table->primary(['id']);
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
}
