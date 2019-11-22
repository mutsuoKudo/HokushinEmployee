<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Library\BaseClass;
use DB;


class BaseClassTest extends TestCase
{


    // テスト用に作成したtest_laravelDB内のデータを使用しています

    //employeesテーブルのtaishokubiが入力されていないデータ全て（＝在籍者）
    public function testBaseClass_all()
    {

        $BaseClass = new BaseClass();

        $result_employees = $BaseClass->all();

        // var_dump($result_employees);

        $this->assertEquals(2018100031, $result_employees[0]->shain_cd);
        $this->assertEquals(2018110031, $result_employees[1]->shain_cd);
        $this->assertEquals(2019010011, $result_employees[2]->shain_cd);
        $this->assertEquals(2019070011, $result_employees[3]->shain_cd);
    }

    //スタッフ全員の入社年と退職年を算出
    public function testBaseClass_nyusya_taishoku_year()
    {

        $BaseClass = new BaseClass();

        $result_nyusya_taishoku_year = $BaseClass->nyusya_taishoku_year();

        // var_dump($result_nyusya_taishoku_year);

        $this->assertEquals(2018, $result_nyusya_taishoku_year[0][0]->nyushanen);
        $this->assertEquals(2019, $result_nyusya_taishoku_year[0][1]->nyushanen);
        $this->assertEquals(2019, $result_nyusya_taishoku_year[1][0]->taishokunen);
    }

    //部門ごとのスタッフ情報
    public function testBaseClass_department()
    {

        $BaseClass = new BaseClass();

        $result_department1 = $BaseClass->department(1);
        $result_department2 = $BaseClass->department(2);
        $result_department3 = $BaseClass->department(3);
        $result_department4 = $BaseClass->department(4);
        $result_department5 = $BaseClass->department(5);

        $this->assertEmpty($result_department1);

        $this->assertEquals(2018110031, $result_department2[0]->shain_cd);

        $this->assertEquals(2019070011, $result_department3[0]->shain_cd);

        $this->assertEquals(2018100031, $result_department4[0]->shain_cd);
        $this->assertEquals(2019010011, $result_department4[1]->shain_cd);

        $this->assertEmpty($result_department5);
    }

    //入社年ごとのスタッフ情報 CHECK
    public function testBaseClass_nyushabi()
    {

        $BaseClass = new BaseClass();

        // 社員情報（入社日が2007年度）取得
        $result_nyushabi1 = $BaseClass->nyushabi("2018-01-01", "2018-12-31");
        $result_nyushabi2 = $BaseClass->nyushabi("2019-01-01", "2019-12-31");

        $this->assertEquals(2018100031, $result_nyushabi1[0]->shain_cd);
        $this->assertEquals(2018110031, $result_nyushabi1[1]->shain_cd);
        $this->assertEquals(2019010011, $result_nyushabi2[0]->shain_cd);
        $this->assertEquals(2019070011, $result_nyushabi2[1]->shain_cd);
    }

    //年代ごとのスタッフ情報
    public function testBaseClass_age()
    {

        $BaseClass = new BaseClass();

        $result_age1 = $BaseClass->age(20, 29);
        $result_age2 = $BaseClass->age(30, 39);
        $result_age3 = $BaseClass->age(40, 49);
        $result_age4 = $BaseClass->age(50, 59);
        $result_age5 = $BaseClass->age(60, 69);

        $this->assertEquals(2018100031, $result_age1[0]->shain_cd);
        $this->assertEquals(2018110031, $result_age1[1]->shain_cd);
        $this->assertEquals(2019010011, $result_age1[2]->shain_cd);

        $this->assertEquals(2019070011, $result_age2[0]->shain_cd);

        $this->assertEmpty($result_age3);
        $this->assertEmpty($result_age4);
        $this->assertEmpty($result_age5);
    }

