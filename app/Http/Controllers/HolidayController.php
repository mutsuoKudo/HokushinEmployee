<?php

namespace App\Http\Controllers;

use App\Holiday;
use App\Employee;
use \DB;

use Illuminate\Http\Request;

class HolidayController extends Controller
{
    //

    public function holiday($id)
    {

        //employeesテーブルのデータを詳細テーブルで表示していた社員コードの分取得
        $employee = Employee::find($id);

        //詳細ページのプルダウンで選択された年度
        $post_year = $_POST['year'];
        // var_dump('何年の有給についてか:'.$post_year);


        //基準日の計算(入社日に+6か月)　※ XXXX-XX-01　XXXX-XXの部分は個人で計算されます。
        $kijunbi = DB::table('employees')
            ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "kijunbi"'))
            ->where('shain_cd', $id)
            ->first();
        // ->toSQL();
        // var_dump($kijunbi);

        // 基準月を抜き出す
        $kijunbi_month = substr($kijunbi->kijunbi, 5, 2);
        // var_dump('基準月:' . $kijunbi_month);

        // 基準年を抜き出す
        $kijunbi_year = substr($kijunbi->kijunbi, 0, 4);
        // var_dump('基準年:' . $kijunbi_year);

        // 基準年月を抜き出す
        $kijunbi_year_month = $kijunbi_year . $kijunbi_month;
        // var_dump('基準年月:' . $kijunbi_year_month);


        //入社日の取得
        $nyushabi = DB::table('employees')
            ->select('nyushabi')
            ->where('shain_cd', $id)
            ->first();

        //入社年月の抜き出し
        $nyushabi_year = substr($nyushabi->nyushabi, 0, 4);
        $nyushabi_month = substr($nyushabi->nyushabi, 5, 2);
        $nyushabi_year_month = $nyushabi_year . $nyushabi_month;
        // var_dump("入社年月" . $nyushabi_year_month);


        // 選択された年数に＋1年する (=今期最後の月で使用する年数)
        $day_max_year = $post_year + 1;

        //初年度最後の月
        // $first_day_max = $kijunbi_year + 1 . $kijunbi_max_month;
        $first_day_max_pre = DB::table('employees')
            ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +17 MONTH) AS "first_day_max_pre"'))
            ->where('shain_cd', $id)
            ->first();

        $first_day_max1 = substr($first_day_max_pre->first_day_max_pre, 0, 4);
        $first_day_max2 = substr($first_day_max_pre->first_day_max_pre, 5, 2);
        $first_day_max = $first_day_max1 . $first_day_max2;
        var_dump("初年度最後の月" . $first_day_max);





