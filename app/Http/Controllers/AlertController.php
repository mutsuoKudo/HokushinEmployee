<?php

namespace App\Http\Controllers;

use App\Holiday;
use App\Employee;
use DB;
use Illuminate\Support\Carbon;

use Illuminate\Http\Request;

class AlertController extends Controller
{
    //未消化アラートが出ている人
    public function mishouka()
    {
        // $employees = Employee::all();
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->get();

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

        $title = "在籍者";


        //入社日の取得
        $nyushabi = DB::table('employees')
            ->select('nyushabi')
            ->first();

        //入社年月の抜き出し
        $nyushabi_year = substr($nyushabi->nyushabi, 0, 4);
        $nyushabi_month = substr($nyushabi->nyushabi, 5, 2);
        $nyushabi_year_month = $nyushabi_year . $nyushabi_month;
        $nyushabi_year_month2 = $nyushabi_year . "-" .$nyushabi_month . " 00:00:00";


        //一番最近のデータの年月(0000-00)を作成(=現在日時になる)
        $year_month_a_pre = DB::table('holidays')
            // ->select('year', 'month')
            ->select(db::raw('year,lpad(month, 2, "0") as month'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
        // var_dump($year_month_a_pre);

        //一番最近のデータの年
        $year_month_a1 = $year_month_a_pre->year;
        //一番最近のデータの月
        $year_month_a2 = $year_month_a_pre->month;
        //一番最近のデータの年月（0000年00月）
        $year_month_a = $year_month_a1 . "年" . $year_month_a2 . "月";
        //一番最近のデータの年月（000000：文字なし）
        $year_month_b = $year_month_a1 . $year_month_a2;
        // $year_month_c = $year_month_a1 . "-" . $year_month_a2 . "-00 00:00:00";
        $year_month_c = $year_month_a1 . "-" . $year_month_a2 . "-01 00:00:00";


        $year_month_c_date = date('Y-m-d', strtotime($year_month_c));
        $nyushabi_year_month_date = date('Y-m-d', strtotime($nyushabi_year_month));


        var_dump($year_month_c_date);
        var_dump($nyushabi_year_month_date);

        // $test = date('Y-m-d',strtotime($year_month_c)) - date('Y-m-d',strtotime($nyushabi_year_month));
        // var_dump($test);

        $date = Carbon::parse($year_month_c);
        $now = Carbon::parse($nyushabi_year_month2);


        $diff = $date->diffInDays($now);
        var_dump($date);
        var_dump($now);
        var_dump($diff);





















        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }












    //残数僅少アラートが出ている人
    public function zansu_kinshou($id)
    { }

    
}
