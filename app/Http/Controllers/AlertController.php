<?php

namespace App\Http\Controllers;

use App\Holiday;
use App\Employee;
use DB;

use Illuminate\Support\Carbon;

use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;
use SebastianBergmann\Environment\Console;


// use App\Library\BaseClass;
use App\Library\BaseClass;


class AlertController extends Controller
{
    //未消化アラートが出ている人
    public function mishouka()
    {


        // 必須項目ここから 

        // クラスのインスタンス化
        $class = new BaseClass();

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

        // $select_nyusha_year = DB::table('employees')
        //     ->select(db::raw('distinct DATE_FORMAT(nyushabi, "%Y") as nyushanen'))
        //     ->whereNull('taishokubi')
        //     ->orderBy('nyushanen', 'asc')
        //     ->get();

        // $select_taishoku_year = DB::table('employees')
        //     ->select(db::raw('distinct DATE_FORMAT(taishokubi, "%Y") as taishokunen'))
        //     ->whereNotNull('taishokubi')
        //     ->orderBy('taishokunen', 'asc')
        //     ->get();

        $title = "未消化アラート対象者";
        $mishouka_title = "on";
        // 必須項目ここまで



        // 基準日から三カ月前の期間を含む人を抽出（退職者以外）

        // list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $class->year_month();
        // //一番最近のデータの月
        // $year_month_a2 = $year_month_a2_pre;


        $employees_pre = DB::table('employees')
            ->whereNull('taishokubi')
            ->Where(function ($query) {

                // クラスのインスタンス化
                $class = new BaseClass();

                list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $class->year_month();
                //一番最近のデータの月
                $year_month_a2 = $year_month_a2_pre;

                $before3_1_pre = $year_month_a2 - 5;
                $before3_1 = str_pad($before3_1_pre, 2, 0, STR_PAD_LEFT);
                // var_dump('入社月が' . $before3_1 . '月のひとが対象');

                $before3_2_pre = $year_month_a2 - 4;
                $before3_2 = str_pad($before3_2_pre, 2, 0, STR_PAD_LEFT);
                // var_dump('入社月が' . $before3_2 . '月のひとが対象');

                $before3_3_pre = $year_month_a2 - 3;
                $before3_3 = str_pad($before3_3_pre, 2, 0, STR_PAD_LEFT);
                // var_dump('入社月が' . $before3_3 . '月のひとが対象');

                $query->orwhere('nyushabi', 'LIKE', "%" . $before3_1 . "%")
                    ->orwhere('nyushabi', 'LIKE', "%" . $before3_2 . "%")
                    ->orWhere('nyushabi', 'LIKE', "%" . $before3_3 . "%");
            })
            ->get();

        // ->toSQL();
        // dd($employees_pre);
        // var_dump($employees_pre);


        // 基準月が最新データから3か月以内に来る人の社員コードが入った配列
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

            // //基準日を求める
            // $kijunbi1 = DB::table('employees')
            //     ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "kijunbi_first"'))
            //     ->where('shain_cd', $select_shain_cd[$i])
            //     ->get();

            // // 基準月を抜き出す
            // foreach ($kijunbi1 as $kijunbi_month_pre1) {
            //     $kijunbi_month1 = substr($kijunbi_month_pre1->kijunbi_first, 5, 2);
            // }

            list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $class->kijunbi($select_shain_cd[$i]);

            // 基準月を抜き出す
            $kijunbi_month = $kijunbi_month_pre;
            // echo ('<pre>');
            // var_dump('基準月:' . $kijunbi_month);
            // echo ('</pre>');


            //本年度の始まりを作成
            $day_min = date('Y') - 1 . $kijunbi_month;

            // echo ('<pre>');
            // var_dump('アラート対象年度の始まり:' . $day_min);
            // echo ('</pre>');


            //本年度の終わりを計算
            list($end_kijunbi_year_pre, $end_kijunbi_month_pre, $end_kijunbi_pre) = $class->kijunbi($select_shain_cd[$i]);
            $kijunbi_month = $end_kijunbi_month_pre;
            // $end_kijunbi = DB::table('employees')
            //     ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y%m01") , INTERVAL +17 MONTH) AS "kijunbi_end"'))
            //     ->where('shain_cd', $select_shain_cd[$i])
            //     ->get();

            // echo ('<pre>');
            // var_dump($end_kijunbi[0][0]);
            // echo ('</pre>');

            // // 本年度の終わりの年と月を抜き出す
            // foreach ($end_kijunbi as $end_kijunbi2) {

            //     echo ('<pre>');
            // var_dump($end_kijunbi2[0]);
            // echo ('</pre>');

            //     $kijunbi_month = substr($end_kijunbi2[0]->kijunbi_end, 5, 2);
            // }

            //本年度終わりの年を計算
            if ($kijunbi_month >= 7) {
                $kijunbi_year = date('Y') - 1 + 1;
            } else {
                $kijunbi_year = date('Y') - 1;
            }

            //本年度の始まりを作成
            $day_max = $kijunbi_year . $kijunbi_month;


            // var_dump("本年度の始まり" . $day_min);
            // var_dump("本年度の終わり" . $day_max);

            // 本年度の有給取得数を計算
            $holiday_count = $class->holiday_count($day_min, $day_max, $select_shain_cd[$i]);
            //     $holiday_count = DB::table('holidays')
            //         ->select(DB::raw('sum(day) AS sumday'))
            //         //基準日から一年分
            //         ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
            //         ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
            //         ->where('shain_cd', $select_shain_cd[$i])
            //         ->get();
            //     // dd($holiday_count);
            //     // ->toSQL();

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
            // var_dump("5日以上休んでない人は社員コードが入っていて、5日以上休んでいる人は0が入っている");
            // var_dump($select_employee);
            // echo ('</pre>');
        }


        // 5日以上休んでいない人だけの社員コードはいれつを作成
        for ($i = 0; $i < count($select_shain_cd); $i++) {
            // 5日以上取得している人は$select_employeeに0が入っている
            if ($select_employee[$i] == 0) {
                // var_dump("5日以上休んでる人");

                // 5日以上休んでいない人は$select_shain_cd2に社員コードを入れていく
            } else {
                // var_dump("5日以上休んでない人");
                $select_shain_cd2[] = $select_employee[$i];
            }
        }

        // echo ('<pre>');
        // var_dump("5日以上休んでない人の社員コード");
        // var_dump($select_shain_cd2);
        // echo ('</pre>');


