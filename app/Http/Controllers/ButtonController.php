<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use DB;

class ButtonController extends Controller
{

    //ALL
    public function all()
    {
        // $employees = Employee::all();
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->get();

        $title = "ALL";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }

    //代表取締役
    public function department1()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 1)
            ->get();

        $title = "代表取締役";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }

    //管理部
    public function department2()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 2)
            ->get();

        $title = "管理部";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }

    //営業部
    public function department3()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 3)
            ->get();

        $title = "営業部";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }

    //システム開発部
    public function department4()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 4)
            ->get();

        $title = "システム開発部";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }

    //研修生
    public function department5()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 5)
            ->get();

        $title = "研修生";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }

    //2013年
    public function nyushabi2013()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', ["2013-01-01", "2013-12-31"])
            ->get();

        $title = "2013年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }
    //2014年
    public function nyushabi2014()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', ["2014-01-01", "2014-12-31"])
            ->get();

        $title = "2014年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }
    //2015年
    public function nyushabi2015()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', ["2015-01-01", "2015-12-31"])
            ->get();

        $title = "2015年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }
    //2016年
    public function nyushabi2016()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', ["2016-01-01", "2016-12-31"])
            ->get();

        $title = "2016年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }
    //2017年
    public function nyushabi2017()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', ["2017-01-01", "2017-12-31"])
            ->get();

        $title = "2017年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }
    //2018年
    public function nyushabi2018()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', ["2018-01-01", "2018-12-31"])
            ->get();

        $title = "2018年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }
    //2019年
    public function nyushabi2019()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', ["2019-01-01", "2019-12-31"])
            ->get();

        $title = "2019年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }
    //2020年
    public function nyushabi2020()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', ["2020-01-01", "2020-12-31"])
            ->get();

        $title = "2020年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }



    //20代
    public function age20()
    {
        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween($birthday, [20, 29])
            // ->orderBY($birthday, 'DESC')
            ->get();

        $title = "20代";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }
    //30代
    public function age30()
    {
        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween($birthday, [30, 39])
            ->get();

        $title = "30代";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }
    //40代
    public function age40()
    {
        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween($birthday, [40, 49])
            ->get();

        $title = "40代";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }
    //50代
    public function age50()
    {
        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween($birthday, [50, 59])
            ->get();

        $title = "50代";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }
    //60代
    public function age60()
    {
        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween($birthday, [60, 69])
            ->get();

        $title = "60代";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }
    //その他の年代
    public function age_other()
    {
        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->where($birthday, '<', 20)
            ->where($birthday, '>', 69)
            ->get();

        $title = "その他の年代";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }

    //退職者
    public function retirement()
    {

        $employees = DB::table('employees')
            ->whereNotNull('taishokubi')
            ->get();

        $title = "退職者";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
        ]);
    }




    //全社員の平均年齢
    public function all_avg()
    {
        // テーブルを指定
        // $employees = DB::table('employees');
        $employees = DB::table('employees');
        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        $all_avg = $employees
            ->whereNull('taishokubi')
            ->avg($birthday);

        $employees = Employee::all();

        $title = "ALL";

        return view('employees')->with([
            'all_avg' => round(($all_avg), 1, PHP_ROUND_HALF_UP),
            'title' => $title,
            'employees' => $employees,
        ]);
    }

    //部門別の平均年齢
    public function department_avg()
    {
        // テーブルを指定
        // $employees = DB::table('employees');
        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        $department_avg1 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 1)
            ->avg($birthday);

        $department_avg2 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 2)
            ->avg($birthday);

        $department_avg3 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 3)
            ->avg($birthday);

        $department_avg4 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 4)
            ->avg($birthday);

        $department_avg5 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 5)
            ->avg($birthday);



        $employees = Employee::all();
        $title = "ALL";

        return view('employees')->with([
            'department_avg1' => round(($department_avg1), 1, PHP_ROUND_HALF_UP),
            'department_avg2' => round(($department_avg2), 1, PHP_ROUND_HALF_UP),
            'department_avg3' => round(($department_avg3), 1, PHP_ROUND_HALF_UP),
            'department_avg4' => round(($department_avg4), 1, PHP_ROUND_HALF_UP),
            'department_avg5' => round(($department_avg5), 1, PHP_ROUND_HALF_UP),
            'title' => $title,
            'employees' => $employees,
        ]);
    }

    //男女別の平均年齢
    public function gender_avg()
    {
        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        $gender_avg1 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('gender', '男')
            ->avg($birthday);

        $gender_avg2 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('gender', '女')
            ->avg($birthday);

        $employees = Employee::all();
        $title = "ALL";

        return view('employees')->with([
            'gender_avg1' => round(($gender_avg1), 1, PHP_ROUND_HALF_UP),
            'gender_avg2' => round(($gender_avg2), 1, PHP_ROUND_HALF_UP),
            'title' => $title,
            'employees' => $employees,
        ]);
    }

    //全社員の人数
    public function all_count()
    {
        // テーブルを指定
        $all_count = DB::table('employees')
            ->whereNull('taishokubi')
            ->count();

        $employees = Employee::all();
        $title = "ALL";

        return view('employees')->with([
            'all_count' => $all_count,
            'title' => $title,
            'employees' => $employees,
        ]);
    }

    //部門別の人数
    public function department_count()
    {

        $all_department1 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 1)
            ->count();

        $all_department2 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 2)
            ->count();

        $all_department3 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 3)
            ->count();

        $all_department4 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 4)
            ->count();

        $all_department5 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 5)
            ->count();

        $employees = Employee::all();
        $title = "ALL";

        return view('employees')->with([
            'all_department1' => $all_department1,
            'all_department2' => $all_department2,
            'all_department3' => $all_department3,
            'all_department4' => $all_department4,
            'all_department5' => $all_department5,
            'title' => $title,
            'employees' => $employees,
        ]);
    }

    //男女別の人数
    public function gender_count()
    {

        $all_gender1 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('gender', '男')
            ->count();

        $all_gender2 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('gender', '女')
            ->count();

        $employees = Employee::all();
        $title = "ALL";

        return view('employees')->with([
            'all_gender1' => $all_gender1,
            'all_gender2' => $all_gender2,
            'title' => $title,
            'employees' => $employees,
        ]);
    }

    //年代別の人数
    public function age_count()
    {
        // $employees = DB::table('employees');

        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        //20代
        $age1 = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween($birthday, [20, 29])
            ->count();

        //30代
        $age2 = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween($birthday, [30, 39])
            ->count();

        //40代
        $age3 = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween($birthday, [40, 49])
            ->count();

        //50代
        $age4 = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween($birthday, [50, 59])
            ->count();

        //60代
        $age5 = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween($birthday, [60, 69])
            ->count();

        //その他
        $age6 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where($birthday, '=>', 70)
            ->count();

        $employees = Employee::all();
        $title = "ALL";

        return view('employees')->with([
            'age1' => $age1,
            'age2' => $age2,
            'age3' => $age3,
            'age4' => $age4,
            'age5' => $age5,
            'age6' => $age6,
            'title' => $title,
            'employees' => $employees,
        ]);
    }


    //shainテーブルアップデート
    // public function shain_update(Request $request, $id)
    // {

    //     // $employees = Employee::all();

    //     $update = DB::raw('UPDATE employees, shain 
    //     SET shain.shain_cd,
    //     shain.shain_mei,
    //     shain.shain_mei_kana,
    //     shain.shain_mei_romaji,
    //     shain.shain_mail,
    //     shain.gender,
    //     shain.shain_birthday,
    //     shain.nyushabi,
    //     shain.tensekibi,
    //     shain.taishokubi,
    //     shain.department,
    //     shain.remarks 
    //     = 
    //     employees.shain_cd,
    //     employees.shain_mei,
    //     employees.shain_mei_kana,
    //     employees.shain_mei_romaji,
    //     employees.shain_mail,
    //     employees.gender,
    //     employees.shain_birthday,
    //     employees.nyushabi,
    //     employees.tensekibi,
    //     employees.taishokubi,
    //     employees.department,
    //     employees.remarks 
    //     WHERE TableA.id = TableB.id;')->save();

    //     return redirect('/')->with('status', 'UPDATE完了!');
    // }
}
