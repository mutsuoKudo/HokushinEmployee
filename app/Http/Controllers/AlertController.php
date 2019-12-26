<?php

namespace App\Http\Controllers;

use DB;
use App\Library\BaseClass;
use App\Library\OverTimeWorkingClass;


class AlertController extends Controller
{
    //未消化アラート
    public function mishouka()
    {


        // 必須項目ここから 

        // クラスのインスタンス化
        $class = new BaseClass();

        // 入社年月の取得
        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;


        $title = "未消化アラート対象者";
        // 必須項目ここまで



        // 基準日から4カ月前の期間を含む人を抽出（退職者以外）
        $employees_pre = DB::table('employees')
            ->whereNull('taishokubi')
            ->Where(function ($query) {

                // クラスのインスタンス化
                $class = new BaseClass();

                list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $class->year_month();
                //一番最近のデータの月
                $year_month_a2 = $year_month_a2_pre;

                if ((int) $year_month_a2 >= 6) {

                    $before3_1_pre = $year_month_a2 - 5;
                    $before3_1 = str_pad($before3_1_pre, 2, 0, STR_PAD_LEFT);
                    // var_dump('入社月が' . $before3_1 . '月のひとが対象');

                    $before3_2_pre = $year_month_a2 - 4;
                    $before3_2 = str_pad($before3_2_pre, 2, 0, STR_PAD_LEFT);
                    // var_dump('入社月が' . $before3_2 . '月のひとが対象');

                    $before3_3_pre = $year_month_a2 - 3;
                    $before3_3 = str_pad($before3_3_pre, 2, 0, STR_PAD_LEFT);
                    // var_dump('入社月が' . $before3_3 . '月のひとが対象');

                    $before3_4_pre = $year_month_a2 - 2;
                    $before3_4 = str_pad($before3_4_pre, 2, 0, STR_PAD_LEFT);
                    // var_dump('入社月が' . $before3_4 . '月のひとが対象');

                    $query->orwhere('nyushabi', 'LIKE', "%-" . $before3_1 . "-%")
                        ->orWhere('nyushabi', 'LIKE', "%-" . $before3_4 . "-%")
                        ->orwhere('nyushabi', 'LIKE', "%-" . $before3_2 . "-%")
                        ->orWhere('nyushabi', 'LIKE', "%-" . $before3_3 . "-%");
                } elseif ((int) $year_month_a2 == 1) {

                    $before3_1 = '11';
                    // var_dump('入社月が' . $before3_1 . '月のひとが対象');

                    $before3_2 = '10';
                    // var_dump('入社月が' . $before3_2 . '月のひとが対象');

                    $before3_3 = '09';
                    // var_dump('入社月が' . $before3_3 . '月のひとが対象');

                    $before3_4 = '08';
                    // var_dump('入社月が' . $before3_4 . '月のひとが対象');

                    $query->orwhere('nyushabi', 'LIKE', "%-" . $before3_1 . "-%")
                        ->orWhere('nyushabi', 'LIKE', "%-" . $before3_4 . "-%")
                        ->orwhere('nyushabi', 'LIKE', "%-" . $before3_2 . "-%")
                        ->orWhere('nyushabi', 'LIKE', "%-" . $before3_3 . "-%");
                } elseif ((int) $year_month_a2 == 2) {

                    $before3_1 = '12';
                    // var_dump('入社月が' . $before3_1 . '月のひとが対象');

                    $before3_2 = '11';
                    // var_dump('入社月が' . $before3_2 . '月のひとが対象');

                    $before3_3 = '10';
                    // var_dump('入社月が' . $before3_3 . '月のひとが対象');

                    $before3_4 = '09';
                    // var_dump('入社月が' . $before3_4 . '月のひとが対象');

                    $query->orwhere('nyushabi', 'LIKE', "%-" . $before3_1 . "-%")
                        ->orWhere('nyushabi', 'LIKE', "%-" . $before3_4 . "-%")
                        ->orwhere('nyushabi', 'LIKE', "%-" . $before3_2 . "-%")
                        ->orWhere('nyushabi', 'LIKE', "%-" . $before3_3 . "-%");
                } elseif ((int) $year_month_a2 == 3) {

                    $before3_1 = '01';
                    // var_dump('入社月が' . $before3_1 . '月のひとが対象');

                    $before3_2 = '12';
                    // var_dump('入社月が' . $before3_2 . '月のひとが対象');

                    $before3_3 = '11';
                    // var_dump('入社月が' . $before3_3 . '月のひとが対象');

                    $before3_4 = '10';
                    // var_dump('入社月が' . $before3_4 . '月のひとが対象');

                    $query->orwhere('nyushabi', 'LIKE', "%-" . $before3_1 . "-%")
                        ->orWhere('nyushabi', 'LIKE', "%-" . $before3_4 . "-%")
                        ->orwhere('nyushabi', 'LIKE', "%-" . $before3_2 . "-%")
                        ->orWhere('nyushabi', 'LIKE', "%-" . $before3_3 . "-%");
                } elseif ((int) $year_month_a2 == 4) {

                    $before3_1 = '02';
                    // var_dump('入社月が' . $before3_1 . '月のひとが対象');

                    $before3_2 = '01';
                    // var_dump('入社月が' . $before3_2 . '月のひとが対象');

                    $before3_3 = '12';
                    // var_dump('入社月が' . $before3_3 . '月のひとが対象');

                    $before3_4 = '11';
                    // var_dump('入社月が' . $before3_4 . '月のひとが対象');

                    $query->orwhere('nyushabi', 'LIKE', "%-" . $before3_1 . "-%")
                        ->orWhere('nyushabi', 'LIKE', "%-" . $before3_4 . "-%")
                        ->orwhere('nyushabi', 'LIKE', "%-" . $before3_2 . "-%")
                        ->orWhere('nyushabi', 'LIKE', "%-" . $before3_3 . "-%");
                } elseif ((int) $year_month_a2 == 5) {

                    $before3_1 = '03';
                    // var_dump('入社月が' . $before3_1 . '月のひとが対象');

                    $before3_2 = '02';
                    // var_dump('入社月が' . $before3_2 . '月のひとが対象');

                    $before3_3 = '01';
                    // var_dump('入社月が' . $before3_3 . '月のひとが対象');

                    $before3_4 = '12';
                    // var_dump('入社月が' . $before3_4 . '月のひとが対象');

                    $query->orwhere('nyushabi', 'LIKE', "%-" . $before3_1 . "-%")
                        ->orWhere('nyushabi', 'LIKE', "%-" . $before3_4 . "-%")
                        ->orwhere('nyushabi', 'LIKE', "%-" . $before3_2 . "-%")
                        ->orWhere('nyushabi', 'LIKE', "%-" . $before3_3 . "-%");
                }

                // var_dump($year_month_a2);
                // var_dump($before3_1);
                // var_dump($before3_2);
                // var_dump($before3_3);
                // var_dump($before3_4);
            })



            ->get();


        // 基準月が最新データから3か月以内に来る人の社員コードが入った配列
        foreach ($employees_pre as $employee) {
            $select_shain_cd[] = [$employee->shain_cd];
        }


        // 4ヶ月以内に基準月がくる人の数分繰り返す
        for ($i = 0; $i < count($select_shain_cd); $i++) {

            //基準日を求める
            list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $class->kijunbi($select_shain_cd[$i]);

            // 基準月を抜き出す
            $kijunbi_month = $kijunbi_month_pre;
            // 基準年を抜き出す
            $kijunbi_year = $kijunbi_year_pre;
            // 基準年月を抜き出す
            $kijunbi_year_month = $kijunbi_year_month_pre;


            //  勤続年数を計算

            //一番最近のデータの年月(0000-00)を作成(=現在日時になる)
            list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $class->year_month();

            $year = $year_month_a1_pre;
            $month = $year_month_a2_pre;

            $second_kijunbi_year = (int) $kijunbi_year + 1;
            $second_kijunbi_month = (int) $kijunbi_month;

            // 入社日の取得
            list($nyushabi_year_pre, $nyushabi_month_pre, $nyushabi_year_month_pre) = $class->nyushabi_year_month($select_shain_cd[$i]);
            $nyushabi_year = $nyushabi_year_pre;
            $nyushabi_year_month = $nyushabi_year_month_pre;

            // var_dump($select_shain_cd[$i]);


            if (($second_kijunbi_year > (int) $year) or ($second_kijunbi_year == (int) $year and $second_kijunbi_month > (int) $month)) {

                $kinzoku_year = 0;

                // var_dump('0');
                // var_dump($year);
                // var_dump($month);
                // var_dump($second_kijunbi_year);
                // var_dump($second_kijunbi_month);
            } else {
                // if ((int) $nyushabi_year == $year - 1) {
                // 現在の年月と基準日年月が同じの時は勤続年数は1年
                // if (($second_kijunbi_year == (int) $year and $second_kijunbi_month == (int) $month) or ((int) $nyushabi_year == $year - 1)) {

                // $kinzoku_year =  (int) $year - (int) $nyushabi_year;
                // $kinzoku_year =  1;

                // var_dump('2');
                // var_dump($year);
                // var_dump($month);
                // var_dump($second_kijunbi_year);
                // var_dump($second_kijunbi_month);
                // } else {
                // 入社年が現在年-1と同じ場合、勤続年数は現在年-入社年（例えば、現在年が2019年の時、2018年のひとが対象になり、2019-2018になる。）
                // if ((int) $nyushabi_year == $year - 1) {

                //     // $kinzoku_array[] = 1;　でもいいのでは
                //     $kinzoku_year = (int) $year - (int) $nyushabi_year;

                // var_dump('3');
                // var_dump($nyushabi_year);
                // var_dump($year);
                // var_dump($month);
                // var_dump($second_kijunbi_year);
                // var_dump($second_kijunbi_month);
                // } else {

                $kinzoku_year = (int) $year - (int) $nyushabi_year - 1;

                // var_dump('通った4');
                // var_dump($nyushabi_year);
                // var_dump($year);
                // var_dump($month);
                // var_dump($second_kijunbi_year);
                // var_dump($second_kijunbi_month);
                // }
                // }
            }

            // var_dump($kinzoku_year);


            // // 基準年が最新データの年より大きければ、月数は関係なく勤続年数は0年
            // // 基準年が最新データの年と同じであれば、月数は大きい時勤続年数は０年

            // if (($kijunbi_year > $year and ($kijunbi_month > $month or $kijunbi_month =  $month or $kijunbi_month < $month)) or ($kijunbi_year == $year and $kijunbi_month > $month)
            // ) {
            //     $kinzoku_year = 0;
            // } else {
            //     $kinzoku_year = $year - $kijunbi_year;
            // }



            // // 入社日の取得
            // list($nyushabi_year_pre, $nyushabi_month_pre, $nyushabi_year_month_pre) = $class->nyushabi_year_month($select_shain_cd[$i]);
            // $nyushabi_year_month = $nyushabi_year_month_pre;


            if ($kinzoku_year == 0) {
                $day_min = $nyushabi_year_month;
            } else {
                //本年度の始まりを作成
                if ($kijunbi_month == 01) {
                    $day_min = date('Y') . $kijunbi_month;
                } else {
                    $day_min = date('Y') - 1 . $kijunbi_month;
                }
            }



            //本年度の終わりを計算
            list($end_kijunbi_year_pre, $end_kijunbi_month_pre, $end_kijunbi_pre) = $class->end_kijunbi($select_shain_cd[$i]);
            $kijunbi_month = $end_kijunbi_month_pre;

            //本年度終わりの年を計算
            if ($kijunbi_month >= 7) {
                $kijunbi_year = date('Y') - 1 + 1;
            }


            //本年度の終わりを作成

            if ($kinzoku_year == 0) {

                $day_max = $kijunbi_year + 1 . $kijunbi_month;
            } else {

                $day_max = $kijunbi_year . $kijunbi_month;
            }


            // 本年度の有給取得数を計算
            $holiday_count = $class->holiday_count($day_min, $day_max, $select_shain_cd[$i]);


            // 1日も休んでいない場合、0を代入。休んでいる場合はその日数を取得。
            foreach ($holiday_count as $counts) {
                if (is_null($counts->sumday)) {
                    $holiday_count_int = "0";
                } else {
                    $holiday_count_int = $counts->sumday;
                }
            }



            // 取得日数が5日未満の人は配列に社員コードを入れ、5日以上取得している人は0を入れる。
            if ($holiday_count_int < 5) {
                $select_employee[] = $select_shain_cd[$i];
            } else {
                $select_employee[] = "0";
            }

            // var_dump($select_shain_cd[$i]);
            // var_dump($day_min);
            // var_dump($day_max);
            // var_dump($holiday_count);
        }





        // 5日以上休んでいない人だけの社員コードはいれつを作成
        for ($i = 0; $i < count($select_shain_cd); $i++) {
            // 5日以上取得している人は$select_employeeに0が入っている
            if ($select_employee[$i] == 0) {

                // 5日以上休んでいない人は$select_shain_cd2に社員コードを入れていく
            } else {
                $select_shain_cd2[] = $select_employee[$i];
            }
        }

        // var_dump($select_shain_cd2);






        $select_shain_cd3 = [];
        // 5日以上休んでいない人の人数分繰り返す
        for ($i = 0; $i < count($select_shain_cd2); $i++) {


            // 基準日を求める
            $kijunbi = DB::table('employees')
                ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "kijunbi"'))
                ->where('shain_cd', $select_shain_cd2[$i])
                ->get();


            // 基準年を抜き出す
            $kijunbi_year = substr($kijunbi[0]->kijunbi, 0, 4);
            // 基準月を抜き出す
            $kijunbi_month = substr($kijunbi[0]->kijunbi, 5, 2);



            // 一番最新のデータの年月を求める
            list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $class->year_month();

            //一番最近のデータの年
            $year_month_a1 = $year_month_a1_pre;
            //一番最近のデータの月
            $year_month_a2 = $year_month_a2_pre;


            // 初回基準に達しているかどうか判定するための準備
            // 初回基準年と最新データの年を比べる

            if ($kijunbi_year < $year_month_a1) {
                $first_kijunbi_year_result_pre = 'small';
            } elseif ($kijunbi_year == $year_month_a1) {
                $first_kijunbi_year_result_pre = 'same';
            } else {
                $first_kijunbi_year_result_pre = 'large';
            }




            if ($kijunbi_month <= $year_month_a2) {
                $first_kijunbi_month_result_pre = 'same_small';
                // } elseif ($kijunbi_month == $year_month_a2) {
                //     $first_kijunbi_month_result_pre = 'same';
            } else {
                $first_kijunbi_month_result_pre = 'large';
            }




            // 基準年が現在の年数より小さいとき(月数がなんでも))、基準年が現在年と同じで、月が現在年より小さいか同じの時は表示する（＝初回基準月以降のひと）。
            if (
                ($first_kijunbi_year_result_pre == 'small'
                    and ($first_kijunbi_month_result_pre == 'same_small' or $first_kijunbi_month_result_pre == 'large'))
                or ($first_kijunbi_year_result_pre == 'same' and ($first_kijunbi_month_result_pre == 'same_small'))
                // ($first_kijunbi_year_result_pre == 'small'
                //     and ($first_kijunbi_month_result_pre == 'small' or $first_kijunbi_month_result_pre == 'same' or $first_kijunbi_month_result_pre == 'large'))
                // or ($first_kijunbi_year_result_pre == 'same' and ($first_kijunbi_month_result_pre == 'small' or $first_kijunbi_month_result_pre == 'same'))
            ) {
                // 表示する
                $first_kijunbi_result = 'true';
            } else {
                // 表示しない
                $first_kijunbi_result = 'false';
            }


            // 基準月が4か月以内に来る人かつ有給を５日以上取得していない人かつ初回基準月以降のひとだけの社員コードはいれつを作成

            if ($first_kijunbi_result == 'true') {
                $select_shain_cd3[] = $select_shain_cd2[$i];
            }
        }

        // if (count($select_shain_cd3) == 0) {
        //     list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $class->year_month();

        //     //一番最近のデータの月
        //     $month = $year_month_a2_pre;

        //     return view('/alert')->with([
        //         'title' => $title,
        //         'select_nyusha_year' => $select_nyusha_year,
        //         'select_taishoku_year' => $select_taishoku_year,
        //         // 該当する社員コードの入った配列
        //         'select_shain_cd3' => $select_shain_cd3,
        //         // 最新データの月
        //         'month' => $month,

        //     ]);
        // }




        // 基準月が3か月以内に来る人かつ有給を５日以上取得していない人かつ初回基準月以降のひとだけの社員コードに該当する社員情報を取得
        for ($i = 0; $i < count($select_shain_cd3); $i++) {

            // employeesテーブルに入っている情報
            $employees_prepre = $class->employees($select_shain_cd3[$i]);

            // 基準日を求める
            list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $class->kijunbi($select_shain_cd3[$i]);

            // 基準月を抜き出す
            $select_employees_kijunbi_month = $kijunbi_month_pre;


            // 基準日が3か月以内にきて、5日以上有給休暇をしようしていない人の休暇情報の計算↓
            //本年度の始まりを作成(アラート表示される人は現在-1年度のひと)
            $select_day_min = date('Y') - 1 . $select_employees_kijunbi_month;


            //本年度の終わりを計算
            list($end_kijunbi_year_pre, $end_kijunbi_month_pre, $end_kijunbi_pre) = $class->end_kijunbi($select_shain_cd[$i]);
            $select_kijunbi_month2 = $end_kijunbi_month_pre;



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


            // 所得日数がNULLの場合は０を代入
            if ($select_holiday_count[0]->sumday == null) {
                $select_holiday_count[0]->sumday = 0;
            }


            // 社員情報と基準月、取得日数を配列に格納
            $employees_array[] = [$employees_prepre, $select_employees_kijunbi_month, $select_holiday_count];
            // echo ('</pre>');
        }




        // 残日数の計算
        //対象者人数-1回繰り返す
        for ($i = 0; $i < count($select_shain_cd3); $i++) {

            //基準日を求める
            list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $class->kijunbi($select_shain_cd3[$i]);

            // 基準年を抜き出す
            $kijunbi_year = $kijunbi_year_pre;

            // 基準月を抜き出す
            $kijunbi_month = $kijunbi_month_pre;
            // var_dump('基準月:' . $kijunbi_month);


            //  勤続年数を計算
            //一番最近のデータの年月を求める
            list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $class->year_month();

            //一番最近のデータの年
            $year = $year_month_a1_pre;

            //一番最近のデータの月
            $month = $year_month_a2_pre;


            // 初年度の人は勤続年数を0にする = 2回目の基準日($kijunbi_year + 1 と　$kijunbi_monthであらわす）が到来していない人
            // if ($kijunbi_year + 1 >= $year and $kijunbi_month >= $month) {
            //     $kinzoku_year = 0;
            // } else {
            $kinzoku_year = $year - $kijunbi_year;
            // }


            //入社日の取得
            list($nyushabi_year_pre, $nyushabi_month_pre, $nyushabi_year_month_pre) = $class->nyushabi_year_month($select_shain_cd3[$i]);

            //入社年月
            $nyushabi_year_month = $nyushabi_year_month_pre;


            // 勤続年数と入社年月を配列に格納
            $kinzoku_array[] = [$kinzoku_year, $nyushabi_year_month];
            $kijunbi_array[] = [$kijunbi_year, $kijunbi_month];
        }

        // 有給情報を入れる配列の作成
        $holiday_array = [];


        //対象者人数分繰り返す
        for ($i = 0; $i < count($select_shain_cd3); $i++) {

            //初年度最後の年月を求める
            list($first_day_max_year_pre, $first_day_max_month_pre, $first_day_max_pre) = $class->first_day_max_2($select_shain_cd3[$i]);
            // 初年度最後の年月をフォーマット
            $first_day_max_year = $first_day_max_year_pre;
            $first_day_max_month = $first_day_max_month_pre;
            $first_day_max = $first_day_max_pre;

            //勤続年数分繰り返す。$kinzoku_arrayは[0]から始まるので、2人分の勤続年数データが入っている場合、勤続年数配列は0～1までしか入っていないので、人数-1回分となる。
            for ($d = 0; $d <= $kinzoku_array[$i][0]; $d++) {

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

                    //消化日数
                    //年度最後の月計算(入社日に+17ヶ月(試用期間6ヶ月+1年度11ヶ月=17ヶ月)))
                    $day_max = $class->first_day_max($select_shain_cd3[$i]);

                    // var_dump('初年度の始まり' . $kinzoku_array[$i][1]);
                    // var_dump('初年度の終わり' . $day_max);

                    $holiday_count_int = $class->holiday_count_int($kinzoku_array[$i][1], $day_max, $select_shain_cd3[$i]);

                    // var_dump('初年度の消化日数:');
                    // var_dump($holiday_count_int);


                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);

                    // var_dump('初年度の消化残:');
                    // var_dump($nokori);

                    //繰越日数（消化残が最大繰り越し日数より大きい場合、繰り越し日数は最大繰り越し日数と同じになる。小さい場合、繰り越し日数は消化残と同じ日数になる。）
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);

                    // var_dump('初年度の繰越日数:');
                    // var_dump($carry_over_count);

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


                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);


