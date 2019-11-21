<?php
// アプリ本体の画面遷移テスト
namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


// 同じ名前のボタンが2個以上有る場合はセレクターで指定
// 1つの場合はそのままボタン名？を指定
class DisplayTest extends DuskTestCase
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

    // 新規作成ボタンクリック→新規作成ページ表示
    public function testDisplay_create()
    {

        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->assertSee('その他の機能')
                ->assertSee('在籍者')
                ->screenshot('トップページから新規作成' . date('Ymd-his'))
                ->press('新規作成')
                ->screenshot('新規登録' . date('Ymd-his'))
                ->press('#app > main > div > div.mb-5 > div > div > div > form > button')
                // ※新規登録画面には2つトップに戻るボタンがあるので繰り返す
                ->screenshot('新規登録からトップに戻った①' . date('Ymd-his'))

                ->press('新規作成')
                ->press('#app > main > div > div.mt-3.text-center > form > button')
                ->screenshot('新規登録からトップに戻った②' . date('Ymd-his'));
        });
    }

    // 詳細ボタンクリック→詳細ページ表示
    public function testDisplay_show()
    {

        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->assertSee('その他の機能')
                ->assertSee('在籍者')
                ->screenshot('トップページから詳細' . date('Ymd-his'))
                ->press('詳細')
                ->screenshot('詳細' . date('Ymd-his'))
                // ※詳細画面には2つトップに戻るボタンがあるので繰り返す
                ->press('#app > main > div > div > div:nth-child(1) > div > div > form:nth-child(1) > button')
                ->screenshot('詳細からトップに戻った①' . date('Ymd-his'))

                ->press('詳細')
                ->press('#app > main > div > div > div.panel.panel-default.mt-5 > div.mt-5.text-center.mb-5 > form:nth-child(1) > button')
                ->screenshot('詳細からトップに戻った②' . date('Ymd-his'));
        });
    }

    // 詳細ページ→有給取得日明細
    public function testDisplay_holiday()
    {

        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('詳細')
                ->assertSee('詳細表示')
                ->screenshot('詳細から有給取得日明細' . date('Ymd-his'))
                ->press('有給取得日明細')
                ->screenshot('有給取得日明細' . date('Ymd-his'))

                // ※有給取得日明細画面には2つトップに戻るボタンがあるので繰り返す
                // 画面上部にあるトップに戻るボタン
                ->press('#app > main > div > div > div:nth-child(1) > div > div > form:nth-child(1) > button')
                ->screenshot('有給取得日明細からトップに戻った①' . date('Ymd-his'))

                // 画面下部にあるトップに戻るボタン
                ->press('詳細')
                ->press('#app > main > div > div > div.panel.panel-default.mt-5 > div.mt-5.text-center > form:nth-child(1) > button')
                ->screenshot('有給取得日明細からトップに戻った②' . date('Ymd-his'))

                // ※有給取得日明細画面には2つ詳細画面に戻るボタンがあるので繰り返す
                // 画面上部にある詳細画面に戻るボタン
                ->press('詳細')
                ->press('#app > main > div > div > div:nth-child(1) > div > div > form:nth-child(2) > button')
                ->screenshot('有給取得日明細から詳細に戻った②' . date('Ymd-his'))

                // 画面下部にある詳細画面に戻るボタン
                ->press('詳細')
                ->press('#app > main > div > div > div.panel.panel-default.mt-5 > div.mt-5.text-center > form:nth-child(2) > button')
                ->screenshot('有給取得日明細から詳細に戻った②' . date('Ymd-his'));
        });
    }

    // 詳細ページ→編集
    public function testDisplay_edit()
    {

        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('詳細')
                ->assertSee('詳細表示')
                ->screenshot('詳細から編集' . date('Ymd-his'))

                // 編集ボタン、2つあるので繰り返す
                // 画面上部にある編集ボタン
                ->press('#app > main > div > div > div:nth-child(1) > div > div > form:nth-child(2) > button')
                ->screenshot('編集①' . date('Ymd-his'))

                // ※編集画面には2つトップに戻るボタンがあるので繰り返す
                // 画面上部のトップに戻るボタンを押すパターン
                ->press('#app > main > div > div > div:nth-child(1) > div > div > form:nth-child(1) > button')
                ->screenshot('編集①からトップに戻った①' . date('Ymd-his'))

                // 画面下部のトップに戻るボタンを押すパターン
                // 詳細ボタン→編集ボタン→ 画面下部のトップに戻るボタン
                ->press('詳細')
                ->press('#app > main > div > div > div:nth-child(1) > div > div > form:nth-child(2) > button')
                ->press('#app > main > div > div > div.mt-3.p-0.text-center > form:nth-child(1) > button')

                ->screenshot('編集①からトップに戻った②' . date('Ymd-his'))


                // 編集画面には2つの詳細画面に戻るボタンがあるので繰り返す
                // 画面上部の詳細画面に戻るボタンを押すパターン
                // 詳細ボタン→編集ボタン→ 画面上部の詳細画面に戻るボタン
                ->press('詳細')
                ->press('#app > main > div > div > div:nth-child(1) > div > div > form:nth-child(2) > button')
                ->press('#app > main > div > div > div:nth-child(1) > div > div > form:nth-child(2) > button')

                ->screenshot('編集①から詳細に戻った①' . date('Ymd-his'))

                // 画面下部の詳細画面に戻るボタンを押すパターン
                // 編集ボタン→ 画面下部の詳細画面に戻るボタン
                ->press('#app > main > div > div > div:nth-child(1) > div > div > form:nth-child(2) > button')
                ->press('#app > main > div > div > div.mt-3.p-0.text-center > form:nth-child(2) > button')

                ->screenshot('編集①から詳細に戻った②' . date('Ymd-his'))




                // 画面下部にある編集ボタン
                ->press('#app > main > div > div > div.panel.panel-default.mt-5 > div.mt-5.text-center.mb-5 > form:nth-child(2) > button')
                ->screenshot('編集②' . date('Ymd-his'))

                // ※編集画面には2つトップに戻るボタンがあるので繰り返す
                // 画面上部のトップに戻るボタンを押すパターン
                ->press('#app > main > div > div > div:nth-child(1) > div > div > form:nth-child(1) > button')
                ->screenshot('編集②からトップに戻った①' . date('Ymd-his'))

                // 画面下部のトップに戻るボタンを押すパターン
                // 詳細ボタン→編集ボタン→ 画面下部のトップに戻るボタン
                ->press('詳細')
                ->press('#app > main > div > div > div.panel.panel-default.mt-5 > div.mt-5.text-center.mb-5 > form:nth-child(2) > button')
                ->press('#app > main > div > div > div.mt-3.p-0.text-center > form:nth-child(1) > button')
                ->screenshot('編集②からトップに戻った②' . date('Ymd-his'))

                // 編集画面には2つの詳細画面に戻るボタンがあるので繰り返す
                // 画面上部の詳細画面に戻るボタンを押すパターン
                // 詳細ボタン→編集ボタン→ 画面上部のトップに戻るボタン
                ->press('詳細')
                ->press('#app > main > div > div > div.panel.panel-default.mt-5 > div.mt-5.text-center.mb-5 > form:nth-child(2) > button')
                ->press('#app > main > div > div > div:nth-child(1) > div > div > form:nth-child(2) > button')
                ->screenshot('編集②から詳細に戻った①' . date('Ymd-his'))

                // 画面下部の詳細画面に戻るボタンを押すパターン
                // 編集ボタン→ 画面下部のトップに戻るボタン
                ->press('#app > main > div > div > div.panel.panel-default.mt-5 > div.mt-5.text-center.mb-5 > form:nth-child(2) > button')
                ->press('#app > main > div > div > div.mt-3.p-0.text-center > form:nth-child(2) > button')
                ->screenshot('編集②から詳細に戻った②' . date('Ymd-his'));
        });
    }





    // 在籍者ボタン
    public function testDisplay_all()
    { 
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > form:nth-child(1) > input')
                ->assertSee('在籍者')
                ->screenshot('在籍者ボタンクリック' . date('Ymd-his'));
        });
    }

    // 部門別ボタン
    public function testDisplay_department()
    { 
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('#dropdownMenuButton_department')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(1) > input.mr-2.mt-1.table_reset')
                ->assertSee('代表取締役')
                ->screenshot('部門別（代表取締役）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_department')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(2) > input.mr-2.mt-1.table_reset')
                ->assertSee('管理部')
                ->screenshot('部門別（管理部）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_department')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(3) > input.mr-2.mt-1.table_reset')
                ->assertSee('営業部')
                ->screenshot('部門別（営業部）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_department')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(4) > input.mr-2.mt-1.table_reset')
                ->assertSee('システム開発部')
                ->screenshot('部門別（システム開発部）ボタンクリック' . date('Ymd-his'));
        });
    }

    // 入社年別ボタン
    public function testDisplay_nyushabi()
    { 
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('#dropdownMenuButton_nyushabi')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(1) > input.mr-2.mt-1.table_reset')
                ->assertSee('2007年入社')
                ->screenshot('入社年別（2007年）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_nyushabi')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(2) > input.mr-2.mt-1.table_reset')
                ->assertSee('2014年入社')
                ->screenshot('入社年別（2014年）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_nyushabi')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(3) > input.mr-2.mt-1.table_reset')
                ->assertSee('2016年入社')
                ->screenshot('入社年別（2016年）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_nyushabi')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(4) > input.mr-2.mt-1.table_reset')
                ->assertSee('2017年入社')
                ->screenshot('入社年別（2017年）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_nyushabi')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(5) > input.mr-2.mt-1.table_reset')
                ->assertSee('2018年入社')
                ->screenshot('入社年別（2018年）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_nyushabi')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(6) > input.mr-2.mt-1.table_reset')
                ->assertSee('2019年入社')
                ->screenshot('入社年別（2019年）ボタンクリック' . date('Ymd-his'));


        });
    }

    // 年代別ボタン
    public function testDisplay_age()
    { 
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('#dropdownMenuButton_age')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(1) > input.mr-2.mt-1.table_reset')
                ->assertSee('20代')
                ->screenshot('年代別（20代）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_age')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(2) > input.mr-2.mt-1.table_reset')
                ->assertSee('30代')
                ->screenshot('年代別（30代）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_age')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(3) > input.mr-2.mt-1.table_reset')
                ->assertSee('40代')
                ->screenshot('年代別（40代）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_age')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(4) > input.mr-2.mt-1.table_reset')
                ->assertSee('50代')
                ->screenshot('年代別（50代）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_age')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(5) > input.mr-2.mt-1.table_reset')
                ->assertSee('60代')
                ->screenshot('年代別（60代）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_age')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(6) > input.mr-2.mt-1.table_reset')
                ->assertSee('その他の年代')
                ->screenshot('年代別（その他の年代）ボタンクリック' . date('Ymd-his'));

        });
    }

    // 有給基準月別ボタン
    public function testDisplay_kijun_month()
    { 
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('#dropdownMenuButton_kijun_month')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(1) > input.mr-2.mt-1.table_reset')
                ->assertSee('1月')
                ->screenshot('有給基準月別（1月）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_kijun_month')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(2) > input.mr-2.mt-1.table_reset')
                ->assertSee('2月')
                ->screenshot('有給基準月別（2月）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_kijun_month')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(3) > input.mr-2.mt-1.table_reset')
                ->assertSee('3月')
                ->screenshot('有給基準月別（3月）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_kijun_month')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(4) > input.mr-2.mt-1.table_reset')
                ->assertSee('4月')
                ->screenshot('有給基準月別（4月）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_kijun_month')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(5) > input.mr-2.mt-1.table_reset')
                ->assertSee('5月')
                ->screenshot('有給基準月別（5月）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_kijun_month')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(6) > input.mr-2.mt-1.table_reset')
                ->assertSee('6月')
                ->screenshot('有給基準月別（6月）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_kijun_month')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(7) > input.mr-2.mt-1.table_reset')
                ->assertSee('7月')
                ->screenshot('有給基準月別（7月）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_kijun_month')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(8) > input.mr-2.mt-1.table_reset')
                ->assertSee('8月')
                ->screenshot('有給基準月別（8月）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_kijun_month')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(9) > input.mr-2.mt-1.table_reset')
                ->assertSee('9月')
                ->screenshot('有給基準月別（9月）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_kijun_month')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(10) > input.mr-2.mt-1.table_reset')
                ->assertSee('10月')
                ->screenshot('有給基準月別（10月）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_kijun_month')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(11) > input.mr-2.mt-1.table_reset')
                ->assertSee('11月')
                ->screenshot('有給基準月別（11月）ボタンクリック' . date('Ymd-his'))

                ->press('#dropdownMenuButton_kijun_month')
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > div.dropdown.open.show > div > form:nth-child(12) > input.mr-2.mt-1.table_reset')
                ->assertSee('12月')
                ->screenshot('有給基準月別（12月）ボタンクリック' . date('Ymd-his'));

            });
    }

    // 退職者ボタン
    public function testDisplay_retirement()
    { 
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(2) > form:nth-child(6) > input.mr-2.mt-1.function-button.table_reset')
                ->assertSee('退職者')
                ->screenshot('退職者ボタンクリック' . date('Ymd-his'));

            });
    }

    // 未消化アラートボタン
    public function testDisplay_mishouka()
    { 
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(3) > form:nth-child(1) > input.mr-2.mt-1.function-button.table_reset')
                ->assertSee('未消化アラート対象者')
                ->screenshot('未消化アラートボタンクリック' . date('Ymd-his'));

            });
    }

    // 残数僅少アラートボタン
    public function testDisplay_zansu_kinshou()
    { 
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('#app > main > div > div > div:nth-child(1) > div > div:nth-child(3) > form:nth-child(2) > input.mr-2.mt-1.function-button.table_reset')
                ->assertSee('残数僅少アラート対象者')
                ->screenshot('残数僅少アラートボタンクリック' . date('Ymd-his'));

            });
    }

    // 平均年齢（在籍者）ボタン
    public function testDisplay_all_avg()
    { 
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('平均年齢（在籍者）')
                ->pause('1000')
                ->assertSee('≪全在籍者の平均年齢≫')
                ->screenshot(' 平均年齢（在籍者）ボタンクリック' . date('Ymd-his'));
            });
    }


    // 平均年齢（部門別）ボタン
    public function testDisplay_department_avg()
    { 
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('平均年齢（部門別）')
                ->pause('1000')
                ->assertSee('≪部門別の平均年齢≫')
                ->screenshot(' 平均年齢（部門別）ボタンクリック' . date('Ymd-his'));
            });
    }

    // 平均年齢（男女別）ボタン
    public function testDisplay_gender_avg()
    { 
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('平均年齢（男女別）')
                ->pause('1000')
                ->assertSee('≪男女別の平均年齢≫')
                ->screenshot(' 平均年齢（男女別）ボタンクリック' . date('Ymd-his'));
            });
    }

    // 人数（在籍者）ボタン
    public function testDisplay_all_count()
    { 
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('人数（在籍者）')
                ->pause('1000')
                ->assertSee('≪全在籍者の人数≫')
                ->screenshot(' 人数（在籍者）ボタンクリック' . date('Ymd-his'));
            });
    }

    // 人数（部門別）ボタン
    public function testDisplay_department_count()
    { 
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('人数（部門別）')
                ->pause('1000')
                ->assertSee('≪部門別の人数≫')
                ->screenshot(' 人数（部門別）ボタンクリック' . date('Ymd-his'));
            });
    }

    // 人数（男女別）ボタン
    public function testDisplay_gender_count()
    { 
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('人数（男女別）')
                ->pause('1000')
                ->assertSee('≪男女別の人数≫')
                ->screenshot(' 人数（男女別）ボタンクリック' . date('Ymd-his'));
            });
    }

    // 人数（年代別）ボタン
    public function testDisplay_age_count()
    { 
        $this->browse(function ($browser) {
            $browser->visit('/employee/public/')

                // ->dump($browser)
                ->press('人数（年代別）')
                ->pause('1000')
                ->assertSee('≪年代別の人数≫')
                ->screenshot(' 人数（年代別）ボタンクリック' . date('Ymd-his'));
            });
    }

    // ロゴ
    public function testDisplay_logo()
    {
        $this->browse(function ($browser){
            $browser->visit('employee/public')

            ->assertSee('名簿表示')
            ->assertSee('その他の機能')
            ->assertSee('在籍者')
            ->press('#app > nav > div > a')
            ->assertSee('名簿表示')
            ->assertSee('その他の機能')
            ->assertSee('在籍者')
            ->screenshot('ロゴ押下');
        });
     }
}
