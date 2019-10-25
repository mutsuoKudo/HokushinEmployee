<?php

namespace App\Http\Controllers;

use App\Holiday;
use App\Employee;
use DB;
use Illuminate\Support\Carbon;

use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;
use SebastianBergmann\Environment\Console;

class AlertController extends Controller
{
    //未消化アラートが出ている人
    public function mishouka()
    {
        // 必須項目ここから 
        $select_nyusha_year = DB::table('employees')
            ->select(db::raw('distinct DATE_FORMAT(nyushabi, "%Y") as nyushanen'))
            ->whereNull('taishokubi')
            ->orderBy('nyushanen', 'asc')
            ->get();

        $select_taishoku_year = DB::table('employees')
            ->select(db::raw('distinct DATE_FORMAT(taishokubi, "%Y") as taishokunen'))
            ->whereNotNull('taishokubi')
            ->orderBy('taishokunen', 'asc')
            ->get();

        $title = "未消化アラート対象者";
        $mishouka_title = "on";
        // 必須項目ここまで



        // $employees = Employee::all();
        // $employees = DB::table('employees')
        //     ->whereNull('taishokubi')
        //     ->get();

        //アラート用(基準月の3ヶ月前の時点で5日以上取得していないとき（＝未消化）)
        // 基準月から３ヶ月前の計算(基準月の3ヶ月前の時点で5日以上取得していないとき（＝未消化）)

        // 現在の月（現在10月ならデータは8月分なので8月とする）から3か月前の月を計算
        // $before3_1_pre = date('m') - 2 - 3;
        // $before3_1 = str_pad($before3_1_pre, 2, 0, STR_PAD_LEFT);
        // var_dump('3か月前' . $before3_1);

        // $before3_2_pre = date('m') - 2 + 1 - 3;
        // $before3_2 = str_pad($before3_2_pre, 2, 0, STR_PAD_LEFT);
        // var_dump('2か月前' . $before3_2);

        // $before3_3_pre = date('m') - 2 + 2 - 3;
        // $before3_3 = str_pad($before3_3_pre, 2, 0, STR_PAD_LEFT);
        // var_dump('1か月前' . $before3_3);







        $employees_pre = DB::table('employees')
            ->whereNull('taishokubi')
            ->Where(function ($query) {

                $before3_1_pre = date('m') - 2 - 5;
                $before3_1 = str_pad($before3_1_pre, 2, 0, STR_PAD_LEFT);
                // var_dump('入社月が' . $before3_1 . '月のひと');

                $before3_2_pre = date('m') - 2 - 4;
                $before3_2 = str_pad($before3_2_pre, 2, 0, STR_PAD_LEFT);
                // var_dump('入社月が' . $before3_2 . '月のひと');

                $before3_3_pre = date('m') - 2 - 3;
                $before3_3 = str_pad($before3_3_pre, 2, 0, STR_PAD_LEFT);
                // var_dump('入社月が' . $before3_3 . '月のひと');

                $query->orwhere('nyushabi', 'LIKE', "%" . $before3_1 . "%")
                    ->orwhere('nyushabi', 'LIKE', "%" . $before3_2 . "%")
                    ->orWhere('nyushabi', 'LIKE', "%" . $before3_3 . "%");
            })
            ->get();
        // ->toSQL();
        // dd($employees_pre);
        // var_dump($employees_pre);


        // var_dump("date" . $year);

        //  SELECT * FROM `employees`WHERE ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) > 20190801

        // select nyushabi ,ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH), shain_mei from `employees` WHERE taishokubi is NULL AND ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) > '2019-08-01'

        // select * from `employees` where ( `nyushabi` LIKE "%-05-%" or `nyushabi` LIKE "%-06-%" or `nyushabi` LIKE "%-07-%") AND taishokubi is Null


        // 基準月が現在（10月時点でデータが8月末分までなので8月とする）から3か月以内に来る人
        foreach ($employees_pre as $employee) {
            $select_shain_cd[] = [$employee->shain_cd];
        }

        // echo ('<pre>');
        // var_dump("3ヶ月以内に基準月がくる人");
        // var_dump($select_shain_cd);
        // echo ('</pre>');


        // echo ('<pre>');
        // var_dump("3ヶ月以内に基準月がくる人の数" . count($select_shain_cd));
        // echo ('</pre>');

        // 3ヶ月以内に基準月がくる人の数分繰り返す
        for ($i = 0; $i < count($select_shain_cd); $i++) {

            //基準日を求める
            $kijunbi1 = DB::table('employees')
                ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "kijunbi_first"'))
                ->where('shain_cd', $select_shain_cd[$i])
                ->get();

            // 基準月を抜き出す
            foreach ($kijunbi1 as $kijunbi_month_pre1) {
                $kijunbi_month1 = substr($kijunbi_month_pre1->kijunbi_first, 5, 2);
            }

            //本年度の始まりを作成
            $day_min = date('Y') - 1 . $kijunbi_month1;


            //本年度の終わりを計算
            $kijunbi2 = DB::table('employees')
                ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y%m01") , INTERVAL +17 MONTH) AS "kijunbi_end"'))
                ->where('shain_cd', $select_shain_cd[$i])
                ->get();

            // 本年度の終わりの年と月を抜き出す
            foreach ($kijunbi2 as $kijunbi_month_pre2) {
                $kijunbi_month2 = substr($kijunbi_month_pre2->kijunbi_end, 5, 2);
            }

            //本年度終わりの年を計算
            if ($kijunbi_month1 >= 7) {
                $kijunbi_year = date('Y') - 1 + 1;
            } else {
                $kijunbi_year = date('Y') - 1;
            }

            //本年度の始まりを作成
            $day_max = $kijunbi_year . $kijunbi_month2;


            // var_dump("本年度の始まり" . $day_min);
            // var_dump("本年度の終わり" . $day_max);

            // 本年度の有給取得数を計算
            $holiday_count = DB::table('holidays')
                ->select(DB::raw('sum(day) AS sumday'))
                //基準日から一年分
                ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                ->where('shain_cd', $select_shain_cd[$i])
                ->get();
            // dd($holiday_count);
            // ->toSQL();


            // 1日も休んでいない場合、0を代入。休んでいる場合はその日数を取得。
            foreach ($holiday_count as $counts) {
                if (is_null($counts->sumday)) {
                    $holiday_count_int = "0";
                    // $test = "中身からっぽだ！";
                } else {
                    $holiday_count_int = $counts->sumday;
                    // $test = "中身はいってる！";
                }
            }

            // var_dump($test);
            // var_dump("取得日数" . $holiday_count_int);

            // 取得日数が5日未満の人は配列に社員コードを入れ、5日以上取得している人は0を入れる。
            if ($holiday_count_int < 5) {
                $select_employee[] = $select_shain_cd[$i];
                // var_dump("休んでない人1");
            } else {
                $select_employee[] = "0";
                // var_dump("休んでる人1");
            }

            // echo ('<pre>');
            // var_dump("休んでない人は社員コード入れる");
            // var_dump($select_employee);
            // echo ('</pre>');

        }


        // 5日以上休んでいない人だけの社員コードはいれつを作成
        for ($i = 0; $i < count($select_shain_cd); $i++) {
            // var_dump("select_employee");
            // var_dump($select_employee[$i]);
            if ($select_employee[$i] == 0) {
                // var_dump("休んでる人");
            } else {
                // var_dump("休んでる人");
                $select_shain_cd2[] = $select_employee[$i];
            }
        }

        // var_dump("休んでない人の社員コード");
        // var_dump($select_shain_cd2);





        // 5日以上休んでいない人の人数分繰り返す
        for ($i = 0; $i < count($select_shain_cd2); $i++) {
            // if ($select_shain_cd2[$i] == 0) {
            //     // var_dump("休んでる人なのでWHEREにいれない");
            // } else {


            // 初回基準日の計算（初回基準月が来ていない人は表示しないようにするために必要）
            $first_kijunbi = DB::table('employees')
                ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y%m01") , INTERVAL +6 MONTH) AS "first_kijunbi"'))
                ->where('shain_cd', $select_shain_cd2[$i])
                ->get();

            // 初回基準日の年と月をそれぞれ抜き出す
            foreach ($first_kijunbi as $f_kijunbi) {
                $first_kijunbi_year = substr($f_kijunbi->first_kijunbi, 0, 4);
                $first_kijunbi_month = substr($f_kijunbi->first_kijunbi, 5, 2);
            }


            // var_dump('初回基準年');
            // var_dump((int) $first_kijunbi_year);
            // var_dump('初回基準月');
            // var_dump((int) $first_kijunbi_month);
            // }


            // var_dump('ここから');



            // 初回基準年と初回基準月を現在の年月と比べる
            if ($first_kijunbi_year < date('Y')) {
                $first_kijunbi_year_result_pre = 'small';
                // var_dump('year_small');
            } elseif ($first_kijunbi_year = date('Y')) {
                $first_kijunbi_year_result_pre = 'same';
                // var_dump('year_same');
            } else {
                $first_kijunbi_year_result_pre = 'large';
                // var_dump('year_large');

            }


            if ($first_kijunbi_month < (date('m') - 2)) {
                $first_kijunbi_month_result_pre = 'small';
                // var_dump('month_small');
            } elseif ($first_kijunbi_month == (date('m') - 2)) {
                $first_kijunbi_month_result_pre = 'same';
                // var_dump('month_same');
            } else {
                $first_kijunbi_month_result_pre = 'large';
                // var_dump('month_large');

            }

            // var_dump($first_kijunbi_year_result_pre);
            // var_dump($first_kijunbi_month_result_pre);


            // 基準年が現在の年数より小さいとき(月数がなんでも))、基準年が現在年と同じで、月が現在年より小さいか同じの時は表示する。
            if (
                $first_kijunbi_year_result_pre == 'small' and ($first_kijunbi_month_result_pre == 'small' or $first_kijunbi_month_result_pre == 'same' or $first_kijunbi_month_result_pre == 'large')
                or ($first_kijunbi_year_result_pre == 'same' and ($first_kijunbi_month_result_pre == 'small' or $first_kijunbi_month_result_pre == 'same'))
            ) {
                // 表示する
                $first_kijunbi_result = 'true';
                // var_dump('true');
            } else {
                // 表示しない
                $first_kijunbi_result = 'false';
                // var_dump('false');

            }
            // var_dump('ここまで');

            // if($first_kijunbi_year_result_pre == 'small'){
            //     $first_kijunbi_result = 'true';
            //     var_dump('true');
            // }else{
            //     $first_kijunbi_result = 'false';
            //     var_dump('false');

            // }

            // var_dump('ここみて');
            // var_dump($first_kijunbi_result == 'true');

            // 基準月が3か月以内に来る人かつ有給を５日以上取得していない人かつ初回基準月以降のひとだけの社員コードはいれつを作成
            if ($first_kijunbi_result == 'true') {
                $select_shain_cd3[] = $select_shain_cd2[$i];
            }

            // var_dump('ここみて');
            // var_dump($select_shain_cd3);

            // $employees_prepre = DB::table('employees')
            //     ->whereNull('taishokubi')
            //     ->where('shain_cd', $select_shain_cd2[$i])
            //     ->get();
            // // ->toSQL();




            // $employees2[] = [$employees_prepre];
        }

        // 基準月が3か月以内に来る人かつ有給を５日以上取得していない人かつ初回基準月以降のひとだけの社員コードに該当する社員情報を取得
        for ($i = 0; $i < count($select_shain_cd3); $i++) {

            // employeesテーブルに入っている情報
            $employees_prepre = DB::table('employees')
                ->whereNull('taishokubi')
                ->where('shain_cd', $select_shain_cd3[$i])
                ->get();
            // ->toSQL();


            // 基準月の計算
            $select_employees_kijunbi = DB::table('employees')
                ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "select_kijunbi"'))
                ->where('shain_cd', $select_shain_cd3[$i])
                ->get();

            // 基準月を抜き出す
            foreach ($select_employees_kijunbi as $select_employees_kijunbis) {
                $select_employees_kijunbi_month = substr($select_employees_kijunbis->select_kijunbi, 5, 2);
            }







            //本年度の始まりを作成
            $select_day_min = date('Y') - 1 . $select_employees_kijunbi_month;

            //本年度の終わりを計算
            $select_kijunbi2 = DB::table('employees')
                ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y%m01") , INTERVAL +17 MONTH) AS "select_kijunbi_end"'))
                ->where('shain_cd', $select_shain_cd3[$i])
                ->get();

            // 本年度の終わりの月を抜き出す
            foreach ($select_kijunbi2 as $selecT_kijunbi_month_pre2) {
                $select_kijunbi_month2 = substr($selecT_kijunbi_month_pre2->select_kijunbi_end, 5, 2);
            }



            //本年度終わりの年を計算
            if ($select_employees_kijunbi_month >= 7) {
                $select_kijunbi_year = date('Y') - 1 + 1;
            } else {
                $select_kijunbi_year = date('Y') - 1;
            }

            //本年度の始まりを作成
            $select_day_max = $select_kijunbi_year . $select_kijunbi_month2;



            // 本年度の有給取得数を計算
            $select_holiday_count = DB::table('holidays')
                ->select(DB::raw('sum(day) AS sumday'))
                //基準日から一年分
                ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $select_day_min)
                ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $select_day_max)
                ->where('shain_cd', $select_shain_cd3[$i])
                ->get();
            // dd($select_holiday_count);
            // ->toSQL();

