<?php
// app/Library/BaseClass.php
namespace app\Library;

use DB;
use App\Library\BaseClass;
use App\Employee;


class OverTimeWorkingClass
{

    // 時間外労働アラートで使用する必須項目たち
    public function overtime_working_all()
    {
        //一番最近のデータの年月を作成(=現在日時になる)
        $year_month_a_pre = DB::table('overtime_workings')
            ->select(db::raw('year,lpad(month, 2, "0") as month'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
        // var_dump($year_month_a_pre);
        //一番最近のデータの年
        $latest_year_pre = $year_month_a_pre->year;
        //一番最近のデータの月
        $latest_month_pre = $year_month_a_pre->month;
        // $latest_month = 2;

        //一番最近のデータの年月
        $latest_year_month_pre = $latest_year_pre . '年' . $latest_month_pre . '月';
        // var_dump('現在年:' . $latest_year);
        // var_dump('現在月:' . $latest_month);

        return [$latest_year_pre, $latest_month_pre, $latest_year_month_pre];
    }


    // 時間外労働（月）アラート
    public function overtime_working_this_month($latest_year, $latest_month)
    {

        // クラスのインスタンス化
        $class = new BaseClass();

        // 在籍者全員のデータ取得
        $employees = $class->all();

        // 在籍者の人数
        $employees_count = count($employees);
        // var_dump($employees_count);

        // 在籍者の社員コード
        for ($i = 0; $i < $employees_count; $i++) {
            $shain_cd_array[] = $employees[$i]->shain_cd;
        }
        // var_dump($shain_cd_array);


        // $overtime_working_this_month_array_pre = [];


        // 時間外労働（月）
        for ($i = 0; $i <= $employees_count - 1; $i++) {

            $overtime_working_this_month_pre = DB::table('overtime_workings')
                ->where('year', $latest_year)
                ->where('month', $latest_month)
                ->where('shain_cd', $shain_cd_array[$i])
                ->first();

            // var_dump($overtime_working_this_month_pre);

            if (is_null($overtime_working_this_month_pre)) {
                $overtime_working_this_month = 0;
            } else {
                $overtime_working_this_month = (float) $overtime_working_this_month_pre->overtime_working;
            }

            // はいれつの中に社員コードと当月の時間外労働を入れておく
            $overtime_working_this_month_array_pre[] = [$shain_cd_array[$i], $overtime_working_this_month];
        }


        // 在籍者全員の当月時間外労働データ
        // var_dump('在籍者全員の当月時間外労働データ');
        // var_dump($overtime_working_this_month_array_pre);


        // 例外時間外労働上限（月）に引っ掛かるものを抽出する
        $exception_working_overtime_month_pre = DB::table('overtime_working_constants')
            ->select('exception_working_overtime_month')
            ->first();

        $exception_working_overtime_month = $exception_working_overtime_month_pre->exception_working_overtime_month;
        // var_dump('例外時間外労働上限（月）');
        // var_dump($exception_working_overtime_month);

        $overtime_working_this_month_array = [];
        for ($i = 0; $i <= $employees_count - 1; $i++) {

            // アラートなので規定時間-10で判断し引っ掛かる人のみ配列に入れる
            if ($overtime_working_this_month_array_pre[$i][1] >= $exception_working_overtime_month - 10) {
                // $overtime_working_this_month_array = [$shain_cd_array[$i], $overtime_working_this_month_array_pre[$i][1]];
                $overtime_working_this_month_array[] = [$shain_cd_array[$i], $overtime_working_this_month_array_pre[$i][1]];
            }
        }

        // var_dump($overtime_working_this_month_array);


        $employees_overtime_working_this_month_pre = [];
        // if (is_null($overtime_working_this_month_array)) {
        if (empty($overtime_working_this_month_array)) {
            var_dump('例外時間外労働上限（月）に引っ掛かったひとはいません');
        } else {
            // 例外時間外労働上限（月）に引っ掛かったひと

            // var_dump($overtime_working_this_month_array);

            // 時間外労働上限（月）に引っ掛かった人の従業員データ
            for ($i = 0; $i <= count($overtime_working_this_month_array) - 1; $i++) {
                $employees_overtime_working_this_month_pre[] = DB::table('employees')
                    ->whereNull('taishokubi')
                    ->where('department', '!=', '05')
                    ->where('shain_cd', $overtime_working_this_month_array[$i][0])
                    ->get();
            }
        }
        var_dump('例外時間外労働上限（月）に引っ掛かったひとの従業員データ');
        var_dump($employees_overtime_working_this_month_pre);


        return $employees_overtime_working_this_month_pre;
    }


    // 時間外労働（年）アラート
    public function overtime_working_year($latest_year, $latest_month)
    {

        // クラスのインスタンス化
        $class = new BaseClass();

        // 在籍者全員のデータ取得
        $employees = $class->all();

        // 在籍者の人数
        $employees_count = count($employees);
        // var_dump($employees_count);

        // 在籍者の社員コード
        for ($i = 0; $i < $employees_count; $i++) {
            $shain_cd_array[] = $employees[$i]->shain_cd;
        }
        // var_dump($shain_cd_array);


        // 例外時間外労働（年）
        // 変更必要！！！※期間が4月～じゃなくなったときに使えなくなる
        $overtime_working_year_array_pre = [];
        // 選択された月までの例外時間外労働を抜き出す
        // 選択した月数が4月より大きい場合は4月から選択した月数まで繰り返してOK（年数が全て同じなので…）
        if ((int) $latest_month >= 4) {
            // var_dump('通った');

            // 在籍者人数分繰り返す
            for ($m = 0; $m <= $employees_count - 1; $m++) {

                $overtime_working_year = DB::table('overtime_workings')
                    ->select(DB::raw('sum(CASE WHEN year =' . $latest_year . ' and month >= 4 and shain_cd = ' . $shain_cd_array[$m] . ' THEN overtime_working ELSE 0 END) AS sumday'))
                    ->get();
                // var_dump($overtime_working);

                // 順番に追加していく
                $overtime_working_year_array_pre[] = [$shain_cd_array[$m], $overtime_working_year[0]->sumday,];
            }


            // 選択した月数が1・2・3月だった場合、前年の4月から今年の選択した年数になる
        } else {
            for ($m = 0; $m <= $employees_count - 1; $m++) {

                $latest_year_before = $latest_year - 1;
                $overtime_working_year = DB::table('overtime_workings')
                    ->select(DB::raw('sum(CASE WHEN (year =' . $latest_year_before . ' and month >= 4 and shain_cd = ' . $shain_cd_array[$m] . ' ) or (year = ' . $latest_year . ' and month >= 1 and month <= 3 and shain_cd = ' . $shain_cd_array[$m] . ' ) THEN overtime_working ELSE 0 END) AS sumday'))
                    ->get();

                // 順番に追加していく
                $overtime_working_year_array_pre[] = [$shain_cd_array[$m], $overtime_working_year[0]->sumday,];
            }
        }


        // var_dump('年間時間外労働');
        // var_dump($overtime_working_year_array_pre);


        //  時間外労働上限（年）に引っ掛かるものを抽出する
        $exception_working_overtime_year_pre = DB::table('overtime_working_constants')
            ->select('exception_working_overtime_year')
            ->first();


        $exception_working_overtime_year = $exception_working_overtime_year_pre->exception_working_overtime_year;
        // var_dump('時間外労働上限（年）');
        // var_dump($exception_working_overtime_year);


        $overtime_working_year_array = [];
        for ($i = 0; $i <= $employees_count - 1; $i++) {
            if ($overtime_working_year_array_pre[$i][1] >= $exception_working_overtime_year - 10) {
                // var_dump('通った');
                $overtime_working_year_array[] = [$shain_cd_array[$i], $overtime_working_year_array_pre[$i][1]];
            }
        }

        // var_dump($overtime_working_year_array);


        $employees_overtime_working_year_pre = [];

        if (empty($overtime_working_year_array)) {
            var_dump('例外時間外労働上限（月）に引っ掛かったひとはいません');
        } else {
            // 例外時間外労働上限（月）に引っ掛かったひと
            // var_dump($overtime_working_year_array);

            // 時間外労働上限（月）に引っ掛かった人の従業員データ
            for ($i = 0; $i <= count($overtime_working_year_array) - 1; $i++) {
                $employees_overtime_working_year_pre[] = DB::table('employees')
                    ->whereNull('taishokubi')
                    ->where('department', '!=', '05')
                    ->where('shain_cd', $overtime_working_year_array[$i][0])
                    ->get();
            }
        }

        var_dump('例外時間外労働上限（月）に引っ掛かったひとの従業員データ');
        var_dump($employees_overtime_working_year_pre);
        // var_dump($employees_overtime_working_year[0][0]->shain_cd);

        return $employees_overtime_working_year_pre;
    }

    // 平均（月）アラート
    public function overtime_working_avarage($latest_year, $latest_month)
    {

        // クラスのインスタンス化
        $class = new BaseClass();

        // 在籍者全員のデータ取得
        $employees = $class->all();

        // 在籍者の人数
        $employees_count = count($employees);
        // var_dump($employees_count);

        // 在籍者の社員コード
        for ($i = 0; $i < $employees_count; $i++) {
            $shain_cd_array[] = $employees[$i]->shain_cd;
        }
        // var_dump($shain_cd_array);

        // 時間外労働（月）
        for ($i = 0; $i <= $employees_count - 1; $i++) {

            $overtime_working_this_month_pre = DB::table('overtime_workings')
                ->where('year', $latest_year)
                ->where('month', $latest_month)
                ->where('shain_cd', $shain_cd_array[$i])
                ->first();

            // var_dump($overtime_working_this_month_pre);

            if (is_null($overtime_working_this_month_pre)) {
                $overtime_working_this_month = 0;
            } else {
                $overtime_working_this_month = (float) $overtime_working_this_month_pre->overtime_working;
            }

            // はいれつの中に社員コードと当月の時間外労働を入れておく
            $overtime_working_this_month_array_pre[] = [$shain_cd_array[$i], $overtime_working_this_month];
        }


        // 休日労働（月）
        for ($i = 0; $i <= $employees_count - 1; $i++) {
            // 休日労働（月）
            $holiday_working_this_month_pre = DB::table('holiday_workings')
                ->select(DB::raw('sum(holiday_working) as holiday_working'))
                ->where('year', $latest_year)
                ->where('month', $latest_month)
                ->where('shain_cd', $shain_cd_array[$i])
                ->get();
            // ->toSql();
            // dd($overtime_working_this_month);

            // var_dump($holiday_working_this_month_pre[0]);

            if (is_null($holiday_working_this_month_pre)) {
                $holiday_working_this_month = 0;
            } else {
                // $holiday_working_this_month = (float) $holiday_working_this_month_pre->overtime_working;
                $holiday_working_this_month = (float) $holiday_working_this_month_pre[0]->holiday_working;
            }

            $holiday_working_this_month_array_pre[] = [$shain_cd_array[$i], $holiday_working_this_month];
        }



        $post_year_month_slash = $latest_year . '/' . $latest_month . '/1';
        $before_1_year_month_pre = date('Y/m/d', strtotime($post_year_month_slash . "-1 month"));
        $before_1_year = substr($before_1_year_month_pre, 0, 4);
        $before_1_month = substr($before_1_year_month_pre, 5, 2);

        // echo ('<pre>');
        // var_dump('1か月前:');
        // var_dump($before_1_year);
        // var_dump($before_1_month);
        // echo ('</pre>');


        $before_2_year_month_pre = date('Y/m/d', strtotime($post_year_month_slash . "-2 month"));
        $before_2_year = substr($before_2_year_month_pre, 0, 4);
        $before_2_month = substr($before_2_year_month_pre, 5, 2);

        // echo ('<pre>');
        // var_dump('2か月前:');
        // var_dump($before_2_year);
        // var_dump($before_2_month);
        // echo ('</pre>');

        $before_3_year_month_pre = date('Y/m/d', strtotime($post_year_month_slash . "-3 month"));
        $before_3_year = substr($before_3_year_month_pre, 0, 4);
        $before_3_month = substr($before_3_year_month_pre, 5, 2);

        // echo ('<pre>');
        // var_dump('3か月前:');
        // var_dump($before_3_year);
        // var_dump($before_3_month);
        // echo ('</pre>');

        $before_4_year_month_pre = date('Y/m/d', strtotime($post_year_month_slash . "-4 month"));
        $before_4_year = substr($before_4_year_month_pre, 0, 4);
        $before_4_month = substr($before_4_year_month_pre, 5, 2);

        // echo ('<pre>');
        // var_dump('4か月前:');
        // var_dump($before_4_year);
        // var_dump($before_4_month);
        // echo ('</pre>');

        $before_5_year_month_pre = date('Y/m/d', strtotime($post_year_month_slash . "-5 month"));
        $before_5_year = substr($before_5_year_month_pre, 0, 4);
        $before_5_month = substr($before_5_year_month_pre, 5, 2);

        // echo ('<pre>');
        // var_dump('5か月前:');
        // var_dump($before_5_year);
        // var_dump($before_5_month);
        // echo ('</pre>');

        // 5ヶ月前の年を入れます
        $before_year_array = [$before_1_year, $before_2_year, $before_3_year, $before_4_year, $before_5_year];
        // var_dump($before_year_array);

        // 5か月前の月をいれます
        $before_month_array = [$before_1_month, $before_2_month, $before_3_month, $before_4_month, $before_5_month];
        // var_dump($before_month_array);





        // 例外時間外労働（平均）
        // 5か月分の時間外労働を入れておく配列
        $overtime_working_before_array = [];
        // 上の配列に入った労働時間の合計を計算し入れておく配列
        $overtime_working_before_sum_array = [];

        // 5か月分の時間外労働を入れておく配列
        $holiday_working_before_array = [];
        // 上の配列に入った労働時間の合計を計算し入れておく配列
        $holiday_working_before_sum_array = [];


        // 平均の上限に引っ掛かるものを抽出する
        $overtime_working_average_pre = DB::table('overtime_working_constants')
            ->select('overtime_working_average')
            ->first();

        $overtime_working_average = $overtime_working_average_pre->overtime_working_average;
        // var_dump('平均の上限');
        // var_dump($overtime_working_average);

        $avarege = [];

        // for ($m = 0; $m <= $employees_count - 1; $m++) {
        for ($m = 0; $m <= 2 - 1; $m++) {
            // 5か月前から1か月前までの時間外労働を抽出
            for ($i = 0; $i <= 4; $i++) {
                // for ($i = 0; $i <= 1; $i++) {


                $overtime_working_before_months_pre = DB::table('overtime_workings')
                    ->select('overtime_working')
                    ->where('year', $before_year_array[$i])
                    ->where('month', $before_month_array[$i])
                    ->where('shain_cd', $shain_cd_array[$m])
                    ->first();



                if (is_null($overtime_working_before_months_pre)) {
                    $overtime_working_before_months = 0;
                } else {
                    $overtime_working_before_months = (float) $overtime_working_before_months_pre->overtime_working;
                }


                // echo '<pre>';
                // var_dump('ここみて');
                // var_dump($overtime_working_before_months);
                // var_dump($before_year_array[$i]);
                // var_dump($before_month_array[$i]);
                // var_dump($shain_cd_array[$m]);
                // echo '</pre>';


                // var_dump($overtime_working_before_months);
                // 5か月分の時間外労働をどんどん入れてく
                if ($m >= 1 and $i == 0) {
                    // var_dump('リセット2');
                    $overtime_working_before_array = [];
                }
                $overtime_working_before_array[] = $overtime_working_before_months;
                // var_dump($overtime_working_before_array);

                // 上の配列にはいった労働時間をどんどん足して入れてく
                // $overtime_working_before_sum_pre = array_sum($overtime_working_before_array);
                // var_dump($overtime_working_before_sum_pre);

                // $overtime_working_before_sum_array[] = [$shain_cd_array[$m], $i + 1, $overtime_working_before_sum_pre];
                // var_dump('$i' . $i);
                if ($m >= 1 and $i == 0) {
                    // var_dump('リセット');
                    $overtime_working_before_sum_array = [];
                }
                $overtime_working_before_sum_array[] = array_sum($overtime_working_before_array);
                // var_dump($overtime_working_before_sum_array);


                // var_dump('ここみて');
                // var_dump($overtime_working_before_sum_array);
                // var_dump($overtime_working_before_sum_array);
            }


            for ($i = 0; $i <= 4; $i++) {
                // 5か月前から1か月前までの休日労働を抽出
                $holiday_working_before_months_pre = DB::table('holiday_workings')
                    // ->select('holiday_working')
                    ->select(DB::raw('sum(holiday_working) as holiday_working'))
                    ->where('year', $before_year_array[$i])
                    ->where('month', $before_month_array[$i])
                    ->where('shain_cd', $shain_cd_array[$m])
                    ->get();


                if (is_null($holiday_working_before_months_pre)) {
                    $holiday_working_before_months = 0;
                } else {
                    $holiday_working_before_months = (float) $holiday_working_before_months_pre[0]->holiday_working;
                }

                // echo '<pre>';
                // var_dump('ここみて2');
                // var_dump($holiday_working_before_months);
                // var_dump($before_year_array[$i]);
                // var_dump($before_month_array[$i]);
                // var_dump($shain_cd_array[$m]);
                // echo '</pre>';

                // var_dump($holiday_working_before_months);


                // 5か月分の休日労働をどんどん入れてく
                $holiday_working_before_array[] = $holiday_working_before_months;

                // var_dump($holiday_working_before_array);

                // 上の配列にはいった休日労働をどんどん足して入れてく
                // $holiday_working_before_sum_pre = array_sum($holiday_working_before_array);
                // var_dump($holiday_working_before_sum_pre);

                // $holiday_working_before_sum_array[] = [$shain_cd_array[$m], $i + 1,  $holiday_working_before_sum_pre];
                $holiday_working_before_sum_array[] = array_sum($holiday_working_before_array);
                // var_dump($holiday_working_before_sum_array);

                // $months_average = ($overtime_working_this_month_array_pre[$i][1] + $overtime_working_before_sum_pre + $holiday_working_this_month_array_pre[$i][1] + $holiday_working_before_sum_pre) / ($i + 2);
                // var_dump('平均');
                // var_dump($shain_cd_array[$m]);
                // var_dump($months_average);
            }
            $months_average = [];
            for ($i = 0; $i <= 4; $i++) {
                $months_average_pre = [(float) $overtime_working_this_month_array_pre[$m][1], (float) $overtime_working_before_sum_array[$i], (float) $holiday_working_this_month_array_pre[$m][1], (float) $holiday_working_before_sum_array[$i]];
                $months_average = array_sum($months_average_pre) / ($i + 2);

                // echo '<pre>';
                // var_dump('平均');
                // var_dump((float)$overtime_working_this_month_array_pre[$m][1]);
                // var_dump((float)$overtime_working_before_sum_array[$i]);
                // var_dump((float)$holiday_working_this_month_array_pre[$m][1]);
                // var_dump((float)$holiday_working_before_sum_array[$i]);
                // var_dump($i + 2);
                // var_dump('ここよ');
                // var_dump(array_sum($months_average_pre));
                // var_dump($i + 2);
                // var_dump($months_average);
                // echo '</pre>';

                if ($months_average >= $overtime_working_average - 10) {
                    $avarege[] = $shain_cd_array[$m];
                }
                // var_dump('平均越え');
                // var_dump($avarege);
            }
        }

        // 重複した社員コードを消しちゃう
        $overtime_working_avarege_unique = array_unique($avarege);
        // 重複した社員コードを消したあとキーを振り直す
        $overtime_working_avarege_unique_values = array_values($overtime_working_avarege_unique);
        // var_dump($overtime_working_avarege_unique_values);




        $employees_overtime_working_avarege_pre = [];
        if (empty($overtime_working_avarege_unique_values)) {
            var_dump('平均（月）に引っ掛かったひとはいません');
        } else {
            // 平均（月）に引っ掛かったひと

            // var_dump($overtime_and_holiday_working_sum_array);

            // 平均（月）に引っ掛かったひと
            for ($i = 0; $i <= count($overtime_working_avarege_unique_values) - 1; $i++) {

                $employees_overtime_working_avarege_pre[] = DB::table('employees')
                    ->whereNull('taishokubi')
                    ->where('department', '!=', '05')
                    ->where('shain_cd', $overtime_working_avarege_unique_values[$i])
                    ->get();
            }
        }
        var_dump('平均（月）に引っ掛かったひとの従業員データ');
        var_dump($employees_overtime_working_avarege_pre);
        // var_dump($employees_overtime_and_holiday_working_sum[0][0]->shain_cd);

        return $employees_overtime_working_avarege_pre;
    }


    // 時間外労働時間が45時間を超えた月が年に6回まで
    public function overtime_working_45($latest_year, $latest_month)
    {

        // クラスのインスタンス化
        $class = new BaseClass();

        // 在籍者全員のデータ取得
        $employees = $class->all();

        // 在籍者の人数
        $employees_count = count($employees);
        // var_dump($employees_count);

        // 在籍者の社員コード
        for ($i = 0; $i < $employees_count; $i++) {
            $shain_cd_array[] = $employees[$i]->shain_cd;
        }
        // var_dump($shain_cd_array);


        // 時間外労働が45時間以上あった月が年に6-1回以上
        $overtime_working_45_array_pre = [];
        $overtime_working_45_result = 0;
        // 選択した月数が4月より大きい場合は4月から選択した月数まで繰り返してOK（年数が全て同じなので…）
        if ((int) $latest_month >= 4) {

            // 在籍者人数分繰り返す
            for ($m = 0; $m <= $employees_count - 1; $m++) {
                // 四月からデータ最新年月まで繰り返す
                for ($i = 4; $i <= (int) $latest_month; $i++) {
                    $overtime_working_45 = DB::table('overtime_workings')
                        ->select('overtime_working')
                        ->where('year', $latest_year)
                        ->where('month', $i)
                        ->where('shain_cd', $shain_cd_array[$m])
                        ->first();
                    // ->toSql();



                    // 入力されていない場合は0時間とする
                    if (is_null($overtime_working_45)) {
                        $overtime_working_45_result = 0;
                        // 入力されている場合は配列使いやすくするために入れ替える
                    } else {
                        $overtime_working_45_result = $overtime_working_45->overtime_working;
                    }
                    // var_dump($overtime_working_result);


                    // 順番に追加していく
                    $overtime_working_45_array_pre[] = [$shain_cd_array[$m], $latest_year, $i, $overtime_working_45_result];
                    // $overtime_working_array_pre[] = [$shain_cd_array[$m] => [$latest_year, $i, $overtime_working_result,]];
                }
            }
        } else {

            for ($m = 0; $m <= $employees_count - 1; $m++) {
                // 1月からデータ最新年月まで繰り返す
                for ($i = 1; $i <= (int) $latest_month; $i++) {
                    $overtime_working_45 = DB::table('overtime_workings')
                        ->select('overtime_working')
                        ->where('year', $latest_year)
                        ->where('month', $i)
                        ->where('shain_cd', $shain_cd_array[$m])
                        ->first();

                    // 入力されていない場合は0時間とする
                    if (is_null($overtime_working_45)) {
                        $overtime_working_45_result = 0;
                        // 入力されている場合は配列使いやすくするために入れ替える
                    } else {
                        $overtime_working_45_result = $overtime_working_45->overtime_working;
                    }
                    // 順番に追加していく
                    $overtime_working_45_array_pre[] = [$shain_cd_array[$m], $latest_year, $i, $overtime_working_45_result];
                }

                // var_dump($overtime_working_year_array_pre);

                // 前年の4月から12月まで繰り返す
                for ($i2 = 4; $i2 <= 12; $i2++) {
                    $overtime_working_45 = DB::table('overtime_workings')
                        ->select('overtime_working')
                        ->where('year', $latest_year - 1)
                        ->where('month', $m)
                        ->where('shain_cd', $shain_cd_array[$m])
                        ->first();

                    // 入力されていない場合は0時間とする
                    if (is_null($overtime_working_45)) {
                        $overtime_working_result = 0;
                        // 入力されている場合は配列使いやすくするために入れ替える
                    } else {
                        $overtime_working_45_result = $overtime_working_45->overtime_working;
                    }
                    // 順番に追加していく
                    $overtime_working_45_array_pre[] = [$shain_cd_array[$m], $latest_year, $i2, $overtime_working_45_result];
                }
            }
        }


        // var_dump($overtime_working_45_array_pre);

        // var_dump($overtime_working_array_pre[0][3]);

        $overtime_working_45_array = [];
        for ($i = 0; $i <= count($overtime_working_45_array_pre) - 1; $i++) {
            // 45時間以上働いている月を抽出
            if ($overtime_working_45_array_pre[$i][3] >= 45) {
                // var_dump('45H以上働いている');
                // var_dump($overtime_working_array_pre[$i]);

                // $overtime_working_45_array[] = [$overtime_working_45_array_pre[$i][0] => $overtime_working_45_array_pre[$i]];
                // $overtime_working_45_array[] = [$overtime_working_45_array_pre[$i]];
                $overtime_working_45_array[] = $overtime_working_45_array_pre[$i][0];
            }
        }

        // var_dump($overtime_working_45_array);

        // 45時間以上時間外労働している月が入った配列を社員コードが重複したものでまとめる
        $array = array_count_values($overtime_working_45_array);
        // var_dump('ここみて');
        // var_dump($array);

        $overtime_working_45_array_result = [];
        for ($i = 1; $i <= count($overtime_working_45_array) - 1; $i++) {

            // 同じ社員コードが5回以上カウントされている場合はアラートを表示するので社員コードのみを配列に入れなおす
            if ($array[$overtime_working_45_array[$i]] >= 6 - 1) {
                // var_dump('5回以上');
                $overtime_working_45_array_result[] = $overtime_working_45_array[$i];
            }
        }

        // アラートを出す社員コード※重複分そのまま入ってしまっている
        // var_dump($overtime_working_45_array_result);

        // 重複した社員コードを消しちゃう
        $overtime_working_45_array_result_unique = array_unique($overtime_working_45_array_result);
        // 重複した社員コードを消したあとキーを振り直す
        $overtime_working_45_array_result_values = array_values($overtime_working_45_array_result_unique);
        // var_dump('overtime_working_45_array_result_values');
        // var_dump($overtime_working_45_array_result_values);



        $employees_overtime_working_45_pre = [];
        // 時間外労働45時間以上が一年で規定回数以上あった従業員データ
        for ($i = 0; $i <= count($overtime_working_45_array_result_values) - 1; $i++) {

            $employees_overtime_working_45_pre[] = DB::table('employees')
                ->whereNull('taishokubi')
                ->where('department', '!=', '05')
                ->where('shain_cd', $overtime_working_45_array_result_values[$i])
                ->get();
        }

        var_dump('時間外労働45時間以上が一年で規定回数以上あった従業員データ');
        var_dump($employees_overtime_working_45_pre);
        // var_dump($employees_overtime_working_45[0][0]->shain_cd);

        return $employees_overtime_working_45_pre;
    }


    // 休日労働回数（月）アラート
    public function holiday_working_this_month_count($latest_year, $latest_month)
    {

        // クラスのインスタンス化
        $class = new BaseClass();

        // 在籍者全員のデータ取得
        $employees = $class->all();

        // 在籍者の人数
        $employees_count = count($employees);
        // var_dump($employees_count);

        // 在籍者の社員コード
        for ($i = 0; $i < $employees_count; $i++) {
            $shain_cd_array[] = $employees[$i]->shain_cd;
        }
        // var_dump($shain_cd_array);


        // 休日労働回数（月）
        for ($i = 0; $i <= $employees_count - 1; $i++) {
            $holiday_working_this_month_count = DB::table('holiday_workings')
                ->where('year', $latest_year)
                ->where('month', $latest_month)
                ->where('shain_cd', $shain_cd_array[$i])
                ->count();

            $holiday_working_this_month_count_array_pre[] = [$shain_cd_array[$i], $holiday_working_this_month_count];
        }


        // 在籍者全員の当月休日労働回数データ
        // var_dump('在籍者全員の当月休日労働回数データ');
        // var_dump($holiday_working_this_month_count_array_pre);

        // 休日労働回数上限（月）に引っ掛かるものを抽出する
        $holiday_working_this_month_count_array = [];
        for ($i = 0; $i <= $employees_count - 1; $i++) {
            // ※休日労働回数の上限は2019/12/25時点で2日になっており、定数としてDBにいれてないので、2-1で1をアラート条件にしています。
            if ($holiday_working_this_month_count_array_pre[$i][1] >= 1) {
                // var_dump($holiday_working_this_month_count_array_pre[$i][1]);
                // var_dump('罰金');
                $holiday_working_this_month_count_array[] = [$shain_cd_array[$i], $holiday_working_this_month_count_array_pre[$i][1]];
            }
            // else{
            //     $holiday_working_this_month_count_array[] = null;
            // }
        }


        $employees_holiday_working_this_month_count_pre = [];
        if (empty($holiday_working_this_month_count_array)) {
            var_dump('休日労働回数上限（月）に引っ掛かったひとはいません');
        } else {
            // 休日労働回数上限（月）に引っ掛かったひと

            // var_dump($holiday_working_this_month_count_array);

            // 休日労働回数上限（月）に引っ掛かったひとの従業員データ
            for ($i = 0; $i <= count($holiday_working_this_month_count_array) - 1; $i++) {

                $employees_holiday_working_this_month_count_pre[] = DB::table('employees')
                    ->whereNull('taishokubi')
                    ->where('department', '!=', '05')
                    ->where('shain_cd', $holiday_working_this_month_count_array[$i][0])
                    ->get();
            }
        }
        var_dump('休日労働回数上限（月）に引っ掛かったひとの従業員データ');
        var_dump($employees_holiday_working_this_month_count_pre);
        // var_dump($employees_holiday_working_this_month_count[0][0]->shain_cd);
    }


    // 時間外労働+休日労働（月）アラート
    public function overtime_and_holiday_working_sum($latest_year,$latest_month)
    {

        // クラスのインスタンス化
        $class = new BaseClass();

        // 在籍者全員のデータ取得
        $employees = $class->all();

        // 在籍者の人数
        $employees_count = count($employees);
        // var_dump($employees_count);

        // 在籍者の社員コード
        for ($i = 0; $i < $employees_count; $i++) {
            $shain_cd_array[] = $employees[$i]->shain_cd;
        }
        // var_dump($shain_cd_array);


        // 時間外労働（月）
        for ($i = 0; $i <= $employees_count - 1; $i++) {

            $overtime_working_this_month_pre = DB::table('overtime_workings')
                ->where('year', $latest_year)
                ->where('month', $latest_month)
                ->where('shain_cd', $shain_cd_array[$i])
                ->first();

            // var_dump($overtime_working_this_month_pre);

            if (is_null($overtime_working_this_month_pre)) {
                $overtime_working_this_month = 0;
            } else {
                $overtime_working_this_month = (float) $overtime_working_this_month_pre->overtime_working;
            }

            // はいれつの中に社員コードと当月の時間外労働を入れておく
            $overtime_working_this_month_array_pre[] = [$shain_cd_array[$i], $overtime_working_this_month];
        }

        // 休日労働（月）
        for ($i = 0; $i <= $employees_count - 1; $i++) {

            // 休日労働（月）
            $holiday_working_this_month_pre = DB::table('holiday_workings')
                ->select(DB::raw('sum(holiday_working) as holiday_working'))
                ->where('year', $latest_year)
                ->where('month', $latest_month)
                ->where('shain_cd', $shain_cd_array[$i])
                ->get();
            // ->toSql();
            // dd($overtime_working_this_month);

            // var_dump($holiday_working_this_month_pre[0]);

            if (is_null($holiday_working_this_month_pre)) {
                $holiday_working_this_month = 0;
            } else {
                // $holiday_working_this_month = (float) $holiday_working_this_month_pre->overtime_working;
                $holiday_working_this_month = (float) $holiday_working_this_month_pre[0]->holiday_working;
            }

            $holiday_working_this_month_array_pre[] = [$shain_cd_array[$i], $holiday_working_this_month];
        }


        $overtime_and_holiday_working_sum_array_pre = [];
        for ($i = 0; $i <= $employees_count - 1; $i++) {
            $overtime_and_holiday_working_sum_array_pre[] =
                [$overtime_working_this_month_array_pre[$i][0], $overtime_working_this_month_array_pre[$i][1] + $holiday_working_this_month_array_pre[$i][1]];
        }
        // var_dump($overtime_and_holiday_working_sum_array_pre);

        //  時間外労働+休日労働上限（月）に引っ掛かるものを抽出する
        $overtime_and_holiday_working_pre = DB::table('overtime_working_constants')
            ->select('overtime_and_holiday_working')
            ->first();

        $overtime_and_holiday_working = $overtime_and_holiday_working_pre->overtime_and_holiday_working;
        // var_dump('時間外労働+休日労働上限（月）');
        // var_dump($overtime_and_holiday_working);

        $overtime_and_holiday_working_sum_array = [];
        for ($i = 0; $i <= $employees_count - 1; $i++) {
            // アラートなので規定時間-10で判断
            if ($overtime_and_holiday_working_sum_array_pre[$i][1] >= $overtime_and_holiday_working - 10) {
                $overtime_and_holiday_working_sum_array[] = [$shain_cd_array[$i], $overtime_and_holiday_working_sum_array_pre[$i][1]];
            }
            // else {
            //     $overtime_and_holiday_working_sum_array = null;
            // }
        }

        // var_dump($overtime_and_holiday_working_sum_array);


        $employees_overtime_and_holiday_working_sum_pre = [];
        if (empty($overtime_and_holiday_working_sum_array)) {
            var_dump('時間外労働+休日労働上限（月）に引っ掛かったひとはいません');
        } else {
            // 時間外労働+休日労働上限（月）に引っ掛かったひと

            // var_dump($overtime_and_holiday_working_sum_array);

            // 時間外労働+休日労働上限（月）に引っ掛かったひと
            for ($i = 0; $i <= count($overtime_and_holiday_working_sum_array) - 1; $i++) {

                $employees_overtime_and_holiday_working_sum_pre[] = DB::table('employees')
                    ->whereNull('taishokubi')
                    ->where('department', '!=', '05')
                    ->where('shain_cd', $overtime_and_holiday_working_sum_array[$i][0])
                    ->get();
            }
        }
        var_dump('時間外労働+休日労働上限（月）に引っ掛かったひとの従業員データ');
        var_dump($employees_overtime_and_holiday_working_sum_pre);
        // var_dump($employees_overtime_and_holiday_working_sum[0][0]->shain_cd);

        return $employees_overtime_and_holiday_working_sum_pre;

    }
}