        // 5日以上休んでいない人の人数分繰り返す
        for ($i = 0; $i < count($select_shain_cd2); $i++) {

            list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $class->kijunbi($select_shain_cd2[$i]);
            // 基準年を抜き出す
            $kijunbi_year = $kijunbi_year_pre;
            // var_dump('基準年:' . $kijunbi_year);

            // 基準月を抜き出す
            $kijunbi_month = $kijunbi_month_pre;
            // var_dump('基準月:' . $kijunbi_month);


            // // 初回基準日の計算（初回基準月が来ていない人は表示しないようにするために必要）
            // $first_kijunbi = DB::table('employees')
            //     ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y%m01") , INTERVAL +6 MONTH) AS "first_kijunbi"'))
            //     ->where('shain_cd', $select_shain_cd2[$i])
            //     ->get();

            // // 初回基準日の年と月をそれぞれ抜き出す
            // foreach ($first_kijunbi as $f_kijunbi) {
            //     $first_kijunbi_year = substr($f_kijunbi->first_kijunbi, 0, 4);
            //     $first_kijunbi_month = substr($f_kijunbi->first_kijunbi, 5, 2);
            // }


            // var_dump('初回基準年');
            // var_dump((int) $first_kijunbi_year);
            // var_dump('初回基準月');
            // var_dump((int) $first_kijunbi_month);
            // }


            // var_dump('ここから');


            list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $class->year_month();
            //一番最近のデータの年
            $year_month_a1 = $year_month_a1_pre;
            //一番最近のデータの月
            $year_month_a2 = $year_month_a2_pre;


            // 初回基準に達しているかどうか判定するための準備
            // 初回基準年と最新データの年を比べる
            if ($kijunbi_year < $year_month_a1) {
                $first_kijunbi_year_result_pre = 'small';
                // var_dump('year_small');
            } elseif ($kijunbi_year = $year_month_a1) {
                $first_kijunbi_year_result_pre = 'same';
                // var_dump('year_same');
            } else {
                $first_kijunbi_year_result_pre = 'large';
                // var_dump('year_large');

            }


            if ($kijunbi_month < $year_month_a2) {
                $first_kijunbi_month_result_pre = 'small';
                // var_dump('month_small');
            } elseif ($kijunbi_month == $year_month_a2) {
                $first_kijunbi_month_result_pre = 'same';
                // var_dump('month_same');
            } else {
                $first_kijunbi_month_result_pre = 'large';
                // var_dump('month_large');

            }

            // var_dump($first_kijunbi_year_result_pre);
            // var_dump($first_kijunbi_month_result_pre);


            // 基準年が現在の年数より小さいとき(月数がなんでも))、基準年が現在年と同じで、月が現在年より小さいか同じの時は表示する（＝初回基準月以降のひと）。
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

            // echo ('<pre>');
            // var_dump($first_kijunbi_result);
            // echo ('</pre>');


            // 基準月が3か月以内に来る人かつ有給を５日以上取得していない人かつ初回基準月以降のひとだけの社員コードはいれつを作成
            if ($first_kijunbi_result == 'true') {
                $select_shain_cd3[] = $select_shain_cd2[$i];
            }
        }


        // echo ('<pre>');
        // var_dump("基準月が3か月以内に来る人かつ有給を５日以上取得していない人かつ初回基準月以降のひとだけの社員コード");
        // var_dump($select_shain_cd3);
        // echo ('</pre>');

        // 基準月が3か月以内に来る人かつ有給を５日以上取得していない人かつ初回基準月以降のひとだけの社員コードに該当する社員情報を取得
        for ($i = 0; $i < count($select_shain_cd3); $i++) {

            // employeesテーブルに入っている情報
            $employees_prepre = $class->employees($select_shain_cd3[$i]);

            // $employees_prepre = DB::table('employees')
            //     ->whereNull('taishokubi')
            //     ->where('shain_cd', $select_shain_cd3[$i])
            //     ->get();
            // ->toSQL();


            list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $class->kijunbi($select_shain_cd3[$i]);

            // 基準月を抜き出す
            $select_employees_kijunbi_month = $kijunbi_month_pre;
            // var_dump('基準月:' . $kijunbi_month);

            // // 基準月の計算
            // $select_employees_kijunbi = DB::table('employees')
            //     ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "select_kijunbi"'))
            //     ->where('shain_cd', $select_shain_cd3[$i])
            //     ->get();

            // // 基準月を抜き出す
            // foreach ($select_employees_kijunbi as $select_employees_kijunbis) {
            //     $select_employees_kijunbi_month = substr($select_employees_kijunbis->select_kijunbi, 5, 2);



            // 基準日が3か月以内にきて、5日以上有給休暇をしようしていない人の休暇情報の計算↓
            //本年度の始まりを作成(アラート表示される人は現在-1年度のひと)
            $select_day_min = date('Y') - 1 . $select_employees_kijunbi_month;

            //本年度の終わりを計算
            // $select_kijunbi2 = $class->end_kijunbi($select_shain_cd3[$i]);
            list($end_kijunbi_year_pre, $end_kijunbi_month_pre, $end_kijunbi_pre) = $class->kijunbi($select_shain_cd[$i]);
            $select_kijunbi_month2 = $end_kijunbi_month_pre;
            // $select_kijunbi2 = DB::table('employees')
            //     ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y%m01") , INTERVAL +17 MONTH) AS "select_kijunbi_end"'))
            //     ->where('shain_cd', $select_shain_cd3[$i])
            //     ->get();


            // echo ('<pre>');
            // var_dump($select_kijunbi2);
            // var_dump($select_kijunbi2[0][0]->select_kijunbi_end);
            // echo ('</pre>');


            // 本年度の終わりの月を抜き出す
            // foreach ($select_kijunbi2 as $selecT_kijunbi_month_pre2) {

            //     // echo ('<pre>');
            //     // var_dump($selecT_kijunbi_month_pre2);
            //     // echo ('</pre>');

            //     $select_kijunbi_month2 = substr($selecT_kijunbi_month_pre2[0]->select_kijunbi_end, 5, 2);
            // }

            //本年度終わりの年を計算（月が7月よりも小さい時は年度始まりと同じ年）
            if ($select_employees_kijunbi_month >= 7) {
                $select_kijunbi_year = date('Y') - 1 + 1;
            } else {
                $select_kijunbi_year = date('Y') - 1;
            }

            //本年度の終わりを作成
            $select_day_max = $select_kijunbi_year . $select_kijunbi_month2;



            // 本年度の有給取得数を計算
            $select_holiday_count = $class->holiday_count($select_day_min, $select_day_max, $select_shain_cd3[$i]);

            // $select_holiday_count = DB::table('holidays')
            //     ->select(DB::raw('sum(day) AS sumday'))
            //     //基準日から一年分
            //     ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $select_day_min)
            //     ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $select_day_max)
            //     ->where('shain_cd', $select_shain_cd3[$i])
            //     ->get();
            // dd($select_holiday_count);
            // ->toSQL();

            // 所得日数がNULLの場合は０を代入
            if (is_null($select_holiday_count)) {
                $select_holiday_count = 0;
            }


            // 社員情報と基準月、取得日数を配列に格納
            $employees_array[] = [$employees_prepre, $select_employees_kijunbi_month, $select_holiday_count];
        }





