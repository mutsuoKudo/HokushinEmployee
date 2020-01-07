<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Library\OverTimeWorkingClass;
use DB;


class OverTimeWorkingClassTest extends TestCase
{


    // ☆時間外労働アラートで使用する必須項目たち
    public function testOverTimeWorkingClass_overtime_working_all()
    {


        DB::table('overtime_workings')->insert([
            ['shain_cd' => '2018100031', 'year' => 2019, 'month' => 4, 'overtime_working' => '630.0'],
            ['shain_cd' => '2018100031', 'year' => 2019, 'month' => 5, 'overtime_working' => '80.5'],
            ['shain_cd' => '2018100031', 'year' => 2019, 'month' => 6, 'overtime_working' => '80.0'],
            ['shain_cd' => '2018100031', 'year' => 2019, 'month' => 7, 'overtime_working' => '80.0']
        ]);

        DB::table('holiday_workings')->insert([
            ['shain_cd' => '2018100031', 'year' => 2019, 'month' => 7, 'day' => 1, 'holiday_working' => '10.0'],
            ['shain_cd' => '2018100031', 'year' => 2019, 'month' => 7, 'day' => 2, 'holiday_working' => '10.0']
        ]);

        $OverTimeWorkingClass = new OverTimeWorkingClass();

        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $OverTimeWorkingClass->overtime_working_all();

        var_dump($latest_year_pre);
        var_dump($latest_month_pre);
        var_dump($latest_year_month_pre);

        $this->assertEquals('2019', $latest_year_pre);
        $this->assertEquals('07', $latest_month_pre);
        $this->assertEquals('2019年07月', $latest_year_month_pre);

        return [$latest_year_pre, $latest_month_pre, $latest_year_month_pre];
    }



    // 時間外労働（月）アラート
    public function testOverTimeWorkingClass_overtime_working_this_month()
    {

        $OverTimeWorkingClass = new OverTimeWorkingClass();

        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $OverTimeWorkingClass->overtime_working_all();

        $latest_year = $latest_year_pre;
        $latest_month = $latest_month_pre;

        list($employees_overtime_working_this_month_pre, $overtime_working_this_month_array) = $OverTimeWorkingClass->overtime_working_this_month($latest_year, $latest_month);

        var_dump($employees_overtime_working_this_month_pre[0][0]->shain_cd);
        var_dump($overtime_working_this_month_array[0][1]);


        $this->assertEquals(2018100031, $employees_overtime_working_this_month_pre[0][0]->shain_cd);
        $this->assertEquals(80.0, $overtime_working_this_month_array[0][1]);
    }


    // 時間外労働（年）アラート
    public function testOverTimeWorkingClass_overtime_working_year()
    {

        $OverTimeWorkingClass = new OverTimeWorkingClass();

        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $OverTimeWorkingClass->overtime_working_all();

        $latest_year = $latest_year_pre;
        $latest_month = $latest_month_pre;


        list($employees_overtime_working_year_pre, $overtime_working_year_array) = $OverTimeWorkingClass->overtime_working_year($latest_year, $latest_month);

        var_dump($employees_overtime_working_year_pre[0][0]->shain_cd);
        var_dump($overtime_working_year_array[0][1]);


        $this->assertEquals(2018100031, $employees_overtime_working_year_pre[0][0]->shain_cd);
        $this->assertEquals(870.5, $overtime_working_year_array[0][1]);
    }


    // 平均（月）アラート
    public function testOverTimeWorkingClass_overtime_working_avarage()
    {

        $OverTimeWorkingClass = new OverTimeWorkingClass();

        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $OverTimeWorkingClass->overtime_working_all();

        $latest_year = $latest_year_pre;
        $latest_month = $latest_month_pre;


        list($employees_overtime_working_avarege_pre, $test) = $OverTimeWorkingClass->overtime_working_avarage($latest_year, $latest_month);

        var_dump($employees_overtime_working_avarege_pre[0][0]->shain_cd);
        var_dump($test[0][1]);
        // var_dump($test);


        $this->assertEquals(2018100031, $employees_overtime_working_avarege_pre[0][0]->shain_cd);
        $this->assertEquals('2ヶ月平均', $test[0][1]);
        $this->assertEquals('3ヶ月平均', $test[1][1]);
        $this->assertEquals('4ヶ月平均', $test[2][1]);
        $this->assertEquals('5ヶ月平均', $test[3][1]);
        $this->assertEquals('6ヶ月平均', $test[4][1]);
    }


    // 時間外労働時間が45時間を超えた月が年に6回まで
    public function testOverTimeWorkingClass_overtime_working_45()
    {

        $OverTimeWorkingClass = new OverTimeWorkingClass();

        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $OverTimeWorkingClass->overtime_working_all();

        $latest_year = $latest_year_pre;
        $latest_month = $latest_month_pre;


        list($employees_overtime_working_45_pre, $overtime_working_45_array_count_values_pre) = $OverTimeWorkingClass->overtime_working_45($latest_year, $latest_month);

        var_dump($employees_overtime_working_45_pre[0][0]->shain_cd);
        var_dump($overtime_working_45_array_count_values_pre[2018100031]);


        $this->assertEquals(2018100031, $employees_overtime_working_45_pre[0][0]->shain_cd);
        $this->assertEquals(4, $overtime_working_45_array_count_values_pre[2018100031]);
    }


