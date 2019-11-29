<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HolidaycontrollerTest extends TestCase
{

    public function testController_HolidaycontrollerTest()
    {

        // 勤続年数1年未満
        $response = $this->json('post', '/holiday/2018100031', [
            'year' => '2019',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',

        ]);
        $response->assertStatus(200);

        // 勤続年数12年
        $response = $this->json('post', '/holiday/202012', [
            'year' => '2019',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',

        ]);
        $response->assertStatus(200);

        // 初回基準月未満
        $response = $this->json('post', '/holiday/2019070011', [
            'year' => '00',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',
        ]);
        $response->assertSee('初回基準月未満');
        $response->assertStatus(200);

        // 初回基準月未満で退社
        $response = $this->json('post', '/holiday/2018110051', [
            'year' => '00',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',
        ]);
        $response->assertSee('初回基準月未満');
        $response->assertStatus(200);

        // 準社員
        $response = $this->json('post', '/holiday/202013', [
            'year' => '2019',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',
        ]);
        $response->assertSee('※準社員には有給がつきません。');
        $response->assertStatus(200);

        // 未消化アラート(基準年月＝現在年月・取得日数5日)
        $response = $this->json('post', '/holiday/202016', [
            'year' => '2018',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',
        ]);
        $response->assertDontSee('最低5日取得する必要があります！');
        $response->assertStatus(200);

        // 未消化アラート（勤続年数12年・取得日数0日）
        $response = $this->json('post', '/holiday/202018', [
            'year' => '2018',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',
        ]);
        $response->assertSee('最低5日取得する必要があります！');
        $response->assertStatus(200);

        // 残数僅少アラート(残り0日)
        $response = $this->json('post', '/holiday/202014', [
            'year' => '2018',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',
        ]);
        $response->assertSee('※有給なくなりました！');
        $response->assertStatus(200);

        // 残数僅少アラート(残り2日)
        $response = $this->json('post', '/holiday/202017', [
            'year' => '2018',
            'top_url' => '/employee/public',
            'scroll_top2' => '0',
        ]);
        $response->assertSee('※有給残り僅かです！');
        $response->assertStatus(200);


        $response = $this->json('get', '/mishouka');
        $response->assertSee('社員コード');
        $response->assertSee('202018');
        $response->assertStatus(200);
        
        $response = $this->json('get', '/zansu_kinshou');
        $response->assertSee('社員コード');
        $response->assertSee('202014');
        $response->assertSee('202017');
        $response->assertStatus(200);

        var_dump('HolidayControllerTest END');
    }
}
