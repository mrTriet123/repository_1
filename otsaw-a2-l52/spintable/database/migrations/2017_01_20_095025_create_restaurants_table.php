<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchant_id')->unsigned();
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
            $table->string('name');
            $table->string('tel_no');
            $table->integer('restaurant_type_id')->unsigned();
            $table->foreign('restaurant_type_id')->references('id')->on('restaurant_types')->onDelete('cascade');
            $table->time('operating_hour_start');
            $table->time('operating_hour_end');
            $table->float('gst');
            $table->float('service_charge');
            $table->tinyInteger('is_featured')->default(0);
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
        Schema::drop('restaurants');
    }
}
