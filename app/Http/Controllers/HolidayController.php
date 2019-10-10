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


        //入社日の取得
        $nyushabi = DB::table('employees')
            ->select('nyushabi')
            ->where('shain_cd', $id)
            ->first();

        //入社年月の抜き出し
        $nyushabi_year = substr($nyushabi->nyushabi, 0, 4);
        $nyushabi_month = substr($nyushabi->nyushabi, 5, 2);
        $nyushabi_year_month = $nyushabi_year . $nyushabi_month;
        // var_dump("入社年月".$nyushabi_year_month);

        // 選択年度最後の月（基準月+11ヶ月）の計算(入社月　+6 -1)
        // $kijunbi_max_pre = DB::table('employees')
        //     ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +5 MONTH) AS "kijunbi_max_pre"'))
        //     ->where('shain_cd', $id)
        //     ->first();
        // ->toSQL();

        // 選択年度最後の月を抜き出す
        // $kijunbi_max_month = substr($kijunbi_max_pre->kijunbi_max_pre, 5, 2);
        // var_dump('選択年度今期最後の月:' . $kijunbi_max_month);

        // 選択された年数に＋1年する (=今期最後の月で使用する年数)
        $day_max_year = $post_year + 1;

        //今期最後の月
        // $day_max = $day_max_year . $kijunbi_max_month;


        //年度始まりの月
        // $day_min = $post_year . $kijunbi_month;


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

        // 勤続年数を計算
        $kinzoku_year = date("Y") - $kijunbi_year;
        // var_dump('勤続年数:' . $kinzoku_year);

        //配列の作成
        $array = [];




        //勤続年数分のデータを配列にいれる。 基準日が2017年だったら、2017年・2018年・2019年分
        for ($i = 0; $i <= $kinzoku_year; $i++) {

            if ($i == 0) {
                var_dump("ここは初年度");

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
                var_dump("初年度最後の月" . $day_max);


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
                    ->get();
                // ->toSQL();
                // var_dump($get_holiday);



            } elseif ($i == 1) {
                var_dump("ここは" . $i . "年目");

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
                var_dump($i . "年度初めの年月" . $day_min);

                //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                $day_max_year = substr($first_day_max, 0, 4);
                $day_max_month = substr($first_day_max, 4, 2);

                $day_max = $day_max_year + $i . $day_max_month;
                var_dump($i . "年度最後の年月" . $day_max);


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
                    ->get();
                // ->toSQL();
                // var_dump($get_holiday);



            } elseif ($i == 2) {
                var_dump("ここは" . $i . "年目");

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
                var_dump($i . "年度初めの年月" . $day_min);

                //年度最後の年月（初年度最後の年に$i($i=2)年足したもの(ex:2017/3が初年度最後の場合、2019/3になる))
                $day_max_year = substr($first_day_max, 0, 4);
                $day_max_month = substr($first_day_max, 4, 2);

                $day_max = $day_max_year + $i . $day_max_month;
                var_dump($i . "年度最後の年月" . $day_max);


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
                    ->get();
                // ->toSQL();
                // var_dump($get_holiday);



            } elseif ($i == 3) {
                var_dump("ここは" . $i . "年目");
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
                var_dump($i . "年度初めの年月" . $day_min);

                //年度最後の年月（初年度最後の年に$i($i=3)年足したもの(ex:2017/3が初年度最後の場合、2020/3になる))
                $day_max_year = substr($first_day_max, 0, 4);
                $day_max_month = substr($first_day_max, 4, 2);

                $day_max = $day_max_year + $i . $day_max_month;
                var_dump($i . "年度最後の年月" . $day_max);


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
                var_dump($i . "年度初めの年月" . $day_min);

                //年度最後の年月（初年度最後の年に$i($i=4)年足したもの(ex:2017/3が初年度最後の場合、2021/3になる))
                $day_max_year = substr($first_day_max, 0, 4);
                $day_max_month = substr($first_day_max, 4, 2);

                $day_max = $day_max_year + $i . $day_max_month;
                var_dump($i . "年度最後の年月" . $day_max);


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
                var_dump($i . "年度初めの年月" . $day_min);

                //年度最後の年月（初年度最後の年に$i($i=5)年足したもの(ex:2017/3が初年度最後の場合、2022/3になる))
                $day_max_year = substr($first_day_max, 0, 4);
                $day_max_month = substr($first_day_max, 4, 2);

                $day_max = $day_max_year + $i . $day_max_month;
                var_dump($i . "年度最後の年月" . $day_max);


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
                    ->get();
                // ->toSQL();
                // var_dump($get_holiday);



            } elseif ($i >= 6) {
                // var_dump("ここは" . $i . "年目");

                $huyo_holiday = "20";
                $max_carry_over = "20";
                $carry_over = $array[$i - 1][6];
                var_dump($i . "年目のcarry_overは" . $carry_over);


                // 期首残高
                $kisyu_nokori = $huyo_holiday + $carry_over;

                //消化日数
                //年度初めの年月（基準日に$i($i>=6)年足したもの(ex:2016/10が基準日の場合、2022/10~になる))
                $day_min_pre = $kijunbi_year + $i;
                $day_min = $day_min_pre . $kijunbi_month;
                var_dump($i . "年度初めの年月" . $day_min);

                //年度最後の年月（初年度最後の年に$i($i>=6)年足したもの(ex:2017/3が初年度最後の場合、2023/3~になる))
                $day_max_year = substr($first_day_max, 0, 4);
                $day_max_month = substr($first_day_max, 4, 2);

                $day_max = $day_max_year + $i . $day_max_month;
                var_dump($i . "年度最後の年月" . $day_max);


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
                    ->get();
                // ->toSQL();
                // var_dump($get_holiday);
            }


            //[0]付与日数/[1]最大繰り越し日数/[2]前期繰越/[3]期首残高/[4]消化日数/[5]消化残/[6]繰越日数/[7]月別消化日数/[8]年度最後の年月
            $array[] = [$huyo_holiday, $max_carry_over, $carry_over, $kisyu_nokori, $holiday_count_int, $nokori, $carry_over_count, $get_holiday, $day_max];
        }

        // var_dump("ここみろ！");
        // var_dump($array[0][6]);
        // var_dump("ここみろ！ここまで");



        //基準月前の有給について…
        //基準年月を作成
        $kijunbi_year_month = $kijunbi_year . $kijunbi_month;
        $array2 = [];
        //現在の年月を作成
        $year_month = date("Ym");


        //初回基準日に達していない場合、付与日数３日で計算
        if ($kijunbi_year_month > $year_month) {

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
                ->get();
            // ->toSQL();
            // var_dump($get_holiday);

            //[0]付与日数/[1]最大繰り越し日数/[2]前期繰越/[3]期首残高/[4]消化日数/[5]消化残/[6]繰越日数/[7]月別消化日数
            $array2[] = [$huyo_holiday, $max_carry_over, $carry_over, $kisyu_nokori, $holiday_count_int, $nokori, $carry_over_count];

            var_dump($array2);
        }




        //アラート用
        //選択年度終わりから３ヶ月前の計算（警告を出すため）
        $kijunbi_before3 = DB::table('employees')
            ->select(db::raw('SUBDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL -3 MONTH) AS "kijunbi_before3"'))
            ->where('shain_cd', $id)
            ->first();
        // ->toSQL();
        // var_dump($kijunbi_before3);

        //選択年度終わりから３ヶ月前の月を抜き出す
        $kijunbi_before3_month = substr($kijunbi_before3->kijunbi_before3, 5, 2);
        // var_dump('基準日の３ヶ月前:' . $kijunbi_before3_month);
        // var_dump($kijunbi_month);

        //選択年度終わりから３ヶ月前（=warning）
        $warning = $day_max_year . $kijunbi_before3_month;
        var_dump('年度最後の月の三ヶ月前:' . $warning);


        var_dump($kinzoku_year);
        if ($kijunbi_year_month > $year_month) {
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


        //現在の年を作成
        $year = date("Y");
        //現在の月を作成
        $month = date("m");
        //一番最近のデータの年月(0000-00)を作成
        $year_month_a_pre = DB::table('holidays')
            ->select('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
        // var_dump($year_month_a_pre);

        $year_month_a1 = $year_month_a_pre->year;
        $year_month_a2 = $year_month_a_pre->month;
        $year_month_a = $year_month_a1 . "年" . $year_month_a2 . "月";

        var_dump($year_month_a2);
        var_dump($year_month_a1);
        var_dump("←ここみて");


        // $collection = collect($array[$array_count][7]);

        // if ($collection->contains("month",$year_month_a2)) {
        //     var_dump("OK");
        // } else {
        //     var_dump("NG");
        // }






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
            //現在の年月
            'year_month' => $year_month,
            //現在の年月-2か月（月日入り）
            'year_month_a' => $year_month_a,
            //現在の年月-2か月（年のみ）
            'year_month_a1' => $year_month_a1,
            //現在の年月-2か月（月のみ）
            'year_month_a2' => $year_month_a2,
            //現在の年
            'year' => $year,
            //現在の月
            'month' => $month,

            //現在の月
            'month' => $month,


            //配列
            'array' => $array,
            //配列2(研修期間中の)
            'array2' => $array2,
            //配列の指定
            'array_count' => $array_count,

            //警告
            'warning' => $warning,


        ]);
    }
}
