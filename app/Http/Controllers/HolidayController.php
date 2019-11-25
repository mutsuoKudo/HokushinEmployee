<?php

namespace App\Http\Controllers;

use App\Employee;
use \DB;
use App\Library\BaseClass;


class HolidayController extends Controller
{
    //

    public function holiday($id)
    {
        // クラスのインスタンス化
        $class = new BaseClass();
        //employeesテーブルのデータを詳細テーブルで表示していた社員コードの分取得
        $employee = Employee::find($id);

        //詳細ページのプルダウンで選択された年度
        if(isset($_POST['year'])){
            $post_year = $_POST['year'];
        }else{
            // $post_year = substr($id, 0, 4);
            $post_year = '2019';
            
            // if($post_year == '2019'){
            //     $post_year == '00';
            // }
        }
        // var_dump('何年の有給についてか:'.$post_year);

        list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $class->kijunbi($id);
        // 基準年を抜き出す
        $kijunbi_year = $kijunbi_year_pre;
        // var_dump('基準年:' . $kijunbi_year);

        // 基準月を抜き出す
        $kijunbi_month = $kijunbi_month_pre;
        // var_dump('基準月:' . $kijunbi_month);

        // 基準年月を抜き出す
        $kijunbi_year_month = $kijunbi_year_month_pre;
        // var_dump('基準年月:' . $kijunbi_year_month);


        //入社日の取得
        //入社日の取得
        list($nyushabi_year_pre, $nyushabi_month_pre, $nyushabi_year_month_pre) = $class->nyushabi_year_month($id);

        //入社年月
        $nyushabi_year_month = $nyushabi_year_month_pre;
        //入社年
        $nyushabi_year = $nyushabi_year_pre;


        //初年度最後の月
        list($first_day_max_year_pre, $first_day_max_month_pre, $first_day_max_pre) = $class->first_day_max_2($id);
        $first_day_max_month = $first_day_max_month_pre;
        $first_day_max = $first_day_max_pre;


        //アラート用(基準月の3ヶ月前の時点で5日以上取得していないとき（＝未消化）)
        // 基準月から３ヶ月前の計算(基準月の3ヶ月前の時点で5日以上取得していないとき（＝未消化）)
        $kijunbi_before3 = DB::table('employees')
            ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +14 MONTH) AS "kijunbi_before3"'))
            ->where('shain_cd', $id)
            ->first();


        //  勤続年数を計算
        //一番最近のデータの年月(0000-00)を作成(=現在日時になる)
        list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $class->year_month();

        $year = $year_month_a1_pre;
        $month = $year_month_a2_pre;
        // var_dump('現在年:' . $year);
        // var_dump('現在月:' . $month);

        if (isset($employee->taishokubi)) {

            $taishokubi_year = substr($employee->taishokubi, 0, 4);

            $kinzoku_year = $taishokubi_year - $nyushabi_year - 1;

            // 勤続年数は0より小さくならないようにする
            if ($kinzoku_year <= 0) {
                $kinzoku_year = 0;
            }
        } else {


            // 初年度の人
            if ($kijunbi_year + 1 >= $year and $kijunbi_month >= $month) {
                $kinzoku_year = 0;

            } elseif ($kijunbi_month > $month) {
                $kinzoku_year = $year - 1 - $kijunbi_year;

            } else {
                $kinzoku_year = $year - $kijunbi_year;
            }
        }
        

        //配列の作成
        $array = [];


