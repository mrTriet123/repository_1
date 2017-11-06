<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDishesAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_dishes_addons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_dishes_id')->unsigned();
            $table->foreign('order_dishes_id')->references('id')->on('order_dishes')->onDelete('cascade');
            $table->integer('addon_id')->unsigned();
            $table->foreign('addon_id')->references('id')->on('addons')->onDelete('cascade');
            $table->integer('quantity');
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
        Schema::drop('order_dishes_addons');
    }
}
