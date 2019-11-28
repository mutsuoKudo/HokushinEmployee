<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HolidaycontrollerTest extends TestCase
{

    public function testController_HolidaycontrollerTest()
    {


        // 勤続年数12年
        $response = $this->json('post', '/holiday/202012', [
            'year' => '2019',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',

        ]);
        $response->assertStatus(200);

        // 勤続年数1年未満
        $response = $this->json('post', '/holiday/2019070011', [
            'year' => '00',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',
        ]);
        $response->assertStatus(200);

        // 2019年退社
        $response = $this->json('post', '/holiday/2018110051', [
            'year' => '01',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',
        ]);
        $response->assertStatus(200);

        // 準社員
        $response = $this->json('post', '/holiday/202013', [
            'year' => '2019',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',
        ]);
        $response->assertStatus(200);

        // 未消化アラート
        $response = $this->json('post', '/holiday/202014', [
            'year' => '2018',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',
        ]);
        $response->assertStatus(200);


        $response = $this->json('get', '/mishouka');
        $response->assertStatus(200);

        $response = $this->json('get', '/zansu_kinshou');
        $response->assertStatus(200);

        var_dump('HolidayControllerTest END');
    }
}