    //その他の年代のスタッフ情報
    public function testBaseClass_age_other()
    {

        $BaseClass = new BaseClass();

        $result_age_other = $BaseClass->age_other(20, 69);

        $this->assertEmpty($result_age_other);
    }

    // 基準月ごとのスタッフ情報
    public function testBaseClass_kijun_month()
    {

        $BaseClass = new BaseClass();

        $result_kijun_month1 = $BaseClass->kijun_month("%-07-%");
        $result_kijun_month2 = $BaseClass->kijun_month("%-08-%");
        $result_kijun_month3 = $BaseClass->kijun_month("%-09-%");
        $result_kijun_month4 = $BaseClass->kijun_month("%-10-%");
        $result_kijun_month5 = $BaseClass->kijun_month("%-11-%");
        $result_kijun_month6 = $BaseClass->kijun_month("%-12-%");
        $result_kijun_month7 = $BaseClass->kijun_month("%-01-%");
        $result_kijun_month8 = $BaseClass->kijun_month("%-02-%");
        $result_kijun_month9 = $BaseClass->kijun_month("%-03-%");
        $result_kijun_month10 = $BaseClass->kijun_month("%-04-%");
        $result_kijun_month11 = $BaseClass->kijun_month("%-05-%");
        $result_kijun_month12 = $BaseClass->kijun_month("%-06-%");

        $this->assertEquals(2018100031, $result_kijun_month4[0]->shain_cd);
        $this->assertEquals(2018110031, $result_kijun_month5[0]->shain_cd);
        $this->assertEquals(2019010011, $result_kijun_month7[0]->shain_cd);

        $this->assertEquals(2019070011, $result_kijun_month1[0]->shain_cd);

        $this->assertEquals(2020, $result_kijun_month2[0]->shain_cd);

        // $this->assertEmpty($result_kijun_month2);
        $this->assertEmpty($result_kijun_month3);
        $this->assertEmpty($result_kijun_month6);
        $this->assertEmpty($result_kijun_month8);
        $this->assertEmpty($result_kijun_month9);
        $this->assertEmpty($result_kijun_month10);
        $this->assertEmpty($result_kijun_month11);
        $this->assertEmpty($result_kijun_month12);
    }

    //退職したスタッフ情報
    public function testBaseClass_retirement()
    {

        $BaseClass = new BaseClass();

        $result_retirement = $BaseClass->retirement();

        $this->assertEquals(2018110051, $result_retirement[0]->shain_cd);
    }

    //退職した年ごとのスタッフ情報 CHECK
    public function testBaseClass_first_taishokubi()
    {

        $BaseClass = new BaseClass();

        $result_taishokubi = $BaseClass->taishokubi("2019-01-01", "2019-12-31");

        $this->assertEquals(2018110051, $result_taishokubi[0]->shain_cd);
    }







    // ここから下のテストで基本的に使用する社員コードをここにいれとこ
    // ※これ変えたらほとんどのメソッドのアサート変えなきゃいけなくなるよ
    public function testBaseClass_variable_container()
    {

        $id = "2018100031";

        $this->assertEquals('2018100031', $id);

        return $id;
    }

    public function testBaseClass_variable_container2()
    {

        $id2 = "2020";

        $this->assertEquals('2020', $id2);

        return $id2;
    }



    /**
     * @depends testBaseClass_variable_container
     */

    // 基準日テスト
    public function testBaseClass_kijunbil_year($id)
    {
        $BaseClass = new BaseClass();

        list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $BaseClass->kijunbi($id);

        var_dump($kijunbi_year_pre);
        var_dump($kijunbi_month_pre);
        var_dump($kijunbi_year_month_pre);

        $this->assertEquals('2019', $kijunbi_year_pre);
        $this->assertEquals('04', $kijunbi_month_pre);
        $this->assertEquals('201904', $kijunbi_year_month_pre);

        return [$kijunbi_year_pre, $kijunbi_month_pre];
    }

