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
        // 必須項目ここまで



        // $employees = Employee::all();
        // $employees = DB::table('employees')
        //     ->whereNull('taishokubi')
        //     ->get();

        //アラート用(基準月の3ヶ月前の時点で5日以上取得していないとき（＝未消化）)
        // 基準月から３ヶ月前の計算(基準月の3ヶ月前の時点で5日以上取得していないとき（＝未消化）)
        $before3_1 = date('m') - 2 - 3;
        $before3_2 = date('m') - 2 + 1 - 3;
        $before3_3 = date('m') - 2 + 2 - 3;
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('nyushabi', 'LIKE', '%-0' . $before3_1 . '-%')
            -> orWhere('nyushabi', 'LIKE', '%-0' . $before3_2 . '-%')
            ->orWhere('nyushabi', 'LIKE', '%-0' . $before3_3 . '-%')
            ->get();
        // ->toSQL();

        // var_dump($employees);
        var_dump($before3_1);
        var_dump($before3_2);
        var_dump($before3_3);


        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }












    //残数僅少アラートが出ている人
    public function zansu_kinshou($id)
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
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->get();



        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }
}