    // 休日労働回数（月）アラート
    public function testOverTimeWorkingClass_holiday_working_this_month_count()
    {

        $OverTimeWorkingClass = new OverTimeWorkingClass();

        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $OverTimeWorkingClass->overtime_working_all();

        $latest_year = $latest_year_pre;
        $latest_month = $latest_month_pre;


        list($employees_holiday_working_this_month_count_pre, $holiday_working_this_month_count_array) = $OverTimeWorkingClass->holiday_working_this_month_count($latest_year, $latest_month);

        var_dump($employees_holiday_working_this_month_count_pre[0][0]->shain_cd);
        var_dump($holiday_working_this_month_count_array[0][1]);


        $this->assertEquals(2018100031, $employees_holiday_working_this_month_count_pre[0][0]->shain_cd);
        $this->assertEquals(2, $holiday_working_this_month_count_array[0][1]);
    }


    // 時間外労働+休日労働（月）アラート
    public function testOverTimeWorkingClass_overtime_and_holiday_working_sum()
    {

        $OverTimeWorkingClass = new OverTimeWorkingClass();

        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $OverTimeWorkingClass->overtime_working_all();

        $latest_year = $latest_year_pre;
        $latest_month = $latest_month_pre;


        list($employees_overtime_and_holiday_working_sum_pre, $overtime_and_holiday_working_sum_array) = $OverTimeWorkingClass->overtime_and_holiday_working_sum($latest_year, $latest_month);

        var_dump($employees_overtime_and_holiday_working_sum_pre[0][0]->shain_cd);
        var_dump($overtime_and_holiday_working_sum_array[0][1]);


        $this->assertEquals(2018100031, $employees_overtime_and_holiday_working_sum_pre[0][0]->shain_cd);
        $this->assertEquals(100, $overtime_and_holiday_working_sum_array[0][1]);

        DB::table('overtime_workings')
            ->where('shain_cd', '2018100031')
            ->delete();

        DB::table('holiday_workings')
            ->where('shain_cd', '2018100031')
            ->delete();
    }








    // 時間外労働（年）アラート2
    public function testOverTimeWorkingClass_overtime_working_year2()
    {

        DB::table('overtime_workings')->insert([
            ['shain_cd' => '2018100031', 'year' => 2019, 'month' => 11, 'overtime_working' => '50.0'],
            ['shain_cd' => '2018100031', 'year' => 2019, 'month' => 12, 'overtime_working' => '50.0'],
            ['shain_cd' => '2018100031', 'year' => 2020, 'month' => 1, 'overtime_working' => '630.0']
        ]);

        $OverTimeWorkingClass = new OverTimeWorkingClass();

        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $OverTimeWorkingClass->overtime_working_all();

        $latest_year = $latest_year_pre;
        $latest_month = $latest_month_pre;


        list($employees_overtime_working_year_pre, $overtime_working_year_array) = $OverTimeWorkingClass->overtime_working_year($latest_year, $latest_month);

        var_dump($employees_overtime_working_year_pre[0][0]->shain_cd);
        var_dump($overtime_working_year_array[0][1]);


        $this->assertEquals(2018100031, $employees_overtime_working_year_pre[0][0]->shain_cd);
        $this->assertEquals(730, $overtime_working_year_array[0][1]);

        DB::table('overtime_workings')
        ->where('shain_cd', '2018100031')
        ->delete();

    }


    
    // 時間外労働時間が45時間を超えた月が年に6回まで2
    public function testOverTimeWorkingClass_overtime_working_45_2()
    {

        DB::table('overtime_workings')->insert([
            ['shain_cd' => '2018100031', 'year' => 2019, 'month' => 10, 'overtime_working' => '50.0'],
            ['shain_cd' => '2018100031', 'year' => 2019, 'month' => 11, 'overtime_working' => '50.0'],
            ['shain_cd' => '2018100031', 'year' => 2019, 'month' => 12, 'overtime_working' => '50.0'],
            ['shain_cd' => '2018100031', 'year' => 2020, 'month' => 1, 'overtime_working' => '630.0']
        ]);

        $OverTimeWorkingClass = new OverTimeWorkingClass();

        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $OverTimeWorkingClass->overtime_working_all();

        $latest_year = $latest_year_pre;
        $latest_month = $latest_month_pre;


        list($employees_overtime_working_45_pre, $overtime_working_45_array_count_values_pre) = $OverTimeWorkingClass->overtime_working_45($latest_year, $latest_month);

        var_dump($employees_overtime_working_45_pre[0][0]->shain_cd);
        var_dump($overtime_working_45_array_count_values_pre[2018100031]);


        $this->assertEquals(2018100031, $employees_overtime_working_45_pre[0][0]->shain_cd);
        $this->assertEquals(4, $overtime_working_45_array_count_values_pre[2018100031]);

        DB::table('overtime_workings')
        ->where('shain_cd', '2018100031')
        ->delete();
    }
}