    /**
     * @depends testBaseClass_variable_container
     */
    //初年度最後（年）テスト
    public function testBaseClass_first_day_max_2_year($id)
    {

        $BaseClass = new BaseClass();

        list($first_day_max_year_pre, $first_day_max_month_pre, $first_day_max_pre) = $BaseClass->first_day_max_2($id);

        var_dump($first_day_max_year_pre);
        var_dump($first_day_max_month_pre);
        var_dump($first_day_max_pre);

        $this->assertEquals('2020', $first_day_max_year_pre);
        $this->assertEquals('03', $first_day_max_month_pre);
        $this->assertEquals('202003', $first_day_max_pre);
    }

    /**
     * @depends testBaseClass_variable_container
     */
    //初年度最後(まとめたもののみ)テスト
    public function testBaseClass_first_day_max($id)
    {

        $BaseClass = new BaseClass();

        $result_first_day_max = $BaseClass->first_day_max($id);

        var_dump($result_first_day_max);

        $this->assertEquals('202003', $result_first_day_max);

        return $result_first_day_max;
    }

    /**
     * @depends testBaseClass_variable_container
     */
    //年度終わりから３ヶ月前（=warning）テスト
    public function testBaseClass_warning($id)
    {

        $BaseClass = new BaseClass();

        $kijunbi_before3 = DB::table('employees')
            ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +14 MONTH) AS "kijunbi_before3"'))
            ->where('shain_cd', $id)
            ->first();

        // 1年目の終わりから3ヶ月前を取得
        $result_warning = $BaseClass->warning($kijunbi_before3, 1);

        var_dump($result_warning);

