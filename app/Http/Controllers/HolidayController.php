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
        //
        // $holiday = Holiday::find($id);
        $employee = Employee::find($id);


        // // 基準日の計算
        // $kijunbi2 = DB::raw('ADDDATE(nyushabi , INTERVAL +3 MONTH) AS "kijunbi"');
        // $kijunbi = DB::table('employees')
        //     ->select($kijunbi2)
        //     ->where('shain_cd', $id)
        //     // ->toSQL();
        //     ->get();
        //     // ->toArray();

        //     $collection = collect($kijunbi)->toArray();
        // var_dump($collection);


        // var_dump($kijunbi);


        // $test_array = ["テスト1","テスト2", "テスト3"];
        // var_dump($test_array);


        // 今期有給消化日の計算
        // $get_holiday2 = DB::raw('holidays.date');
        // $get_holiday = DB::table('employees')
        //     ->select($get_holiday2)
        //     ->join('holidays', 'employees.shain_cd', '=', 'holidays.shain_cd')
        //     // ->where('employees.shain_cd', $id)
        //     ->where(DB::raw('employees.shain_cd = 15010051'))
        //     ->toSQL();
        // ->get();



        // $employees = Employee::all();
        // $get_holiday = DB::table('holidays')
        // ->select('date')
        // // ->where('shain_cd', $id)
        // ->whereNull('date ')
        // ->toSQL();
        // ->get();

        $holiday_count = DB::table('holidays')
            ->where('shain_cd', $id)
            ->count();

        $holiday = DB::table('holidays')
            ->select('day')
            ->where('shain_cd', $id)
            ->get();

            var_dump($holiday);



        // var_dump($holiday);


        return view('/holiday')->with([
            // 'holiday' => $holiday,
            'employee' => $employee,
            'holiday_count' => $holiday_count,
            'holiday' => $holiday,
            'shain_cd' => $id,

        ]);
    }
}






// $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

// $employees = DB::table('employees')
//     ->whereNull('taishokubi')
//     ->whereBetween($birthday, [60, 69])
//     ->get();

// $title = "60代";

// return view('employees')->with([
//     'title' => $title,
//     'employees' => $employees,
// ]);
