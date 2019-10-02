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

        $employee = Employee::find($id);

        $holiday_count = DB::table('holidays')
            // ->where('shain_cd', $id)
            // ->where('holidays.year',"2019")
            ->select(DB::raw('sum(day)'))
            ->where(DB::raw('CONCAT(year, month) > 20194 AND CONCAT(year, month) < 20203 AND shain_cd = 2007010021'))
            // ->where('shain_cd',$id)
            // ->sum('day')
            // ->get();
        ->toSQL();

        // SELECT year,month FROM holidays WHERE CONCAT(year, month) BETWEEN 20194 AND 20203 AND shain_cd = 2007010021
        // SELECT year,month,sum(day) FROM holidays WHERE CONCAT(year, month) BETWEEN 20194 AND 20203 AND shain_cd = 2007010021
        

        // var_dump($holiday_count);


        $get_holiday = DB::table('holidays')
            ->select('day', 'month')
            // ->where('shain_cd', $id)
            ->join('employees', 'holidays.shain_cd', '=', 'employees.shain_cd')
            ->where('employees.shain_cd', $id)
            ->where('holidays.year', "2019")
            ->whereNotNull('holidays.month')
            // ->toSQL();
            ->get();

        // var_dump($get_holiday_5);

        // SELECT day FROM holidays LEFT JOIN employees USING (shain_cd) WHERE shain_cd = 15010051 AND month = 5

        // select `day` from `holidays` inner join `employees` on `holidays`.`shain_cd` = `employees`.`shain_cd` where employees.`shain_cd` = 15010051 and holidays.`month` = 5



        return view('/holiday')->with([
            // 'holiday' => $holiday,
            'employee' => $employee,
            'holiday_count' => $holiday_count,
            'get_holiday' => $get_holiday,
            'shain_cd' => $id,
            // 'year' -> $year,

        ]);


    }

    
}
