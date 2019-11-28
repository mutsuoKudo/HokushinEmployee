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



class CrudControllerTest extends TestCase
{

    public function testController_CRUDController()
    {

        $response = $this->json('post', '/add', [
            'url' => '/employee/public',
            'scroll_top' => '0',
        ]);
        $response->assertStatus(200);


        $response = $this->json('get', '/add2');
        $response->assertStatus(200);

        // 新規作成及び更新で使用するjpgファイル
        $path_jpg  = 'C:\xampp\htdocs\employee\storage\app\添付ファイルテスト①.jpg';
        $original_name_jpg  = '添付ファイルテスト①.jpg';
        $mime_type_jpg  = 'image/jpg';
        $size_jpg  = 600;
        $error_jpg   = null;
        $test_jpg   = true;
        $file_jpg = new UploadedFile($path_jpg, $original_name_jpg, $mime_type_jpg, $size_jpg, $error_jpg, $test_jpg);


        // 新規作成及び更新で使用するpngファイル
        $path_png  = 'C:\xampp\htdocs\employee\storage\app\添付ファイルテスト④.png';
        $original_name_png  = '添付ファイルテスト④.png';
        $mime_type_png  = 'image/png';
        $size_png  = 600;
        $error_png   = null;
        $test_png   = true;
        $file_png = new UploadedFile($path_png, $original_name_png, $mime_type_png, $size_png, $error_png, $test_png);

        // 新規作成及び更新で使用するgifファイル（エラー用）
        $path_gif  = 'C:\xampp\htdocs\employee\storage\app\添付ファイルテスト⑤.gif';
        $original_name_gif  = '添付ファイルテスト⑤.gif';
        $mime_type_gif  = 'image/gif';
        $size_gif  = 600;
        $error_gif  = null;
        $test_gif   = true;
        $file_gif = new UploadedFile($path_gif, $original_name_gif, $mime_type_gif, $size_gif, $error_gif, $test_gif);

        // 新規登録jpgテスト
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
            'pic' => $file_jpg,
            'remarks' => null,
        ]);

        $this->assertDatabaseHas('employees', ['shain_cd' => '00']);

        $time = date("Ymdhi");
        $file_name_jpg = $time . '_00_shinkitouroku.jpg';
        $this->assertDatabaseHas('employees', ['pic' => $file_name_jpg]);

        // 　挿入したデータをを削除する
        \DB::table('employees')
            ->where('shain_cd', '00')
            ->delete();


        // 新規登録pngテスト
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
            'pic' => $file_png,
            'remarks' => null,
        ]);

        $this->assertDatabaseHas('employees', ['shain_cd' => '00']);

        $time = date("Ymdhi");
        $file_name_png = $time . '_00_shinkitouroku.png';
        $this->assertDatabaseHas('employees', ['pic' => $file_name_png]);

        // 　挿入したデータをを削除する
        \DB::table('employees')
            ->where('shain_cd', '00')
            ->delete();

        // storageに保存した現物も削除
        $pic_file = glob('C:\xampp\htdocs\employee\public\storage\post_images\*');

        // ファイル名に社員コードが入っているものがあれば削除
        foreach ($pic_file as $p_file_id) {
            if (strpos($p_file_id, 'shinkitouroku')) {
                unlink($p_file_id);
            }
        }


        // 新規登録gif（エラー）テスト
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
            'pic' => $file_gif,
            'remarks' => null,
        ]);

        $this->assertDatabaseMissing('employees', ['shain_cd' => '00']);


        // 新規登録テスト(画像はアップロードしない)
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

        // 更新jpgテスト
        $this->json('patch', '/update/' . $id, [

            'shain_cd' => '00',
            'shain_mei' => 'update_jpg',
            'shain_mei_kana' => 'アップデート',
            'shain_mei_romaji' => 'update',
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
            'pic' => $file_jpg,
            'remarks' => null,
        ]);

        $this->assertDatabaseHas('employees', ['shain_cd' => '00']);
        $this->assertDatabaseHas('employees', ['shain_mei' => 'update_jpg']);

        $time = date("Ymdhi");
        $file_name_jpg_update = $time . '_00_update.jpg';
        $this->assertDatabaseHas('employees', ['pic' => $file_name_jpg_update]);


        // 更新pngテスト
        $this->json('patch', '/update/' . $id, [

            'shain_cd' => '00',
            'shain_mei' => 'update_png',
            'shain_mei_kana' => 'アップデート',
            'shain_mei_romaji' => 'update',
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
            'pic' => $file_png,
            'remarks' => null,
        ]);

        $this->assertDatabaseHas('employees', ['shain_cd' => '00']);
        $this->assertDatabaseHas('employees', ['shain_mei' => 'update_png']);

        $time = date("Ymdhi");
        $file_name_png_update = $time . '_00_update.png';
        $this->assertDatabaseHas('employees', ['pic' => $file_name_png_update]);

        // storageに保存した現物も削除
        $pic_file = glob('C:\xampp\htdocs\employee\public\storage\post_images\*');


        // 更新gif(エラー)テスト
        $this->json('patch', '/update/' . $id, [

            'shain_cd' => '00',
            'shain_mei' => 'update_gif',
            'shain_mei_kana' => 'アップデート',
            'shain_mei_romaji' => 'update',
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
            'pic' => $file_gif,
            'remarks' => null,
        ]);

        $this->assertDatabaseHas('employees', ['shain_cd' => '00']);
        $this->assertDatabaseHas('employees', ['shain_mei' => 'update_gif']);

        $time = date("Ymdhi");
        $file_name_gif_update = $time . '_00_update.gif';
        $this->assertDatabaseMissing('employees', ['pic' => $file_name_gif_update]);



        // 更新のテスト（画像アップロードしない）
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

        // ファイル名に社員コードが入っているものがあれば削除
        foreach ($pic_file as $p_file_id) {
            if (strpos($p_file_id, 'update')) {
                unlink($p_file_id);
            }
        }


        $response = $this->json('post', '/pic_delete/' . $id);
        $this->assertDatabaseMissing('employees', ['pic' => $file_name_png_update]);


        // 　挿入したデータをを削除する
        \DB::table('employees')
            ->where('shain_cd', '00')
            ->delete();

        var_dump('CrudControllerTest END');
    }
}