        $this->assertEquals('202012', $result_warning);
    }

    // 期首残高（付与日数+前期繰越）テスト
    public function testBaseClass_kisyu_nokori()
    {

        $BaseClass = new BaseClass();

        $huyo_holiday = 10;
        $carry_over = 0;

        $result_kisyu_nokori = $BaseClass->kisyu_nokori($huyo_holiday, $carry_over);

        var_dump($result_kisyu_nokori);

        $this->assertEquals(10, $result_kisyu_nokori);

        return $result_kisyu_nokori;
    }

    /**
     * @depends testBaseClass_variable_container
     */
    //入社年・入社月・入社年月の取得
    // IDで抜き出す
    public function testBaseClass_nyushabi_year_month($id)
    {

        $BaseClass = new BaseClass();

        list($nyushabi_year_pre, $nyushabi_month_pre, $nyushabi_year_month_pre) = $BaseClass->nyushabi_year_month($id);

        var_dump($nyushabi_year_pre);
        var_dump($nyushabi_month_pre);
        var_dump($nyushabi_year_month_pre);

        $this->assertEquals('2018', $nyushabi_year_pre);
        $this->assertEquals('10', $nyushabi_month_pre);
        $this->assertEquals('201810', $nyushabi_year_month_pre);

        return $nyushabi_year_month_pre;
    }

    /**
     * @depends testBaseClass_first_day_max
     */

    // 初年度以降の年度終わり
    public function testBaseClass_day_max($first_day_max)
    {

        $BaseClass = new BaseClass();

        // 1年目の年度終わり
        $result_day_max = $BaseClass->day_max($first_day_max, 1);

        var_dump($result_day_max);

        $this->assertEquals('202103', $result_day_max);

        return $result_day_max;
    }


    /**
     * @depends testBaseClass_variable_container
     * @depends testBaseClass_nyushabi_year_month
     * @depends testBaseClass_day_max
     */

    //消化日数を計算
    public function testBaseClass_holiday_count_int($id, $nyushabi_year_month_pre, $result_day_max)
    {

        $BaseClass = new BaseClass();

        $result_holiday_count_int = $BaseClass->holiday_count_int($nyushabi_year_month_pre, $result_day_max, $id);

        var_dump($result_holiday_count_int);

        $this->assertEquals(4.5, $result_holiday_count_int);

        return $result_holiday_count_int;
    }

    /**
     * @depends testBaseClass_variable_container2
     * @depends testBaseClass_nyushabi_year_month
     * @depends testBaseClass_day_max
     */

    public function testBaseClass_holiday_count_int_isnull($id2, $nyushabi_year_month_pre, $result_day_max)
    {

        $BaseClass = new BaseClass();

        $result_holiday_count_int = $BaseClass->holiday_count_int($nyushabi_year_month_pre, $result_day_max, $id2);

        var_dump('here');
        var_dump($result_holiday_count_int);

        $this->assertEquals(0, $result_holiday_count_int);

        return $result_holiday_count_int;
    }

    /**
     * @depends testBaseClass_holiday_count_int
     * @depends testBaseClass_kisyu_nokori
     */

    //消化残（期首残高-消化日数）
    public function testBaseClass_nokori($result_holiday_count_int, $result_kisyu_nokori)
    {

        $BaseClass = new BaseClass();

        $result_nokori = $BaseClass->nokori($result_kisyu_nokori, $result_holiday_count_int);

        var_dump($result_nokori);

        $this->assertEquals(5.5, $result_nokori);

        return $result_nokori;
    }

    /**
     * @depends testBaseClass_nokori
     */

    //繰越日数
    public function testBaseClass_carry_over_count($result_nokori)
    {

        $BaseClass = new BaseClass();

        // 初年度の最大繰越日数
        $max_carry_over = 10;

        $result_carry_over_count = $BaseClass->carry_over_count($result_nokori, $max_carry_over);

        var_dump($result_carry_over_count);

        $this->assertEquals(5.5, $result_carry_over_count);
    }


    //繰越日数2
    public function testBaseClass_carry_over_count2()
    {

        $BaseClass = new BaseClass();

        // 最大繰越日数
        $max_carry_over = 14;

        $result_carry_over_count2 = $BaseClass->carry_over_count(14, $max_carry_over);
        var_dump($result_carry_over_count2);

        $this->assertEquals(14, $result_carry_over_count2);
    }


    /**
     * @depends testBaseClass_variable_container
     */
    //初年度以降の年度初め
    public function testBaseClass_day_min($id)
    {

        $BaseClass = new BaseClass();


        // ここかえたい TODO
        list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $BaseClass->kijunbi($id);

        // 1年目の年度初め
        $result_day_min = $BaseClass->day_min($kijunbi_year_pre, 1, $kijunbi_month_pre);

        var_dump($result_day_min);

        $this->assertEquals(202004, $result_day_min);


        return $result_day_min;
    }


    /**
     * @depends testBaseClass_variable_container
     * @depends testBaseClass_nyushabi_year_month
     * @depends testBaseClass_day_max
     * @depends testBaseClass_day_min
     */
    //月別消化日数
    public function testBaseClass_get_holiday($id, $nyushabi_year_month_pre, $result_day_max, $result_day_min)
    {

        $BaseClass = new BaseClass();

        $result_get_holiday_first = $BaseClass->get_holiday($id, $nyushabi_year_month_pre, $result_day_max);
        $result_get_holiday = $BaseClass->get_holiday($id, $result_day_min, $result_day_max);

        // var_dump($result_get_holiday_first);
        // var_dump($result_get_holiday);

        $this->assertEquals(2018, $result_get_holiday_first[0]->year);
        $this->assertEquals(10, $result_get_holiday_first[0]->month);
        $this->assertEquals(1.0, $result_get_holiday_first[0]->day);

        $this->assertEquals(2019, $result_get_holiday_first[1]->year);
        $this->assertEquals(1, $result_get_holiday_first[1]->month);
        $this->assertEquals(1.0, $result_get_holiday_first[1]->day);

        $this->assertEquals(2019, $result_get_holiday_first[2]->year);
        $this->assertEquals(2, $result_get_holiday_first[2]->month);
        $this->assertEquals(1.0, $result_get_holiday_first[2]->day);

        $this->assertEquals(2019, $result_get_holiday_first[3]->year);
        $this->assertEquals(8, $result_get_holiday_first[3]->month);
        $this->assertEquals(1.0, $result_get_holiday_first[3]->day);

        $this->assertEquals(2019, $result_get_holiday_first[4]->year);
        $this->assertEquals(9, $result_get_holiday_first[4]->month);
        $this->assertEquals(0.5, $result_get_holiday_first[4]->day);

        $this->assertEmpty($result_get_holiday);
    }

    // 初年度以降の前期繰越
    public function testBaseClass_carry_over()
    {

        $BaseClass = new BaseClass();

        // 1年目の前期繰越
        // 1年目の最大繰越日数
        $max_carry_over = 11;
        // 有給に関するデータを入れておく（取得残日数が入っていればいいので他は0）
        $array[] = [0, 0, 0, 0, 0, 0, 5.5, 0, 0, 0];
        $result_carry_over = $BaseClass->carry_over($array, 1, $max_carry_over);

        $this->assertEquals(5.5, $result_carry_over);
    }


    // 初年度以降の前期繰越（残日数が最大繰り越し日数よりも多かった場合）
    public function testBaseClass_carry_over2()
    {

        $BaseClass = new BaseClass();


        // 1年目の前期繰越
        // 1年目の最大繰越日数
        $max_carry_over = 11;
        // 有給に関するデータを入れておく（取得残日数が入っていればいいので他は0）
        $array[] = [0, 0, 0, 0, 0, 0, 12, 0, 0, 0];
        $result_carry_over = $BaseClass->carry_over($array, 1, $max_carry_over);

        $this->assertEquals(11, $result_carry_over);
    }

    //一番最近のデータの年月(0000-00)を作成(=現在日時になる（◎月時点のデータですとか）)
    public function testBaseClass_year_month()
    {

        $BaseClass = new BaseClass();

        list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $BaseClass->year_month();

        $this->assertEquals(2019, $year_month_a1_pre);
        $this->assertEquals("09", $year_month_a2_pre);
        $this->assertEquals("2019年09月", $year_month_a_pre);
        $this->assertEquals(201909, $year_month_b_pre);
    }

    /**
     * @depends testBaseClass_variable_container
     */
    // 主にアラートで使用
    //IDで抜き出す社員情報
    public function testBaseClass_employees($id)
    {
        $BaseClass = new BaseClass();

        $result_employees = $BaseClass->employees($id);

        // var_dump($result_employees);

        $this->assertEquals(2018100031, $result_employees[0][0]->shain_cd);
    }





    /**
     * @depends testBaseClass_variable_container
     */
    //年度の終わりを計算
    public function testBaseClass_end_kijunbi($id)
    {

        $BaseClass = new BaseClass();

        list($end_kijunbi_year_pre, $end_kijunbi_month_pre, $end_kijunbi_pre) = $BaseClass->end_kijunbi($id);

        $this->assertEquals(2020, $end_kijunbi_year_pre);
        $this->assertEquals(03, $end_kijunbi_month_pre);
        $this->assertEquals(202003, $end_kijunbi_pre);
    }



    /**
     * @depends testBaseClass_variable_container
     */
    //消化日数を計算
    public function testBaseClass_holiday_count($id)
    {

        $BaseClass = new BaseClass();



        //本年度の始まりを計算
        list($nyushabi_year_pre, $nyushabi_month_pre, $nyushabi_year_month_pre) = $BaseClass->nyushabi_year_month($id);


        $day_min = "201810";
        $day_max = "202003";

        $result_holiday_count = $BaseClass->holiday_count($day_min, $day_max, $id);

        var_dump($result_holiday_count);
        var_dump($day_min);
        var_dump($day_max);

        $this->assertEquals("4.5", $result_holiday_count[0]->sumday);
    }


    //全在籍社員の入社年・入社月・入社年月(20191001)・入社年月(2019-10-01)の取得
    // データ数指定
    public function testBaseClass_all_nyushabi_year_month()
    {

        $BaseClass = new BaseClass();

        //入社年月を求める
        list($nyushabi_year_pre, $nyushabi_month_pre, $nyushabi_year_month_pre, $nyushabi_pre) = $BaseClass->all_nyushabi_year_month(0);

        var_dump('here');
        var_dump($nyushabi_year_pre);

        // 入社年月
        $nyushabi = $nyushabi_year_month_pre;
        // 入社年
        $nyushabi_year = $nyushabi_year_pre;


        $this->assertEquals("201810", $nyushabi);
        $this->assertEquals("2018", $nyushabi_year);
    }

    // 退職日取得
    public function testBaseClass_retirement_id()
    {

        $BaseClass = new BaseClass();

        list($taishokubi_year_pre, $taishokubi_month_pre, $taishokubi_year_month_pre, $taishokubi_pre) = $BaseClass->retirement_id('2018110051');

        // 退職年を抜き出す
        $taishokubi_year = $taishokubi_year_pre;
        // 退職年月を抜き出す
        $taishokubi_year_month = $taishokubi_year_month_pre;


        $this->assertEquals("2019", $taishokubi_year);
        $this->assertEquals("201901", $taishokubi_year_month);
    }


    /**
     * @depends testBaseClass_variable_container
     */
    // ストレージに入った写真の削除
    public function testBaseClass_pic_file_delete($id)
    {

        $BaseClass = new BaseClass();

        // 同じ名前が含まれた写真を削除（＝旧データを削除）
        $BaseClass->pic_file_delete($id, 'tesuto de-ta');

        // 写真が入っているフォルダのファイルをすべて取得
        $pic_file = glob('C:\xampp\htdocs\employee\public\storage\post_images\*');

        // var_dump($pic_file);

        foreach ($pic_file as $p_file_id) {
            if (strpos($p_file_id, $id . '_' . 'tesuto de-ta')) {
                $result_pic_file_delete = 'NO';
            } else {
                $result_pic_file_delete = 'OK';
            }
        }

        $this->assertEquals("OK", $result_pic_file_delete);
    }


    /**
     * @depends testBaseClass_variable_container
     */
    // ストレージに入った写真の削除
    public function testBaseClass_pic_file_db_save($id)
    {

        $BaseClass = new BaseClass();

        $BaseClass->pic_file_db_save($id, '2020000000', $id, 'tesuto de-ta', 'jpg');

        $result_pic_file_db_save_pre = DB::table('employees')
            ->select('pic')
            ->get();

        var_dump($result_pic_file_db_save_pre[0]->pic);
        $result_pic_file_db_save = $result_pic_file_db_save_pre[0]->pic;

        $this->assertEquals("2020000000_2018100031_tesuto de-ta.jpg", $result_pic_file_db_save);

        //　挿入したpicパスを削除する
        \DB::table('employees')
            ->where('shain_cd', '2018100031')
            ->update(['pic' => null]);
    }


    public function testBaseClass_employee_create()
    {


        $BaseClass = new BaseClass();

        $request = [
            'shain_cd' => '2021',
            'shain_mei' => '新規登録',
            'shain_mei_kana' => '新規登録',
            'shain_mei_romaji' => '新規登録',
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
            `updated_at` => '""',
            `created_at` => '""'
        ];

        

        // 入力した内容を新規登録
        $BaseClass->employee_create((object) $request);

        $result_employee_create = DB::table('employees')
            ->where('shain_cd','2021')
            ->get();

            var_dump($result_employee_create[0]->shain_cd);

        $this->assertEquals("2021", $result_employee_create[0]->shain_cd);

         //　挿入したデータをを削除する
         \DB::table('employees')
         ->where('shain_cd', '2021')
         ->delete();
    }
}
