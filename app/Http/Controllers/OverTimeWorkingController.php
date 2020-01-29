<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employee;
use \DB;
use App\Library\BaseClass;
use App\Library\OverTimeWorkingClass;

class OverTimeWorkingController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function over_time(Request $request, $id)
    // public function holiday($id)
    {

        // 定数取得
        // 基本時間外労働（日）
        $overtime_working_constants_pre = DB::table('overtime_working_constants')
            ->first();
        // $base_working_overtime_month_pre = DB::table('overtime_working_constants')
        //     ->select('base_working_overtime_month')
        //     ->first();

        // var_dump('定数取得');
        // var_dump($overtime_working_constants_pre);


        // 基本時間外労働（日）
        $base_working_overtime_day = $overtime_working_constants_pre->base_working_overtime_day;
        // var_dump('基本時間外労働（日）');
        // var_dump($base_working_overtime_day);

        // 基本時間外労働時間（月）
        $base_working_overtime_month = $overtime_working_constants_pre->base_working_overtime_month;
        // var_dump('基本時間外労働時間（月）');
        // var_dump($base_working_overtime_month);

        // 基本時間外労働時間（年）
        $base_working_overtime_year = $overtime_working_constants_pre->base_working_overtime_year;
        // var_dump('基本時間外労働時間（年）');
        // var_dump($base_working_overtime_year);

        // 例外時間外労働（月）
        $exception_working_overtime_month = $overtime_working_constants_pre->exception_working_overtime_month;
        // var_dump('例外時間外労働（月）');
        // var_dump($exception_working_overtime_month);

        // 例外時間外労働（年）
        $exception_working_overtime_year = $overtime_working_constants_pre->exception_working_overtime_year;
        // var_dump('例外時間外労働（年）');
        // var_dump($exception_working_overtime_year);

        // 時間外労働平均
        $overtime_working_average = $overtime_working_constants_pre->overtime_working_average;
        // var_dump('時間外労働平均');
        // var_dump($overtime_working_average);

        // 時間外労働+休日労働	
        $overtime_and_holiday_working = $overtime_working_constants_pre->overtime_and_holiday_working;
        // var_dump('時間外労働+休日労働	');
        // var_dump($overtime_and_holiday_working);




        //employeesテーブルのデータを詳細テーブルで表示していた社員コードの分取得
        $employee = Employee::find($id);

        //詳細ページのプルダウンで選択された年度
        $post_year = $request->year;
        $post_month = $request->month;
        // $post_year = substr($post_year_month_pre, 0, 4);
        // $post_month = substr($post_year_month_pre, 4, 2);
        // $post_year_month_pre = $request->year_month;
        // $post_year = substr($post_year_month_pre, 0, 4);
        // $post_month = substr($post_year_month_pre, 4, 2);
        $post_year_month = $post_year . '年' . $post_month . '月';



        // var_dump($post_year_month_pre);
        // var_dump($post_year);
        // var_dump($post_month);



        // 例外時間外労働（月）
        $overtime_working_this_month_pre = DB::table('overtime_workings')
            ->where('year', $post_year)
            ->where('month', $post_month)
            ->where('shain_cd', $id)
            ->first();
        // ->toSql();
        // dd($overtime_working_this_month_pre);


        if (is_null($overtime_working_this_month_pre)) {
            $overtime_working_this_month = 0;
        } else {
            $overtime_working_this_month = (float) $overtime_working_this_month_pre->overtime_working;
        }
        // echo ('<pre>');
        // var_dump('当月時間外労働:' . $overtime_working_this_month);
        // echo ('</pre>');
        // dd($overtime_working_this_month);


        // 休日労働（月）
        $holiday_working_this_month_pre = DB::table('holiday_workings')
            ->select(DB::raw('sum(holiday_working) as holiday_working'))
            ->where('year', $post_year)
            ->where('month', $post_month)
            ->where('shain_cd', $id)
            ->get();
        // ->toSql();
        // dd($overtime_working_this_month);

        // var_dump($holiday_working_this_month_pre);

        if (is_null($holiday_working_this_month_pre)) {
            $holiday_working_this_month = 0;
        } else {
            // $holiday_working_this_month = (float) $holiday_working_this_month_pre->overtime_working;
            $holiday_working_this_month = (float) $holiday_working_this_month_pre[0]->holiday_working;
        }

        // echo ('<pre>');
        // var_dump('当月休日労働:' . $holiday_working_this_month);
        // echo ('</pre>');


        $overtime_and_holiday_working_sum = $overtime_working_this_month + $holiday_working_this_month;

        // echo ('<pre>');
        // var_dump('当月時間外+休日労働:' . $overtime_and_holiday_working_sum);
        // echo ('</pre>');

        // 休日労働回数（月）
        $holiday_working_this_month_count_pre1 = DB::table('holiday_workings')
            ->select('count')
            ->where('year', $post_year)
            ->where('month', $post_month)
            ->where('shain_cd', $id)
            // ->count();
            ->first();

        $holiday_working_this_month_count_pre2 = (array) $holiday_working_this_month_count_pre1;

        if (empty($holiday_working_this_month_count_pre2)) {

            $holiday_working_this_month_count = 0;
        } else {
            foreach ($holiday_working_this_month_count_pre2 as $key => $value) {
                $holiday_working_this_month_count = $value;
            }
        }


        // echo ('<pre>');
        // var_dump('当月休日労働回数:' . $holiday_working_this_month_count);
        // var_dump($holiday_working_this_month_count);
        // echo ('</pre>');



        $overtime_working_array = [];
        // 選択された月までの例外時間外労働を抜き出す
        // 選択した月数が4月より大きい場合は4月から選択した月数まで繰り返してOK（年数が全て同じなので…）
        if ((int) $post_month >= 4) {
            // var_dump('通った1');

            for ($i = 4; $i <= (int) $post_month; $i++) {
                // var_dump('通った1-1');
                $overtime_working = DB::table('overtime_workings')
                    ->select('overtime_working')
                    ->where('year', $post_year)
                    ->where('month', $i)
                    ->where('shain_cd', $id)
                    ->first();

                // 入力されていない場合は0時間とする
                if (is_null($overtime_working)) {
                    $overtime_working_result = 0;
                    // 入力されている場合は配列使いやすくするために入れ替える
                } else {
                    $overtime_working_result = $overtime_working->overtime_working;
                }

                $holiday_working = DB::table('holiday_workings')
                    ->select(DB::raw('sum(holiday_working) as holiday_working'))
                    ->where('year', $post_year)
                    ->where('month', $i)
                    ->where('shain_cd', $id)
                    ->first();

                // var_dump($holiday_working);
                // 入力されていない場合は0時間とする
                if (is_null($holiday_working)) {
                    $holiday_working_result = 0;
                    // 入力されている場合は配列使いやすくするために入れ替える
                } else {
                    $holiday_working_result = $holiday_working->holiday_working;
                }

                $overtime_working_sum = $overtime_working_result + $holiday_working_result;


                // 順番に追加していく
                array_push($overtime_working_array, $overtime_working_sum);
            }

            // 選択した月数が1・2・3月だった場合、前年の4月から今年の選択した年数になる
        } else {
            // var_dump('通った2');
            // 1月から選択した年まで繰り返す
            for ($i = 1; $i <= (int) $post_month; $i++) {
                // var_dump('通った2-1');
                $overtime_working = DB::table('overtime_workings')
                    ->select('overtime_working')
                    ->where('year', $post_year)
                    ->where('month', $i)
                    ->where('shain_cd', $id)
                    ->first();

                // 入力されていない場合は0時間とする
                if (is_null($overtime_working)) {
                    $overtime_working_result = 0;
                    // 入力されている場合は配列使いやすくするために入れ替える
                } else {
                    $overtime_working_result = $overtime_working->overtime_working;
                }

                $holiday_working = DB::table('holiday_workings')
                    ->select(DB::raw('sum(holiday_working) as holiday_working'))
                    ->where('year', $post_year)
                    ->where('month', $i)
                    ->where('shain_cd', $id)
                    ->first();

                // 入力されていない場合は0時間とする
                if (is_null($holiday_working)) {
                    $holiday_working_result = 0;
                    // 入力されている場合は配列使いやすくするために入れ替える
                } else {
                    $holiday_working_result = $holiday_working->holiday_working;
                }

                $overtime_working_sum = $overtime_working_result + $holiday_working_result;


                // 順番に追加していく
                array_push($overtime_working_array, $overtime_working_sum);
            }

            // var_dump($overtime_working_array);

            // 前年の4月から12月まで繰り返す
            for ($i2 = 4; $i2 <= 12; $i2++) {
                $overtime_working = DB::table('overtime_workings')
                    ->select('overtime_working')
                    ->where('year', $post_year - 1)
                    ->where('month', $i2)
                    ->where('shain_cd', $id)
                    ->first();

                // 入力されていない場合は0時間とする
                if (is_null($overtime_working)) {
                    $overtime_working_result = 0;
                    // 入力されている場合は配列使いやすくするために入れ替える
                } else {
                    $overtime_working_result = $overtime_working->overtime_working;
                    // var_dump($overtime_working_result);
                }


                $holiday_working = DB::table('holiday_workings')
                    ->select(DB::raw('sum(holiday_working) as holiday_working'))
                    ->where('year', $post_year - 1)
                    ->where('month', $i2)
                    ->where('shain_cd', $id)
                    ->first();

                // 入力されていない場合は0時間とする
                if (is_null($holiday_working)) {
                    $holiday_working_result = 0;
                    // 入力されている場合は配列使いやすくするために入れ替える
                } else {
                    $holiday_working_result = $holiday_working->holiday_working;
                }

                
                $overtime_working_sum = $overtime_working_result + $holiday_working_result;


                // 順番に追加していく
                array_push($overtime_working_array, $overtime_working_sum);

            }
        }


        // var_dump($overtime_working_array);


        // 例外時間外労働（年）
        $overtime_working_sum = 0;
        // 45時間超えることができるのは年6回まで
        $overtime_working_count_array = [];
        // 抜き出した時間外労働を足していく
        foreach ($overtime_working_array as $owa) { //変数bに配列aの中身を順番に入れていく
            // 例外時間外労働の計算

            $overtime_working_sum += (float) $owa;
            // var_dump('合計');
            // var_dump($overtime_working_sum);
            // 45時間超えることができるのは年6回までの計算
            if ($owa >= $base_working_overtime_month) {
                // var_dump('この一年のうちに45時間以上時間外労働してる月があるぞ！');
                array_push($overtime_working_count_array, 1);
            }
        }

        // var_dump('合計');
        // var_dump($overtime_working_sum);


        // echo ('<pre>');
        // var_dump('1年のうち45時間以上の時間が一労働をした月数:');
        // var_dump($overtime_working_count_array);
        // echo ('</pre>');

        $overtime_working_count_array_result = count($overtime_working_count_array);

        // var_dump($overtime_working_count_array_result);





        $post_year_month_slash = $post_year . '/' . $post_month . '/1';
        // $post_year_month_slash = '2019/4/1';

        // var_dump('選択年月');
        // var_dump($post_year_month_slash);

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

        $before_year_array = [$before_1_year, $before_2_year, $before_3_year, $before_4_year, $before_5_year];
        // var_dump($before_year_array);

        $before_month_array = [$before_1_month, $before_2_month, $before_3_month, $before_4_month, $before_5_month];
        // var_dump($before_month_array);




        // 例外時間外労働（平均）
        // 5か月分の時間外労働を入れておく配列
        $overtime_working_before_array = [];
        // 上の配列に入った労働時間の合計を計算し入れておく配列
        $overtime_working_before_sum_array = [];

        // 5か月前から1か月前までの時間外労働を抽出
        for ($i = 0; $i <= 4; $i++) {

            $overtime_working_before_months_pre = DB::table('overtime_workings')
                ->select('overtime_working')
                ->where('year', $before_year_array[$i])
                ->where('month', $before_month_array[$i])
                ->where('shain_cd', $id)
                ->first();

            if (is_null($overtime_working_before_months_pre)) {
                $overtime_working_before_months = 0;
            } else {
                $overtime_working_before_months = (float) $overtime_working_before_months_pre->overtime_working;
            }

            // 5か月分の時間外労働をどんどん入れてく
            array_push($overtime_working_before_array, $overtime_working_before_months);

            // 上の配列にはいった労働時間をどんどん足して入れてく
            $overtime_working_before_sum_pre =  array_sum($overtime_working_before_array);
            array_push($overtime_working_before_sum_array, $overtime_working_before_sum_pre);
        }

        // var_dump($overtime_working_before_array);
        // var_dump($overtime_working_before_sum_array);







        // 休日労働（平均）
        // 5か月分の時間外労働を入れておく配列
        $holiday_working_before_array = [];
        // 上の配列に入った労働時間の合計を計算し入れておく配列
        $holiday_working_before_sum_array = [];

        // 5か月前から1か月前までの時間外労働を抽出
        for ($i = 0; $i <= 4; $i++) {

            $holiday_working_before_months_pre = DB::table('holiday_workings')
                // ->select('holiday_working')
                ->select(DB::raw('sum(holiday_working) as holiday_working'))
                ->where('year', $before_year_array[$i])
                ->where('month', $before_month_array[$i])
                ->where('shain_cd', $id)
                ->get();


            if (is_null($holiday_working_before_months_pre)) {
                $holiday_working_before_months = 0;
            } else {
                $holiday_working_before_months = (float) $holiday_working_before_months_pre[0]->holiday_working;
            }

            // var_dump($holiday_working_before_months);


            // 5か月分の時間外労働をどんどん入れてく
            array_push($holiday_working_before_array, $holiday_working_before_months);

            // 上の配列にはいった労働時間をどんどん足して入れてく
            $holiday_working_before_sum_pre =  array_sum($holiday_working_before_array);
            // var_dump($holiday_working_before_sum_pre);

            array_push($holiday_working_before_sum_array, $holiday_working_before_sum_pre);
        }

        // var_dump($holiday_working_before_array);
        // var_dump($holiday_working_before_sum_array);




        // 2か月平均
        $two_months_average = ($overtime_working_this_month + $overtime_working_before_sum_array[0] + $holiday_working_this_month + $holiday_working_before_sum_array[0]) / 2;
        // 3か月平均
        $three_months_average = ($overtime_working_this_month + $overtime_working_before_sum_array[1] + $holiday_working_this_month + $holiday_working_before_sum_array[1]) / 3;
        // 4か月平均
        $four_months_average = ($overtime_working_this_month + $overtime_working_before_sum_array[2] + $holiday_working_this_month + $holiday_working_before_sum_array[2]) / 4;
        // 5か月平均
        $five_months_average = ($overtime_working_this_month + $overtime_working_before_sum_array[3] + $holiday_working_this_month + $holiday_working_before_sum_array[3]) / 5;
        // 6か月平均
        $six_months_average = ($overtime_working_this_month + $overtime_working_before_sum_array[4] + $holiday_working_this_month + $holiday_working_before_sum_array[4]) / 6;

        // var_dump('2か月平均');
        // var_dump($overtime_working_this_month . '+' . $overtime_working_before_sum_array[0] . '+' . $holiday_working_before_sum_array[0] . '/ 2');
        // var_dump($two_months_average);

        // var_dump('3か月平均');
        // var_dump($overtime_working_this_month . '+' . $overtime_working_before_sum_array[1] . '+' . $holiday_working_before_sum_array[1] . '/ 3');
        // var_dump($three_months_average);

        // var_dump('4か月平均');
        // var_dump($overtime_working_this_month . '+' . $overtime_working_before_sum_array[2] . '+' . $holiday_working_before_sum_array[2] . '/ 4');
        // var_dump($four_months_average);

        // var_dump('5か月平均');
        // var_dump($overtime_working_this_month . '+' . $overtime_working_before_sum_array[3] . '+' . $holiday_working_before_sum_array[3] . '/ 5');
        // var_dump($five_months_average);

        // var_dump('6か月平均');
        // var_dump($overtime_working_this_month . '+' . $overtime_working_before_sum_array[4] . '+' . $holiday_working_before_sum_array[4] . '/ 6');
        // var_dump($six_months_average);














        //一番最近のデータの年月を作成(=現在日時になる)
        $year_month_a_pre = DB::table('overtime_workings')
            ->select(db::raw('year,lpad(month, 2, "0") as month'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
        // var_dump($year_month_a_pre);

        if (is_null($year_month_a_pre)) {

            //一番最近のデータの年は0にしとく
            $latest_year1 = 0;
            //一番最近のデータの月は0にしとく
            $latest_month1 = 0;
            //一番最近のデータの年月は00にしとく
            $latest_year_month1 = $latest_year1 . $latest_month1;
        } else {
            //一番最近のデータの年
            $latest_year1 = $year_month_a_pre->year;
            //一番最近のデータの月
            $latest_month1 = $year_month_a_pre->month;

            //一番最近のデータの年月
            $latest_year_month1 = $latest_year1 . '年' . $latest_month1 . '月';
        }

        $holiday_working_year_month_pre = DB::table('holiday_workings')
            ->select(db::raw('year,lpad(month, 2, "0") as month'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();

        if (is_null($holiday_working_year_month_pre)) {
            //一番最近のデータの年は0にしとく
            $latest_year2 = 0;
            //一番最近のデータの月は0にしとく
            $latest_month2 = 0;
            //一番最近のデータの年月は00にしとく
            $latest_year_month2 = $latest_year2 . $latest_month2;

            // 休日労働テーブルにデータが入っているならそれを最新データ年月にする
        } else {
            //一番最近のデータの年
            $latest_year2 = $holiday_working_year_month_pre->year;
            //一番最近のデータの月
            $latest_month2 = $holiday_working_year_month_pre->month;
            //一番最近のデータの年月
            $latest_year_month2 = $latest_year2 . '年' . $latest_month2 . '月';
        }

        // var_dump('現在年1:' . $latest_year1);
        // var_dump('現在月1:' . $latest_month1);
        // var_dump('現在年2:' . $latest_year2);
        // var_dump('現在月2:' . $latest_month2);

        // 年月が新しい方を最新データにする
        if ($latest_year1 > $latest_year2 or ($latest_year1 == $latest_year2 and $latest_month1 >= $latest_month2)) {
            $latest_year = $latest_year1;
            $latest_month = $latest_month1;
            $latest_year_month = $latest_year_month1;
        } else {
            $latest_year = $latest_year2;
            $latest_month = $latest_month2;
            $latest_year_month = $latest_year_month2;
        }




        // 選択した年＝最新データ年で月数が大きい場合と、選択した年の方が最新データ年より大きい場合はエラーを表示
        // 2019年4月より前の年月を選択した場合もエラーを表示
        $overtime_working_error = null;
        $overtime_working_error2 = null;
        if (((int) $post_year == $latest_year and (int) $post_month > (int) $latest_month)) {
            // if((int)$post_year > $latest_year){
            $overtime_working_error = '最新データよりも未来の年月を選択しないでください～';
        } elseif ((int) $post_year <= 2019 and (int) $post_month < 4) {
            $overtime_working_error2 = '2019年4月よりも前の年月を選択しないでください～';
        }
        // var_dump((int)$post_year);
        // var_dump($latest_year);
        // var_dump((int)$post_month);
        // var_dump((int)$latest_month);
        // var_dump($overtime_working_error);
        // var_dump($overtime_working_error2);






        // トップページに戻るボタン押下時のスクロール位置とトップページURL
        $top_url = $request->top_url;
        $scroll_top = $request->scroll_top2;



        return view('/over_time_working')->with([
            //社員名で使用する
            'employee' => $employee,
            //社員コードで使用する
            'shain_cd' => $id,
            //選択年t月
            'post_year_month' => $post_year_month,
            //選択年
            'post_year' => $post_year,
            //選択月
            'post_month' => $post_month,

            // 最新年
            'latest_year' => $latest_year,
            // 最新月
            'latest_month' => $latest_month,
            // 最新年月
            'latest_year_month' => $latest_year_month,

            // 基本時間外労働時間（日）
            'base_working_overtime_day' => $base_working_overtime_day,

            // 基本時間外労働時間（月）
            'base_working_overtime_month' => $base_working_overtime_month,

            // 基本時間外労働時間（年）
            'base_working_overtime_year' => $base_working_overtime_year,

            // 例外時間外労働（月）
            'exception_working_overtime_month' => $exception_working_overtime_month,

            // 例外時間外労働（年）
            'exception_working_overtime_year' => $exception_working_overtime_year,

            // 時間外労働平均
            'overtime_working_average' => $overtime_working_average,

            // 時間外労働+休日労働
            'overtime_and_holiday_working' => $overtime_and_holiday_working,


            // トップページに戻るボタン押下時のスクロール位置とトップページURL
            'top_url' => $top_url,
            'scroll_top' => $scroll_top,

            // 当月時間外労働
            'overtime_working_this_month' => $overtime_working_this_month,
            // 例外時間外労働合計のデータ
            'overtime_working_sum' => $overtime_working_sum,

            // 時間外労働が45時間以上の月が選択月までに何個あるか
            'overtime_working_count_array_result' => $overtime_working_count_array_result,

            // 当月休日労働
            'holiday_working_this_month' => $holiday_working_this_month,
            // 休日労働回数（月）
            'holiday_working_this_month_count' => $holiday_working_this_month_count,


            // 時間外+休日労働
            'overtime_and_holiday_working_sum' => $overtime_and_holiday_working_sum,

            // 二か月平均
            'two_months_average' => round($two_months_average, 2),
            // 三か月平均
            'three_months_average' => round($three_months_average, 2),
            // 四か月平均
            'four_months_average' => round($four_months_average, 2),
            // 五か月平均
            'five_months_average' => round($five_months_average, 2),
            // 六か月平均
            'six_months_average' => round($six_months_average, 2),


            // 最新データよりも未来の年月を選択しないでくださいエラー
            'overtime_working_error' => $overtime_working_error,
            // 2019年4月よりも前の年月を選択しないでくださいエラー
            'overtime_working_error2' => $overtime_working_error2,

        ]);
    }




    public function over_time_ranking()
    {

        $title = '時間外労働ランキング';

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


        // 時間外労働（年）を取得
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


        // 時間外労働のデータを降順に並び替え
        foreach ($overtime_working_year_array_pre as $key => $value) {
            $sort[$key] = $value[1];
        }

        array_multisort($sort, SORT_DESC, $overtime_working_year_array_pre);
        // print_r($overtime_working_year_array_pre);


        // var_dump('年間時間外労働');
        // var_dump($overtime_working_year_array_pre);


        $rank_pre = 1;
        $rank = [];
        foreach ($overtime_working_year_array_pre as $key => $value) {
            //キーが0(先頭)の時
            // var_dump($key);
            // var_dump($value[1]);
            if ($key == 0) {
                // var_dump($rank_pre);
                // var_dump($overtime_working_year_array_pre[$key][0]);
                // var_dump($overtime_working_year_array_pre[$key][1]);
                $rank[] = [$rank_pre, $overtime_working_year_array_pre[$key][0], $overtime_working_year_array_pre[$key][1]];
            } else {
                $pre = $key - 1; //ひとつ前の数値と比較

                // var_dump('ひとつ前の数値と比較');
                // var_dump($value[1]);
                // var_dump($overtime_working_year_array_pre[$pre][1]);
                // var_dump($value[1] === $overtime_working_year_array_pre[$pre][1]);

                if ($value[1] === $overtime_working_year_array_pre[$pre][1]) { //同じなら同位

                    // echo $rank_pre . "位：{$value[1]}";
                    // var_dump($rank_pre);
                    // var_dump($overtime_working_year_array_pre[$key][0]);
                    // var_dump($overtime_working_year_array_pre[$key][1]);

                    $rank[] = [$rank_pre, $overtime_working_year_array_pre[$key][0], $overtime_working_year_array_pre[$key][1]];
                } else { //違えばrankに足しこんで次の順位
                    $rank_pre = 1 + $key;
                    // var_dump($rank_pre);
                    // var_dump($overtime_working_year_array_pre[$key][0]);
                    // var_dump($overtime_working_year_array_pre[$key][1]);
                    // echo ($key + 1) . "位：{$value[1]}";
                    $rank[] = [$rank_pre, $overtime_working_year_array_pre[$key][0], $overtime_working_year_array_pre[$key][1]];
                }
            }
            // echo "<br>";
        }

        // var_dump($rank);
        $ranking = [];
        foreach ($rank as $key => $value) {
            // var_dump($rank[$key][0] <= 10);
            if ($rank[$key][0] <= 10) {
                $ranking[] = $rank[$key];
            }
        }
        // var_dump($ranking);

        $employees_overtime_working_year_pre = [];

        if (empty($ranking)) {
            // var_dump('例外時間外労働上限（月）に引っ掛かったひとはいません');
        } else {
            // 例外時間外労働ランキング10位以内に入った人

            // 時間外労働上限（月）に引っ掛かった人の従業員データ
            for ($i = 0; $i <= count($ranking) - 1; $i++) {
                $employees_overtime_working_year_pre[] = DB::table('employees')
                    ->whereNull('taishokubi')
                    ->where('department', '!=', '05')
                    ->where('shain_cd', $ranking[$i][1])
                    ->get();
            }
        }

        $employees_overtime_working_year = $employees_overtime_working_year_pre;
        $overtime_working_year_array_result = $ranking;



        //  時間外労働上限（年）
        $exception_working_overtime_year_pre = DB::table('overtime_working_constants')
            ->select('exception_working_overtime_year')
            ->first();

        $exception_working_overtime_year = $exception_working_overtime_year_pre->exception_working_overtime_year;
        // var_dump('時間外労働上限（年）');
        // var_dump($exception_working_overtime_year);





        return view('overtime_working_ranking')->with([
            'title' => $title,

            // 社員情報はいれつ
            'employees' => $employees,

            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,

            // 最新年
            'latest_year' => $latest_year,
            // 最新月
            'latest_month' => $latest_month,
            // 最新年月
            'latest_year_month' => $latest_year_month,

            'overtime_working_year_array_result' => $overtime_working_year_array_result,
            'employees_overtime_working_year' => $employees_overtime_working_year,

            'exception_working_overtime_year' => $exception_working_overtime_year,

        ]);
    }
}