        //アラート用(基準月の3ヶ月前の時点で5日以上取得していないとき（＝未消化）)
        // 基準月から３ヶ月前の計算(基準月の3ヶ月前の時点で5日以上取得していないとき（＝未消化）)
        $kijunbi_before3 = DB::table('employees')
            ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +14 MONTH) AS "kijunbi_before3"'))
            ->where('shain_cd', $id)
            ->first();
        // ->toSQL();
        // var_dump("基準月から３ヶ月前");
        // var_dump($kijunbi_before3);

        //年度終わりから３ヶ月前の年を抜き出す
        // $kijunbi_before3_year = substr($kijunbi_before3->kijunbi_before3, 0, 4);
        // var_dump('基準日の３ヶ月前の年:' . $kijunbi_before3_year);

        // //年度終わりから３ヶ月前の月を抜き出す
        // $kijunbi_before3_month = substr($kijunbi_before3->kijunbi_before3, 5, 2);
        // var_dump('基準日の３ヶ月前の月:' . $kijunbi_before3_month);

        // //年度終わりから３ヶ月前（=warning）
        // $warning = $kijunbi_before3_year . $kijunbi_before3_month;
        // var_dump('年度最後の月の三ヶ月前:' . $warning);




        // 勤続年数を計算(計算の仕方間違ってる！！修正必要)
        $year_pre = date('Ym') - 2;
        $year = substr($year_pre, 0, 4);

        // $kinzoku_year = date("Y") - $kijunbi_year;
        $kinzoku_year = $year - $kijunbi_year;
        // $kinzoku_year = 30;
        // var_dump('勤続年数:' . $year_pre);
        // var_dump('勤続年数:' . $year . "-" .$kijunbi_year);
        var_dump('勤続年数:' . $kinzoku_year);


        //配列の作成
        $array = [];




        //勤続年数分のデータを配列にいれる。 基準日が2017年だったら、2017年・2018年・2019年分
        for ($i = 0; $i <= $kinzoku_year; $i++) {


            if ($i == 0) {
                // var_dump("ここは初年度");

                //付与日数
                $huyo_holiday = "10";
                //最大繰り越し日数
                $max_carry_over = "10";
                //前期繰越
                $carry_over = 0;

                // 期首残高（付与日数+前期繰越）
                $kisyu_nokori = $huyo_holiday + $carry_over;

                //消化日数
                //年度最後の月計算(入社日に+17ヶ月(試用期間6ヶ月+1年度11ヶ月=17ヶ月)))
                $day_max_pre = DB::table('employees')
                    ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +17 MONTH) AS "day_max_pre"'))
                    ->where('shain_cd', $id)
                    ->first();

                $day_max1 = substr($day_max_pre->day_max_pre, 0, 4);
                $day_max2 = substr($day_max_pre->day_max_pre, 5, 2);
                //年度最後の年月
                $day_max = $day_max1 . $day_max2;
                // var_dump("初年度最初の月" . $nyushabi_year_month);
                // var_dump("初年度最後の月" . $day_max);

                //年度終わりから３ヶ月前の年を抜き出す
                $kijunbi_before3_year = substr($kijunbi_before3->kijunbi_before3, 0, 4);
                // var_dump('基準日の３ヶ月前の年:' . $kijunbi_before3_year);

                //年度終わりから３ヶ月前の月を抜き出す
                $kijunbi_before3_month = substr($kijunbi_before3->kijunbi_before3, 5, 2);
                // var_dump('基準日の３ヶ月前の月:' . $kijunbi_before3_month);

                //年度終わりから３ヶ月前（=warning）
                $warning = $kijunbi_before3_year + $i . $kijunbi_before3_month;
                var_dump('年度最後の月の三ヶ月前:' . $warning);


                $holiday_count = DB::table('holidays')
                    ->select(DB::raw('sum(day) AS sumday'))
                    //入社日から～基準日に1年度分（11ヶ月）足したもの(ex:2016/10/1入社なら、2016/10/1~（基準日が2017/4/1なので）2018/3/1まで))
                    ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $nyushabi_year_month)
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    ->where('shain_cd', $id)
                    ->get();
                // ->toSQL();
                // var_dump('初年度の消化日数:');
                // var_dump($holiday_count);

                //配列で取得された消化日数の一番目を変数にいれる
                foreach ($holiday_count as $counts) {

                    if (is_null($counts->sumday)) {
                        $holiday_count_int = 0;
                    } else {
                        $holiday_count_int = $counts->sumday;
                    }
                }

                //消化残（期首残高-消化日数）
                $nokori = $kisyu_nokori - $holiday_count_int;

                //繰越日数（消化残が最大繰り越し日数より大きい場合、繰り越し日数は最大繰り越し日数と同じになる。小さい場合、繰り越し日数は消化残と同じ日数になる。）
                if ($nokori > $max_carry_over) {
                    $carry_over_count = $max_carry_over;
                } else {
                    $carry_over_count = $nokori;
                }

                //月別消化日数
                $get_holiday = DB::table('holidays')
                    ->select('year', 'day', 'month')
                    ->join('employees', 'holidays.shain_cd', '=', 'employees.shain_cd')
                    ->where('employees.shain_cd', $id)
                    //入社日から～基準日に1年度分（11ヶ月）足したもの(ex:2016/10/1入社なら、2016/10/1~（基準日が2017/4/1なので）2018/3/1まで))
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '>=', $nyushabi_year_month)
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    ->orderBy('year', 'asc')
                    ->orderBy('month', 'asc')
                    ->get();
                // ->toSQL();
                // var_dump($get_holiday);



            } elseif ($i == 1) {
                // var_dump("ここは" . $i . "年目");

                //付与日数
                $huyo_holiday = "11";
                //最大繰り越し日数
                $max_carry_over = "11";
                //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                $carry_over = $array[$i - 1][6];
                // var_dump($i . "年目のcarry_overは" . $carry_over);

                // 期首残高
                $kisyu_nokori = $huyo_holiday + $carry_over;

                //消化日数
                //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                $day_min_pre = $kijunbi_year + $i;
                $day_min = $day_min_pre . $kijunbi_month;
                // var_dump($i . "年度初めの年月" . $day_min);

                //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                $day_max_year = substr($first_day_max, 0, 4);
                $day_max_month = substr($first_day_max, 4, 2);

                $day_max = $day_max_year + $i . $day_max_month;
                // var_dump($i . "年度最後の年月" . $day_max);

                //年度終わりから３ヶ月前の年を抜き出す
                $kijunbi_before3_year = substr($kijunbi_before3->kijunbi_before3, 0, 4);
                // var_dump('基準日の３ヶ月前の年:' . $kijunbi_before3_year);

                //年度終わりから３ヶ月前の月を抜き出す
                $kijunbi_before3_month = substr($kijunbi_before3->kijunbi_before3, 5, 2);
                // var_dump('基準日の３ヶ月前の月:' . $kijunbi_before3_month);

                //年度終わりから３ヶ月前（=warning）
                $warning = $kijunbi_before3_year + $i . $kijunbi_before3_month;
                // var_dump('年度最後の月の三ヶ月前:' . $warning);


                $holiday_count = DB::table('holidays')
                    ->select(DB::raw('sum(day) AS sumday'))
                    //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                    ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    ->where('shain_cd', $id)
                    // ->first();
                    ->get();
                // ->toSQL();
                // var_dump('消化日数:');
                // var_dump($holiday_count);

                //配列で取得された消化日数の一番目を変数にいれる
                foreach ($holiday_count as $counts) {

                    if (is_null($counts->sumday)) {
                        $holiday_count_int = 0;
                    } else {
                        $holiday_count_int = $counts->sumday;
                    }
                }

                //消化残
                $nokori = $kisyu_nokori - $holiday_count_int;

                //繰越日数
                if ($nokori > $max_carry_over) {
                    $carry_over_count = $max_carry_over;
                } else {
                    $carry_over_count = $nokori;
                }

                //月別消化日数
                $get_holiday = DB::table('holidays')
                    ->select('year', 'day', 'month')
                    ->join('employees', 'holidays.shain_cd', '=', 'employees.shain_cd')
                    ->where('employees.shain_cd', $id)
                    //基準日～
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '>=', $day_min)
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    ->orderBy('year', 'asc')
                    ->orderBy('month', 'asc')
                    ->get();
                // ->toSQL();
                // var_dump($get_holiday);



            } elseif ($i == 2) {
                // var_dump("ここは" . $i . "年目");

                $huyo_holiday = "12";
                $max_carry_over = "12";
                $carry_over = $array[$i - 1][6];
                // var_dump($i . "年目のcarry_overは" . $carry_over);

                // 期首残高
                $kisyu_nokori = $huyo_holiday + $carry_over;

                //消化日数
                //年度初めの年月（基準日に$i($i=2)年足したもの(ex:2016/10が基準日の場合、2018/10になる))
                $day_min_pre = $kijunbi_year + $i;
                $day_min = $day_min_pre . $kijunbi_month;
                // var_dump($i . "年度初めの年月" . $day_min);

                //年度最後の年月（初年度最後の年に$i($i=2)年足したもの(ex:2017/3が初年度最後の場合、2019/3になる))
                $day_max_year = substr($first_day_max, 0, 4);
                $day_max_month = substr($first_day_max, 4, 2);

                $day_max = $day_max_year + $i . $day_max_month;
                // var_dump($i . "年度最後の年月" . $day_max);

                //年度終わりから３ヶ月前の年を抜き出す
                $kijunbi_before3_year = substr($kijunbi_before3->kijunbi_before3, 0, 4);
                // var_dump('基準日の３ヶ月前の年:' . $kijunbi_before3_year);

                //年度終わりから３ヶ月前の月を抜き出す
                $kijunbi_before3_month = substr($kijunbi_before3->kijunbi_before3, 5, 2);
                // var_dump('基準日の３ヶ月前の月:' . $kijunbi_before3_month);

                //年度終わりから３ヶ月前（=warning）
                $warning = $kijunbi_before3_year + $i . $kijunbi_before3_month;
                // var_dump('年度最後の月の三ヶ月前:' . $warning);


                $holiday_count = DB::table('holidays')
                    ->select(DB::raw('sum(day) AS sumday'))
                    //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                    ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    ->where('shain_cd', $id)
                    // ->first();
                    ->get();
                // ->toSQL();
                // var_dump('消化日数:');
                // var_dump($holiday_count);

                //配列で取得された消化日数の一番目を変数にいれる
                foreach ($holiday_count as $counts) {

                    if (is_null($counts->sumday)) {
                        $holiday_count_int = 0;
                    } else {
                        $holiday_count_int = $counts->sumday;
                    }
                }

                //消化残
                $nokori = $kisyu_nokori - $holiday_count_int;

                //繰越日数
                if ($nokori > $max_carry_over) {
                    $carry_over_count = $max_carry_over;
                } else {
                    $carry_over_count = $nokori;
                }

                //月別消化日数
                $get_holiday = DB::table('holidays')
                    ->select('year', 'day', 'month')
                    ->join('employees', 'holidays.shain_cd', '=', 'employees.shain_cd')
                    ->where('employees.shain_cd', $id)
                    //基準日～
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '>=', $day_min)
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    ->orderBy('year', 'asc')
                    ->orderBy('month', 'asc')
                    ->get();
                // ->toSQL();
                // var_dump($get_holiday);



            } elseif ($i == 3) {
                // var_dump("ここは" . $i . "年目");
                $huyo_holiday = "14";
                $max_carry_over = "14";
                $carry_over = $array[$i - 1][6];
                // var_dump($i . "年目のcarry_overは" . $carry_over);


                // 期首残高
                $kisyu_nokori = $huyo_holiday + $carry_over;

                //消化日数
                //年度初めの年月（基準日に$i($i=3)年足したもの(ex:2016/10が基準日の場合、2019/10になる))
                $day_min_pre = $kijunbi_year + $i;
                $day_min = $day_min_pre . $kijunbi_month;
                // var_dump($i . "年度初めの年月" . $day_min);

                //年度最後の年月（初年度最後の年に$i($i=3)年足したもの(ex:2017/3が初年度最後の場合、2020/3になる))
                $day_max_year = substr($first_day_max, 0, 4);
                $day_max_month = substr($first_day_max, 4, 2);

                $day_max = $day_max_year + $i . $day_max_month;
                // var_dump($i . "年度最後の年月" . $day_max);

                //年度終わりから３ヶ月前の年を抜き出す
                $kijunbi_before3_year = substr($kijunbi_before3->kijunbi_before3, 0, 4);
                // var_dump('基準日の３ヶ月前の年:' . $kijunbi_before3_year);

                //年度終わりから３ヶ月前の月を抜き出す
                $kijunbi_before3_month = substr($kijunbi_before3->kijunbi_before3, 5, 2);
                // var_dump('基準日の３ヶ月前の月:' . $kijunbi_before3_month);

                //年度終わりから３ヶ月前（=warning）
                $warning = $kijunbi_before3_year + $i . $kijunbi_before3_month;
                // var_dump('年度最後の月の三ヶ月前:' . $warning);


                $holiday_count = DB::table('holidays')
                    ->select(DB::raw('sum(day) AS sumday'))
                    //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                    ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    ->where('shain_cd', $id)
                    // ->first();
                    ->get();
                // ->toSQL();
                // var_dump('消化日数:');
                // var_dump($holiday_count);

                //配列で取得された消化日数の一番目を変数にいれる
                foreach ($holiday_count as $counts) {

                    if (is_null($counts->sumday)) {
                        $holiday_count_int = 0;
                    } else {
                        $holiday_count_int = $counts->sumday;
                    }
                }

                //消化残
                $nokori = $kisyu_nokori - $holiday_count_int;

                //繰越日数
                if ($nokori > $max_carry_over) {
                    $carry_over_count = $max_carry_over;
                } else {
                    $carry_over_count = $nokori;
                }

                //月別消化日数
                $get_holiday = DB::table('holidays')
                    ->select('year', 'day', 'month')
                    ->join('employees', 'holidays.shain_cd', '=', 'employees.shain_cd')
                    ->where('employees.shain_cd', $id)
                    //基準日～
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '>=', $day_min)
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    ->orderBy('year', 'asc')
                    ->orderBy('month', 'asc')
                    ->get();
                // ->toSQL();
                // var_dump($get_holiday);



            } elseif ($i == 4) {
                // var_dump("ここは" . $i . "年目");
                $huyo_holiday = "16";
                $max_carry_over = "16";
                $carry_over = $array[$i - 1][6];
                // var_dump($i . "年目のcarry_overは" . $carry_over);


                // 期首残高
                $kisyu_nokori = $huyo_holiday + $carry_over;

                //消化日数
                //年度初めの年月（基準日に$i($i=4)年足したもの(ex:2016/10が基準日の場合、2020/10になる))
                $day_min_pre = $kijunbi_year + $i;
                $day_min = $day_min_pre . $kijunbi_month;
                // var_dump($i . "年度初めの年月" . $day_min);

                //年度最後の年月（初年度最後の年に$i($i=4)年足したもの(ex:2017/3が初年度最後の場合、2021/3になる))
                $day_max_year = substr($first_day_max, 0, 4);
                $day_max_month = substr($first_day_max, 4, 2);

                $day_max = $day_max_year + $i . $day_max_month;
                // var_dump($i . "年度最後の年月" . $day_max);

                //年度終わりから３ヶ月前の年を抜き出す
                $kijunbi_before3_year = substr($kijunbi_before3->kijunbi_before3, 0, 4);
                // var_dump('基準日の３ヶ月前の年:' . $kijunbi_before3_year);

                //年度終わりから３ヶ月前の月を抜き出す
                $kijunbi_before3_month = substr($kijunbi_before3->kijunbi_before3, 5, 2);
                // var_dump('基準日の３ヶ月前の月:' . $kijunbi_before3_month);

                //年度終わりから３ヶ月前（=warning）
                $warning = $kijunbi_before3_year + $i . $kijunbi_before3_month;
                // var_dump('年度最後の月の三ヶ月前:' . $warning);


                $holiday_count = DB::table('holidays')
                    ->select(DB::raw('sum(day) AS sumday'))
                    //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                    ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    ->where('shain_cd', $id)
                    // ->first();
                    ->get();
                // ->toSQL();
                // var_dump('消化日数:');
                // var_dump($holiday_count);
                //配列で取得された消化日数の一番目を変数にいれる

                foreach ($holiday_count as $counts) {

                    if (is_null($counts->sumday)) {
                        $holiday_count_int = 0;
                    } else {
                        $holiday_count_int = $counts->sumday;
                    }
                }

                //消化残
                $nokori = $kisyu_nokori - $holiday_count_int;

                //繰越日数
                if ($nokori > $max_carry_over) {
                    $carry_over_count = $max_carry_over;
                } else {
                    $carry_over_count = $nokori;
                }

                //月別消化日数
                $get_holiday = DB::table('holidays')
                    ->select('year', 'day', 'month')
                    ->join('employees', 'holidays.shain_cd', '=', 'employees.shain_cd')
                    ->where('employees.shain_cd', $id)
                    //基準日～
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '>=', $day_min)
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    ->orderBy('year', 'asc')
                    ->orderBy('month', 'asc')
                    ->get();
                // ->toSQL();
                // var_dump($get_holiday);



            } elseif ($i == 5) {
                // var_dump("ここは" . $i . "年目");

                $huyo_holiday = "18";
                $max_carry_over = "18";
                $carry_over = $array[$i - 1][6];
                // var_dump($i . "年目のcarry_overは" . $carry_over);


                // 期首残高
                $kisyu_nokori = $huyo_holiday + $carry_over;

                //消化日数
                //年度初めの年月（基準日に$i($i=5)年足したもの(ex:2016/10が基準日の場合、2021/10になる))
                $day_min_pre = $kijunbi_year + $i;
                $day_min = $day_min_pre . $kijunbi_month;
                // var_dump($i . "年度初めの年月" . $day_min);

                //年度最後の年月（初年度最後の年に$i($i=5)年足したもの(ex:2017/3が初年度最後の場合、2022/3になる))
                $day_max_year = substr($first_day_max, 0, 4);
                $day_max_month = substr($first_day_max, 4, 2);

                $day_max = $day_max_year + $i . $day_max_month;
                // var_dump($i . "年度最後の年月" . $day_max);

                //年度終わりから３ヶ月前の年を抜き出す
                $kijunbi_before3_year = substr($kijunbi_before3->kijunbi_before3, 0, 4);
                // var_dump('基準日の３ヶ月前の年:' . $kijunbi_before3_year);

                //年度終わりから３ヶ月前の月を抜き出す
                $kijunbi_before3_month = substr($kijunbi_before3->kijunbi_before3, 5, 2);
                // var_dump('基準日の３ヶ月前の月:' . $kijunbi_before3_month);

                //年度終わりから３ヶ月前（=warning）
                $warning = $kijunbi_before3_year + $i . $kijunbi_before3_month;
                // var_dump('年度最後の月の三ヶ月前:' . $warning);


                $holiday_count = DB::table('holidays')
                    ->select(DB::raw('sum(day) AS sumday'))
                    //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                    ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    ->where('shain_cd', $id)
                    // ->first();
                    ->get();
                // ->toSQL();
                // var_dump('消化日数:');
                // var_dump($holiday_count);
                //配列で取得された消化日数の一番目を変数にいれる

                //配列で取得された消化日数の一番目を変数にいれる
                foreach ($holiday_count as $counts) {

                    if (is_null($counts->sumday)) {
                        $holiday_count_int = 0;
                    } else {
                        $holiday_count_int = $counts->sumday;
                    }
                }

                //消化残
                $nokori = $kisyu_nokori - $holiday_count_int;

                //繰越日数
                if ($nokori > $max_carry_over) {
                    $carry_over_count = $max_carry_over;
                } else {
                    $carry_over_count = $nokori;
                }

                //月別消化日数
                $get_holiday = DB::table('holidays')
                    ->select('year', 'day', 'month')
                    ->join('employees', 'holidays.shain_cd', '=', 'employees.shain_cd')
                    ->where('employees.shain_cd', $id)
                    //基準日～
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '>=', $day_min)
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    ->orderBy('year', 'asc')
                    ->orderBy('month', 'asc')
                    ->get();
                // ->toSQL();
                // var_dump($get_holiday);



            } elseif ($i >= 6) {
                // var_dump("ここは" . $i . "年目");

                $huyo_holiday = "20";
                $max_carry_over = "20";
                $carry_over = $array[$i - 1][6];
                // var_dump($i . "年目のcarry_overは" . $carry_over);


                // 期首残高
                $kisyu_nokori = $huyo_holiday + $carry_over;

                //消化日数
                //年度初めの年月（基準日に$i($i>=6)年足したもの(ex:2016/10が基準日の場合、2022/10~になる))
                $day_min_pre = $kijunbi_year + $i;
                $day_min = $day_min_pre . $kijunbi_month;
                // var_dump($i . "年度初めの年月" . $day_min);

                //年度最後の年月（初年度最後の年に$i($i>=6)年足したもの(ex:2017/3が初年度最後の場合、2023/3~になる))
                $day_max_year = substr($first_day_max, 0, 4);
                $day_max_month = substr($first_day_max, 4, 2);

                $day_max = $day_max_year + $i . $day_max_month;
                // var_dump($i . "年度最後の年月" . $day_max);

                //年度終わりから３ヶ月前の年を抜き出す
                $kijunbi_before3_year = substr($kijunbi_before3->kijunbi_before3, 0, 4);
                // var_dump('基準日の３ヶ月前の年:' . $kijunbi_before3_year);

                //年度終わりから３ヶ月前の月を抜き出す
                $kijunbi_before3_month = substr($kijunbi_before3->kijunbi_before3, 5, 2);
                // var_dump('基準日の３ヶ月前の月:' . $kijunbi_before3_month);

                //年度終わりから３ヶ月前（=warning）
                $warning = $kijunbi_before3_year + $i . $kijunbi_before3_month;
                // var_dump('年度最後の月の三ヶ月前:' . $warning);


                $holiday_count = DB::table('holidays')
                    ->select(DB::raw('sum(day) AS sumday'))
                    //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                    ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    ->where('shain_cd', $id)
                    // ->first();
                    ->get();
                // ->toSQL();
                // var_dump('消化日数:');
                // var_dump($holiday_count);
                //配列で取得された消化日数の一番目を変数にいれる

                //配列で取得された消化日数の一番目を変数にいれる
                foreach ($holiday_count as $counts) {

                    if (is_null($counts->sumday)) {
                        $holiday_count_int = 0;
                    } else {
                        $holiday_count_int = $counts->sumday;
                    }
                }

                //消化残
                $nokori = $kisyu_nokori - $holiday_count_int;

                //繰越日数
                if ($nokori > $max_carry_over) {
                    $carry_over_count = $max_carry_over;
                } else {
                    $carry_over_count = $nokori;
                }

                //月別消化日数
                $get_holiday = DB::table('holidays')
                    ->select('year', 'day', 'month')
                    ->join('employees', 'holidays.shain_cd', '=', 'employees.shain_cd')
                    ->where('employees.shain_cd', $id)
                    //基準日～
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '>=', $day_min)
                    ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    ->orderBy('year', 'asc')
                    ->orderBy('month', 'asc')
                    ->get();
                // ->toSQL();
                // var_dump($get_holiday);
            }

            //[0]付与日数/[1]最大繰り越し日数/[2]前期繰越/[3]期首残高/[4]消化日数/[5]消化残/[6]繰越日数/[7]月別消化日数/[8]年度最後の年月/[9]年度終わりの3ヶ月前
            $array[] = [$huyo_holiday, $max_carry_over, $carry_over, $kisyu_nokori, $holiday_count_int, $nokori, $carry_over_count, $get_holiday, $day_max, $warning];

        }
    

        // var_dump("ここみろ！");
        // var_dump($array[0][9]);
        // var_dump($array[1][9]);
        // var_dump("ここみろ！ここまで");





        //一番最近のデータの年月(0000-00)を作成(=現在日時になる)
        $year_month_a_pre = DB::table('holidays')
            // ->select('year', 'month')
            ->select(db::raw('year,lpad(month, 2, "0") as month'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
        // var_dump($year_month_a_pre);

        //一番最近のデータの年
        $year_month_a1 = $year_month_a_pre->year;
        //一番最近のデータの月
        $year_month_a2 = $year_month_a_pre->month;
        //一番最近のデータの年月（0000年00月）
        $year_month_a = $year_month_a1 . "年" . $year_month_a2 . "月";
        //一番最近のデータの年月（000000：文字なし）
        $year_month_b = $year_month_a1 . $year_month_a2;



        //初回基準日に達していない場合・・・
        $array2 = [];

        // var_dump($year_month_b . ">=" . $nyushabi_year_month . "AND" . $year_month_b . "<" . $kijunbi_year_month);
        if ($year_month_b >= $nyushabi_year_month and $year_month_b < $kijunbi_year_month) {

            // var_dump("ここは研修期間");

            //付与日数
            $huyo_holiday = "3";
            //最大繰り越し日数
            $max_carry_over = "3";
            //前期繰越
            $carry_over = 0;

            // 期首残高（付与日数+前期繰越）
            $kisyu_nokori = $huyo_holiday + $carry_over;

            //消化日数
            $holiday_count = DB::table('holidays')
                ->select(DB::raw('sum(day) AS sumday'))
                //入社日から～基準日
                ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $nyushabi_year_month)
                ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $kijunbi_year_month)
                ->where('shain_cd', $id)
                ->get();
            // ->toSQL();
            // var_dump('初年度の消化日数:');
            // var_dump($holiday_count);

            //配列で取得された消化日数の一番目を変数にいれる
            foreach ($holiday_count as $counts) {

                if (is_null($counts->sumday)) {
                    $holiday_count_int = 0;
                } else {
                    $holiday_count_int = $counts->sumday;
                }
            }

            //消化残（期首残高-消化日数）
            $nokori = $kisyu_nokori - $holiday_count_int;

            //繰越日数（消化残が最大繰り越し日数より大きい場合、繰り越し日数は最大繰り越し日数と同じになる。小さい場合、繰り越し日数は消化残と同じ日数になる。）
            if ($nokori > $max_carry_over) {
                $carry_over_count = $max_carry_over;
            } else {
                $carry_over_count = $nokori;
            }

            //月別消化日数
            $get_holiday = DB::table('holidays')
                ->select('year', 'day', 'month')
                ->join('employees', 'holidays.shain_cd', '=', 'employees.shain_cd')
                ->where('employees.shain_cd', $id)
                //入社日から～基準日
                ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '>=', $nyushabi_year_month)
                ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $kijunbi_year_month)
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();
            // ->toSQL();
            // var_dump($get_holiday);

            //[0]付与日数/[1]最大繰り越し日数/[2]前期繰越/[3]期首残高/[4]消化日数/[5]消化残/[6]繰越日数/[7]月別消化日数
            $array2[] = [$huyo_holiday, $max_carry_over, $carry_over, $kisyu_nokori, $holiday_count_int, $nokori, $carry_over_count, $get_holiday];
        }

        // var_dump("ここみろ！");
        // var_dump($array2);
        // var_dump("ここみろ！ここまで");




        //準社員の人は有給がないので表示しない
        $jun_shain_pre = DB::table('employees')
            ->where('remarks', 'LIKE', "%準社員%")
            ->where('shain_cd', $id)
            ->first();

        if (isset($jun_shain_pre)) {
            $jun_shain = "準社員";
            // var_dump($jun_shain);
        } else {
            // var_dump("正社員");
            $jun_shain = "正社員";
        }



        
        if (($year_month_b >= $nyushabi_year_month and $year_month_b < $kijunbi_year_month) or $jun_shain == "準社員") {
            $array_count = -1;
        } else {
            //選択した年度と配列を結ぶ
            //勤続年数分配列が作られているので、勤続年数分繰り返す
            for ($i = 0; $i <= $kinzoku_year; $i++) {
                //選択した年度が初年度だった場合、配列は0番目？を指定
                if ($post_year == $kijunbi_year) {
                    $array_count = 0;
                    //選択した年度が初年度以外場合、配列は$i番目？を指定
                } elseif ($post_year == $kijunbi_year + $i) {
                    $array_count = $i;
                }
            }
        }
        

        
        // //現在の年を作成
        // $year = date("Y");
        // //現在の月を作成
        // $month = date("m");




        //今月と先月が基準日の人を求める(入社日に+6か月)　※ XXXX-XX-01　XXXX-XXの部分は個人で計算されます。
        // var_dump($kijunbi);
        // foreach ($kijunbi as $kijunbis) {
        //     $kijunbi2 = $kijunbis;
        // }
        // var_dump($kijunbi2);



        // select year,lpad(month, 2, "0") as month from holidays WHERE shain_cd = 2018041011 order by year desc, month desc

        $latest_date = db::table('holidays')
            ->select(db::raw('year as year,lpad(month, 2, "0") as month,day as day'))
            ->where('shain_cd', $id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
        // ->toSQL();

        // var_dump($latest_date);


        if (is_null($latest_date)) {
            $latest_year = 0000;
            $latest_month = 00;
            $latest_day = 00;
        } else {
            $latest_year = $latest_date->year;
            $latest_month = $latest_date->month;
            $latest_day = $latest_date->day;
        }


        // var_dump($latest_date->year);
        // var_dump($latest_date->month);
        // var_dump($latest_date->day);


        // var_dump("個人の最新データ" . $latest_year);
        // var_dump("個人の最新データ" . $latest_month);
        // var_dump("個人の最新データ" . $latest_day);
      





        $top_url = $_POST['top_url'];
        $scroll_top = $_POST['scroll_top2'];

if($year_month_b >= $nyushabi_year_month and $year_month_b < $kijunbi_year_month){
    $mishouka_alert = "no";
}else{
    if($array[$array_count][9] <= $year_month_b AND $array[$array_count][4] <=5){
        $mishouka_alert = "yes";
    }else{
        $mishouka_alert = "no";
    }
}
                                   



        return view('/holiday')->with([
            //社員名で使用する
            'employee' => $employee,
            //社員コードで使用する
            'shain_cd' => $id,
            //選択年度
            'post_year' => $post_year,
            //基準年
            'kijunbi_year' => $kijunbi_year,
            //基準月
            'kijunbi_month' => $kijunbi_month,
            //基準年月
            'kijunbi_year_month' => $kijunbi_year_month,
            //現在の年月-2か月（月日入り）
            'year_month_a' => $year_month_a,
            //現在の年月-2か月（文字なし）
            'year_month_b' => $year_month_b,
            //現在の年月-2か月（年のみ）
            'year_month_a1' => $year_month_a1,
            //現在の年月-2か月（月のみ）
            'year_month_a2' => $year_month_a2,
            // //現在の年
            // 'year' => $year,
            // //現在の月
            // 'month' => $month,
            //入社日年月
            'nyushabi_year_month' => $nyushabi_year_month,
            //準社員かどうか
            'jun_shain' => $jun_shain,
            // //個人の最新データ
            // 'latest_date' => $latest_date,
            //基準月の一か月前（年度終わりの月）
            'first_day_max2' => $first_day_max2,
            //未消化アラート
            'mishouka_alert' => $mishouka_alert,


            'latest_year' => $latest_year,
            'latest_month' => $latest_month,
            'latest_day' => $latest_day,



            //今月と先月が基準日の人を求める
            // 'kijunbi_now' => $kijunbi_now,


            //配列
            'array' => $array,
            //配列2(研修期間中の)
            'array2' => $array2,
            //配列の指定
            'array_count' => $array_count,

            'top_url' => $top_url,
            'scroll_top' => $scroll_top,
            
        ]);

    }
}