        // 残日数の計算
        //対象者人数-1回繰り返す
        for ($i = 0; $i < count($select_shain_cd3); $i++) {

            //基準日の計算(入社日に+6か月)　※ XXXX-XX-01　XXXX-XXの部分は個人で計算されます。
            list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $class->kijunbi($select_shain_cd3[$i]);
            // 基準年を抜き出す
            $kijunbi_year = $kijunbi_year_pre;
            // var_dump('基準年:' . $kijunbi_year);

            // 基準月を抜き出す
            $kijunbi_month = $kijunbi_month_pre;
            // var_dump('基準月:' . $kijunbi_month);


            // $kijunbi = DB::table('employees')
            //     ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "kijunbi"'))
            //     ->where('shain_cd', $select_shain_cd3[$i])
            //     ->get();

            // // 基準年を抜き出す
            // $kijunbi_year = substr($kijunbi[0]->kijunbi, 0, 4);
            // // var_dump('基準年:' . $kijunbi_year);

            // // 基準月を抜き出す
            // $kijunbi_month = substr($kijunbi[0]->kijunbi, 5, 2);
            // // var_dump('基準月:' . $kijunbi_month);


            //  勤続年数を計算
            //一番最近のデータの年月(0000-00)を作成(=現在日時になる)
            list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $class->year_month();
            //一番最近のデータの年
            // $year_month_a1 = $year_month_a1_pre;
            $year = $year_month_a1_pre;
            //一番最近のデータの月
            // $year_month_a2 = $year_month_a2_pre;
            $month = $year_month_a2_pre;


            // $year_month_pre = DB::table('holidays')
            //     // ->select('year', 'month')
            //     ->select(db::raw('year,lpad(month, 2, "0") as month'))
            //     ->orderBy('year', 'desc')
            //     ->orderBy('month', 'desc')
            //     // ->where('shain_cd', $select_shain_cd3[$i])
            //     ->first();

            // // echo ('<pre>');
            // // var_dump($year_month_pre->year);
            // // var_dump($year_month_pre->month);
            // // echo ('</pre>');

            // $year = $year_month_pre->year;
            // $month = $year_month_pre->month;
            // // var_dump('現在年:' . $year);
            // // var_dump('現在月:' . $month);

            // 本年度が初年度か初年度じゃないか確認（初年度の場合取得範囲が長い）
            // if ($kijunbi_year + 1 >= $year_month_a1 and $kijunbi_month >= $year_month_a1) {
            //     // var_dump('初年度');
            //     $kinzoku_year = 0;
            // } else {
            //     // var_dump('初年度以降');
            //     $kinzoku_year = $year_month_a1 - $kijunbi_year + 1;
            // }

            if ($kijunbi_year + 1 >= $year and $kijunbi_month >= $month) {
                $kinzoku_year = 0;
            } else {
                $kinzoku_year = $year - $kijunbi_year;
            }

            // $kinzoku_year = $year - $kijunbi_year;
            // var_dump($kinzoku_year);


            //入社日の取得
            list($nyushabi_year_pre, $nyushabi_month_pre, $nyushabi_year_month_pre) = $class->nyushabi_year_month($select_shain_cd3[$i]);
            // //入社年
            // $nyushabi_year = $nyushabi_year_pre;
            // //入社月
            // $nyushabi_month = $nyushabi_month_pre;
            //入社年月
            $nyushabi_year_month = $nyushabi_year_month_pre;

            // $nyushabi = DB::table('employees')
            //     ->select('nyushabi')
            //     ->where('shain_cd', $select_shain_cd3[$i])
            //     ->get();

            // // var_dump('入社日');
            // // var_dump($nyushabi);

            // //入社年月の抜き出し
            // $nyushabi_year = substr($nyushabi[0]->nyushabi, 0, 4);
            // $nyushabi_month = substr($nyushabi[0]->nyushabi, 5, 2);
            // $nyushabi_year_month = $nyushabi_year . $nyushabi_month;


            // //初年度最後の月
            // // $first_day_max = $kijunbi_year + 1 . $kijunbi_max_month;
            // $first_day_max_pre = DB::table('employees')
            //     ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +17 MONTH) AS "first_day_max_pre"'))
            //     ->where('shain_cd', $select_shain_cd3[$i])
            //     ->first();

            // $first_day_max1 = substr($first_day_max_pre->first_day_max_pre, 0, 4);
            // $first_day_max2 = substr($first_day_max_pre->first_day_max_pre, 5, 2);
            // $first_day_max = $first_day_max1 . $first_day_max2;
            // // var_dump("初年度最後の月" . $first_day_max);



            // echo ('<pre>');
            // var_dump('対象者CD');
            // var_dump($select_shain_cd3[$i]);
            // var_dump('対象者基準日');
            // var_dump($kijunbi);
            // var_dump('勤続年数:' . $kinzoku_year);
            // var_dump("入社年月" . $nyushabi_year_month);
            // echo ('</pre>');


            // 勤続年数と入社年月を配列に格納
            $kinzoku_array[] = [$kinzoku_year, $nyushabi_year_month];
        }

        // echo ('<pre>');
        // var_dump('勤続年数と入社日');
        // var_dump($kinzoku_array[0]);
        // var_dump($kinzoku_array[1]);
        // var_dump($kinzoku_array[2]);
        // var_dump($kinzoku_array[$i-1][1]);
        // var_dump($kinzoku_array);
        // echo ('</pre>');



        // 有給情報を入れる配列の作成
        $holiday_array = [];


        //対象者人数分繰り返す
        for ($i = 0; $i < count($select_shain_cd3); $i++) {
            //初年度最後の月
            // $first_day_max = $kijunbi_year + 1 . $kijunbi_max_month;
            $first_day_max_pre = DB::table('employees')
                ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +17 MONTH) AS "first_day_max_pre"'))
                ->where('shain_cd', $select_shain_cd3[$i])
                ->first();

            $first_day_max_year = substr($first_day_max_pre->first_day_max_pre, 0, 4);
            $first_day_max_month = substr($first_day_max_pre->first_day_max_pre, 5, 2);
            $first_day_max = $first_day_max_year . $first_day_max_month;

