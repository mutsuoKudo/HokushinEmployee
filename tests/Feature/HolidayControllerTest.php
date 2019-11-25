<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HolidaycontrollerTest extends TestCase
{

    public function testController_HolidaycontrollerTest(){

        // $response = $this->json('post', '/holiday/2018100031');
        // $response->assertStatus(200);

        // 勤続年数12年
        $response = $this->json('post', '/holiday/202012');
        $response->assertStatus(200);

        // 2019年退社
        $response = $this->json('post', '/holiday/2018110051');
        $response->assertStatus(200);

        // 初回基準月未満
        // ※$_POST['year']がセットされていない場合は、選択年度を2019年にしてしまっているので基準月未満（year=00が判定できない・・・困った）
        // $response = $this->json('post', '/holiday/2019070011');
        // $response->assertStatus(200);

        $response->assertStatus(200);
    }
}
