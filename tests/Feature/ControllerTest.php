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
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\UpdatePost;



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


        $response = $this->post('/show/00', [
            'url' => 'http://localhost/employee/public/',
            'scroll_top' => '0',
        ]);
        $response->assertStatus(200);


        $response = $this->post('/edit/00');
        $response->assertStatus(200);


        $response = $this->get('/edit2/00');
        $response->assertStatus(200);

        $id = '00';

        $response = $this->json('patch', '/update/' . $id, [
            'id' => '00',
            'shain_cd' => '00',
            'shain_mei' => 'アップデート',
            'shain_mei_kana' => 'アップデート',
            'shain_mei_romaji' => 'アップデート',
            'shain_mail' => 'アップデート',
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

        $this->assertDatabaseHas('employees', ['shain_mei' => 'アップデート']);



        // shain_cd 00に写真のパスを保存（パスのみの保存でstorageに現物の保存はしていません）
        \DB::table('employees')
            ->where('shain_cd', '00')
            ->update(['pic' => '201911270000_00_update.jpg']);
        // 保存されたかチェック
        $this->assertDatabaseHas('employees', ['pic' => '201911270000_00_update.jpg']);


        $response = $this->json('post', '/pic_delete/' . $id);
        $this->assertDatabaseMissing('employees', ['pic' => '201911270000_00_update.jpg']);

        // 　挿入したデータをを削除する
        // \DB::table('employees')
        //     ->where('shain_cd', '00')
        //     ->delete();


        // Storage::disk('local')->put('file.png', 'Contents');
        // $file = Storage::get('file.png');
        // 作成した画像を移動
        \Storage::disk('local')->put('file.png', 'Contents');
        // $image = \Storage::disk('local')->get('storage\app\file.png');
        $image = \Storage::get('file.png');;
        $data = 'data: image/png;base64,' . base64_encode($image);


        // Storage::fake('local');

        // $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->json('patch', '/update/' . $id, [
            'shain_mei' => 'update_pic',
            'pic' => $data,
        ]);


        // ファイルが保存されたことをアサートする
        // Storage::disk('local')->assertExists($file->hashName());

        // ファイルが存在しないことをアサートする
        // Storage::disk('local')->assertMissing('missing.jpg');


        // Storage::disk('images')->assertExists('image/avatar.jpg');


        // $this->assertDatabaseHas('employees', ['shain_mei' => '写真保存太郎']);
        // $response->assertStatus(200);


        // 　挿入したデータをを削除する
        // \DB::table('employees')
        //     ->where('shain_cd', '00')
        //     ->delete();
    }
}
// 