        //勤続年数分のデータを配列にいれる。 基準日が2017年だったら、2017年・2018年・2019年分
        for ($i = 0; $i <= $kinzoku_year; $i++) {


            if ($i == 0) {
                // echo ('<pre>');
                // var_dump("ここは初年度");

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

                $day_max = $class->first_day_max($id);
                // var_dump("初年度最後の月" . $day_max);

                $warning = $class->warning($kijunbi_before3, $i);
                // var_dump('年度最後の月の三ヶ月前:' . $warning);

                $holiday_count_int = $class->holiday_count_int($nyushabi_year_month, $day_max, $id);
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

                //月別消化日数
                $get_holiday = $class->get_holiday($id, $nyushabi_year_month, $day_max);
                // var_dump('初年度の月別消化日数:');
                // var_dump($get_holiday);

                // echo ('</pre>');



            } elseif ($i == 1) {
                // echo ('<pre>');
                // var_dump("ここは" . $i . "年目");

                //付与日数
                $huyo_holiday = "11";
                //最大繰り越し日数
                $max_carry_over = "11";

                //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                $carry_over = $class->carry_over($array, $i, $max_carry_over);
                // var_dump($i . '年目の前期繰越:');
                // var_dump($carry_over);

                // 期首残高
                $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                // var_dump($i . '年目の期首残高:');
                // var_dump($kisyu_nokori);

                //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                $day_min = $class->day_min($kijunbi_year, $i, $kijunbi_month);
                // var_dump($i . '年目の年度初めの年月:');
                // var_dump($day_min);


                //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                $day_max = $class->day_max($first_day_max, $i);
                // var_dump($i . '年目の年度最後の年月:');
                // var_dump($day_max);

                //年度終わりから３ヶ月前（=warning）
                $warning = $class->warning($kijunbi_before3, $i);
                // var_dump($i . '年目の年度最後の月の三ヶ月前:' . $warning);

                //消化日数
                $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $id);
                // var_dump($i . '年目の消化日数:');
                // var_dump($holiday_count_int);

                //消化残（期首残高-消化日数）
                $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                // $nokori = $kisyu_nokori - $holiday_count_int;

                // var_dump($i . '年目の消化残:');
                // var_dump($nokori);

                //繰越日数
                $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                // var_dump($i . '年目の繰越日数:');
                // var_dump($carry_over_count);

                //月別消化日数
                $get_holiday = $class->get_holiday($id, $day_min, $day_max);
                // var_dump($i . '年目の月別消化日数:');
                // var_dump($get_holiday);

                // echo ('</pre>');
            } elseif ($i == 2) {
                // echo ('<pre>');

                $huyo_holiday = "12";
                $max_carry_over = "12";

                //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                $carry_over = $class->carry_over($array, $i, $max_carry_over);
                // var_dump($i . '年目の前期繰越:');
                // var_dump($carry_over);

                // 期首残高
                $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                // var_dump($i . '年目の期首残高:');
                // var_dump($kisyu_nokori);

                //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                $day_min = $class->day_min($kijunbi_year, $i, $kijunbi_month);
                // var_dump($i . '年目の年度初めの年月:');
                // var_dump($day_min);

                //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                $day_max = $class->day_max($first_day_max, $i);
                // var_dump($i . '年目の年度最後の年月:');
                // var_dump($day_max);

                //年度終わりの３ヶ月前（=warning）
                $warning = $class->warning($kijunbi_before3, $i);
                // var_dump($i . '年目終わりの３ヶ月前:' . $warning);

                //消化日数
                $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $id);
                // var_dump($i . '年目の消化日数:');
                // var_dump($holiday_count_int);


                //消化残（期首残高-消化日数）
                $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                // var_dump($i . '年目の消化残:');
                // var_dump($nokori);

                //繰越日数
                $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                // var_dump($i . '年目の繰越日数:');
                // var_dump($carry_over_count);

                //月別消化日数
                $get_holiday = $class->get_holiday($id, $day_min, $day_max);
                // var_dump($i . '年目の月別消化日数:');
                // var_dump($get_holiday);

