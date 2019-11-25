<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Library\BaseClass;
use App\Http\Controllers;
use App\Http\Controllers\CRUDController;
use DB;

use Illuminate\Http\UploadedFile;
use Tests\Feature\File;
use Illuminate\Support\Facades\Storage;


class ControllerTest extends TestCase
{
    public function testController_ButtonController()
    {

        $response = $this->json('get', '/all');
        $response->assertStatus(200);

        $response = $this->json('get', '/department1');
        $response->assertStatus(200);

        $response = $this->json('get', '/department2');
        $response->assertStatus(200);

        $response = $this->json('get', '/department3');
        $response->assertStatus(200);

        $response = $this->json('get', '/department4');
        $response->assertStatus(200);

        $response = $this->json('get', '/nyushabi2007');
        $response->assertStatus(200);

        $response = $this->json('get', '/nyushabi2014');
        $response->assertStatus(200);

        $response = $this->json('get', '/nyushabi2015');
        $response->assertStatus(200);

        $response = $this->json('get', '/nyushabi2016');
        $response->assertStatus(200);

        $response = $this->json('get', '/nyushabi2017');
        $response->assertStatus(200);

        $response = $this->json('get', '/nyushabi2018');
        $response->assertStatus(200);

        $response = $this->json('get', '/nyushabi2019');
        $response->assertStatus(200);

        // $response = $this->json('get', '/nyushabi2020');
        // $response->assertStatus(200);

        $response = $this->json('get', '/age20');
        $response->assertStatus(200);

        $response = $this->json('get', '/age30');
        $response->assertStatus(200);

        $response = $this->json('get', '/age40');
        $response->assertStatus(200);

        $response = $this->json('get', '/age50');
        $response->assertStatus(200);

        $response = $this->json('get', '/age60');
        $response->assertStatus(200);

        $response = $this->json('get', '/age_other');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month01');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month02');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month03');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month04');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month05');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month06');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month07');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month08');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month09');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month10');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month11');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month12');
        $response->assertStatus(200);

        $response = $this->json('get', '/retirement');
        $response->assertStatus(200);

        $response = $this->json('get', '/taishokubi2016');
        $response->assertStatus(200);

        $response = $this->json('get', '/taishokubi2017');
        $response->assertStatus(200);

        $response = $this->json('get', '/taishokubi2018');
        $response->assertStatus(200);

        $response = $this->json('get', '/taishokubi2019');
        $response->assertStatus(200);

        $response = $this->json('get', '/all_avg');
        $response->assertStatus(200);

        $response = $this->json('get', '/department_avg');
        $response->assertStatus(200);

        $response = $this->json('get', '/gender_avg');
        $response->assertStatus(200);

        $response = $this->json('get', '/all_count');
        $response->assertStatus(200);

        $response = $this->json('get', '/department_count');
        $response->assertStatus(200);

        $response = $this->json('get', '/gender_count');
        $response->assertStatus(200);

        $response = $this->json('get', '/age_count');
        $response->assertStatus(200);
    }

    public function testController_CRUDController()
    {

        $response = $this->json('post', '/add');
        $response->assertStatus(200);


        $response = $this->json('get', '/add2');
        $response->assertStatus(200);



        $response = $this->json('post', '/submit', [
            'post_url_create' => 'http://localhost/employee/public/',
            'top_scroll_top' => '0',
            'shain_cd' => '00',
            'shain_mei' => '新規登録',
            'shain_mei_kana' => '新規登録',
            'shain_mei_romaji' => 'shinkitouroku',
            'shain_mail' => '新規登録',
            'gender' => '男',
            'shain_zip_code' => null,
            'shain_jyusho' => '札幌市',
            'shain_jyusho_tatemono' => 'ハイツ',
            'shain_birthday' => '1990-01-01',
            'nyushabi' => 20110501,
            'seishain_tenkanbi' => null,
            'tensekibi' => null,
            'taishokubi' => null,
            'shain_keitai' => null,
            'shain_tel' => null,
            'koyohoken_bango' => null,
            'shakaihoken_bango' => null,
            'kisonenkin_bango' => null,
            'monthly_saraly' => null,
            'department' => '04',
            'name_card' => null,
            'id_card' => null,
            'fuyo_kazoku' => null,
            'test' => null,
            'remarks' => null,
        ]);

        $this->assertDatabaseHas('employees', ['shain_cd' => '00']);

        // 　挿入したデータをを削除する
        // \DB::table('employees')
        // ->where('shain_cd', '00')
        // ->delete();
             

        $response = $this->post('/show/00', [
            'url' => 'http://localhost/employee/public/',
            'scroll_top' => '0',
        ]);
        $response->assertStatus(200);


        $response = $this->post('/edit/00');
        $response->assertStatus(200);


        $response = $this->get('/edit2/00');
        $response->assertStatus(200);

        
        // 　挿入したデータをを削除する
        \DB::table('employees')
        ->where('shain_cd', '00')
        ->delete();
    }
}
