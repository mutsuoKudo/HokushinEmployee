<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HolidayWorkings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holiday_workings', function (Blueprint $table) {
            $table->string('shain_cd');
            $table->integer('year');
            $table->integer('month');
            $table->integer('count');
            $table->decimal('holiday_working', 4, 1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