            if (is_null($select_holiday_count)) {
                $select_holiday_count = 0;
            }


            // var_dump("ここみて");
            // var_dump($select_holiday_count);




            // 社員情報と基準月、取得日数を配列に格納
            $employees2[] = [$employees_prepre, $select_employees_kijunbi_month, $select_holiday_count];
        }




        // /配列の作成
        $array = [];



        // var_dump('対象者人数');
        // var_dump(count($select_shain_cd3));
        //対象者人数-1回繰り返す
        for ($i = 0; $i < count($select_shain_cd3); $i++) {

            //基準日の計算(入社日に+6か月)　※ XXXX-XX-01　XXXX-XXの部分は個人で計算されます。
            $kijunbi = DB::table('employees')
                ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "kijunbi"'))
                ->where('shain_cd', $select_shain_cd3[$i])
                ->get();


            // 基準年を抜き出す
            $kijunbi_year = substr($kijunbi[0]->kijunbi, 0, 4);
            // var_dump('基準年:' . $kijunbi_year);

            // 基準月を抜き出す
            $kijunbi_month = substr($kijunbi[0]->kijunbi, 5, 2);
            // var_dump('基準月:' . $kijunbi_month);


            //  勤続年数を計算
            $year_pre =  date("Ym", strtotime("-2 month"));
            $year = substr($year_pre, 0, 4);
            $month = substr($year_pre, 4, 2);
            // var_dump('現在年:' . $year);
            // var_dump('現在月:' . $month);

            if ($kijunbi_year + 1 >= $year and $kijunbi_month >= $month) {
                // var_dump('初年度');
                $kinzoku_year = 0;
            } else {
                // var_dump('初年度以降');
                $kinzoku_year = $year - $kijunbi_year - 1;
            }

            // $kinzoku_year = $year - $kijunbi_year;

            // var_dump($kinzoku_year);


            //入社日の取得
            $nyushabi = DB::table('employees')
                ->select('nyushabi')
                ->where('shain_cd', $select_shain_cd3[$i])
                ->get();

            // var_dump('入社日');
            // var_dump($nyushabi);

            //入社年月の抜き出し
            $nyushabi_year = substr($nyushabi[0]->nyushabi, 0, 4);
            $nyushabi_month = substr($nyushabi[0]->nyushabi, 5, 2);
            $nyushabi_year_month = $nyushabi_year . $nyushabi_month;



            //初年度最後の月
            // $first_day_max = $kijunbi_year + 1 . $kijunbi_max_month;
            $first_day_max_pre = DB::table('employees')
                ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +17 MONTH) AS "first_day_max_pre"'))
                ->where('shain_cd', $select_shain_cd3[$i])
                ->first();

            $first_day_max1 = substr($first_day_max_pre->first_day_max_pre, 0, 4);
            $first_day_max2 = substr($first_day_max_pre->first_day_max_pre, 5, 2);
            $first_day_max = $first_day_max1 . $first_day_max2;
            // var_dump("初年度最後の月" . $first_day_max);



            // echo ('<pre>');
            // var_dump('対象者CD');
            // var_dump($select_shain_cd3[$i]);
            // var_dump('対象者基準日');
            // var_dump($kijunbi);
            // var_dump('勤続年数:' . $kinzoku_year);
            // var_dump("入社年月" . $nyushabi_year_month);
            // echo ('</pre>');

            $kinzoku_array[] = [$kinzoku_year, $nyushabi_year_month];
        }

        // echo ('<pre>');
        // var_dump('勤続年数と入社日');
        // var_dump($kinzoku_array[0]);
        // var_dump($kinzoku_array[1]);
        // var_dump($kinzoku_array[2]);
        // var_dump($kinzoku_array);
        // echo ('</pre>');





        //対象者人数分繰り返す
        for ($i = 0; $i < count($select_shain_cd3); $i++) {
            // echo ('<pre>');
            // var_dump('$iの動き');
            // var_dump($i);
            // echo ('</pre>');
            //勤続年数分繰り返す。$kinzoku_arrayは[0]から始まるので、2人分の勤続年数データが入っている場合、勤続年数配列は0～1までしか入っていないので、人数-1回分となる。
            // for ($d = 0; $d <= $kinzoku_array[$i]; $d++) {
            for ($d = 0; $d <= $kinzoku_array[$i][0]; $d++) {
                // for ($d = 0; $d <= $kinzoku_array[2]; $d++) {
                // echo ('<pre>');
                // var_dump('$dの動き');
                // var_dump($d);
                // var_dump($kinzoku_array[$i]);
                // echo ('</pre>');

                if ($d == 0) {
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
                        ->where('shain_cd', $select_shain_cd3[$i])
                        ->first();

                    $day_max1 = substr($day_max_pre->day_max_pre, 0, 4);
                    $day_max2 = substr($day_max_pre->day_max_pre, 5, 2);
                    //年度最後の年月
                    $day_max = $day_max1 . $day_max2;

                    // echo ('<pre>');
                    // var_dump('0年目');
                    // var_dump($select_shain_cd3[$i]);
                    // var_dump($day_max_pre);
                    // var_dump($nyushabi_year_month);
                    // var_dump($kinzoku_array[$i][1]);
                    // var_dump($day_max);
                    // echo ('</pre>');


                    $holiday_count = DB::table('holidays')
                        ->select(DB::raw('sum(day) AS sumday'))
                        //入社日から～基準日に1年度分（11ヶ月）足したもの(ex:2016/10/1入社なら、2016/10/1~（基準日が2017/4/1なので）2018/3/1まで))
                        // ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $nyushabi_year_month)
                        ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $kinzoku_array[$i][1])
                        ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                        ->where('shain_cd', $select_shain_cd3[$i])
                        ->get();
                    // ->toSQL();
                    // var_dump('初年度の消化日数:');
                    // var_dump($holiday_count);
                    // var_dump($nyushabi_year_month);
                    // var_dump($day_max);
                    // dd($holiday_count);

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
                } elseif ($d == 1) {
                    // var_dump("ここは" . $i . "年目");

                    //付与日数
                    $huyo_holiday = "11";
                    //最大繰り越し日数
                    $max_carry_over = "11";

                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    if ($array[$i][6] > $max_carry_over) {
                        $carry_over = $max_carry_over;
                    } else {
                        $carry_over = $array[$i][6];
                    }

                    // 期首残高
                    $kisyu_nokori = $huyo_holiday + $carry_over;

                    //消化日数
                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min_pre = $kijunbi_year + $d;
                    $day_min = $day_min_pre . $kijunbi_month;
                    // var_dump($i . "年度初めの年月" . $day_min);

                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max_year = substr($first_day_max, 0, 4);
                    $day_max_month = substr($first_day_max, 4, 2);

                    $day_max = $day_max_year + $d . $day_max_month;
                    // var_dump($i . "年度最後の年月" . $day_max);

                    // echo ('<pre>');
                    // var_dump('一年目');
                    // var_dump($day_min);
                    // var_dump($day_max);
                    // echo ('</pre>');


                    $holiday_count = DB::table('holidays')
                        ->select(DB::raw('sum(day) AS sumday'))
                        //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                        ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                        ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                        ->where('shain_cd', $select_shain_cd3[$i])
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
                } elseif ($d == 2) {
                    // var_dump("ここは" . $i . "年目");

                    $huyo_holiday = "12";
                    $max_carry_over = "12";
                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    if ($array[$d + 1][6] > $max_carry_over) {
                        $carry_over = $max_carry_over;
                    } else {
                        $carry_over = $array[$d + 1][6];
                    }


                    // var_dump('ここから');
                    // echo ('<pre>');
                    // var_dump($d);
                    // var_dump($array[$d+1][6]);
                    // var_dump($max_carry_over);
                    // var_dump('付与日数'.$huyo_holiday);
                    // var_dump('前期繰越'.$carry_over);
                    // echo ('</pre>');


                    // 期首残高
                    $kisyu_nokori = $huyo_holiday + $carry_over;

                    //消化日数
                    //年度初めの年月（基準日に$i($i=2)年足したもの(ex:2016/10が基準日の場合、2018/10になる))
                    $day_min_pre = $kijunbi_year + $d;
                    $day_min = $day_min_pre . $kijunbi_month;
                    // var_dump($i . "年度初めの年月" . $day_min);

                    //年度最後の年月（初年度最後の年に$i($i=2)年足したもの(ex:2017/3が初年度最後の場合、2019/3になる))
                    $day_max_year = substr($first_day_max, 0, 4);
                    $day_max_month = substr($first_day_max, 4, 2);

                    $day_max = $day_max_year + $d . $day_max_month;
                    // var_dump($i . "年度最後の年月" . $day_max);

                    // echo ('<pre>');
                    // var_dump($day_min);
                    // var_dump($day_max);
                    // echo ('</pre>');

                    $holiday_count = DB::table('holidays')
                        ->select(DB::raw('sum(day) AS sumday'))
                        //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                        ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                        ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                        ->where('shain_cd', $select_shain_cd3[$i])
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
                } elseif ($d == 3) {
                    // var_dump("ここは" . $i . "年目");
                    $huyo_holiday = "14";
                    $max_carry_over = "14";
                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    if ($array[$d + 1][6] > $max_carry_over) {
                        $carry_over = $max_carry_over;
                    } else {
                        $carry_over = $array[$d + 1][6];
                    }

                    // 期首残高
                    $kisyu_nokori = $huyo_holiday + $carry_over;

                    //消化日数
                    //年度初めの年月（基準日に$i($i=3)年足したもの(ex:2016/10が基準日の場合、2019/10になる))
                    $day_min_pre = $kijunbi_year + $d;
                    $day_min = $day_min_pre . $kijunbi_month;
                    // var_dump($i . "年度初めの年月" . $day_min);

                    //年度最後の年月（初年度最後の年に$i($i=3)年足したもの(ex:2017/3が初年度最後の場合、2020/3になる))
                    $day_max_year = substr($first_day_max, 0, 4);
                    $day_max_month = substr($first_day_max, 4, 2);

                    $day_max = $day_max_year + $d . $day_max_month;
                    // var_dump($i . "年度最後の年月" . $day_max);

                    // echo ('<pre>');
                    // var_dump($day_min);
                    // var_dump($day_max);
                    // echo ('</pre>');


                    $holiday_count = DB::table('holidays')
                        ->select(DB::raw('sum(day) AS sumday'))
                        //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                        ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                        ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                        ->where('shain_cd', $select_shain_cd3[$i])
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
                } elseif ($d == 4) {
                    // var_dump("ここは" . $i . "年目");
                    $huyo_holiday = "16";
                    $max_carry_over = "16";
                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    if ($array[$d + 1][6] > $max_carry_over) {
                        $carry_over = $max_carry_over;
                    } else {
                        $carry_over = $array[$d + 1][6];
                    }

                    // 期首残高
                    $kisyu_nokori = $huyo_holiday + $carry_over;

                    //消化日数
                    //年度初めの年月（基準日に$i($i=4)年足したもの(ex:2016/10が基準日の場合、2020/10になる))
                    $day_min_pre = $kijunbi_year + $d;
                    $day_min = $day_min_pre . $kijunbi_month;
                    // var_dump($i . "年度初めの年月" . $day_min);

                    //年度最後の年月（初年度最後の年に$i($i=4)年足したもの(ex:2017/3が初年度最後の場合、2021/3になる))
                    $day_max_year = substr($first_day_max, 0, 4);
                    $day_max_month = substr($first_day_max, 4, 2);

                    $day_max = $day_max_year + $d . $day_max_month;
                    // var_dump($i . "年度最後の年月" . $day_max);

                    // echo ('<pre>');
                    // var_dump($day_min);
                    // var_dump($day_max);
                    // echo ('</pre>');


                    $holiday_count = DB::table('holidays')
                        ->select(DB::raw('sum(day) AS sumday'))
                        //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                        ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                        ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                        ->where('shain_cd', $select_shain_cd3[$i])
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
                } elseif ($d == 5) {
                    // var_dump("ここは" . $i . "年目");

                    $huyo_holiday = "18";
                    $max_carry_over = "18";
                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    if ($array[$d + 1][6] > $max_carry_over) {
                        $carry_over = $max_carry_over;
                    } else {
                        $carry_over = $array[$d + 1][6];
                    }

                    // 期首残高
                    $kisyu_nokori = $huyo_holiday + $carry_over;

                    //消化日数
                    //年度初めの年月（基準日に$i($i=5)年足したもの(ex:2016/10が基準日の場合、2021/10になる))
                    $day_min_pre = $kijunbi_year + $d;
                    $day_min = $day_min_pre . $kijunbi_month;
                    // var_dump($i . "年度初めの年月" . $day_min);

                    //年度最後の年月（初年度最後の年に$i($i=5)年足したもの(ex:2017/3が初年度最後の場合、2022/3になる))
                    $day_max_year = substr($first_day_max, 0, 4);
                    $day_max_month = substr($first_day_max, 4, 2);

                    $day_max = $day_max_year + $d . $day_max_month;
                    // var_dump($i . "年度最後の年月" . $day_max);

                    // echo ('<pre>');
                    // var_dump($day_min);
                    // var_dump($day_max);
                    // echo ('</pre>');

                    $holiday_count = DB::table('holidays')
                        ->select(DB::raw('sum(day) AS sumday'))
                        //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                        ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                        ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                        ->where('shain_cd', $select_shain_cd3[$i])
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
                } elseif ($d >= 6) {
                    // var_dump("ここは" . $i . "年目");

                    $huyo_holiday = "20";
                    $max_carry_over = "20";
                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    if ($array[$d + 1][6] > $max_carry_over) {
                        $carry_over = $max_carry_over;
                    } else {
                        $carry_over = $array[$d + 1][6];
                    }

                    // 期首残高
                    $kisyu_nokori = $huyo_holiday + $carry_over;

                    //消化日数
                    //年度初めの年月（基準日に$i($i>=6)年足したもの(ex:2016/10が基準日の場合、2022/10~になる))
                    $day_min_pre = $kijunbi_year + $d;
                    $day_min = $day_min_pre . $kijunbi_month;
                    // var_dump($i . "年度初めの年月" . $day_min);

                    //年度最後の年月（初年度最後の年に$i($i>=6)年足したもの(ex:2017/3が初年度最後の場合、2023/3~になる))
                    $day_max_year = substr($first_day_max, 0, 4);
                    $day_max_month = substr($first_day_max, 4, 2);

                    $day_max = $day_max_year + $d . $day_max_month;
                    // var_dump($i . "年度最後の年月" . $day_max);

                    // echo ('<pre>');
                    // var_dump($day_min);
                    // var_dump($day_max);
                    // echo ('</pre>');


                    $holiday_count = DB::table('holidays')
                        ->select(DB::raw('sum(day) AS sumday'))
                        //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                        ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                        ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                        ->where('shain_cd', $select_shain_cd3[$i])
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
                }
                //[0]付与日数/[1]最大繰り越し日数/[2]前期繰越/[3]期首残高/[4]消化日数/[5]消化残/[6]繰越日数/[7]年度最後の年月/
                $array[] = [$huyo_holiday, $max_carry_over, $carry_over, $kisyu_nokori, $holiday_count_int, $nokori, $carry_over_count, $day_max, $select_shain_cd3[$i]];
            }
        }
        // echo ('<pre>');
        // var_dump($array);
        // var_dump($res);
        // var_dump($pu);
        // echo ('</pre>');

        // $zan_holiday = [];

        for ($i = 0; $i < count($select_shain_cd3); $i++) {
            //社員の有給情報の入ったはいれつの数分だけ繰り返す
            for ($y = 0; $y < count($array); $y++) {

                if ($array[$y][8][0] == $select_shain_cd3[$i][0]) {
                    // echo ('<pre>');
                    // var_dump('はいってるよ！');
                    // var_dump($select_shain_cd3[$i][0]);
                    // var_dump($array[$y][8][0]);
                    //     var_dump($array[$y][8][0] == $select_shain_cd3[$i][0]);
                    // echo ('</pre>');

                    // $zan_holiday_personal_pre = [
                    //     $select_shain_cd3[$i][0] =>
                    //     [$array[$y][5], $select_shain_cd3[$i][0]]
                    // ];

                    $zan_holiday_personal[$select_shain_cd3[$i][0]] = [$array[$y][5], $select_shain_cd3[$i][0]];
                    // $zan_holiday_personal = array_merge($zan_holiday, $zan_holiday_personal_pre);

                    // var_dump('配列名');
                    // var_dump($zan_holiday_personal);
                } else {
                    // echo ('<pre>');
                    // var_dump('はいってないよ！');
                    // var_dump($select_shain_cd3[$i][0]);
                    // var_dump($array[$y][8][0]);
                    //     var_dump($array[$y][8][0] == $select_shain_cd3[$i][0]);
                    // echo ('</pre>');
                }
                // echo ('<pre>');
                // var_dump($select_shain_cd3[$i][0]);
                // var_dump($array[$y][8]);
                // echo ('</pre>');
                // $res = array_search($select_shain_cd3[$i][0], $array[$y][8]);


            }
        }

        // echo ('<pre>');
        // var_dump('ここから');
        //     var_dump($zan_holiday_personal);

        // var_dump($pu[$select_shain_cd3[0][0]]);
        // var_dump($select_shain_cd3[0]);
        // var_dump($select_shain_cd3[1]);
        // var_dump($select_shain_cd3[2]);
        // var_dump('ここまで');
        // echo ('</pre>');







        // echo ('<pre>');
        // var_dump($employee_pre2);
        // var_dump($employees2[0][0][0]->shain_cd);
        // var_dump($employees2[0][0][0]->shain_cd);
        // var_dump($employees2[0][2][0]->sumday);
        // var_dump($employees2);
        // var_dump($array);
        // var_dump($array[1][5]);
        // var_dump($array[2]);
        // var_dump($array[2][4]);
        // echo ('</pre>');

        // echo ('<pre>');
        // var_dump("休んでない人の情報");
        // var_dump($employees);
        // echo ('</pre>');


        // echo ('<pre>');
        // var_dump("休んでない人の情報ここみろ");
        // var_dump($employees2[0][1]);
        // var_dump($employees2[1][1]);
        // var_dump($employees2[2][1]);
        // var_dump($employees2[3][1]);
        // echo ('</pre>');






        return view('/alert')->with([
            'title' => $title,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
            // 未消化アラートの説明
            'mishouka_title' => $mishouka_title,
            // 社員情報はいれつ
            'employees2' => $employees2,
            // 該当する社員コードの入った配列
            'select_shain_cd3' => $select_shain_cd3,
            // 現在の月
            'month' => date('m') - 2,
            // 消化残等が入った配列
            'array' => $array,

            'zan_holiday_personal' => $zan_holiday_personal,




        ]);
    }












    //残数僅少アラートが出ている人
    public function zansu_kinshou()
    {
        // 必須項目ここから 
        $select_nyusha_year = DB::table('employees')
            ->select(db::raw('distinct DATE_FORMAT(nyushabi, "%Y") as nyushanen'))
            ->whereNull('taishokubi')
            ->orderBy('nyushanen', 'asc')
            ->get();

        $select_taishoku_year = DB::table('employees')
            ->select(db::raw('distinct DATE_FORMAT(taishokubi, "%Y") as taishokunen'))
            ->whereNotNull('taishokubi')
            ->orderBy('taishokunen', 'asc')
            ->get();

        $title = "残数僅少アラート対象者";
        // 必須項目ここまで


        // $employees = Employee::all();
        $employees_count = DB::table('employees')
            ->whereNull('taishokubi')
            ->get();

        for ($i = 0; $i < count($employees_count); $i++) {
            $shain_cd_array[] = $employees_count[$i]->shain_cd;
        }

        // echo ('<pre>');
        // var_dump($employees[0]->shain_cd);
        // var_dump($shain_cd_array);
        // echo ('</pre>');


        // $kijunbi_array = [];

        for ($i = 0; $i < count($employees_count); $i++) {

            //入社日を求める
            $nyushabi = DB::table('employees')
                ->select('nyushabi')
                ->whereNull('taishokubi')
                ->get();

            // echo ('<pre>');
            // var_dump($nyushabi[$i]->nyushabi);
            // var_dump($nyushabi);
            // echo ('</pre>'); 

            // 入社日フォーマット
            // foreach ($nyushabi as $nyushabi_year_month) {
            $nyushabi_year = substr($nyushabi[$i]->nyushabi, 0, 4);
            $nyushabi_month = substr($nyushabi[$i]->nyushabi, 5, 2);
            // }

            $first_day_min = $nyushabi_year . $nyushabi_month;

            // echo ('<pre>');
            // var_dump($nyushabi[$i]->nyushabi);
            // var_dump($first_day_min);
            // echo ('</pre>');

            //基準日を求める
            $kijunbi = DB::table('employees')
                ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "kijunbi_first"'))
                ->where('shain_cd', $shain_cd_array[$i])
                ->whereNull('taishokubi')
                ->get();

            // echo ('<pre>');
            // var_dump($kijunbi[0]->kijunbi_first);
            // echo ('</pre>');

            // 初回基準年月を抜き出す
            foreach ($kijunbi as $first_kijunbi_year_month_pre) {
                $first_kijunbi_year = substr($first_kijunbi_year_month_pre->kijunbi_first, 0, 4);
                $first_kijunbi_month = substr($first_kijunbi_year_month_pre->kijunbi_first, 5, 2);
            }

            //初年度の終わり（=初回基準月）の作成
            $first_day_max = $first_kijunbi_year . $first_kijunbi_month - 1;

            // //1年目の年度終わり月を計算
            // $day_max_pre = DB::table('employees')
            //     ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +17 MONTH) AS "kijunbi"'))
            //     ->where('shain_cd', $shain_cd_array[$i])
            //     ->whereNull('taishokubi')
            //     ->get();

            // //  1年目の年度終わり年月を抜き出す
            // foreach ($day_max_pre as $dmp) {
            //     $day_max_pre_year = substr($dmp->kijunbi, 0, 4);
            //     $day_max_pre_month = substr($dmp->kijunbi, 5, 2);
            // }


            // //1年目の年度終わりの作成
            // $day_max_pre2 = $day_max_pre_year . $day_max_pre_month;

            // echo ('<pre>');
            //     var_dump($day_max_pre);
            //     var_dump($day_max_pre_year);
            //     var_dump($day_max_pre_month);
            //     var_dump($day_max_pre2);
            //     echo ('</pre>');



            // [0]初年度の基準年/[1]初年度の基準月/[2]入社年月（=初年度の始まり）/[3]初年度の終わり/[4]１年目の年度終わり
            $first_kijunbi_array[] = [$first_kijunbi_year, $first_kijunbi_month, $first_day_min, $first_day_max];

            // [0]入社日（月と日）/[1]基準日（月と日）
            $kijunbi_array[] = [$nyushabi[$i]->nyushabi, $kijunbi[0]->kijunbi_first];
        }


        echo ('<pre>');
        // var_dump($kijunbi_array);
        // var_dump("ここ");
        // var_dump($first_kijunbi_array[4][4]);
        // var_dump($first_kijunbi_array[0]);
        echo ('</pre>');


        //  勤続年数を計算
        // 現在の年月を抜き出す
        $year_pre =  date("Ym", strtotime("-2 month"));
        $year = substr($year_pre, 0, 4);
        $month = substr($year_pre, 4, 2);
        // var_dump('現在年:' . $year);
        // var_dump('現在月:' . $month);

        for ($i = 0; $i < count($employees_count); $i++) {
            // 初回基準年が現在年より大きいもしくは同じ　かつ　初回基準月が現在月より大きいもしくは同じの場合、勤続年数は0年（初年度になる）

            // if ($first_kijunbi_array[$i][0] + 1 >= $year and $first_kijunbi_array[$i][1] >= $month) {
            // if ($first_kijunbi_array[$i][0] + 1 > $year OR ($first_kijunbi_array[$i][0] + 1 == $year AND $first_kijunbi_array[$i][1] >= $month)) {

            // echo ('<pre>');
            // var_dump($first_kijunbi_array[$i][0] + 1);
            // var_dump((int)$year);
            // var_dump((int)$first_kijunbi_array[$i][1]);
            // var_dump((int)$month);
            // echo ('</pre>');

            if ($first_kijunbi_array[$i][0] + 1 > (int) $year or ($first_kijunbi_array[$i][0] + 1 == $year and (int) $first_kijunbi_array[$i][1] > $month)) {
                // echo ('<pre>');
                // var_dump('初年度');
                // echo ('</pre>');
                $kinzoku_year[] = 0;
            } else {
                // echo ('<pre>');
                // var_dump('初年度以降');
                // echo ('</pre>');
                $kinzoku_year[] = $year - $first_kijunbi_array[$i][0] - 1;
            }

            // echo ('<pre>');
            // // var_dump($shain_cd_array[$i]);
            // // var_dump($first_kijunbi_array[$i][0]);
            // var_dump($first_kijunbi_array[32][0] + 1 .'>='. $year);
            // // var_dump($year);
            // var_dump($first_kijunbi_array[32][1] .'>='. $month);
            // var_dump($kinzoku_year);
            // // var_dump($month);
            // echo ('</pre>');

            $kinzoku_year_array[] = $kinzoku_year[$i];
        }

        // echo ('<pre>');
        // var_dump($kinzoku_year_array);
        // // var_dump(count($kinzoku_year));
        // echo ('</pre>');


        $array = [];

        //対象者人数分繰り返す
        // for ($i = 0; $i < count($employees_count); $i++) {
        for ($i = 0; $i < 1; $i++) {
            for ($d = 0; $d <= $kinzoku_year_array[$i]; $d++) {

                if ($d == 0) {
                    var_dump("ここは初年度");

                    //付与日数
                    $huyo_holiday = "10";
                    //最大繰り越し日数
                    $max_carry_over = "10";
                    //前期繰越
                    $carry_over = 0;

                    // 期首残高（付与日数+前期繰越）
                    $kisyu_nokori = $huyo_holiday + $carry_over;


                    echo ('<pre>');
                    // var_dump('0年目');
                    var_dump('社員コード' . $shain_cd_array[$i]);
                    var_dump('年度始まり' . $first_kijunbi_array[$i][2]);
                    var_dump('年度終わり' . $first_kijunbi_array[$i][3]);
                    echo ('</pre>');

                    //消化日数
                    $holiday_count = DB::table('holidays')
                        ->select(DB::raw('sum(day) AS sumday'))
                        //入社日から～基準日に1年度分（11ヶ月）足したもの(ex:2016/10/1入社なら、2016/10/1~（基準日が2017/4/1なので）2018/3/1まで))
                        // ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $nyushabi_year_month)
                        ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $first_kijunbi_array[$i][2])
                        ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $first_kijunbi_array[$i][3])
                        ->where('shain_cd', $shain_cd_array[$i])
                        ->get();
                    // ->toSQL();
                    // var_dump('初年度の消化日数:');
                    // var_dump($holiday_count);
                    // var_dump($nyushabi_year_month);
                    // var_dump($day_max);
                    // dd($holiday_count);

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
                } elseif ($d == 1) {
                    var_dump("ここは" . $d . "年目");

                    //付与日数
                    $huyo_holiday = "11";
                    //最大繰り越し日数
                    $max_carry_over = "11";

                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    if ($array[$i][6] > $max_carry_over) {
                        $carry_over = $max_carry_over;
                    } else {
                        $carry_over = $array[$i][6];
                    }

                    // 期首残高
                    $kisyu_nokori = $huyo_holiday + $carry_over;

                    //消化日数
                    //年度初めの年月
                    $day_min_pre = $first_kijunbi_array[$i][0];
                    $day_min = $day_min_pre . $first_kijunbi_array[$i][1];
                    // var_dump($d . "年度初めの年月" . $day_min);

                    // //年度最後の年月
                    // $day_max_year = substr($first_kijunbi_array[$i][4], 0, 4);
                    // $day_max_month = substr($first_kijunbi_array[$i][4], 4, 2);

                    // $day_max = (int) $day_max_year + $d . $day_max_month;
                    // // var_dump($i . "年度最後の年月" . $day_max);


                    $day_max_year = (int) $first_kijunbi_array[$i][0] + $d;
                    $day_max_month = $first_kijunbi_array[$i][1];

                    $day_max = $day_max_year . $day_max_month;
                    echo ('<pre>');
                    // var_dump('一年目');
                    var_dump('社員コード' . $shain_cd_array[$i]);
                    var_dump('年度始まり' . $day_min);
                    // var_dump($day_max);
                    // var_dump($day_max_year);
                    // var_dump($day_max_month);
                    // var_dump($first_kijunbi_array[$i][4]);
                    var_dump('年度終わり' . $day_max);
                    var_dump('年度終わり年' . $day_max_year);
                    var_dump('年度終わり月' . $day_max_month);

                    echo ('</pre>');


                    $holiday_count = DB::table('holidays')
                        ->select(DB::raw('sum(day) AS sumday'))
                        //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                        ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                        ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                        ->where('shain_cd', $shain_cd_array[$i])
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
                } elseif ($d == 2) {
                    var_dump("ここは" . $d . "年目");

                    $huyo_holiday = "12";
                    $max_carry_over = "12";
                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    if ($array[$d - 1][6] > $max_carry_over) {
                        $carry_over = $max_carry_over;
                    } else {
                        $carry_over = $array[$d - 1][6];
                    }


                    // var_dump('ここから');
                    // echo ('<pre>');
                    // var_dump($d);
                    // var_dump($array[$d+1][6]);
                    // var_dump($max_carry_over);
                    // var_dump('付与日数'.$huyo_holiday);
                    // var_dump('前期繰越'.$carry_over);
                    // echo ('</pre>');


                    // 期首残高
                    $kisyu_nokori = $huyo_holiday + $carry_over;

                    $day_max_year = (int) $first_kijunbi_array[$i][0] + $d;
                    $day_max_month = $first_kijunbi_array[$i][1];

                    $day_max = $day_max_year . $day_max_month;
                    echo ('<pre>');
                    // var_dump('一年目');
                    var_dump('社員コード' . $shain_cd_array[$i]);
                    var_dump('年度始まり' . $day_min);
                    // var_dump($day_max);
                    // var_dump($day_max_year);
                    // var_dump($day_max_month);
                    // var_dump($first_kijunbi_array[$i][4]);
                    var_dump('年度終わり' . $day_max);
                    var_dump('年度終わり年' . $day_max_year);
                    var_dump('年度終わり月' . $day_max_month);

                    echo ('</pre>');

                    $holiday_count = DB::table('holidays')
                        ->select(DB::raw('sum(day) AS sumday'))
                        //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                        ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                        ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                        ->where('shain_cd', $shain_cd_array[$i])
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
                } elseif ($d == 3) {
                    var_dump("ここは" . $d . "年目");
                    $huyo_holiday = "14";
                    $max_carry_over = "14";
                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    if ($array[$d - 1][6] > $max_carry_over) {
                        $carry_over = $max_carry_over;
                    } else {
                        $carry_over = $array[$d - 1][6];
                    }


                    // var_dump('ここから');
                    // echo ('<pre>');
                    // var_dump($d);
                    // var_dump($array[$d+1][6]);
                    // var_dump($max_carry_over);
                    // var_dump('付与日数'.$huyo_holiday);
                    // var_dump('前期繰越'.$carry_over);
                    // echo ('</pre>');


                    // 期首残高
                    $kisyu_nokori = $huyo_holiday + $carry_over;

                    $day_max_year = (int) $first_kijunbi_array[$i][0] + $d;
                    $day_max_month = $first_kijunbi_array[$i][1];

                    $day_max = $day_max_year . $day_max_month;
                    echo ('<pre>');
                    // var_dump('3年目');
                    var_dump('社員コード' . $shain_cd_array[$i]);
                    var_dump('年度始まり' . $day_min);
                    // var_dump($day_max);
                    // var_dump($day_max_year);
                    // var_dump($day_max_month);
                    // var_dump($first_kijunbi_array[$i][4]);
                    var_dump('年度終わり' . $day_max);
                    var_dump('年度終わり年' . $day_max_year);
                    var_dump('年度終わり月' . $day_max_month);

                    echo ('</pre>');

                    $holiday_count = DB::table('holidays')
                        ->select(DB::raw('sum(day) AS sumday'))
                        //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                        ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                        ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                        ->where('shain_cd', $shain_cd_array[$i])
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
                } elseif ($d == 4) {
                    var_dump("ここは" . $d . "年目");
                    $huyo_holiday = "16";
                    $max_carry_over = "16";
                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    if ($array[$d - 1][6] > $max_carry_over) {
                        $carry_over = $max_carry_over;
                    } else {
                        $carry_over = $array[$d - 1][6];
                    }


                    // var_dump('ここから');
                    // echo ('<pre>');
                    // var_dump($d);
                    // var_dump($array[$d+1][6]);
                    // var_dump($max_carry_over);
                    // var_dump('付与日数'.$huyo_holiday);
                    // var_dump('前期繰越'.$carry_over);
                    // echo ('</pre>');


                    // 期首残高
                    $kisyu_nokori = $huyo_holiday + $carry_over;

                    $day_max_year = (int) $first_kijunbi_array[$i][0] + $d;
                    $day_max_month = $first_kijunbi_array[$i][1];

                    $day_max = $day_max_year . $day_max_month;
                    echo ('<pre>');
                    // var_dump('3年目');
                    var_dump('社員コード' . $shain_cd_array[$i]);
                    var_dump('年度始まり' . $day_min);
                    // var_dump($day_max);
                    // var_dump($day_max_year);
                    // var_dump($day_max_month);
                    // var_dump($first_kijunbi_array[$i][4]);
                    var_dump('年度終わり' . $day_max);
                    var_dump('年度終わり年' . $day_max_year);
                    var_dump('年度終わり月' . $day_max_month);

                    echo ('</pre>');

                    $holiday_count = DB::table('holidays')
                        ->select(DB::raw('sum(day) AS sumday'))
                        //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                        ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                        ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                        ->where('shain_cd', $shain_cd_array[$i])
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
                } elseif ($d == 5) {
                    var_dump("ここは" . $d . "年目");

                    $huyo_holiday = "18";
                    $max_carry_over = "18";
                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    if ($array[$d - 1][6] > $max_carry_over) {
                        $carry_over = $max_carry_over;
                    } else {
                        $carry_over = $array[$d - 1][6];
                    }


                    // var_dump('ここから');
                    // echo ('<pre>');
                    // var_dump($d);
                    // var_dump($array[$d+1][6]);
                    // var_dump($max_carry_over);
                    // var_dump('付与日数'.$huyo_holiday);
                    // var_dump('前期繰越'.$carry_over);
                    // echo ('</pre>');


                    // 期首残高
                    $kisyu_nokori = $huyo_holiday + $carry_over;

                    $day_max_year = (int) $first_kijunbi_array[$i][0] + $d;
                    $day_max_month = $first_kijunbi_array[$i][1];

                    $day_max = $day_max_year . $day_max_month;
                    echo ('<pre>');
                    // var_dump('3年目');
                    var_dump('社員コード' . $shain_cd_array[$i]);
                    var_dump('年度始まり' . $day_min);
                    // var_dump($day_max);
                    // var_dump($day_max_year);
                    // var_dump($day_max_month);
                    // var_dump($first_kijunbi_array[$i][4]);
                    var_dump('年度終わり' . $day_max);
                    var_dump('年度終わり年' . $day_max_year);
                    var_dump('年度終わり月' . $day_max_month);

                    echo ('</pre>');

                    $holiday_count = DB::table('holidays')
                        ->select(DB::raw('sum(day) AS sumday'))
                        //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                        ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                        ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                        ->where('shain_cd', $shain_cd_array[$i])
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
                } elseif ($d >= 6) {
                    var_dump("ここは" . $d . "年目");

                    $huyo_holiday = "20";
                    $max_carry_over = "20";
                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    if ($array[$d - 1][6] > $max_carry_over) {
                        $carry_over = $max_carry_over;
                    } else {
                        $carry_over = $array[$d - 1][6];
                    }


                    // var_dump('ここから');
                    // echo ('<pre>');
                    // var_dump($d);
                    // var_dump($array[$d+1][6]);
                    // var_dump($max_carry_over);
                    // var_dump('付与日数'.$huyo_holiday);
                    // var_dump('前期繰越'.$carry_over);
                    // echo ('</pre>');


                    // 期首残高
                    $kisyu_nokori = $huyo_holiday + $carry_over;

                    $day_max_year = (int) $first_kijunbi_array[$i][0] + $d;
                    $day_max_month = $first_kijunbi_array[$i][1];

                    $day_max = $day_max_year . $day_max_month;
                    echo ('<pre>');
                    // var_dump('3年目');
                    var_dump('社員コード' . $shain_cd_array[$i]);
                    var_dump('年度始まり' . $day_min);
                    // var_dump($day_max);
                    // var_dump($day_max_year);
                    // var_dump($day_max_month);
                    // var_dump($first_kijunbi_array[$i][4]);
                    var_dump('年度終わり' . $day_max);
                    var_dump('年度終わり年' . $day_max_year);
                    var_dump('年度終わり月' . $day_max_month);

                    echo ('</pre>');

                    $holiday_count = DB::table('holidays')
                        ->select(DB::raw('sum(day) AS sumday'))
                        //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                        ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                        ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                        ->where('shain_cd', $shain_cd_array[$i])
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
                }



                //[0]付与日数/[1]最大繰り越し日数/[2]前期繰越/[3]期首残高/[4]消化日数/[5]消化残/[6]繰越日数/[7]年度最後の年月/
                $array[] = [$huyo_holiday, $max_carry_over, $carry_over, $kisyu_nokori, $holiday_count_int, $nokori, $carry_over_count, $shain_cd_array[$i]];
            }
        }
        // echo ('<pre>');
        // var_dump($array[0]);
        // var_dump($array[1]);
        // var_dump(count($array));
        // echo ('</pre>');

        for ($i = 0; $i < count($shain_cd_array); $i++) {
            //社員の有給情報の入ったはいれつの数分だけ繰り返す
            for ($y = 0; $y < count($array); $y++) {

                // echo ('<pre>');
                // var_dump('判定');
                // var_dump($array[0]);
                // var_dump($array[$y][7]);
                // var_dump($shain_cd_array[$i]);
                // echo ('</pre>');
                if ($array[$y][7] == $shain_cd_array[$i]) {


                    // $zan_holiday_personal[$shain_cd_array[$i]] = [$array[$y][5], $shain_cd_array[$i]];
                    $zan_holiday_personal[$shain_cd_array[$i]] = [$array[$y][5], $shain_cd_array[$i]];
                    echo ('<pre>');
                    var_dump('配列名');
                    var_dump('$zan_holiday_personal' . $shain_cd_array[$i]);
                    echo ('</pre>');
                }
            }
        }
        
        echo ('<pre>');
        var_dump($zan_holiday_personal[$shain_cd_array[0]]);
        echo ('</pre>');

        $employees = Employee::all();

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }
}