            //勤続年数分繰り返す。$kinzoku_arrayは[0]から始まるので、2人分の勤続年数データが入っている場合、勤続年数配列は0～1までしか入っていないので、人数-1回分となる。
            for ($d = 0; $d <= $kinzoku_array[$i][0]; $d++) {
                // for ($d = 0; $d <= $kinzoku_array[2]; $d++) {
                // echo ('<pre>');
                // var_dump('$dの動き');
                // var_dump($d);
                // var_dump($kinzoku_array[$i]);
                // echo ('</pre>');

                if ($d == 0) {
                    // echo ('<pre>');
                    // var_dump($select_shain_cd3[$i]);

                    //付与日数
                    $huyo_holiday = "10";
                    //最大繰り越し日数
                    $max_carry_over = "10";
                    //前期繰越
                    $carry_over = 0;

                    // 期首残高（付与日数+前期繰越）
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // $kisyu_nokori = $huyo_holiday + $carry_over;

                    //消化日数
                    //年度最後の月計算(入社日に+17ヶ月(試用期間6ヶ月+1年度11ヶ月=17ヶ月)))
                    $day_max = $class->first_day_max($select_shain_cd3[$i]);


                    // $day_max_pre = DB::table('employees')
                    //     ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +17 MONTH) AS "day_max_pre"'))
                    //     ->where('shain_cd', $id)
                    //     ->first();

                    // $day_max1 = substr($day_max_pre->day_max_pre, 0, 4);
                    // $day_max2 = substr($day_max_pre->day_max_pre, 5, 2);
                    // //年度最後の年月
                    // $day_max = $day_max1 . $day_max2;
                    // var_dump("初年度最初の月" . $nyushabi_year_month);

                    // var_dump("初年度最初の月" . $kinzoku_array[$i][1]);
                    // var_dump("初年度最後の月" . $day_max);


                    $holiday_count_int = $class->holiday_count_int($nyushabi_year_month, $day_max, $select_shain_cd3[$i]);

                    // $holiday_count = DB::table('holidays')
                    //     ->select(DB::raw('sum(day) AS sumday'))
                    //     //入社日から～基準日に1年度分（11ヶ月）足したもの(ex:2016/10/1入社なら、2016/10/1~（基準日が2017/4/1なので）2018/3/1まで))
                    //     ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $nyushabi_year_month)
                    //     ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    //     ->where('shain_cd', $id)
                    //     ->get();
                    // ->toSQL();

                    // var_dump('初年度の消化日数:');
                    // var_dump($holiday_count_int);

                    // //配列で取得された消化日数の一番目を変数にいれる
                    // foreach ($holiday_count as $counts) {

                    //     if (is_null($counts->sumday)) {
                    //         $holiday_count_int = 0;
                    //     } else {
                    //         $holiday_count_int = $counts->sumday;
                    //     }
                    // }

                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                    // $nokori = $kisyu_nokori - $holiday_count_int;

                    // var_dump('初年度の消化残:');
                    // var_dump($nokori);

                    //繰越日数（消化残が最大繰り越し日数より大きい場合、繰り越し日数は最大繰り越し日数と同じになる。小さい場合、繰り越し日数は消化残と同じ日数になる。）
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);

                    // var_dump('初年度の繰越日数:');
                    // var_dump($carry_over_count);

                    // if ($nokori > $max_carry_over) {
                    //     $carry_over_count = $max_carry_over;
                    // } else {
                    //     $carry_over_count = $nokori;
                    // }



                    // echo ('</pre>');
                } elseif ($d == 1) {
                    // echo ('<pre>');
                    // var_dump($select_shain_cd3[$i]);

                    //付与日数
                    $huyo_holiday = "11";
                    //最大繰り越し日数
                    $max_carry_over = "11";

                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($holiday_array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);

                    // if ($array[$i - 1][6] > $max_carry_over) {
                    //     $carry_over = $max_carry_over;
                    // } else {
                    //     $carry_over = $array[$i - 1][6];
                    // }

                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);

                    // $kisyu_nokori = $huyo_holiday + $carry_over;