                // echo ('</pre>');
            } elseif ($i == 3) {
                // echo ('<pre>');

                $huyo_holiday = "14";
                $max_carry_over = "14";

                //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                $carry_over = $class->carry_over($array, $i, $max_carry_over);
                // var_dump($i . '年目の前期繰越:');
                // var_dump($carry_over);

                // 期首残高
                $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                // var_dump($i . '年目の期首残高:');
                // var_dump($kisyu_nokori);

                //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                $day_min = $class->day_min($kijunbi_year, $i, $kijunbi_month);
                // var_dump($i . '年目の年度初めの年月:');
                // var_dump($day_min);

                $day_max = $class->day_max($first_day_max, $i);
                // var_dump($i . '年目の年度最後の年月:');
                // var_dump($day_max);

                //年度終わりの３ヶ月前（=warning）
                $warning = $class->warning($kijunbi_before3, $i);
                // var_dump($i . '年目の年度最後の月の三ヶ月前:' . $warning);

                //消化日数
                $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $id);
                // var_dump($i . '年目の消化日数:');
                // var_dump($holiday_count_int);


                //消化残（期首残高-消化日数）
                $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                // var_dump($i . '年目の消化残:');
                // var_dump($nokori);

                //繰越日数
                $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                // var_dump($i . '年目の繰越日数:');
                // var_dump($carry_over_count);

                //月別消化日数
                $get_holiday = $class->get_holiday($id, $day_min, $day_max);
                // var_dump($i . '年目の月別消化日数:');
                // var_dump($get_holiday);

                // echo ('</pre>');
            } elseif ($i == 4) {
                // echo ('<pre>');
                // var_dump("ここは" . $i . "年目");

                $huyo_holiday = "16";
                $max_carry_over = "16";

                //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                $carry_over = $class->carry_over($array, $i, $max_carry_over);
                // var_dump($i . '年目の前期繰越:');
                // var_dump($carry_over);


                // 期首残高
                $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                // var_dump($i . '年目の期首残高:');
                // var_dump($kisyu_nokori);


                //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                $day_min = $class->day_min($kijunbi_year, $i, $kijunbi_month);
                // var_dump($i . '年目の年度初めの年月:');
                // var_dump($day_min);

                //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                $day_max = $class->day_max($first_day_max, $i);
                // var_dump($i . '年目の年度最後の年月:');
                // var_dump($day_max);

                //年度終わりの３ヶ月前（=warning）
                $warning = $class->warning($kijunbi_before3, $i);
                // var_dump($i . '年目の年度最後の月の三ヶ月前:' . $warning);

                //消化日数
                $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $id);
                // var_dump($i . '年目の消化日数:');
                // var_dump($holiday_count_int);

                //消化残（期首残高-消化日数）
                $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                // var_dump($i . '年目の消化残:');
                // var_dump($nokori);

                //繰越日数
                $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                // var_dump($i . '年目の繰越日数:');
                // var_dump($carry_over_count);

                //月別消化日数
                $get_holiday = $class->get_holiday($id, $day_min, $day_max);
                // var_dump($i . '年目の月別消化日数:');
                // var_dump($get_holiday);

                // echo ('</pre>');
            } elseif ($i == 5) {
                // echo ('<pre>');
                $huyo_holiday = "18";
                $max_carry_over = "18";

                //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                $carry_over = $class->carry_over($array, $i, $max_carry_over);
                // var_dump($i . '年目の前期繰越:');
                // var_dump($carry_over);

                // 期首残高
                $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                // var_dump($i . '年目の期首残高:');
                // var_dump($kisyu_nokori);

                //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                $day_min = $class->day_min($kijunbi_year, $i, $kijunbi_month);
                // var_dump($i . '年目の年度初めの年月:');
                // var_dump($day_min);

                //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                $day_max = $class->day_max($first_day_max, $i);
                // var_dump($i . '年目の年度最後の年月:');
                // var_dump($day_max);

                //年度終わりの３ヶ月前（=warning）
                $warning = $class->warning($kijunbi_before3, $i);
                // var_dump($i . '年目の年度最後の月の三ヶ月前:' . $warning);

                //消化日数
                $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $id);
                // var_dump($i . '年目の消化日数:');
                // var_dump($holiday_count_int);


                //消化残（期首残高-消化日数）
                $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                // var_dump($i . '年目の消化残:');
                // var_dump($nokori);

                //繰越日数
                $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                // var_dump($i . '年目の繰越日数:');
                // var_dump($carry_over_count);

                //月別消化日数
                $get_holiday = $class->get_holiday($id, $day_min, $day_max);
                // var_dump($i . '年目の月別消化日数:');
                // var_dump($get_holiday);

                // echo ('</pre>');
            } elseif ($i >= 6) {
                // echo ('<pre>');

                $huyo_holiday = "20";
                $max_carry_over = "20";


                //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
                $carry_over = $class->carry_over($array, $i, $max_carry_over);
                // var_dump($i . '年目の前期繰越:');
                // var_dump($carry_over);

                // 期首残高
                $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                // var_dump($i . '年目の期首残高:');
                // var_dump($kisyu_nokori);

                //年度初めの年月（基準日に$i($i=1)年足したもの(ex:2016/10が基準日の場合、2017/10になる))
                $day_min = $class->day_min($kijunbi_year, $i, $kijunbi_month);
                // var_dump($i . '年目の年度初めの年月:');
                // var_dump($day_min);

                //年度最後の年月（初年度最後の年に$i($i=1)年足したもの(ex:2017/3が初年度最後の場合、2018/3になる))
                $day_max = $class->day_max($first_day_max, $i);
                // var_dump($i . '年目の年度最後の年月:');
                // var_dump($day_max);

                //年度終わりの３ヶ月前（=warning）
                $warning = $class->warning($kijunbi_before3, $i);
                // var_dump($i . '年目の年度最後の月の三ヶ月前:' . $warning);

                //消化日数
                $holiday_count_int = $class->holiday_count_int($day_min, $day_max, $id);
                // var_dump($i . '年目の消化日数:');
                // var_dump($holiday_count_int);

                //消化残（期首残高-消化日数）
                $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                // var_dump($i . '年目の消化残:');
                // var_dump($nokori);

                //繰越日数
                $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                // var_dump($i . '年目の繰越日数:');
                // var_dump($carry_over_count);

                //月別消化日数
                $get_holiday = $class->get_holiday($id, $day_min, $day_max);
                // var_dump($i . '年目の月別消化日数:');
                // var_dump($get_holiday);

                // echo ('</pre>');
            }

            //[0]付与日数/[1]最大繰り越し日数/[2]前期繰越/[3]期首残高/[4]消化日数/[5]消化残/[6]繰越日数/[7]月別消化日数/[8]年度最後の年月/[9]年度終わりの3ヶ月前
            $array[] = [$huyo_holiday, $max_carry_over, $carry_over, $kisyu_nokori, $holiday_count_int, $nokori, $carry_over_count, $get_holiday, $day_max, $warning];
        }



        list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $class->year_month();
        //一番最近のデータの年
        $year_month_a1 = $year_month_a1_pre;
        //一番最近のデータの月
        $year_month_a2 = $year_month_a2_pre;
        //一番最近のデータの年月（0000年00月）
        $year_month_a = $year_month_a_pre;
        //一番最近のデータの年月（000000：文字なし）
        $year_month_b = $year_month_b_pre;





        //初回基準日に達していない場合・・・
        $array2 = [];
        //一番最近のデータの年月より初回基準日が大きい人は初回基準日未満

        if ($post_year == 00) {
            // echo ('<pre>');
            // var_dump("研修期間");

            //付与日数
            $huyo_holiday = "3";
            //最大繰り越し日数
            $max_carry_over = "3";
            //前期繰越
            $carry_over = 0;

            // 期首残高（付与日数+前期繰越）
            $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
            // var_dump('研修期間の期首残高:');
            // var_dump($kisyu_nokori);

            //消化日数
            $holiday_count_int = $class->holiday_count_int($nyushabi_year_month, $kijunbi_year_month, $id);
            // var_dump('研修期間の消化日数:');
            // var_dump($holiday_count_int);

            //消化残（期首残高-消化日数）
            $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
            // var_dump('研修期間の消化残:');
            // var_dump($nokori);

            //繰越日数（消化残が最大繰り越し日数より大きい場合、繰り越し日数は最大繰り越し日数と同じになる。小さい場合、繰り越し日数は消化残と同じ日数になる。）
            $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
            // var_dump('研修期間の繰越日数:');
            // var_dump($carry_over_count);

            //月別消化日数
            $get_holiday = $class->get_holiday($id, $nyushabi_year_month, $kijunbi_year_month);
            // var_dump($i . '年目の月別消化日数:');
            // var_dump($get_holiday);

            //[0]付与日数/[1]最大繰り越し日数/[2]前期繰越/[3]期首残高/[4]消化日数/[5]消化残/[6]繰越日数/[7]月別消化日数
            $array2[] = [$huyo_holiday, $max_carry_over, $carry_over, $kisyu_nokori, $holiday_count_int, $nokori, $carry_over_count, $get_holiday];
            // echo ('</pre>');
        }


        // 初回基準月未満で退職した人
        $array3 = [];
        if (isset($employee->taishokubi)) {
            // var_dump('退職者');

            list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $class->kijunbi($id);

            // 基準年月を抜き出す
            $kijunbi_year_month = $kijunbi_year_month_pre;
            // var_dump('基準年月:' . $kijunbi_year_month);

            if ($post_year == 01) {
                // if ($employee->taishokubi < $kijunbi_year_month) {
                echo ('<pre>');
                var_dump('一年未満で退職した人');

                //付与日数
                $huyo_holiday = "3";
                //最大繰り越し日数
                $max_carry_over = "3";
                //前期繰越
                $carry_over = 0;

                // 期首残高（付与日数+前期繰越）
                $kisyu_nokori = $class->kisyu_nokori($huyo_holiday, $carry_over);
                var_dump('期首残高:');
                var_dump($kisyu_nokori);

                //消化日数
                $holiday_count_int = $class->holiday_count_int($nyushabi_year_month, $kijunbi_year_month, $id);
                var_dump('消化日数:');
                var_dump($holiday_count_int);

                //消化残（期首残高-消化日数）
                $nokori = $class->nokori($kisyu_nokori, $holiday_count_int);
                var_dump('消化残:');
                var_dump($nokori);

                //繰越日数（消化残が最大繰り越し日数より大きい場合、繰り越し日数は最大繰り越し日数と同じになる。小さい場合、繰り越し日数は消化残と同じ日数になる。）
                $carry_over_count = $class->carry_over_count($nokori, $max_carry_over);
                var_dump('繰越日数:');
                var_dump($carry_over_count);

                //月別消化日数
                $get_holiday = $class->get_holiday($id, $nyushabi_year_month, $kijunbi_year_month);
                var_dump($i . '消化日数:');
                var_dump($get_holiday);

                //[0]付与日数/[1]最大繰り越し日数/[2]前期繰越/[3]期首残高/[4]消化日数/[5]消化残/[6]繰越日数/[7]月別消化日数
                $array3[] = [$huyo_holiday, $max_carry_over, $carry_over, $kisyu_nokori, $holiday_count_int, $nokori, $carry_over_count, $get_holiday];
                echo ('</pre>');
            }
        }


        //準社員の人は有給がないので表示しない
        $jun_shain_pre = DB::table('employees')
            ->where('remarks', 'LIKE', "%準社員%")
            ->where('shain_cd', $id)
            ->first();

        if (isset($jun_shain_pre)) {
            $jun_shain = "準社員";
        } else {
            $jun_shain = "正社員";
        }




        //選択した年度と配列を結ぶ
        //初回基準月未満の人は別の配列にデータが入っているので除外、準社員のひと、基準月が来る前に退社した人はデータがないので除外
        if ($post_year == 00 or $post_year == 01 or $jun_shain == "準社員") {
            $array_count = -1;
        } else {

            //勤続年数分配列が作られているので、勤続年数分繰り返す
            for ($y = 0; $y <= $kinzoku_year; $y++) {

                if ($post_year - $kijunbi_year == $y) {
                    $array_count = $y;
                }
            }
        }

        var_dump($array_count);





        // トップページに戻るボタン押下時のスクロール位置とトップページURL
        if(isset($_POST['top_url'])){
            $top_url = $_POST['top_url'];
            $scroll_top = $_POST['scroll_top2'];
        }else{
            $top_url = '/employee/public';
            $scroll_top = '0';
        }


        // echo '<pre>';
        // var_dump('ここよ');
        // var_dump($post_year);
        // echo '</pre>';

        // 未消化アラート
        // 初回基準月未満の人と準社員のひとのアラートは表示しない
        // if (($year_month_b >= $nyushabi_year_month and $year_month_b < $kijunbi_year_month) or $jun_shain == "準社員") {
        if ($post_year == 00 or isset($employee->taishokubi) or $jun_shain == "準社員") {
            $mishouka_alert = "no";
            //未消化アラート表示の対象者で基準月が3か月以内に迫っていて消化日数が5日以下の場合、アラートを表示する
        } else {

            if ($array[$array_count][9] <= $year_month_b and $array[$array_count][4] <= 5) {
                $mishouka_alert = "yes";
            } else {
                $mishouka_alert = "no";
            }
        }

        // echo '<pre>';
        // var_dump($array);
        // echo '</pre>';


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
            //入社日年月
            'nyushabi_year_month' => $nyushabi_year_month,
            //準社員かどうか
            'jun_shain' => $jun_shain,
            //基準月の一か月前（年度終わりの月）
            'first_day_max_month' => $first_day_max_month,
            //未消化アラート
            'mishouka_alert' => $mishouka_alert,
            //勤続年数
            'kinzoku_year' => $kinzoku_year,


            //配列
            'array' => $array,
            //配列2(研修期間中の)
            'array2' => $array2,
            //配列3(初回基準月未満で退職した人の)
            'array3' => $array3,
            //配列の指定
            'array_count' => $array_count,

            // トップページに戻るボタン押下時のスクロール位置とトップページURL
            'top_url' => $top_url,
            'scroll_top' => $scroll_top,

        ]);
    }
}
