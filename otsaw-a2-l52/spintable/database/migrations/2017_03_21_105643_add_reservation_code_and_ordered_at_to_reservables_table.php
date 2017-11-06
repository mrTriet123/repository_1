<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReservationCodeAndOrderedAtToReservablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservables', function (Blueprint $table) {
            $table->string('reservation_code');
            $table->dateTime('ordered_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('reservables', function (Blueprint $table) {
            $table->dropColumn('reservation_code');
            $table->dropColumn('ordered_at');
        });
    }
}
