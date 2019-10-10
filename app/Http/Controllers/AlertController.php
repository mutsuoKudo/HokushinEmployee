<?php

namespace App\Http\Controllers;

use App\Holiday;
use App\Employee;
use DB;

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
