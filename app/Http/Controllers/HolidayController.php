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

        //詳細ページのプルダウンで指定された年度
        $post_year = $_POST['year'];
        // var_dump('何年の有給についてか:'.$post_year);

        //employeesテーブルのデータを詳細テーブルで表示していた社員コードの分取得
        $employee = Employee::find($id);

        //入社日の取得
        $nyushabi = DB::table('employees')
            ->select('nyushabi')
            ->where('shain_cd', $id)
            ->first();

        //入社年の抜き出し
        $nyushabi_year = substr($nyushabi->nyushabi, 0, 4);
        var_dump($nyushabi_year);

        //基準日の計算　※ XXXX-XX-01　XXXX-XXの部分は個人で計算されます。
        $kijunbi = DB::table('employees')
            ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "kijunbi"'))
            ->where('shain_cd', $id)
            ->first();
        // ->toSQL();
        var_dump($kijunbi);

        // 基準月を抜き出す
        $kijunbi_month = substr($kijunbi->kijunbi, 5, 2);
        // var_dump('基準月:' . $kijunbi_month);
        // var_dump($kijunbi_month);

        // 基準年を抜き出す
        $kijunbi_year = substr($kijunbi->kijunbi, 0, 4);
        var_dump('基準年:' . $kijunbi_year);


        //基準日の３ヶ月前の計算　※ XXXX-XX-01　XXXX-XXの部分は個人で計算されます。
        $kijunbi_before3 = DB::table('employees')
            ->select(db::raw('SUBDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL -3 MONTH) AS "kijunbi_before3"'))
            ->where('shain_cd', $id)
            ->first();
        // ->toSQL();
        var_dump($kijunbi_before3);

        //年度終わりの３ヶ月前の月を抜き出す
        $kijunbi_before3_month = substr($kijunbi_before3->kijunbi_before3, 5, 2);
        var_dump('基準日の３ヶ月前:' . $kijunbi_before3_month);
        // var_dump($kijunbi_month);


        // 初年度の計算(入社してすぐに3日間の有給が使用できるため)
        //初年度始まりの月　=　入社日
        //初年度最後の月　=　$day_max


        // 選択された年数に＋1年する (=今期最後の月で使用する年数)
        $day_max_year = $post_year + 1;

        //今期最後の月
        $day_max = $day_max_year . $kijunbi_month;
        //今期始まりの月
        $day_min = $post_year . $kijunbi_month;
        //今期最後の月から3ヶ月前（=warning）
        $day_max_before3 = $day_max_year . $kijunbi_before3_month;


        var_dump('年度最後の月:' . $day_max);
        var_dump('年度始まりの月:' . $day_min);
        var_dump('年度最後の月の三ヶ月前:' . $day_max_before3);


        // 勤続年数を計算
        $kinzoku_year = $post_year - $kijunbi_year;
        var_dump('勤続年数:' . $kinzoku_year);

        // 付与日数（=最大繰り越し日数）の計算
        if ($kinzoku_year == 0) {
            // 付与日数
            $huyo_holiday = "10";
            //前年の付与日数
            $huyo_holiday_before = "0";
            // 時効消滅
            $dis_holiday = "0";
        } elseif ($kinzoku_year == 1) {
            $huyo_holiday = "11";
            $huyo_holiday_before = "10";
            $dis_holiday = "10";
        } elseif ($kinzoku_year == 2) {
            $huyo_holiday = "12";
            $huyo_holiday_before = "11";
            $dis_holiday = "11";
        } elseif ($kinzoku_year == 3) {
            $huyo_holiday = "14";
            $huyo_holiday_before = "12";
            $dis_holiday = "12";
        } elseif ($kinzoku_year == 4) {
            $huyo_holiday = "16";
            $huyo_holiday_before = "14";
            $dis_holiday = "14";
        } elseif ($kinzoku_year == 5) {
            $huyo_holiday = "18";
            $huyo_holiday_before = "16";
            $dis_holiday = "16";
        } elseif ($kinzoku_year == 6) {
            $huyo_holiday = "20";
            $huyo_holiday_before = "18";
            $dis_holiday = "18";
        } elseif ($kinzoku_year > 6) {
            $huyo_holiday = "20";
            $huyo_holiday_before = "20";
            $dis_holiday = "20";
        }
        var_dump('付与日数:' . $huyo_holiday);
        var_dump('時効消滅:' . $dis_holiday);
        var_dump('前年の付与日数:' . $huyo_holiday_before);



        //○○年度有給取得日数 = 消化日数
        $holiday_count = DB::table('holidays')
            ->select(DB::raw('sum(day) AS sumday'))
            ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
            ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
            ->where('shain_cd', $id)
            ->first();
        // ->get();
        // ->toSQL();
        // var_dump('消化日数:' . $holiday_count);
        var_dump($holiday_count);

        //$holiday_countをobjectからarrayに変換
        $holiday_count_arr = (array) $holiday_count;
        //arratをintに変換
        $holiday_count_int = (int) $holiday_count_arr;

        var_dump($holiday_count_arr);
        var_dump($holiday_count_int);


        //消化残（付与日数-消化日数）
        $nokori = $huyo_holiday - $holiday_count_int;
        var_dump('消化残:' . $nokori);


        // 前期繰越日数（初年度は0、その後は前期の残日数-時効消滅）

        //初年度
        $first_carry_over = 0;

        //前期の残日数
        // 選択された年数に-1年する (=前年度最後の月で使用する年数)
        $day_max_year_before = $post_year - 1;

        //前期最後の月
        $day_max_before = $day_max_year_before . $kijunbi_month;
        //前期始まりの月
        $day_min_before = $day_max_year_before . $kijunbi_month;

        //前年度有給取得日数 = 前年度消化日数
        $holiday_count_before = DB::table('holidays')
            ->select(DB::raw('sum(day) AS sumday'))
            ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min_before)
            ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max_before)
            ->where('shain_cd', $id)
            ->first();
        // ->get();
        // ->toSQL();
        // var_dump('前年度消化日数:' . $holiday_count);
        var_dump($holiday_count_before);

        //$holiday_count_beforeをobjectからarrayに変換
        $holiday_count_arr_before = (array) $holiday_count_before;
        //arratをintに変換
        $holiday_count_int_before = (int) $holiday_count_arr_before;

        var_dump($holiday_count_arr_before);
        var_dump($holiday_count_int_before);


        //前年度消化残（付与日数-消化日数）
        $nokori_before = $huyo_holiday_before - $holiday_count_int_before;
        var_dump('消化残:' . $nokori_before);


        $carry_over = $nokori_before - $dis_holiday;

        // 期首残高（基準年の時は10で固定、それ以外は付与日数+前期繰越日数）
        $first_kisyu_nokori = 10;
        $kisyu_nokori = $huyo_holiday + $carry_over;






        //○○年度の月別有給取得日数
        $get_holiday = DB::table('holidays')
            ->select('year', 'day', 'month')
            ->join('employees', 'holidays.shain_cd', '=', 'employees.shain_cd')
            ->where('employees.shain_cd', $id)
            ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '>=', $day_min)
            ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
            ->get();
        // ->toSQL();

        // var_dump($get_holiday);





        return view('/holiday')->with([
            'employee' => $employee,
            'holiday_count' => $holiday_count,
            'get_holiday' => $get_holiday,
            'shain_cd' => $id,
            'post_year' => $post_year,
            'kijunbi_month' => $kijunbi_month,
            'day_max' => $day_max,
            'day_min' => $day_min,
            'day_max_before3' => $day_max_before3,
            'first_kisyu_nokori' => $first_kisyu_nokori,
            'nokori' => $nokori,
            'nyushabi_year' => $nyushabi_year,
            //初年度の前期繰越は0
            'first_carry_over' => $first_carry_over,
            'kisyu_nokori' => $kisyu_nokori,

        ]);
    }
}
