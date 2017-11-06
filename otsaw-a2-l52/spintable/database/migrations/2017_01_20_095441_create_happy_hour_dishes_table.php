<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHappyHourDishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('happy_hour_dishes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('happy_hour_id')->unsigned();
            $table->foreign('happy_hour_id')->references('id')->on('happy_hour')->onDelete('cascade');
            $table->integer('dish_id')->unsigned();
            $table->foreign('dish_id')->references('id')->on('dishes')->onDelete('cascade');
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
        Schema::drop('happy_hour_dishes');
    }
}
