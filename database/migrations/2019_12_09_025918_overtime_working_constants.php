<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OvertimeWorkingConstants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_working_constants', function (Blueprint $table) {
            $table->integer('regular_working_hours');

            $table->integer('base_working_overtime_day');
            $table->integer('base_working_overtime_month');
            $table->integer('base_working_overtime_year');

            $table->integer('exception_working_overtime_day');
            $table->integer('exception_working_overtime_month');
            $table->integer('exception_working_overtime_year');

            $table->integer('overtime_working_average');
            $table->integer('overtime_and_holiday_working');
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
