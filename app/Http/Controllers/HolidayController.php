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


        $post_year = $_POST['year'];
        // var_dump('何年の有給についてか:'.$post_year);

        $employee = Employee::find($id);


        //基準月の計算　※日は考慮していません
        $kijunbi = DB::table('employees')
            ->select(db::raw('ADDDATE( nyushabi , INTERVAL +6 MONTH) AS "kijunbi"'))
            ->where('shain_cd', $id)
            ->first();
            // var_dump($kijunbi);


        // 基準月を抜き出す
        $day_month = substr($kijunbi->kijunbi, 5, 2);
        // var_dump('基準月:' . $day_month);
        // var_dump($day_month);

        // 選択された年数に＋1年する
        $day_max_year = $post_year + 1;

        //今期最後の月
        $day_max = $day_max_year . $day_month;
        //今期始まりの月
        $day_min = $post_year . $day_month;

        // var_dump('年度最後の月:' .$day_max);
        // var_dump('年度始まりの月:' .$day_min);



        //○○年度有給取得日数
        $holiday_count = DB::table('holidays')
            ->select(DB::raw('sum(day) AS sumday'))
            ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
            ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
            ->where('shain_cd', $id)
            ->get();
        // ->toSQL();

        // var_dump($holiday_count);


        //○○年度の月別有給取得日数
        $get_holiday = DB::table('holidays')
            ->select('year','day', 'month')
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
            'day_month' =>$day_month,
            'day_max' =>$day_max,
            'day_min' =>$day_min,

        ]);
    }
}