                    //消化日数
                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_year, $i, $kijunbi_month);
                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);

                    // $day_min_pre = $kijunbi_year + $i;
                    // $day_min = $day_min_pre . $kijunbi_month;
                    // var_dump($i . "年度初めの年月" . $day_min);

                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    // $day_max_year = substr($first_day_max, 0, 4);
                    // $day_max_month = substr($first_day_max, 4, 2);

                    // $day_max = $first_day_max_year + $i . $first_day_max_month;

                    $day_max = $class->day_max($first_day_max, $i);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $select_shain_cd3[$i]);
                    // var_dump($d . '年目の消化日数:');
                    // var_dump($holiday_count_int);


                    // $holiday_count = DB::table('holidays')
                    //     ->select(DB::raw('sum(day) AS sumday'))
                    //     //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                    //     ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                    //     ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    //     ->where('shain_cd', $id)
                    //     // ->first();
                    //     ->get();
                    // // ->toSQL();
                    // // var_dump('消化日数:');
                    // // var_dump($holiday_count);

                    // //配列で取得された消化日数の一番目を変数にいれる
                    // foreach ($holiday_count as $counts) {

                    //     if (is_null($counts->sumday)) {
                    //         $holiday_count_int = 0;
                    //     } else {
                    //         $holiday_count_int = $counts->sumday;
                    //     }
                    // }

                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                    // var_dump($d . '年目の消化残:');
                    // var_dump($nokori);

                    // $nokori = $kisyu_nokori - $holiday_count_int;

                    //繰越日数
                    // if ($nokori > $max_carry_over) {
                    //     $carry_over_count = $max_carry_over;
                    // } else {
                    //     $carry_over_count = $nokori;
                    // }
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                    // var_dump($d . '年目の繰越日数:');
                    // var_dump($carry_over_count);


                    // echo ('</pre>');
                } elseif ($d == 2) {
                    // echo ('<pre>');
                    // var_dump($select_shain_cd3[$i]);

                    $huyo_holiday = "12";
                    $max_carry_over = "12";

                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($holiday_array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);

                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);

                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_year, $i, $kijunbi_month);
                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);

                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max = $class->day_max($first_day_max, $i);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $select_shain_cd3[$i]);
                    // var_dump($d . '年目の消化日数:');
                    // var_dump($holiday_count_int);


                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                    // var_dump($d . '年目の消化残:');
                    // var_dump($nokori);

                    //繰越日数
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                    // var_dump($d . '年目の繰越日数:');
                    // var_dump($carry_over_count);

                    // echo ('</pre>');
                } elseif ($d == 3) {
                    // echo ('<pre>');
                    // var_dump($select_shain_cd3[$i]);

                    $huyo_holiday = "14";
                    $max_carry_over = "14";

                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($holiday_array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);

                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);

                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_year, $i, $kijunbi_month);
                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);

                    $day_max = $class->day_max($first_day_max, $i);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $select_shain_cd3[$i]);
                    // var_dump($d . '年目の消化日数:');
                    // var_dump($holiday_count_int);


                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                    // var_dump($d . '年目の消化残:');
                    // var_dump($nokori);

                    //繰越日数
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                    // var_dump($d . '年目の繰越日数:');
                    // var_dump($carry_over_count);

                    echo ('</pre>');
                } elseif ($d == 4) {
                    // echo ('<pre>');
                    // var_dump($select_shain_cd3[$i]);

                    $huyo_holiday = "16";
                    $max_carry_over = "16";

                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($holiday_array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);


                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);


                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_year, $i, $kijunbi_month);
                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);

                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max = $class->day_max($first_day_max, $i);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $select_shain_cd3[$i]);
                    // var_dump($d . '年目の消化日数:');
                    // var_dump($holiday_count_int);

                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                    // $nokori = $kisyu_nokori - $holiday_count_int;
                    // var_dump($d . '年目の消化残:');
                    // var_dump($nokori);

                    //繰越日数
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                    // var_dump($d . '年目の繰越日数:');
                    // var_dump($carry_over_count);

                    echo ('</pre>');
                } elseif ($d == 5) {
                    echo ('<pre>');
                    var_dump($select_shain_cd3[$i]);

                    $huyo_holiday = "18";
                    $max_carry_over = "18";

                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($holiday_array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);

                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);

                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_year, $i, $kijunbi_month);
                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);

                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max = $class->day_max($first_day_max, $i);
                    var_dump($d . '年目の年度最後の年月:');
                    var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $select_shain_cd3[$i]);
                    // var_dump($d . '年目の消化日数:');
                    // var_dump($holiday_count_int);


                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                    // var_dump($d . '年目の消化残:');
                    // var_dump($nokori);

                    //繰越日数
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                    // var_dump($d . '年目の繰越日数:');
                    // var_dump($carry_over_count);


                    // echo ('</pre>');
                } elseif ($d >= 6) {
                    // echo ('<pre>');
                    // var_dump($select_shain_cd3[$i]);

                    $huyo_holiday = "20";
                    $max_carry_over = "20";


                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($holiday_array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);

                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);

                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_year, $i, $kijunbi_month);
                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);

                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max = $class->day_max($first_day_max, $i);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $select_shain_cd3[$i]);
                    // var_dump($d . '年目の消化日数:');
                    // var_dump($holiday_count_int);

                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                    // $nokori = $kisyu_nokori - $holiday_count_int;
                    // var_dump($d . '年目の消化残:');
                    // var_dump($nokori);

                    //繰越日数
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                    // var_dump($d . '年目の繰越日数:');
                    // var_dump($carry_over_count);

                    // echo ('</pre>');
                }
                //[0]付与日数/[1]最大繰り越し日数/[2]前期繰越/[3]期首残高/[4]消化日数/[5]消化残/[6]繰越日数/[7]年度最後の年月/[8]社員コード
                $holiday_array[] = [$huyo_holiday, $max_carry_over, $carry_over, $kisyu_nokori, $holiday_count_int, $nokori, $carry_over_count, $day_max, $select_shain_cd3[$i]];
            }
        }
        // echo ('<pre>');
        // var_dump($array);
        // var_dump($res);
        // var_dump($pu);
        // echo ('</pre>');


        for ($i = 0; $i < count($select_shain_cd3); $i++) {
            //社員の有給情報の入ったはいれつの数分だけ繰り返す
            for ($y = 0; $y < count($holiday_array); $y++) {

                // $holiday_arrayには該当社員のデータが全部入っているので、社員ごとに配列の作成
                if ($holiday_array[$y][8][0] == $select_shain_cd3[$i][0]) {

                    $zan_holiday_personal[$select_shain_cd3[$i][0]] = [$holiday_array[$y][5], $select_shain_cd3[$i][0]];

                    // var_dump('配列名');
                    // var_dump($zan_holiday_personal);
                } else {
                    // 特に何もしない
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
        // var_dump($zan_holiday_personal);

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
        // var_dump($employees_array[0][0][0][0]->shain_cd);
        // var_dump($month);
        // echo ('</pre>');




        return view('/alert')->with([
            'title' => $title,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
            // 未消化アラートの説明
            'mishouka_title' => $mishouka_title,
            // 社員情報はいれつ
            'employees2' => $employees_array,
            // 該当する社員コードの入った配列
            'select_shain_cd3' => $select_shain_cd3,
            // 最新データの月
            'month' => $month,
            // // 消化残等が入った配列
            // 'array' => $holiday_array,
            // $holiday_arrayを整理した配列
            'zan_holiday_personal' => $zan_holiday_personal,




        ]);
    }












    //残数僅少アラートが出ている人
    public function zansu_kinshou()
    {
        // 必須項目ここから 
        // クラスのインスタンス化
        $class = new BaseClass();

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

        $title = "残数僅少アラート対象者";

        $zansuu_title = "on";

        // 必須項目ここまで


        // 社員情報のデータが何個あるか計算します
        $employees_count = $class->all();
        // $employees_count = DB::table('employees')
        //     ->whereNull('taishokubi')
        //     ->get();

        // $shain_cd_array配列に全社員のコードを入れる
        for ($i = 0; $i < count($employees_count); $i++) {
            $shain_cd_array[] = $employees_count[$i]->shain_cd;
        }

        // echo ('<pre>');
        // var_dump("全社員のコード");
        // var_dump($shain_cd_array);
        // echo ('</pre>');

        // 全社員の有給データを求める
        for ($i = 0; $i < count($employees_count); $i++) {
            // echo ('<pre>');


            //入社年月を求める
            list($nyushabi_year_pre, $nyushabi_month_pre, $nyushabi_year_month_pre, $nyushabi_pre) = $class->all_nyushabi_year_month($i);
            //入社年月
            $nyushabi = $nyushabi_year_month_pre;
            // 入社年
            $nyushabi_year = $nyushabi_year_pre;

            // $nyushabi = DB::table('employees')
            //     ->select('nyushabi')
            //     ->whereNull('taishokubi')
            //     ->get();

            // // 入社日フォーマット
            // // foreach ($nyushabi as $nyushabi_year_month) {
            // $nyushabi_year = substr($nyushabi[$i]->nyushabi, 0, 4);
            // $nyushabi_month = substr($nyushabi[$i]->nyushabi, 5, 2);
            // // }

            // $first_day_min = $nyushabi_year . $nyushabi_month;

            // var_dump("入社年月" . $nyushabi);
            // var_dump($nyushabi);

            $first_day_max = $class->first_day_max($shain_cd_array[$i]);

            // var_dump("初年度の終わり" . $first_day_max);
            // var_dump($first_day_max);



            //基準日を求める
            list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $class->kijunbi($shain_cd_array[$i]);
            // 基準年を抜き出す
            $first_kijunbi_year = $kijunbi_year_pre;
            // var_dump('初回基準年:' . $first_kijunbi_year);

            // 基準月を抜き出す
            $first_kijunbi_month = $kijunbi_month_pre;
            // var_dump('初回基準月:' . $first_kijunbi_month);

            // // 基準年月を抜き出す
            // $first_day_max = $kijunbi_year_month_pre;
            // var_dump('初回基準年月:' . $first_day_max);

            $day_min = $class->day_min($first_kijunbi_year, 1, $first_kijunbi_month);
            // var_dump('1年目の年度始まり:' . $day_min);


            $day_max = $class->day_max($first_day_max, 1);

            // var_dump('1年目の年度終わり:' . $day_max);



            // $kijunbi = DB::table('employees')
            //     ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "kijunbi_first"'))
            //     ->where('shain_cd', $shain_cd_array[$i])
            //     ->whereNull('taishokubi')
            //     ->get();

            // // echo ('<pre>');
            // // var_dump($kijunbi[0]->kijunbi_first);
            // // echo ('</pre>');

            // // 初回基準年月を抜き出す
            // foreach ($kijunbi as $first_kijunbi_year_month_pre) {
            //     $first_kijunbi_year = substr($first_kijunbi_year_month_pre->kijunbi_first, 0, 4);
            //     $first_kijunbi_month = substr($first_kijunbi_year_month_pre->kijunbi_first, 5, 2);
            // }

            // //初年度の終わり（=初回基準月）の作成
            // $first_day_max = $first_kijunbi_year . $first_kijunbi_month - 1;

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




            // [0]入社年月（=初年度の始まり）/[1]初年度の終わり/[2]1年目の年度始まり/[3]１年目の年度終わり/[4]初回基準年/[5]初回基準月/[6]入社年
            $kijunbi_array[] = [$nyushabi, $first_day_max, $day_min, $day_max, $first_kijunbi_year, $first_kijunbi_month, $nyushabi_year];

            // [0]入社日（月と日）/[1]基準日（月と日）
            // $kijunbi_array[] = [$nyushabi[$i]->nyushabi, $kijunbi[0]->kijunbi_first];
            // $kijunbi_array[] = [$nyushabi[$i]->nyushabi, $first_day_max];

            // echo ('</pre>');
        }


        // echo ('<pre>');
        // var_dump($kijunbi_array);
        // var_dump("ここ");
        // var_dump($first_kijunbi_array[4][4]);
        // var_dump($first_kijunbi_array[0]);
        // echo ('</pre>');


        //  勤続年数を計算
        //一番最近のデータの年月(0000-00)を作成(=現在日時になる)
        list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $class->year_month();
        //一番最近のデータの年
        $year = $year_month_a1_pre;
        //一番最近のデータの月
        $month = $year_month_a2_pre;

        // $year_month_pre = DB::table('holidays')
        //     ->select(db::raw('year,lpad(month, 2, "0") as month'))
        //     ->orderBy('year', 'desc')
        //     ->orderBy('month', 'desc')
        //     // ->where('shain_cd', $select_shain_cd3[$i])
        //     ->first();

        // echo ('<pre>');
        // var_dump($year_month_pre->year);
        // var_dump($year_month_pre->month);
        // echo ('</pre>');

        // $year = $year_month_pre->year;
        // $month = $year_month_pre->month;
        // var_dump('最新データ年:' . $year);
        // var_dump('最新データ月:' . $month);

        // 現在の年月を抜き出す
        // $year_pre =  date("Ym", strtotime("-2 month"));
        // $year = substr($year_pre, 0, 4);
        // $month = substr($year_pre, 4, 2);
        // var_dump('現在年:' . $year);
        // var_dump('現在月:' . $month);

        //社員情報データ数分繰り返す
        for ($i = 0; $i < count($employees_count); $i++) {

            // if ($first_kijunbi_array[$i][0] + 1 >= $year and $first_kijunbi_array[$i][1] >= $month) {
            // if ($first_kijunbi_array[$i][0] + 1 > $year OR ($first_kijunbi_array[$i][0] + 1 == $year AND $first_kijunbi_array[$i][1] >= $month)) {

            // echo ('<pre>');
            // var_dump($first_kijunbi_array[$i][0] + 1);
            // var_dump((int)$year);
            // var_dump((int)$first_kijunbi_array[$i][1]);
            // var_dump((int)$month);
            // echo ('</pre>');


            // 初回基準年が現在年より大きいもしくは同じ　かつ　初回基準月が現在月より大きいもしくは同じの場合、勤続年数は0年（初年度になる）
            if ($kijunbi_array[$i][4] + 1 > (int) $year or ($kijunbi_array[$i][4] + 1 == $year and (int) $kijunbi_array[$i][5] > $month)) {
                // echo ('<pre>');
                // var_dump('初年度');
                // echo ('</pre>');
                // $kinzoku_year[] = 0;
                $kinzoku_array[] = 0;
            } else {
                // echo ('<pre>');
                // var_dump('初年度以降');
                // var_dump($first_kijunbi_array[$i][0]);
                // echo ('</pre>');
                // $kinzoku_year[] = $year - $kijunbi_array[$i][6] + 1;
                $kinzoku_array[] = $year - $kijunbi_array[$i][6] -1;
            }

            // echo ('<pre>');
            // var_dump("勤続年数");
            // // var_dump($first_kijunbi_array[$i][0]);
            // var_dump($first_kijunbi_array[32][0] + 1 .'>='. $year);
            // // var_dump($year);
            // var_dump($first_kijunbi_array[32][1] .'>='. $month);
            // var_dump($kinzoku_year_array);
            // // var_dump($month);
            // echo ('</pre>');

            // $kinzoku_year_array[] = $kinzoku_year[$i];
        }

        // echo ('<pre>');
        // var_dump("勤続年数");
        // var_dump($kinzoku_array);
        // var_dump(count($kinzoku_year));
        // echo ('</pre>');


        $array = [];

        //対象者人数分繰り返す
        for ($i = 0; $i < count($employees_count); $i++) {
            // for ($i = 0; $i < 1; $i++) {

            // echo ('<pre>');
            // var_dump($kinzoku_year_array);
            // echo ('</pre>');

            // 勤続年数分繰り返す
            for ($d = 0; $d <= $kinzoku_array[$i]; $d++) {

                if ($d == 0) {
                    // echo ('<pre>');
                    // var_dump($shain_cd_array[$i]);

                    //付与日数
                    $huyo_holiday = "10";
                    //最大繰り越し日数
                    $max_carry_over = "10";
                    //前期繰越
                    $carry_over = 0;

                    // 期首残高（付与日数+前期繰越）
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // $kisyu_nokori = $huyo_holiday + $carry_over;


                    //年度最後の月計算(入社日に+17ヶ月(試用期間6ヶ月+1年度11ヶ月=17ヶ月)))
                    $day_max = $class->first_day_max($shain_cd_array[$i]);


                    // $day_max_pre = DB::table('employees')
                    //     ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +17 MONTH) AS "day_max_pre"'))
                    //     ->where('shain_cd', $id)
                    //     ->first();

                    // $day_max1 = substr($day_max_pre->day_max_pre, 0, 4);
                    // $day_max2 = substr($day_max_pre->day_max_pre, 5, 2);
                    // //年度最後の年月
                    // $day_max = $day_max1 . $day_max2;
                    // var_dump("初年度最初の月" . $nyushabi_year_month);

                    // var_dump("初年度最初の月" . $kijunbi_array[$i][0]);
                    // var_dump("初年度最後の月" . $kijunbi_array[$i][1]);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($kijunbi_array[$i][0], $kijunbi_array[$i][1], $shain_cd_array[$i]);

                    // $holiday_count = DB::table('holidays')
                    //     ->select(DB::raw('sum(day) AS sumday'))
                    //     //入社日から～基準日に1年度分（11ヶ月）足したもの(ex:2016/10/1入社なら、2016/10/1~（基準日が2017/4/1なので）2018/3/1まで))
                    //     ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $nyushabi_year_month)
                    //     ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    //     ->where('shain_cd', $id)
                    //     ->get();
                    // ->toSQL();

                    // var_dump('初年度の消化日数:');
                    // var_dump($holiday_count_int);

                    // //配列で取得された消化日数の一番目を変数にいれる
                    // foreach ($holiday_count as $counts) {

                    //     if (is_null($counts->sumday)) {
                    //         $holiday_count_int = 0;
                    //     } else {
                    //         $holiday_count_int = $counts->sumday;
                    //     }
                    // }

                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                    // $nokori = $kisyu_nokori - $holiday_count_int;

                    // var_dump('初年度の消化残:');
                    // var_dump($nokori);

                    //繰越日数（消化残が最大繰り越し日数より大きい場合、繰り越し日数は最大繰り越し日数と同じになる。小さい場合、繰り越し日数は消化残と同じ日数になる。）
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);

                    // var_dump('初年度の繰越日数:');
                    // var_dump($carry_over_count);

                    // if ($nokori > $max_carry_over) {
                    //     $carry_over_count = $max_carry_over;
                    // } else {
                    //     $carry_over_count = $nokori;
                    // }

                    // echo ('</pre>');
                } elseif ($d == 1) {
                    // echo ('<pre>');
                    // var_dump($shain_cd_array[$i]);

                    //付与日数
                    $huyo_holiday = "11";
                    //最大繰り越し日数
                    $max_carry_over = "11";

                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);

                    // if ($array[$i - 1][6] > $max_carry_over) {
                    //     $carry_over = $max_carry_over;
                    // } else {
                    //     $carry_over = $array[$i - 1][6];
                    // }

                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);
                    // $kisyu_nokori = $huyo_holiday + $carry_over;

                    //消化日数
                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_array[$i][4], $d, $kijunbi_array[$i][5]);
                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);

                    // $day_min_pre = $kijunbi_year + $i;
                    // $day_min = $day_min_pre . $kijunbi_month;
                    // var_dump($i . "年度初めの年月" . $day_min);

                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    // $day_max_year = substr($first_day_max, 0, 4);
                    // $day_max_month = substr($first_day_max, 4, 2);

                    // $day_max = $first_day_max_year + $i . $first_day_max_month;

                    $day_max = $class->day_max($kijunbi_array[$i][1], $d);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $shain_cd_array[$i]);
                    // var_dump($d . '年目の消化日数:');
                    // var_dump($holiday_count_int);


                    // $holiday_count = DB::table('holidays')
                    //     ->select(DB::raw('sum(day) AS sumday'))
                    //     //二年目の計算の場合、基準日に1年足したもの～基準日に2年足して1ヶ月引いたもの
                    //     ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                    //     ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                    //     ->where('shain_cd', $id)
                    //     // ->first();
                    //     ->get();
                    // // ->toSQL();
                    // // var_dump('消化日数:');
                    // // var_dump($holiday_count);

                    // //配列で取得された消化日数の一番目を変数にいれる
                    // foreach ($holiday_count as $counts) {

                    //     if (is_null($counts->sumday)) {
                    //         $holiday_count_int = 0;
                    //     } else {
                    //         $holiday_count_int = $counts->sumday;
                    //     }
                    // }

                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                    // var_dump($d . '年目の消化残:');
                    // var_dump($nokori);

                    // $nokori = $kisyu_nokori - $holiday_count_int;
                    
                    //繰越日数
                    // if ($nokori > $max_carry_over) {
                    //     $carry_over_count = $max_carry_over;
                    // } else {
                    //     $carry_over_count = $nokori;
                    // }
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                    // var_dump($d . '年目の繰越日数:');
                    // var_dump($carry_over_count);


                    // echo ('</pre>');
                } elseif ($d == 2) {
                    // echo ('<pre>');
                    // var_dump($shain_cd_array[$i]);

                    $huyo_holiday = "12";
                    $max_carry_over = "12";

                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);

                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);

                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_array[$i][4], $d, $kijunbi_array[$i][5]);
                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);

                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max = $class->day_max($kijunbi_array[$i][1], $d);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $shain_cd_array[$i]);
                    // var_dump($d . '年目の消化日数:');
                    // var_dump($holiday_count_int);

                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                    // var_dump($d . '年目の消化残:');
                    // var_dump($nokori);

                    //繰越日数
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                    // var_dump($d . '年目の繰越日数:');
                    // var_dump($carry_over_count);

                    // echo ('</pre>');
                } elseif ($d == 3) {
                    // echo ('<pre>');
                    // var_dump($shain_cd_array[$i]);

                    $huyo_holiday = "14";
                    $max_carry_over = "14";

                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);

                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);

                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_array[$i][4], $d, $kijunbi_array[$i][5]);
                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);

                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max = $class->day_max($kijunbi_array[$i][1], $d);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $shain_cd_array[$i]);
                    // var_dump($d . '年目の消化日数:');
                    // var_dump($holiday_count_int);

                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                    // var_dump($d . '年目の消化残:');
                    // var_dump($nokori);

                    //繰越日数
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                    // var_dump($d . '年目の繰越日数:');
                    // var_dump($carry_over_count);

                    // echo ('</pre>');
                } elseif ($d == 4) {
                    // echo ('<pre>');
                    // var_dump($shain_cd_array[$i]);

                    $huyo_holiday = "16";
                    $max_carry_over = "16";

                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);

                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);

                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_array[$i][4], $d, $kijunbi_array[$i][5]);
                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);

                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max = $class->day_max($kijunbi_array[$i][1], $d);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $shain_cd_array[$i]);
                    // var_dump($d . '年目の消化日数:');
                    // var_dump($holiday_count_int);

                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                    // var_dump($d . '年目の消化残:');
                    // var_dump($nokori);

                    //繰越日数
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                    // var_dump($d . '年目の繰越日数:');
                    // var_dump($carry_over_count);

                    echo ('</pre>');
                } elseif ($d == 5) {
                    // echo ('<pre>');
                    // var_dump($shain_cd_array[$i]);

                    $huyo_holiday = "18";
                    $max_carry_over = "18";

                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);

                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);

                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_array[$i][4], $d, $kijunbi_array[$i][5]);
                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);

                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max = $class->day_max($kijunbi_array[$i][1], $d);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $shain_cd_array[$i]);
                    // var_dump($d . '年目の消化日数:');
                    // var_dump($holiday_count_int);

                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                    // var_dump($d . '年目の消化残:');
                    // var_dump($nokori);

                    //繰越日数
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                    // var_dump($d . '年目の繰越日数:');
                    // var_dump($carry_over_count);

                    // echo ('</pre>');
                } elseif ($d >= 6) {
                    // echo ('<pre>');
                    // var_dump($shain_cd_array[$i]);

                    $huyo_holiday = "20";
                    $max_carry_over = "20";

                    //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);

                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);

                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_array[$i][4], $d, $kijunbi_array[$i][5]);
                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);

                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max = $class->day_max($kijunbi_array[$i][1], $d);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $shain_cd_array[$i]);
                    // var_dump($d . '年目の消化日数:');
                    // var_dump($holiday_count_int);

                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                    // var_dump($d . '年目の消化残:');
                    // var_dump($nokori);

                    //繰越日数
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                    // var_dump($d . '年目の繰越日数:');
                    // var_dump($carry_over_count);

                    // echo ('</pre>');
                }

                // echo ('<pre>');
                // var_dump("ここみよ");
                // var_dump($shain_cd_array);
                // echo ('</pre>');

                //[0]付与日数/[1]最大繰り越し日数/[2]前期繰越/[3]期首残高/[4]消化日数/[5]消化残/[6]繰越日数/[7]社員コード/
                $array[] = [$huyo_holiday, $max_carry_over, $carry_over, $kisyu_nokori, $holiday_count_int, $nokori, $carry_over_count, $shain_cd_array[$i]];
            }
        }

        // echo ('<pre>');
        // var_dump("はいれつ");
        // var_dump($array);
        // var_dump("はいれつここまで");
        // echo ('</pre>');

        // 全社員数分繰り返す
        for ($i = 0; $i < count($shain_cd_array); $i++) {
            // 全社員の全部の有給情報数分繰り返す
            for ($y = 0; $y < count($array); $y++) {
                // 各社員で配列をわけるため、有給情報に含まれている社員コードと指定した社員コードが一致した場合、
                if ($array[$y][7] == $shain_cd_array[$i]) {
                    // 有給情報を社員コードの名前を含めるはいれつに入れる
                    $zansuu_holiday_personal[$shain_cd_array[$i]] = $array[$y];
                }
            }

            // echo ('<pre>');
            // var_dump("はいれつ");
            // var_dump($zansuu_holiday_personal[$shain_cd_array[$i]][5]);
            // var_dump("はいれつここまで");
            // echo ('</pre>');
        }

        $holidays = [];
        // 全社員数分繰り返す
        for ($i = 0; $i < count($shain_cd_array); $i++) {
            // 消化残日数が3日以下のひとのみ、はいれつに入れていく
            if ($zansuu_holiday_personal[$shain_cd_array[$i]][5] <= 3) {
                array_push($holidays, $zansuu_holiday_personal[$shain_cd_array[$i]]);
                // var_dump('残り少ない！');
            } else {
                // var_dump('大丈夫');
            }
        }

        // echo ('<pre>');
        // var_dump("ほりでー");
        // var_dump($holidays);
        // var_dump("ホリデーここまで");
        // echo ('</pre>');

        $employees_array = [];
        $employees_array_kijunbi = [];

        // 社員情報を取得する
        for ($i = 0; $i < count($holidays); $i++) {
            // for ($i = 0; $i < 1; $i++) {

            // employeesテーブルに入っている情報
            $employees = DB::table('employees')
                ->whereNull('taishokubi')
                ->where('shain_cd', $holidays[$i][7])
                ->get();
            // ->toSQL();

            // 基準月の計算
            list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $class->kijunbi($holidays[$i][7]);

            // 基準月を抜き出す
            $kijunbi_month = $kijunbi_month_pre;
            // var_dump('基準月:' . $kijunbi_month);


            array_push($employees_array, $employees);
            array_push($employees_array_kijunbi, $kijunbi_month);
        }

        // echo ('<pre>');
        // var_dump("対象者社員情報");
        // var_dump($employees_array);
        // var_dump(count($employees_array));
        // var_dump("対象者社員情報ここまで");
        // echo ('</pre>');




        // for ($i = 0; $i < count($shain_cd_array); $i++) {
        //     //社員の有給情報の入ったはいれつの数分だけ繰り返す
        //     for ($y = 0; $y < count($array); $y++) {

        //         // echo ('<pre>');
        //         // var_dump($i);
        //         // var_dump('判定');
        //         // var_dump($array[0]);
        //         // var_dump($array);
        //         // var_dump($array[$y][7] ."==". $shain_cd_array[$i]);
        //         // echo ('</pre>');

        //         if ($array[$y][7] == $shain_cd_array[$i]) {


        //             // var_dump($i);

        //             // $zan_holiday_personal[$shain_cd_array[$i]] = [$array[$y][5], $shain_cd_array[$i]];
        //             // $zan_holiday_personal[$shain_cd_array[$i]] = [$array[$y][5], $shain_cd_array[$i]];
        //             $zan_holiday_personal[$i] = [$array[$y][5], $shain_cd_array[$i]];
        //             // echo ('<pre>');
        //             // var_dump('配列名');
        //             // var_dump('$zan_holiday_personal_' . $i);
        //             // echo ('</pre>');
        //         }
        //     }
        // echo ('<pre>');
        // var_dump($zan_holiday_personal[$shain_cd_array[1]]);
        // var_dump($zan_holiday_personal[$shain_cd_array[0]][0]);
        // var_dump('$zan_holiday_personal_' . $i);
        // var_dump($zan_holiday_personal[$i]);
        // echo ('</pre>');
        // }

        // $select_employees = [];
        // $zan_holiday_personal = [];

        // for ($i = 0; $i < 1; $i++) {
        //     if ($zan_holiday_personal[0][0] <= 5) {
        //         // if($zan_holiday_personal['2007010021'][0] <= 5){
        //         var_dump('5日以上休んでない');
        //         $select_employees = [$zan_holiday_personal[0][1]];
        //     } else {
        //         var_dump('5日以上休んでる');
        //     }
        // }

        // echo ('<pre>');
        // var_dump($select_employees);
        // var_dump($shain_cd_array);
        // echo ('</pre>');

        // $employees = 0;

        // // $employees = Employee::all();
        //     for ($i = 0; $i < count($select_employees); $i++) {
        //         $employees_pre = DB::table('employees')
        //             ->where('shain_cd', $select_employees[$i])
        //             ->whereNull('taishokubi')
        //             ->get();

        //             if(is_null($employees_pre)){
        //                 $employees = 0;
        //             }else{
        //                 $employees = $employees_pre;
        //             }
        //     }

        // employeesテーブルに入っている情報
        // $employees = DB::table('employees')
        //     ->whereNull('taishokubi')
        //     // ->where('shain_cd', $select_shain_cd3[$i])
        //     ->get();
        // // ->toSQL();




        // 社員情報と基準月、取得日数を配列に格納
        // $employees2[] = [$employees_prepre, $select_employees_kijunbi_month, $select_holiday_count];


        return view('zansuu_alert')->with([
            // return view('/employees')->with([
            'title' => $title,
            'zansuu_title' => $zansuu_title,
            'month' => $month,
            // 社員情報はいれつ
            'employees2' => $employees_array,
            // 社員基準日はいれつ
            'employees2_kijunbi' => $employees_array_kijunbi,
            // 社員有給情報はいれつ
            'holidays' => $holidays,
            // 'employees' => $employees_array,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }
}
