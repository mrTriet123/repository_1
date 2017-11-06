<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTypeColumnInReservablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservables', function (Blueprint $table) {
            DB::statement("ALTER TABLE reservables MODIFY COLUMN type ENUM('Reservation', 'Walkin', 'Ordered') NOT NULL");
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
            DB::statement("ALTER TABLE reservables MODIFY COLUMN type ENUM('Reservation', 'Walkin') NOT NULL");
        });
    }
}
