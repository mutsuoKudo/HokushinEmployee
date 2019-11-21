<?php
// ドキュメントの画面遷移テスト
namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;



class DocumentTest extends DuskTestCase
{

    // ドキュメントテスト(通常の遷移をするリンク)

    public function testDisplay_normal_transition()
    {

        $this->browse(function ($browser) {
            $browser->visit('http://localhost/employee/public/login')
            ->assertSee('UserID')
            ->assertSee('Password')

            ->press('#navbarSupportedContent > ul > li:nth-child(1) > a');

            // 新しいウィンドウに切り替える
            $windows = collect($browser->driver->getWindowHandles());
            $browser->driver->switchTo()->window($windows->last());

            // 読み込みに少し時間がかかるのでpause
            $browser->pause('1000')
                ->assertPathIs('/employee/public/employee_doc/employee_doc.html')
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')
                ->screenshot('ドキュメント遷移成功' . date('Ymd-his'))


                // サイドバーのテスト（別ウィンドウにならないやつだけ）
                ->press('#mysidebar > li:nth-child(1) > a')

                ->assertSee('使用方法')
                ->screenshot('サイドバー（使用方法）' . date('Ymd-his'))

                ->back()
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')

                ->press('#mysidebar > li:nth-child(2) > a')
                ->assertSee('基本機能説明')
                ->screenshot('サイドバー（基本機能説明）' . date('Ymd-his'))

                ->back()
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')

                ->press('#mysidebar > li:nth-child(5) > a')
                ->assertSee('テーブル定義書')
                ->screenshot('サイドバー（テーブル定義書）' . date('Ymd-his'))

                ->back()
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')

                ->press('#mysidebar > li:nth-child(6) > a')
                ->assertSee('ファイル内容')
                ->screenshot('サイドバー（ファイル内容）' . date('Ymd-his'))

                ->back()
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')
                ->assertPathIs('/employee/public/employee_doc/employee_doc.html')

                // トップページのリンク
                ->press('#top_howto')
                ->assertSee('使用方法')
                ->screenshot('トップページリンク（使用方法）' . date('Ymd-his'))

                ->back()
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')

                ->press('#top_instructions')
                ->assertsee('基本機能説明')
                ->screenshot('トップページリンク（基本機能説明）' . date('Ymd-his'))

                ->back()
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')

                ->press('#top_sql_tbl')
                ->assertSee('テーブル定義書')
                ->screenshot('トップページリンク（テーブル定義書）' . date('Ymd-his'))

                ->back()
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')

                ->press('#top_file')
                ->assertSee('ファイル内容')
                ->screenshot('トップページリンク（ファイル内容）' . date('Ymd-his'))

                ->back()
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。');
        });
    }

    // ドキュメントテスト（別ウィンドウを開くリンク）
    public function testDisplay_transition_window_by()
    {


        $this->browse(function ($browser) {

            // サイドバーのテスト
            $browser->visit('/employee/public/employee_doc/employee_doc.html')
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')
                ->screenshot('トップページ' . date('Ymd-his'))

                // 取扱説明書
                ->press('#mysidebar > li:nth-child(3) > a');

            // 新しいウィンドウに切り替える
            $windows = collect($browser->driver->getWindowHandles());
            $browser->driver->switchTo()->window($windows->last());

            // pdf読み込みに少し時間がかかるのでpause
            $browser->pause('1000')
                ->assertPathIs('/employee/public/employee_doc/pdf/screen_manual.pdf')
                ->screenshot('サイドバー（取扱説明書）' . date('Ymd-his'))

                ->visit('/employee/public/employee_doc/employee_doc.html')
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')

                // 画面遷移図
                ->press('#mysidebar > li:nth-child(4) > a');

            $windows = collect($browser->driver->getWindowHandles());
            $browser->driver->switchTo()->window($windows->last());

            $browser->pause('1000')
                ->assertPathIs('/employee/public/employee_doc/screen_transition.htm')
                ->screenshot('サイドバー（画面遷移図）' . date('Ymd-his'))

                ->visit('/employee/public/employee_doc/employee_doc.html')
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')

                // テーブル定義
                ->press('#mysidebar > li:nth-child(5) > a')
                // employee
                ->click('#db_table1');

            $windows = collect($browser->driver->getWindowHandles());
            $browser->driver->switchTo()->window($windows->last());

            $browser->assertSee('テーブル情報')
                ->assertSee('hokushin_util')
                ->assertSee('employees')
                ->screenshot('サイドバー（テーブル情報①）' . date('Ymd-his'))

                ->visit('/employee/public/employee_doc/employee_doc.html')
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')

                // テーブル定義
                ->press('#mysidebar > li:nth-child(5) > a')
                // holidays
                ->click('#db_table2');

            $windows = collect($browser->driver->getWindowHandles());
            $browser->driver->switchTo()->window($windows->last());

            $browser->assertSee('テーブル情報')
                ->assertSee('hokushin_util')
                ->assertSee('holidays')
                ->screenshot('サイドバー（テーブル情報②）' . date('Ymd-his'))

                // トップページのリンク
                ->visit('/employee/public/employee_doc/employee_doc.html')
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')

                // 取扱説明書
                ->press('#top_screen_manual');

            // 新しいウィンドウに切り替える
            $windows = collect($browser->driver->getWindowHandles());
            $browser->driver->switchTo()->window($windows->last());

            // pdf読み込みに少し時間がかかるのでpause
            $browser->pause('1000')
                ->assertPathIs('/employee/public/employee_doc/pdf/screen_manual.pdf')
                ->screenshot('トップページリンク（取扱説明書）' . date('Ymd-his'))

                ->visit('/employee/public/employee_doc/employee_doc.html')
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')

                // 画面遷移図
                ->press('#top_screen_transition');

            // 新しいウィンドウに切り替える
            $windows = collect($browser->driver->getWindowHandles());
            $browser->driver->switchTo()->window($windows->last());

            $browser->pause('1000')
                ->assertPathIs('/employee/public/employee_doc/screen_transition.htm')
                ->screenshot('トップページリンク（画面遷移図）' . date('Ymd-his'))

                ->visit('/employee/public/employee_doc/employee_doc.html')
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')

                // テーブル定義
                ->press('#top_sql_tbl')
                // employee
                ->click('#db_table1');

            $windows = collect($browser->driver->getWindowHandles());
            $browser->driver->switchTo()->window($windows->last());

            $browser->assertSee('テーブル情報')
                ->assertSee('hokushin_util')
                ->assertSee('employees')
                ->screenshot('トップページリンク（テーブル情報①）' . date('Ymd-his'))

                ->visit('/employee/public/employee_doc/employee_doc.html')
                ->assertSee('社員情報・有給情報管理ツールドキュメントです。')

                // テーブル定義
                ->press('#top_sql_tbl')
                // holidays
                ->click('#db_table2');

            $windows = collect($browser->driver->getWindowHandles());
            $browser->driver->switchTo()->window($windows->last());

            $browser->assertSee('テーブル情報')
                ->assertSee('hokushin_util')
                ->assertSee('holidays')
                ->screenshot('トップページリンク（テーブル情報②）' . date('Ymd-his'));
        });
    }
}
