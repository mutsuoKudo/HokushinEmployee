<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use DB;

class OverTimeWorkingControllerTest extends TestCase
{
// 最新データ月の数字が4以上の時
    public function testController_OverTimeWorkingController1()
    {
        DB::table('overtime_workings')
        ->where('shain_cd', '2018100031')
        ->delete();

        DB::table('holiday_workings')
        ->where('shain_cd', '2018100031')
        ->delete();

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



        $response = $this->json('post', '/over_time_working/2018100031', [
            'post_year' => '2019',
            'post_month' => '07',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',

        ]);
        $response->assertStatus(200);

        DB::table('overtime_workings')
        ->where('shain_cd', '2018100031')
        ->delete();

        DB::table('holiday_workings')
        ->where('shain_cd', '2018100031')
        ->delete();
    }

  // 最新データ月の数字が4以下の時
    public function testController_OverTimeWorkingController2()
    {
        DB::table('overtime_workings')->insert([
            ['shain_cd' => '2018100031', 'year' => 2019, 'month' => 9, 'overtime_working' => '89.0'],
            ['shain_cd' => '2018100031', 'year' => 2019, 'month' => 10, 'overtime_working' => '90.0'],
            ['shain_cd' => '2018100031', 'year' => 2019, 'month' => 11, 'overtime_working' => '91.0'],
            ['shain_cd' => '2018100031', 'year' => 2019, 'month' => 12, 'overtime_working' => '92.0'],
            ['shain_cd' => '2018100031', 'year' => 2020, 'month' => 1, 'overtime_working' => '1.0']
        ]);

        DB::table('holiday_workings')->insert([
            ['shain_cd' => '2018100031', 'year' => 2020, 'month' => 1, 'day' => 1, 'holiday_working' => '10.0'],
            ['shain_cd' => '2018100031', 'year' => 2020, 'month' => 1, 'day' => 2, 'holiday_working' => '10.0']
        ]);



        $response = $this->json('post', '/over_time_working/2018100031', [
            'post_year' => '2020',
            'post_month' => '01',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',

        ]);
        $response->assertStatus(200);

        DB::table('overtime_workings')
        ->where('shain_cd', '2018100031')
        ->delete();

        DB::table('holiday_workings')
        ->where('shain_cd', '2018100031')
        ->delete();
    }
}


