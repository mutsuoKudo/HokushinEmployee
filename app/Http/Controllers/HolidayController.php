<?php

namespace App\Http\Controllers;
use App\Holiday;
use App\Employee;

use Illuminate\Http\Request;

class HolidayController extends Controller
{
    //

    public function holiday($id)
    {
        //
        $holiday = Holiday::find($id);
        $employee = Employee::find($id);


        
        return view('/holiday')->with([
            'holiday' => $holiday,
            'employee' => $employee,
            ]);
    }
}