                    //消化日数
                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_array[$i][0], $d, $kijunbi_array[$i][1]);

                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);



                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max = $class->day_max($first_day_max, $i);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    // var_dump($nyushabi_year_month . '年目の消化日数:');
                    // var_dump($day_max . '年目の消化日数:');
                    // var_dump($select_shain_cd3[$i][0]);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $select_shain_cd3[$i][0]);

                    //配列で取得された消化日数の一番目を変数にいれる
                    foreach ($holiday_count as $counts) {

                        if (is_null($counts->sumday)) {
                            $holiday_count_int = 0;
                        } else {
                            $holiday_count_int = $counts->sumday;
                        }
                    }

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
                } elseif ($d == 2) {
                    // echo ('<pre>');
                    // var_dump($select_shain_cd3[$i]);

                    $huyo_holiday = "12";
                    $max_carry_over = "12";

                    // 前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($holiday_array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);


                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);


                    //消化日数
                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_array[$i][0], $d, $kijunbi_array[$i][1]);

                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);



                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max = $class->day_max($first_day_max, $i);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $select_shain_cd3[$i][0]);

                    //配列で取得された消化日数の一番目を変数にいれる
                    foreach ($holiday_count as $counts) {

                        if (is_null($counts->sumday)) {
                            $holiday_count_int = 0;
                        } else {
                            $holiday_count_int = $counts->sumday;
                        }
                    }

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

                    // 前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($holiday_array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);


                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);


                    //消化日数
                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_array[$i][0], $d, $kijunbi_array[$i][1]);

                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);



                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max = $class->day_max($first_day_max, $i);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $select_shain_cd3[$i][0]);

                    //配列で取得された消化日数の一番目を変数にいれる
                    foreach ($holiday_count as $counts) {

                        if (is_null($counts->sumday)) {
                            $holiday_count_int = 0;
                        } else {
                            $holiday_count_int = $counts->sumday;
                        }
                    }

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
                    // var_dump($select_shain_cd3[$i]);

                    $huyo_holiday = "16";
                    $max_carry_over = "16";

                    // 前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($holiday_array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);


                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);


                    //消化日数
                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_array[$i][0], $d, $kijunbi_array[$i][1]);

                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);



                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max = $class->day_max($first_day_max, $i);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $select_shain_cd3[$i][0]);

                    //配列で取得された消化日数の一番目を変数にいれる
                    foreach ($holiday_count as $counts) {

                        if (is_null($counts->sumday)) {
                            $holiday_count_int = 0;
                        } else {
                            $holiday_count_int = $counts->sumday;
                        }
                    }

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
                } elseif ($d == 5) {
                    // echo ('<pre>');
                    // var_dump($select_shain_cd3[$i]);

                    $huyo_holiday = "18";
                    $max_carry_over = "18";

                    // 前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($holiday_array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);


                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);


                    //消化日数
                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_array[$i][0], $d, $kijunbi_array[$i][1]);

                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);



                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max = $class->day_max($first_day_max, $i);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $select_shain_cd3[$i][0]);

                    //配列で取得された消化日数の一番目を変数にいれる
                    foreach ($holiday_count as $counts) {

                        if (is_null($counts->sumday)) {
                            $holiday_count_int = 0;
                        } else {
                            $holiday_count_int = $counts->sumday;
                        }
                    }

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


                    // 前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                    $carry_over = $class->carry_over($holiday_array, $i + $d, $max_carry_over);
                    // var_dump($d . '年目の前期繰越:');
                    // var_dump($carry_over);


                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);


                    //消化日数
                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_array[$i][0], $d, $kijunbi_array[$i][1]);

                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);



                    //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                    $day_max = $class->day_max($first_day_max, $i);
                    // var_dump($d . '年目の年度最後の年月:');
                    // var_dump($day_max);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $select_shain_cd3[$i][0]);

                    //配列で取得された消化日数の一番目を変数にいれる
                    foreach ($holiday_count as $counts) {

                        if (is_null($counts->sumday)) {
                            $holiday_count_int = 0;
                        } else {
                            $holiday_count_int = $counts->sumday;
                        }
                    }

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


                //[0]付与日数/[1]最大繰り越し日数/[2]前期繰越/[3]期首残高/[4]消化日数/[5]消化残/[6]繰越日数/[7]年度最後の年月/[8]社員コード
                $holiday_array[] = [$huyo_holiday, $max_carry_over, $carry_over, $kisyu_nokori, $holiday_count_int, $nokori, $carry_over_count, $day_max, $select_shain_cd3[$i]];
            }
        }




        for ($i = 0; $i < count($select_shain_cd3); $i++) {
            //社員の有給情報の入ったはいれつの数分だけ繰り返す
            for ($y = 0; $y < count($holiday_array); $y++) {

                // $holiday_arrayには該当社員のデータが全部入っているので、社員ごとに配列の作成
                if ($holiday_array[$y][8][0] == $select_shain_cd3[$i][0]) {
                    $zan_holiday_personal[$select_shain_cd3[$i][0]] = [$holiday_array[$y][4], $holiday_array[$y][5], $select_shain_cd3[$i][0]];
                } else {
                    // 特に何もしない
                }
            }
        }




        return view('/alert')->with([
            'title' => $title,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
            // 社員情報はいれつ
            'employees2' => $employees_array,
            // 該当する社員コードの入った配列
            'select_shain_cd3' => $select_shain_cd3,
            // 最新データの月
            'month' => $month,
            // $holiday_arrayを整理した配列
            'zan_holiday_personal' => $zan_holiday_personal,

        ]);
    }












    //残数僅少アラート
    public function zansu_kinshou()
    {
        // 必須項目ここから 
        // クラスのインスタンス化
        $class = new BaseClass();

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

        $title = "残数僅少アラート対象者";

        // 必須項目ここまで


        // 社員情報のデータが何個あるか計算します
        $employees_count = $class->all();

        // $shain_cd_array配列に全社員のコードを入れる
        for ($i = 0; $i < count($employees_count); $i++) {
            $shain_cd_array[] = $employees_count[$i]->shain_cd;
        }


        // 全社員の有給データを求める
        for ($i = 0; $i < count($employees_count); $i++) {

            //入社年月を求める
            list($nyushabi_year_pre, $nyushabi_month_pre, $nyushabi_year_month_pre, $nyushabi_pre) = $class->all_nyushabi_year_month($i);
            //入社年月
            $nyushabi = $nyushabi_year_month_pre;
            // 入社年
            $nyushabi_year = $nyushabi_year_pre;

            $first_day_max = $class->first_day_max($shain_cd_array[$i]);


            //基準日を求める
            list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $class->kijunbi($shain_cd_array[$i]);
            // 基準年を抜き出す
            $first_kijunbi_year = $kijunbi_year_pre;
            // var_dump('初回基準年:' . $first_kijunbi_year);

            // 基準月を抜き出す
            $first_kijunbi_month = $kijunbi_month_pre;
            // var_dump('初回基準月:' . $first_kijunbi_month);


            $day_min = $class->day_min($first_kijunbi_year, 1, $first_kijunbi_month);
            // var_dump('1年目の年度始まり:' . $day_min);


            $day_max = $class->day_max($first_day_max, 1);

            // var_dump('1年目の年度終わり:' . $day_max);

            // [0]入社年月（=初年度の始まり）/[1]初年度の終わり/[2]1年目の年度始まり/[3]１年目の年度終わり/[4]初回基準年/[5]初回基準月/[6]入社年
            $kijunbi_array[] = [$nyushabi, $first_day_max, $day_min, $day_max, $first_kijunbi_year, $first_kijunbi_month, $nyushabi_year];
        }


        //  勤続年数を計算
        //一番最近のデータの年月(0000-00)を作成(=現在日時になる)
        list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $class->year_month();
        //一番最近のデータの年
        $year = $year_month_a1_pre;
        //一番最近のデータの月
        $month = $year_month_a2_pre;


        //社員情報データ数分繰り返す
        for ($i = 0; $i < count($employees_count); $i++) {

            // 初回基準年が現在年より大きいもしくは同じ　かつ　初回基準月が現在月より大きいもしくは同じの場合、勤続年数は0年（初年度になる）
            // 初年度の人　=　二回目の基準月が来ていない人


            $second_kijunbi_year = (int) $kijunbi_array[$i][4] + 1;
            $second_kijunbi_month = (int) $kijunbi_array[$i][5];


            // var_dump($employees_count[$i]->shain_cd);

            if (($second_kijunbi_year > (int) $year) or ($second_kijunbi_year == (int) $year and $second_kijunbi_month > (int) $month)) {
                // if ((int) $kijunbi_array[$i][4] + 1 > (int) $year or ((int) $kijunbi_array[$i][4] + 1 == $year and (int) $kijunbi_array[$i][5] > $month)) {

                $kinzoku_array[] = 0;
                // var_dump('通った');
                // var_dump($year);
                // var_dump($month);
                // var_dump($second_kijunbi_year);
                // var_dump($second_kijunbi_month);
            } else {
                // 現在の年月と基準日年月が同じの時は勤続年数は1年
                if (($second_kijunbi_year == (int) $year and $second_kijunbi_month == (int) $month) or ((int) $kijunbi_array[$i][6] == $year - 1)) {
                    $kinzoku_array[] = $year - (int) $kijunbi_array[$i][6];
                    // var_dump('通った2');
                    // var_dump($year);
                    // var_dump($month);
                    // var_dump($second_kijunbi_year);
                    // var_dump($second_kijunbi_month);
                } else {
                    // 入社年が現在年-1と同じ場合、勤続年数は現在年-入社年（例えば、現在年が2019年の時、2018年のひとが対象になり、2019-2018になる。）
                    // if ((int) $kijunbi_array[$i][6] == $year - 1) {

                    // $kinzoku_array[] = 1;　でもいいのでは
                    // $kinzoku_array[] = (int) $year - (int) $kijunbi_array[$i][6];

                    // var_dump('通った3');
                    // var_dump($kijunbi_array[$i][6]);
                    // var_dump($year);
                    // var_dump($month);
                    // var_dump($second_kijunbi_year);
                    // var_dump($second_kijunbi_month);
                    // } else {

                    $kinzoku_array[] = (int) $year - (int) $kijunbi_array[$i][6] - 1;

                    // var_dump('通った4');
                    // var_dump($kijunbi_array[$i][6]);
                    // var_dump($year);
                    // var_dump($month);
                    // var_dump($second_kijunbi_year);
                    // var_dump($second_kijunbi_month);
                    // }
                }
            }

            // var_dump($kinzoku_array);
        }




        $array = [];

        //対象者人数分繰り返す
        for ($i = 0; $i < count($employees_count); $i++) {

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


                    //年度最後の月計算(入社日に+17ヶ月(試用期間6ヶ月+1年度11ヶ月=17ヶ月)))
                    $day_max = $class->first_day_max($shain_cd_array[$i]);

                    //消化日数
                    $holiday_count_int = $class->holiday_count_int($kijunbi_array[$i][0], $kijunbi_array[$i][1], $shain_cd_array[$i]);

                    // var_dump('初年度の消化日数:');
                    // var_dump($holiday_count_int);

                    //消化残（期首残高-消化日数）
                    $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                    // var_dump('初年度の消化残:');
                    // var_dump($nokori);

                    //繰越日数（消化残が最大繰り越し日数より大きい場合、繰り越し日数は最大繰り越し日数と同じになる。小さい場合、繰り越し日数は消化残と同じ日数になる。）
                    $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                    // var_dump('初年度の繰越日数:');
                    // var_dump($carry_over_count);

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


                    // 期首残高
                    $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                    // var_dump($d . '年目の期首残高:');
                    // var_dump($kisyu_nokori);

                    //消化日数
                    //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                    $day_min = $class->day_min($kijunbi_array[$i][4], $d, $kijunbi_array[$i][5]);
                    // var_dump($d . '年目の年度初めの年月:');
                    // var_dump($day_min);

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

                    // echo ('</pre>');
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


                //[0]付与日数/[1]最大繰り越し日数/[2]前期繰越/[3]期首残高/[4]消化日数/[5]消化残/[6]繰越日数/[7]社員コード/
                $array[] = [$huyo_holiday, $max_carry_over, $carry_over, $kisyu_nokori, $holiday_count_int, $nokori, $carry_over_count, $shain_cd_array[$i]];
            }
        }

        // var_dump($array);


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
        }

        $holidays = [];
        // 全社員数分繰り返す
        for ($i = 0; $i < count($shain_cd_array); $i++) {
            // 消化残日数が3日以下のひとのみ、はいれつに入れていく
            if ($zansuu_holiday_personal[$shain_cd_array[$i]][5] <= 3) {
                array_push($holidays, $zansuu_holiday_personal[$shain_cd_array[$i]]);
            } else { }
        }


        $employees_array = [];
        $employees_array_kijunbi = [];

        // 社員情報を取得する
        for ($i = 0; $i < count($holidays); $i++) {

            // employeesテーブルに入っている情報
            $employees = DB::table('employees')
                ->whereNull('taishokubi')
                ->where('shain_cd', $holidays[$i][7])
                ->get();


            // 基準月の計算
            list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $class->kijunbi($holidays[$i][7]);

            // 基準月を抜き出す
            $kijunbi_month = $kijunbi_month_pre;
            // var_dump('基準月:' . $kijunbi_month);


            array_push($employees_array, $employees);
            array_push($employees_array_kijunbi, $kijunbi_month);
        }



        return view('zansuu_alert')->with([
            'title' => $title,
            'month' => $month,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
            // 社員情報はいれつ
            'employees2' => $employees_array,
            // 社員基準日はいれつ
            'employees2_kijunbi' => $employees_array_kijunbi,
            // 社員有給情報はいれつ
            'holidays' => $holidays,

        ]);
    }



















    // 時間外労働アラート
    public function overtime_working_alert()
    {
        $title = "時間外労働アラート対象者";

        // クラスのインスタンス化
        $class = new BaseClass();
        $o_class = new OverTimeWorkingClass();

        // 在籍者全員のデータ取得
        $employees = $class->all();

        // 入社年月・退職年月の取得
        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

        //一番最近のデータの年月を取得(=現在日時になる)
        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $o_class->overtime_working_all();

        //一番最近のデータの年
        $latest_year = $latest_year_pre;
        //一番最近のデータの月
        $latest_month = $latest_month_pre;
        // $latest_month = 2;

        //一番最近のデータの年月(文字あり)
        $latest_year_month = $latest_year_month_pre;



        // 時間外労働（月）アラート対象者を取得
        $employees_overtime_working_this_month = [];
        $employees_overtime_working_this_month = $o_class->overtime_working_this_month($latest_year, $latest_month);
        var_dump('ここだ1');
        var_dump($employees_overtime_working_this_month);
        var_dump(count($employees_overtime_working_this_month));

        // 時間外労働（年）アラート対象者を取得
        $employees_overtime_working_year = [];
        $employees_overtime_working_year = $o_class->overtime_working_year($latest_year, $latest_month);
        var_dump('ここだ2');
        var_dump($employees_overtime_working_year);
        var_dump(count($employees_overtime_working_year));

        // 平均（月）アラート対象者を取得
        $employees_overtime_working_avarege = [];
        $employees_overtime_working_avarege = $o_class->overtime_working_avarage($latest_year, $latest_month);
        var_dump('ここだ3');
        var_dump($employees_overtime_working_avarege);
        var_dump(count($employees_overtime_working_avarege));

        // 時間外労働時間が45時間を超えた月の回数（年）アラート対象者を取得
        $employees_overtime_working_45 = [];
        $employees_overtime_working_45 = $o_class->overtime_working_45($latest_year, $latest_month);
        var_dump('ここだ4');
        var_dump($employees_overtime_working_45);
        var_dump(count($employees_overtime_working_45));

        // 休日労働回数（月）アラート対象者を取得
        $employees_holiday_working_this_month_count = [];
        $employees_holiday_working_this_month_count = $o_class->holiday_working_this_month_count($latest_year, $latest_month);
        var_dump('ここだ5');
        var_dump($employees_holiday_working_this_month_count);
        // var_dump(count($employees_holiday_working_this_month_count));

        // if(is_null($employees_holiday_working_this_month_count)){
        //     $employees_holiday_working_this_month_count[] = 0;
        // }

        // var_dump($employees_holiday_working_this_month_count);
        // var_dump(count($employees_holiday_working_this_month_count));


        // 時間外労働+休日労働（月）アラート対象者を取得
        $employees_overtime_and_holiday_working_sum = [];
        $employees_overtime_and_holiday_working_sum = $o_class->overtime_and_holiday_working_sum($latest_year, $latest_month);

        var_dump('ここだ6');
        var_dump($employees_overtime_and_holiday_working_sum);
        var_dump(count($employees_overtime_and_holiday_working_sum));





        return view('overtime_working_alert')->with([
            'title' => $title,

            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
            // 社員情報はいれつ
            'employees' => $employees,


            // 最新年
            'latest_year' => $latest_year,
            // 最新月
            'latest_month' => $latest_month,
            // 最新年月
            'latest_year_month' => $latest_year_month,

            // 当月時間外労働アラート
            // 'overtime_working_this_month_array' => $overtime_working_this_month_array,

            // アラート引っ掛かった人たち
            'employees_overtime_working_this_month' => $employees_overtime_working_this_month,
            'employees_overtime_working_45' => $employees_overtime_working_45,
            'employees_holiday_working_this_month_count' => $employees_holiday_working_this_month_count,
            'employees_overtime_working_year' => $employees_overtime_working_year,
            'employees_overtime_working_avarege' => $employees_overtime_working_avarege,
            'employees_overtime_and_holiday_working_sum' => $employees_overtime_and_holiday_working_sum,


        ]);
    }












    // 時間外労働（月）アラート
    public function overtime_working_this_month_alert()
    {
        $title = "時間外労働（月）アラート対象者";

        // クラスのインスタンス化
        $class = new BaseClass();
        $o_class = new OverTimeWorkingClass();

        // 在籍者全員のデータ取得
        $employees = $class->all();

        // 入社年月・退職年月の取得
        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

        //一番最近のデータの年月を取得(=現在日時になる)
        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $o_class->overtime_working_all();

        //一番最近のデータの年
        $latest_year = $latest_year_pre;
        //一番最近のデータの月
        $latest_month = $latest_month_pre;
        // $latest_month = 2;

        //一番最近のデータの年月(文字あり)
        $latest_year_month = $latest_year_month_pre;



        // 時間外労働（月）アラート対象者を取得
        $employees_overtime_working_this_month = $o_class->overtime_working_this_month($latest_year, $latest_month);

        // 時間外労働（年）アラート対象者を0にしとく
        $employees_overtime_working_year[] = null;
        // 時間外労働平均（月）アラート対象者を0にしとく
        $employees_overtime_working_avarege[] = null;
        // 時間外労働時間が45時間を超えた月の回数（年）アラート対象者を0にしとく
        $employees_overtime_working_45[] = null;
        // 休日労働回数（月）アラート対象者を0にしとく
        $employees_holiday_working_this_month_count[] = null;
        // 時間外労働+休日労働（月）アラート対象者を0にしとく
        $employees_overtime_and_holiday_working_sum[] = null;



        return view('overtime_working_alert')->with([
            'title' => $title,

            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
            // 社員情報はいれつ
            'employees' => $employees,


            // 最新年
            'latest_year' => $latest_year,
            // 最新月
            'latest_month' => $latest_month,
            // 最新年月
            'latest_year_month' => $latest_year_month,

            // 当月時間外労働アラート
            // 'overtime_working_this_month_array' => $overtime_working_this_month_array,

            // アラート引っ掛かった人たち
            'employees_overtime_working_this_month' => $employees_overtime_working_this_month,

            // 表示しないアラート
            'employees_overtime_working_year' => $employees_overtime_working_year,
            'employees_overtime_working_avarege' => $employees_overtime_working_avarege,
            'employees_overtime_working_45' => $employees_overtime_working_45,
            'employees_holiday_working_this_month_count' => $employees_holiday_working_this_month_count,
            'employees_overtime_and_holiday_working_sum' => $employees_overtime_and_holiday_working_sum,


        ]);
    }




    // 時間外労働（年）アラート
    public function overtime_working_year_alert()
    {
        $title = "時間外労働（年）アラート対象者";

        // クラスのインスタンス化
        $class = new BaseClass();
        $o_class = new OverTimeWorkingClass();

        // 在籍者全員のデータ取得
        $employees = $class->all();

        // 入社年月・退職年月の取得
        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

        //一番最近のデータの年月を取得(=現在日時になる)
        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $o_class->overtime_working_all();

        //一番最近のデータの年
        $latest_year = $latest_year_pre;
        //一番最近のデータの月
        $latest_month = $latest_month_pre;
        // $latest_month = 2;

        //一番最近のデータの年月(文字あり)
        $latest_year_month = $latest_year_month_pre;



        // 時間外労働（年）アラート対象者を取得
        $employees_overtime_working_year = $o_class->overtime_working_year($latest_year, $latest_month);

        // 時間外労働（月）アラート対象者を0にしとく
        $employees_overtime_working_this_month[] = null;
        // 時間外労働平均（月）アラート対象者を0にしとく
        $employees_overtime_working_avarege[] = null;
        // 時間外労働時間が45時間を超えた月の回数（年）アラート対象者を0にしとく
        $employees_overtime_working_45[] = null;
        // 休日労働回数（月）アラート対象者を0にしとく
        $employees_holiday_working_this_month_count[] = null;
        // 時間外労働+休日労働（月）アラート対象者を0にしとく
        $employees_overtime_and_holiday_working_sum[] = null;

        // var_dump($employees_overtime_working_this_month);
        // var_dump($employees_overtime_working_avarege);



        return view('overtime_working_alert')->with([
            'title' => $title,

            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
            // 社員情報はいれつ
            'employees' => $employees,


            // 最新年
            'latest_year' => $latest_year,
            // 最新月
            'latest_month' => $latest_month,
            // 最新年月
            'latest_year_month' => $latest_year_month,

            // 当月時間外労働アラート
            // 'overtime_working_this_month_array' => $overtime_working_this_month_array,

            // アラート引っ掛かった人たち
            'employees_overtime_working_year' => $employees_overtime_working_year,

            // 表示しないアラート
            'employees_overtime_working_this_month' => $employees_overtime_working_this_month,
            'employees_overtime_working_avarege' => $employees_overtime_working_avarege,
            'employees_overtime_working_45' => $employees_overtime_working_45,
            'employees_holiday_working_this_month_count' => $employees_holiday_working_this_month_count,
            'employees_overtime_and_holiday_working_sum' => $employees_overtime_and_holiday_working_sum,


        ]);
    }





    // 平均（月）アラート
    public function overtime_working_avarage_alert()
    {
        $title = "平均（月）アラート対象者";

        // クラスのインスタンス化
        $class = new BaseClass();
        $o_class = new OverTimeWorkingClass();

        // 在籍者全員のデータ取得
        $employees = $class->all();

        // 入社年月・退職年月の取得
        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

        //一番最近のデータの年月を取得(=現在日時になる)
        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $o_class->overtime_working_all();

        //一番最近のデータの年
        $latest_year = $latest_year_pre;
        //一番最近のデータの月
        $latest_month = $latest_month_pre;
        // $latest_month = 2;

        //一番最近のデータの年月(文字あり)
        $latest_year_month = $latest_year_month_pre;



        // 平均（月）アラート対象者を取得
        $employees_overtime_working_avarege = $o_class->overtime_working_avarage($latest_year, $latest_month);

        // 時間外労働（月）アラート対象者を0にしとく
        $employees_overtime_working_this_month[] = null;
        // 時間外労働（年）アラート対象者を0にしとく
        $employees_overtime_working_year[] = null;
        // 時間外労働時間が45時間を超えた月の回数（年）アラート対象者を0にしとく
        $employees_overtime_working_45[] = null;
        // 休日労働回数（月）アラート対象者を0にしとく
        $employees_holiday_working_this_month_count[] = null;
        // 時間外労働+休日労働（月）アラート対象者を0にしとく
        $employees_overtime_and_holiday_working_sum[] = null;

        var_dump($employees_overtime_working_this_month);
        var_dump($employees_overtime_working_year);



        return view('overtime_working_alert')->with([
            'title' => $title,

            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
            // 社員情報はいれつ
            'employees' => $employees,


            // 最新年
            'latest_year' => $latest_year,
            // 最新月
            'latest_month' => $latest_month,
            // 最新年月
            'latest_year_month' => $latest_year_month,

            // 当月時間外労働アラート
            // 'overtime_working_this_month_array' => $overtime_working_this_month_array,

            // アラート引っ掛かった人たち
            'employees_overtime_working_avarege' => $employees_overtime_working_avarege,

            // 表示しないアラート
            'employees_overtime_working_this_month' => $employees_overtime_working_this_month,
            'employees_overtime_working_year' => $employees_overtime_working_year,
            'employees_overtime_working_45' => $employees_overtime_working_45,
            'employees_holiday_working_this_month_count' => $employees_holiday_working_this_month_count,
            'employees_overtime_and_holiday_working_sum' => $employees_overtime_and_holiday_working_sum,


        ]);
    }






    // 時間外労働時間が45時間を超えた月の回数（年）アラート
    public function overtime_working_45()
    {
        $title = "時間外労働時間が45時間を超えた月の回数（年）アラート対象者";

        // クラスのインスタンス化
        $class = new BaseClass();
        $o_class = new OverTimeWorkingClass();

        // 在籍者全員のデータ取得
        $employees = $class->all();

        // 入社年月・退職年月の取得
        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

        //一番最近のデータの年月を取得(=現在日時になる)
        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $o_class->overtime_working_all();

        //一番最近のデータの年
        $latest_year = $latest_year_pre;
        //一番最近のデータの月
        $latest_month = $latest_month_pre;
        // $latest_month = 2;

        //一番最近のデータの年月(文字あり)
        $latest_year_month = $latest_year_month_pre;



        // 時間外労働時間が45時間を超えた月の回数（年）アラート対象者を取得
        $employees_overtime_working_45 = $o_class->overtime_working_45($latest_year, $latest_month);

        // 時間外労働（月）アラート対象者を0にしとく
        $employees_overtime_working_this_month[] = null;
        // 時間外労働（年）アラート対象者を0にしとく
        $employees_overtime_working_year[] = null;
        // 時間外労働平均（月）アラート対象者を0にしとく
        $employees_overtime_working_avarege[] = null;
        // 休日労働回数（月）アラート対象者を0にしとく
        $employees_holiday_working_this_month_count[] = null;
        // 時間外労働+休日労働（月）アラート対象者を0にしとく
        $employees_overtime_and_holiday_working_sum[] = null;

        var_dump($employees_overtime_working_this_month);
        var_dump($employees_overtime_working_year);



        return view('overtime_working_alert')->with([
            'title' => $title,

            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
            // 社員情報はいれつ
            'employees' => $employees,


            // 最新年
            'latest_year' => $latest_year,
            // 最新月
            'latest_month' => $latest_month,
            // 最新年月
            'latest_year_month' => $latest_year_month,

            // 当月時間外労働アラート
            // 'overtime_working_this_month_array' => $overtime_working_this_month_array,

            // アラート引っ掛かった人たち
            'employees_overtime_working_45' => $employees_overtime_working_45,

            // 表示しないアラート
            'employees_overtime_working_avarege' => $employees_overtime_working_avarege,
            'employees_overtime_working_this_month' => $employees_overtime_working_this_month,
            'employees_overtime_working_year' => $employees_overtime_working_year,
            'employees_holiday_working_this_month_count' => $employees_holiday_working_this_month_count,
            'employees_overtime_and_holiday_working_sum' => $employees_overtime_and_holiday_working_sum,


        ]);
    }





    // 休日労働回数（月）アラート
    public function holiday_working_this_month_count()
    {
        $title = "時間外労働時間が45時間を超えた月の回数（年）アラート対象者";

        // クラスのインスタンス化
        $class = new BaseClass();
        $o_class = new OverTimeWorkingClass();

        // 在籍者全員のデータ取得
        $employees = $class->all();

        // 入社年月・退職年月の取得
        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

        //一番最近のデータの年月を取得(=現在日時になる)
        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $o_class->overtime_working_all();

        //一番最近のデータの年
        $latest_year = $latest_year_pre;
        //一番最近のデータの月
        $latest_month = $latest_month_pre;
        // $latest_month = 2;

        //一番最近のデータの年月(文字あり)
        $latest_year_month = $latest_year_month_pre;



        // 休日労働回数（月）アラート対象者を取得
        $employees_holiday_working_this_month_count = $o_class->holiday_working_this_month_count($latest_year, $latest_month);

        // 時間外労働（月）アラート対象者を0にしとく
        $employees_overtime_working_this_month[] = null;
        // 時間外労働（年）アラート対象者を0にしとく
        $employees_overtime_working_year[] = null;
        // 時間外労働平均（月）アラート対象者を0にしとく
        $employees_overtime_working_avarege[] = null;
        // 時間外労働時間が45時間を超えた月の回数（年）アラート対象者を0にしとく
        $employees_overtime_working_45[] = null;

        // 時間外労働+休日労働（月）アラート対象者を0にしとく
        $employees_overtime_and_holiday_working_sum[] = null;

        var_dump($employees_overtime_working_this_month);
        var_dump($employees_overtime_working_year);



        return view('overtime_working_alert')->with([
            'title' => $title,

            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
            // 社員情報はいれつ
            'employees' => $employees,


            // 最新年
            'latest_year' => $latest_year,
            // 最新月
            'latest_month' => $latest_month,
            // 最新年月
            'latest_year_month' => $latest_year_month,

            // 当月時間外労働アラート
            // 'overtime_working_this_month_array' => $overtime_working_this_month_array,

            // アラート引っ掛かった人たち
            'employees_holiday_working_this_month_count' => $employees_holiday_working_this_month_count,

            // 表示しないアラート
            'employees_overtime_working_45' => $employees_overtime_working_45,
            'employees_overtime_working_avarege' => $employees_overtime_working_avarege,
            'employees_overtime_working_this_month' => $employees_overtime_working_this_month,
            'employees_overtime_working_year' => $employees_overtime_working_year,
            'employees_overtime_and_holiday_working_sum' => $employees_overtime_and_holiday_working_sum,


        ]);
    }





    // 時間外労働+休日労働（月）アラート
    public function overtime_and_holiday_working_sum()
    {
        $title = "時間外労働+休日労働（月）アラート対象者";

        // クラスのインスタンス化
        $class = new BaseClass();
        $o_class = new OverTimeWorkingClass();

        // 在籍者全員のデータ取得
        $employees = $class->all();

        // 入社年月・退職年月の取得
        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

        //一番最近のデータの年月を取得(=現在日時になる)
        list($latest_year_pre, $latest_month_pre, $latest_year_month_pre) = $o_class->overtime_working_all();

        //一番最近のデータの年
        $latest_year = $latest_year_pre;
        //一番最近のデータの月
        $latest_month = $latest_month_pre;
        // $latest_month = 2;

        //一番最近のデータの年月(文字あり)
        $latest_year_month = $latest_year_month_pre;



        // 時間外労働+休日労働（月）アラート対象者を取得
        $employees_overtime_and_holiday_working_sum = $o_class->overtime_and_holiday_working_sum($latest_year, $latest_month);

        // 時間外労働（月）アラート対象者を0にしとく
        $employees_overtime_working_this_month[] = null;
        // 時間外労働（年）アラート対象者を0にしとく
        $employees_overtime_working_year[] = null;
        // 時間外労働平均（月）アラート対象者を0にしとく
        $employees_overtime_working_avarege[] = null;
        // 時間外労働時間が45時間を超えた月の回数（年）アラート対象者を0にしとく
        $employees_overtime_working_45[] = null;
        // 休日労働回数（月）アラート対象者を0にしとく
        $employees_holiday_working_this_month_count[] = null;


        var_dump($employees_overtime_working_this_month);
        var_dump($employees_overtime_working_year);



        return view('overtime_working_alert')->with([
            'title' => $title,

            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
            // 社員情報はいれつ
            'employees' => $employees,


            // 最新年
            'latest_year' => $latest_year,
            // 最新月
            'latest_month' => $latest_month,
            // 最新年月
            'latest_year_month' => $latest_year_month,

            // 当月時間外労働アラート
            // 'overtime_working_this_month_array' => $overtime_working_this_month_array,

            // アラート引っ掛かった人たち
            'employees_overtime_and_holiday_working_sum' => $employees_overtime_and_holiday_working_sum,

            // 表示しないアラート
            'employees_holiday_working_this_month_count' => $employees_holiday_working_this_month_count,
            'employees_overtime_working_45' => $employees_overtime_working_45,
            'employees_overtime_working_avarege' => $employees_overtime_working_avarege,
            'employees_overtime_working_this_month' => $employees_overtime_working_this_month,
            'employees_overtime_working_year' => $employees_overtime_working_year,


        ]);
    }
}
