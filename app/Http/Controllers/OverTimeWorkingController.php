<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employee;
use \DB;
use App\Library\BaseClass;

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

        // 時間外労働の定数取得
        // 基本時間外労働（日）
        $base_working_overtime_month_pre = DB::table('overtime_working_constants')
            ->select('base_working_overtime_month')
            ->first();

        $base_working_overtime_month = $base_working_overtime_month_pre->base_working_overtime_month;

        // var_dump('基本時間外労働（日):');
        // var_dump($base_working_overtime_month);

        //employeesテーブルのデータを詳細テーブルで表示していた社員コードの分取得
        $employee = Employee::find($id);

        //詳細ページのプルダウンで選択された年度
        $post_year_month_pre = $request->year_month;
        $post_year = substr($post_year_month_pre, 0, 4);
        $post_month = substr($post_year_month_pre, 4, 2);
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
        echo ('<pre>');
        var_dump('当月時間外労働:' . $overtime_working_this_month);
        echo ('</pre>');
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

        var_dump($holiday_working_this_month_pre);

        if (is_null($holiday_working_this_month_pre)) {
            $holiday_working_this_month = 0;
        } else {
            // $holiday_working_this_month = (float) $holiday_working_this_month_pre->overtime_working;
            $holiday_working_this_month = (float) $holiday_working_this_month_pre[0]->holiday_working;
        }

        echo ('<pre>');
        var_dump('当月休日労働:' . $holiday_working_this_month);
        echo ('</pre>');


        $overtime_and_holiday_working_sum = $overtime_working_this_month + $holiday_working_this_month;

        echo ('<pre>');
        var_dump('当月時間外+休日労働:' . $overtime_and_holiday_working_sum);
        echo ('</pre>');

        // 休日労働回数（月）
        $holiday_working_this_month_count = DB::table('holiday_workings')
            ->where('year', $post_year)
            ->where('month', $post_month)
            ->where('shain_cd', $id)
            ->count();


        echo ('<pre>');
        var_dump('当月休日労働回数:' . $holiday_working_this_month_count);
        echo ('</pre>');




        $overtime_working_array = [];
        // 選択された月までの例外時間外労働を抜き出す
        // 選択した月数が4月より大きい場合は4月から選択した月数まで繰り返してOK（年数が全て同じなので…）
        if ((int) $post_month >= 4) {
            for ($i = 4; $i <= (int) $post_month; $i++) {
                $overtime_working = DB::table('overtime_workings')
                    ->select('overtime_working')
                    ->where('year', $post_year)
                    ->where('month', $i)
                    ->where('shain_cd', $id)
                    ->first();
                // ->toSql();

                // 入力されていない場合は0時間とする
                if (is_null($overtime_working)) {
                    $overtime_working_result = 0;
                    // 入力されている場合は配列使いやすくするために入れ替える
                } else {
                    $overtime_working_result = $overtime_working->overtime_working;
                }
                // 順番に追加していく
                array_push($overtime_working_array, $overtime_working_result);
            }

            // 選択した月数が1・2・3月だった場合、前年の4月から今年の選択した年数になる
        } else {
            // 1月から選択した年まで繰り返す
            for ($i = 1; $i <= (int) $post_month; $i++) {
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
                // 順番に追加していく
                array_push($overtime_working_array, $overtime_working_result);
            }

            // var_dump($overtime_working_array);

            // 前年の4月から12月まで繰り返す
            for ($i = 4; $i <= 12; $i++) {
                $overtime_working = DB::table('overtime_workings')
                    ->select('overtime_working')
                    ->where('year', $post_year - 1)
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
                // 順番に追加していく
                array_push($overtime_working_array, $overtime_working_result);
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
            // 45時間超えることができるのは年6回までの計算
            if ($owa >= $base_working_overtime_month) {
                var_dump('この一年のうちに45時間以上時間外労働してる月があるぞ！');
                array_push($overtime_working_count_array, 1);
            }
        }

        // var_dump('合計');
        // var_dump($overtime_working_sum);


        echo ('<pre>');
        var_dump('1年のうち45時間以上の時間が一労働をした月数:');
        var_dump($overtime_working_count_array);
        echo ('</pre>');

        $overtime_working_count_array_result = count($overtime_working_count_array);

        // var_dump($overtime_working_count_array_result);





        $post_year_month_slash = $post_year . '/' . $post_month . '/1';
        // $post_year_month_slash = '2019/4/1';

        // var_dump('選択年月');
        // var_dump($post_year_month_slash);

        $before_1_year_month_pre = date('Y/m/d', strtotime($post_year_month_slash . "-1 month"));
        $before_1_year = substr($before_1_year_month_pre, 0, 4);
        $before_1_month = substr($before_1_year_month_pre, 5, 2);

        echo ('<pre>');
        var_dump('1か月前:');
        var_dump($before_1_year);
        var_dump($before_1_month);
        echo ('</pre>');


        $before_2_year_month_pre = date('Y/m/d', strtotime($post_year_month_slash . "-2 month"));
        $before_2_year = substr($before_2_year_month_pre, 0, 4);
        $before_2_month = substr($before_2_year_month_pre, 5, 2);

        echo ('<pre>');
        var_dump('2か月前:');
        var_dump($before_2_year);
        var_dump($before_2_month);
        echo ('</pre>');

        $before_3_year_month_pre = date('Y/m/d', strtotime($post_year_month_slash . "-3 month"));
        $before_3_year = substr($before_3_year_month_pre, 0, 4);
        $before_3_month = substr($before_3_year_month_pre, 5, 2);

        echo ('<pre>');
        var_dump('3か月前:');
        var_dump($before_3_year);
        var_dump($before_3_month);
        echo ('</pre>');

        $before_4_year_month_pre = date('Y/m/d', strtotime($post_year_month_slash . "-4 month"));
        $before_4_year = substr($before_4_year_month_pre, 0, 4);
        $before_4_month = substr($before_4_year_month_pre, 5, 2);

        echo ('<pre>');
        var_dump('4か月前:');
        var_dump($before_4_year);
        var_dump($before_4_month);
        echo ('</pre>');

        $before_5_year_month_pre = date('Y/m/d', strtotime($post_year_month_slash . "-5 month"));
        $before_5_year = substr($before_5_year_month_pre, 0, 4);
        $before_5_month = substr($before_5_year_month_pre, 5, 2);

        echo ('<pre>');
        var_dump('5か月前:');
        var_dump($before_5_year);
        var_dump($before_5_month);
        echo ('</pre>');

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
        for ($i = 1; $i <= 5; $i++) {

            $holiday_working_before_months_pre = DB::table('holiday_workings')
                // ->select('holiday_working')
                ->select(DB::raw('sum(holiday_working) as holiday_working'))
                ->where('year', $post_year)
                ->where('month', $post_month - $i)
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
        $two_months_average = ($overtime_working_this_month + $overtime_working_before_sum_array[0] + $holiday_working_before_sum_array[0]) / 2;
        // 3か月平均
        $three_months_average = ($overtime_working_this_month + $overtime_working_before_sum_array[1] + $holiday_working_before_sum_array[1]) / 3;
        // 4か月平均
        $four_months_average = ($overtime_working_this_month + $overtime_working_before_sum_array[2] + $holiday_working_before_sum_array[2]) / 4;
        // 5か月平均
        $five_months_average = ($overtime_working_this_month + $overtime_working_before_sum_array[3] + $holiday_working_before_sum_array[3]) / 5;
        // 6か月平均
        $six_months_average = ($overtime_working_this_month + $overtime_working_before_sum_array[4] + $holiday_working_before_sum_array[4]) / 6;

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
        //一番最近のデータの年
        $latest_year = $year_month_a_pre->year;
        //一番最近のデータの月
        $latest_month = $year_month_a_pre->month;
        //一番最近のデータの年月
        $latest_year_month = $latest_year . $latest_month;

        //一番最近のデータの年月（000000：文字なし）
        $latest_year_month = $latest_year . '年' . $latest_month . '月';
        // var_dump('現在年:' . $latest_year);
        // var_dump('現在月:' . $latest_month);

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

        ]);
    }
}
