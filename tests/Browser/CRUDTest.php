<?php
// アプリ本体のCRUDテスト
namespace Tests\Browser;

use App\Employee;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CRUDTest extends DuskTestCase
{

    // ログインテスト
    public function testDisplay_login()
    {

        $this->browse(function ($browser) {
            $browser->visit('/employee/public/login')

                ->assertSee('UserID')
                ->type('userid', 'hokushin')
                ->type('password', 'hokushinpass')
                ->press('Login')
                ->assertPathIs('/employee/public/home')
                ->screenshot('ログイン成功' . date('Ymd-his'));
            // ->dump($browser)

        });
    }

    // ログアウトテスト
    public function testDisplay_logout()
    {

        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                ->assertSee('在籍者')
                ->press('#dropdownMenuButton_login_user')
                ->press('#navbarSupportedContent > ul > div > div > a')

                ->assertSee('UserID')
                ->assertSee('Password')

                ->assertPathIs('/employee/public/login')
                ->screenshot('ログアウト成功' . date('Ymd-his'));
            // ->dump($browser)

        });
    }

    // ログイン ※これ以降のテストはログイン後のものになるのでもう一度ログインしておく
    public function testDisplay_login2()
    {

        $this->browse(function ($browser) {
            $browser->visit('/employee/public/login')

                ->assertSee('UserID')
                ->type('userid', 'hokushin')
                ->type('password', 'hokushinpass')
                ->press('Login')
                ->assertPathIs('/employee/public/');
            // ->dump($browser)

        });
    }

    // 新規登録テスト（必須項目のみ入力）
    public function testDisplay_create_required()
    {


        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                ->press('新規作成')
                ->assertSee('新規登録')
                ->pause('1000')

                ->type('shain_cd', '00')
                ->type('shain_mei', '新規登録太郎')
                ->type('shain_mei_kana', 'シンキトウロクタロウ')
                ->type('shain_mei_romaji', 'Shinkitouroku Tarou')
                ->type('gender', '男')
                ->pause('1000')
                ->type('department', '04')
                ->pause('2000')
                ->press('登録')

                ->assertSee('新規登録完了')
                ->assertSee('新規登録太郎')
                ->assertSee('シンキトウロクタロウ')
                ->screenshot('新規登録成功（必須項目のみ入力）' . date('Ymd-his'));
            // ->dump($browser)

        });

        Employee::where('shain_mei', '新規登録太郎')->delete();
    }

    // 新規登録テスト（退職日以外全て入力）
    public function testDisplay_create_all()
    {
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                ->press('新規作成')
                ->assertSee('新規登録')
                ->pause('1000')

                ->type('shain_cd', '00')
                ->type('shain_mei', '新規登録太郎')
                ->type('shain_mei_kana', 'シンキトウロクタロウ')
                ->type('shain_mei_romaji', 'Shinkitouroku Tarou')
                ->type('shain_mail', 'shinkitouroku.tarou@hokusys.jp')
                ->type('gender', '男')
                ->pause('1000')

                ->type('shain_zip_code', '123-456')
                ->type('shain_jyusho', '札幌市中央区南3条東2丁目1番地')
                ->type('shain_jyusho_tatemono', 'サンシャインビル3階')
                ->type('shain_birthday', '1994-05-31')
                ->type('nyushabi', '2014-04-01')
                ->type('seishain_tenkanbi', '2014-10-01')
                ->type('tensekibi', '2015-06-01')
                // ->type('taishokubi', '')
                ->type('shain_keitai', '090-1234-5678')
                ->type('shain_tel', '0123-45-6789')
                ->type('koyohoken_bango', '1234-567891-2')
                ->pause('1000')

                ->type('shakaihoken_bango', '2')
                ->type('kisonenkin_bango', '1234-567891')
                ->type('monthly_saraly', '123456')
                ->type('department', '04')
                ->type('name_card', '1')
                ->type('id_card', '1')
                ->type('fuyo_kazoku', '1')
                ->type('test', '100')
                ->attach('pic', 'C:\xampp\htdocs\employee\tests\Browser\添付ファイルテスト①.jpg')
                ->type('remarks', '備考だよ')
                ->pause('2000')
                ->press('登録')

                ->assertSee('新規登録完了')
                ->assertSee('新規登録太郎')
                ->assertSee('シンキトウロクタロウ')
                ->screenshot('新規登録成功（退職日以外全て入力）' . date('Ymd-his'))
                ->press('詳細')
                ->screenshot('新規登録データ確認①' . date('Ymd-his'));
            // ->dump($browser)

        });

        Employee::where('shain_mei', '新規登録太郎')->delete();
    }

    // 新規登録テスト（退職日含めた全て入力）
    public function testDisplay_create_all_retirement()
    {
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                ->press('新規作成')
                ->assertSee('新規登録')
                ->pause('1000')

                ->type('shain_cd', '00')
                ->type('shain_mei', '新規登録太郎')
                ->type('shain_mei_kana', 'シンキトウロクタロウ')
                ->type('shain_mei_romaji', 'Shinkitouroku Tarou')
                ->type('shain_mail', 'shinkitouroku.tarou@hokusys.jp')
                ->type('gender', '男')
                ->pause('1000')

                ->type('shain_zip_code', '123-456')
                ->type('shain_jyusho', '札幌市中央区南3条東2丁目1番地')
                ->type('shain_jyusho_tatemono', 'サンシャインビル3階')
                ->type('shain_birthday', '1994-05-31')
                ->type('nyushabi', '2014-04-01')
                ->type('seishain_tenkanbi', '2014-10-01')
                ->type('tensekibi', '2015-06-01')
                ->type('taishokubi', '2019-12-31')
                ->type('shain_keitai', '090-1234-5678')
                ->type('shain_tel', '0123-45-6789')
                ->type('koyohoken_bango', '1234-567891-2')
                ->pause('1000')

                ->type('shakaihoken_bango', '2')
                ->type('kisonenkin_bango', '1234-567891')
                ->type('monthly_saraly', '123456')
                ->type('department', '04')
                ->type('name_card', '1')
                ->type('id_card', '1')
                ->type('fuyo_kazoku', '1')
                ->type('test', '100')
                ->attach('pic', 'C:\xampp\htdocs\employee\tests\Browser\添付ファイルテスト①.jpg')
                ->type('remarks', '備考だよ')
                ->pause('2000')
                ->press('登録')

                ->assertSee('新規登録完了')
                ->screenshot('新規登録成功（退職日含めた全て入力）' . date('Ymd-his'))

                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > form:nth-child(6) > input.mr-2.mt-1.function-button.table_reset')
                ->assertSee('新規登録太郎')
                ->assertSee('シンキトウロクタロウ')

                ->press('詳細')
                ->screenshot('新規登録データ確認（退職日含めた全て入力）①' . date('Ymd-his'));
            // ->dump($browser)

        });

        Employee::where('shain_mei', '新規登録太郎')->delete();
    }

    // 新規登録テスト（必須項目のみ+添付ファイルがJPGのとき）
    public function testDisplay_create_required_JPG()
    {
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                ->press('新規作成')
                ->assertSee('新規登録')
                ->pause('1000')

                ->type('shain_cd', '00')
                ->type('shain_mei', '新規登録太郎')
                ->type('shain_mei_kana', 'シンキトウロクタロウ')
                ->type('shain_mei_romaji', 'Shinkitouroku Tarou')
                ->type('gender', '男')
                ->type('department', '04')
                ->attach('pic', 'C:\xampp\htdocs\employee\tests\Browser\添付ファイルテスト②.JPG')
                ->pause('2000')
                ->press('登録')

                ->assertSee('新規登録完了')
                ->assertSee('新規登録太郎')
                ->assertSee('シンキトウロクタロウ')
                ->screenshot('新規登録成功（必須項目+JPG）' . date('Ymd-his'));
            // ->dump($browser)

        });

        Employee::where('shain_mei', '新規登録太郎')->delete();
    }

    // 新規登録テスト（必須項目のみ+添付ファイルがjpegのとき）
    public function testDisplay_create_required_jpeg()
    {
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                ->press('新規作成')
                ->assertSee('新規登録')
                ->pause('1000')

                ->type('shain_cd', '00')
                ->type('shain_mei', '新規登録太郎')
                ->type('shain_mei_kana', 'シンキトウロクタロウ')
                ->type('shain_mei_romaji', 'Shinkitouroku Tarou')
                ->type('gender', '男')
                ->type('department', '04')
                ->attach('pic', 'C:\xampp\htdocs\employee\tests\Browser\添付ファイルテスト③.jpeg')
                ->pause('2000')
                ->press('登録')

                ->assertSee('新規登録完了')
                ->assertSee('新規登録太郎')
                ->assertSee('シンキトウロクタロウ')
                ->screenshot('新規登録成功（必須項目+jpeg）' . date('Ymd-his'));
            // ->dump($browser)

        });

        Employee::where('shain_mei', '新規登録太郎')->delete();
    }

    // 新規登録テスト（必須項目のみ+添付ファイルがpngのとき）
    public function testDisplay_create_required_png()
    {
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                ->press('新規作成')
                ->assertSee('新規登録')
                ->pause('1000')

                ->type('shain_cd', '00')
                ->type('shain_mei', '新規登録太郎')
                ->type('shain_mei_kana', 'シンキトウロクタロウ')
                ->type('shain_mei_romaji', 'Shinkitouroku Tarou')
                ->type('gender', '男')
                ->type('department', '04')
                ->attach('pic', 'C:\xampp\htdocs\employee\tests\Browser\添付ファイルテスト④.png')
                ->pause('2000')
                ->press('登録')

                ->assertSee('新規登録完了')
                ->assertSee('新規登録太郎')
                ->assertSee('シンキトウロクタロウ')
                ->screenshot('新規登録成功（必須項目+png）' . date('Ymd-his'));
            // ->dump($browser)

        });

        Employee::where('shain_mei', '新規登録太郎')->delete();
    }

    // 新規登録テスト（必須項目のみ+添付ファイルがjpg/png以外のとき）
    public function testDisplay_create_required_gif()
    {
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                ->press('新規作成')
                ->assertSee('新規登録')
                ->pause('1000')

                ->type('shain_cd', '00')
                ->type('shain_mei', '新規登録太郎')
                ->type('shain_mei_kana', 'シンキトウロクタロウ')
                ->type('shain_mei_romaji', 'Shinkitouroku Tarou')
                ->type('gender', '男')
                ->type('department', '04')
                ->attach('pic', 'C:\xampp\htdocs\employee\tests\Browser\添付ファイルテスト⑤.gif')
                ->pause('2000')
                ->press('登録')

                ->assertSee('写真の拡張子がjpg・png以外だったので新規登録できませんでした')
                ->screenshot('新規登録失敗（必須項目+gif）' . date('Ymd-his'));
            // ->dump($browser)

        });

        Employee::where('shain_mei', '新規登録太郎')->delete();
    }


    // 編集テスト（必須項目のみ入力してあったデータを上書きで全て編集）
    public function testDisplay_edit_all()
    {
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                ->press('新規作成')
                ->assertSee('新規登録')
                ->pause('1000')

                ->type('shain_cd', '00')
                ->type('shain_mei', '新規登録太郎')
                ->type('shain_mei_kana', 'シンキトウロクタロウ')
                ->type('shain_mei_romaji', 'Shinkitouroku Tarou')
                ->type('gender', '男')
                ->type('department', '02')
                ->pause('2000')
                ->press('登録')

                ->assertSee('新規登録太郎')
                ->assertSee('シンキトウロクタロウ')
                ->screenshot('編集用データ作成' . date('Ymd-his'))

                ->press('詳細')
                ->assertSee('新規登録太郎')
                ->pause('1000')

                ->press('#app > main > div > div > div:nth-child(1) > div > div > form:nth-child(2) > button')
                ->assertSee('新規登録太郎')
                ->pause('1000')

                ->type('shain_mei', '編集太郎')
                ->type('shain_mei_kana', 'ヘンシュウタロウ')
                ->type('shain_mei_romaji', 'Hensyuu Tarou')
                ->type('shain_mail', 'hensyuu.tarou@hokusys.jp')
                ->type('gender', '男')
                ->pause('1000')

                ->type('shain_zip_code', '123-456')
                ->type('shain_jyusho', '札幌市中央区南3条東2丁目1番地')
                ->type('shain_jyusho_tatemono', 'サンシャインビル3階')
                ->type('shain_birthday', '1994-05-31')
                ->type('nyushabi', '2014-04-01')
                ->type('seishain_tenkanbi', '2014-10-01')
                ->type('tensekibi', '2015-06-01')
                // ->type('taishokubi', '')
                ->type('shain_keitai', '090-1234-5678')
                ->type('shain_tel', '0123-45-6789')
                ->type('koyohoken_bango', '1234-567891-2')
                ->pause('1000')

                ->type('shakaihoken_bango', '2')
                ->type('kisonenkin_bango', '1234-567891')
                ->type('monthly_saraly', '123456')
                ->type('department', '04')
                ->type('name_card', '1')
                ->type('id_card', '1')
                ->type('fuyo_kazoku', '1')
                ->type('test', '100')
                ->attach('pic', 'C:\xampp\htdocs\employee\tests\Browser\添付ファイルテスト①.jpg')
                ->type('remarks', '備考だよ')
                ->pause('2000')
                ->press('更新')

                ->assertSee('更新完了')
                ->assertSee('編集太郎')
                ->screenshot('更新成功（全データ更新）' . date('Ymd-his'));
            // ->dump($browser)

        });

        Employee::where('shain_mei', '編集太郎')->delete();
    }


    // 編集テスト（必須項目のみ+添付ファイルをJPGで更新）
    public function testDisplay_edit_JPG()
    {
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                ->press('新規作成')
                ->assertSee('新規登録')
                ->pause('1000')

                ->type('shain_cd', '00')
                ->type('shain_mei', '新規登録太郎')
                ->type('shain_mei_kana', 'シンキトウロクタロウ')
                ->type('shain_mei_romaji', 'Shinkitouroku Tarou')
                ->type('gender', '男')
                ->type('department', '02')
                ->pause('2000')
                ->press('登録')

                ->assertSee('新規登録完了')

                ->press('詳細')
                ->assertSee('新規登録太郎')
                ->pause('1000')

                ->press('#app > main > div > div > div:nth-child(1) > div > div > form:nth-child(2) > button')
                ->assertSee('新規登録太郎')
                ->pause('1000')

                ->type('shain_mei', '編集太郎')
                ->type('shain_mei_kana', 'ヘンシュウタロウ')
                ->attach('pic', 'C:\xampp\htdocs\employee\tests\Browser\添付ファイルテスト②.JPG')
                ->pause('2000')
                ->press('更新')

                ->assertSee('更新完了')
                ->assertSee('編集太郎')
                ->assertSee('ヘンシュウタロウ')
                ->screenshot('編集成功（JPG）' . date('Ymd-his'));
            // ->dump($browser)

        });

        Employee::where('shain_mei', '編集太郎')->delete();
    }

    // 編集テスト（必須項目のみ+添付ファイルをjpegで更新）
    public function testDisplay_edit_jpeg()
    {
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                ->press('新規作成')
                ->assertSee('新規登録')
                ->pause('1000')

                ->type('shain_cd', '00')
                ->type('shain_mei', '新規登録太郎')
                ->type('shain_mei_kana', 'シンキトウロクタロウ')
                ->type('shain_mei_romaji', 'Shinkitouroku Tarou')
                ->type('gender', '男')
                ->type('department', '02')
                ->pause('2000')
                ->press('登録')

                ->assertSee('新規登録完了')

                ->press('詳細')
                ->assertSee('新規登録太郎')
                ->pause('1000')

                ->press('#app > main > div > div > div:nth-child(1) > div > div > form:nth-child(2) > button')
                ->assertSee('新規登録太郎')
                ->pause('1000')

                ->type('shain_mei', '編集太郎')
                ->type('shain_mei_kana', 'ヘンシュウタロウ')
                ->attach('pic', 'C:\xampp\htdocs\employee\tests\Browser\添付ファイルテスト③.jpeg')
                ->pause('2000')
                ->press('更新')

                ->assertSee('更新完了')
                ->assertSee('編集太郎')
                ->assertSee('ヘンシュウタロウ')
                ->screenshot('編集成功（jpeg）' . date('Ymd-his'));
            // ->dump($browser)

        });

        Employee::where('shain_mei', '編集太郎')->delete();
    }

    // 編集テスト（必須項目のみ+添付ファイルをpngで更新）
    public function testDisplay_edit_png()
    {
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                ->press('新規作成')
                ->assertSee('新規登録')
                ->pause('1000')

                ->type('shain_cd', '00')
                ->type('shain_mei', '新規登録太郎')
                ->type('shain_mei_kana', 'シンキトウロクタロウ')
                ->type('shain_mei_romaji', 'Shinkitouroku Tarou')
                ->type('gender', '男')
                ->type('department', '02')
                ->pause('2000')
                ->press('登録')

                ->assertSee('新規登録完了')

                ->press('詳細')
                ->assertSee('新規登録太郎')
                ->pause('1000')

                ->press('#app > main > div > div > div:nth-child(1) > div > div > form:nth-child(2) > button')
                ->assertSee('新規登録太郎')
                ->pause('1000')

                ->type('shain_mei', '編集太郎')
                ->type('shain_mei_kana', 'ヘンシュウタロウ')
                ->attach('pic', 'C:\xampp\htdocs\employee\tests\Browser\添付ファイルテスト④.png')
                ->pause('2000')
                ->press('更新')

                ->assertSee('更新完了')
                ->assertSee('編集太郎')
                ->assertSee('ヘンシュウタロウ')
                ->screenshot('編集成功（png）' . date('Ymd-his'));
            // ->dump($browser)

        });

        Employee::where('shain_mei', '編集太郎')->delete();
    }

    // 削除ボタン（新規登録→削除）
    public function testDisplay_delete()
    {
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                ->press('新規作成')
                ->assertSee('新規登録')
                ->pause('1000')

                ->type('shain_cd', '00')
                ->type('shain_mei', '新規登録太郎')
                ->type('shain_mei_kana', 'シンキトウロクタロウ')
                ->type('shain_mei_romaji', 'Shinkitouroku Tarou')
                ->type('gender', '男')
                ->type('department', '02')
                ->pause('2000')
                ->press('登録')

                ->assertSee('新規登録完了')

                ->press('削除')
                ->acceptDialog()
                ->assertSee('削除完了')

                ->screenshot('削除成功' . date('Ymd-his'));
            // ->dump($browser)

        });
    }

    // 削除ボタン（新規登録→削除キャンセル）
    public function testDisplay_delete_cancel()
    {
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                ->press('新規作成')
                ->assertSee('新規登録')
                ->pause('1000')

                ->type('shain_cd', '00')
                ->type('shain_mei', '新規登録太郎')
                ->type('shain_mei_kana', 'シンキトウロクタロウ')
                ->type('shain_mei_romaji', 'Shinkitouroku Tarou')
                ->type('gender', '男')
                ->type('department', '02')
                ->pause('2000')
                ->press('登録')

                ->assertSee('新規登録完了')

                ->press('削除')
                ->dismissDialog()
                ->acceptDialog()
                ->assertSee('新規登録太郎')

                ->screenshot('削除キャンセル' . date('Ymd-his'));
            // ->dump($browser)

        });

        Employee::where('shain_mei', '新規登録太郎')->delete();

        // 写真が入っているフォルダのファイルをすべて取得
        $pic_file = glob('C:\xampp\htdocs\employee\public\storage\post_images\*');

        // ファイル名にいま保存しようとしている社員コードと社員名（ローマ字）がすでに入っていたら削除
        // DBはupdateで上書きしちゃうのでわざわざ消さなくてもおｋ
        foreach ($pic_file as $p_file_id) {
            if (strpos($p_file_id, '00_Hensyuu Tarou.jpg')) {
                unlink($p_file_id);
            }
        }
    }
}
