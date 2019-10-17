<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use DB;
use Response;

class ButtonController extends Controller
{

    //在籍者
    public function all()
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

    //代表取締役
    public function department1()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 1)
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

        $title = "代表取締役";


        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //管理部
    public function department2()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 2)
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

        $title = "管理部";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,

        ]);
    }

    //営業部
    public function department3()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 3)
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


        $title = "営業部";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //システム開発部
    public function department4()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 4)
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

        $title = "システム開発部";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //研修生
    public function department5()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', 5)
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

        $title = "研修生";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //2007年
    public function nyushabi2007()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', ["2007-01-01", "2007-12-31"])
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


        $title = "2007年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }
    //2014年
    public function nyushabi2014()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', ["2014-01-01", "2014-12-31"])
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

        $title = "2014年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }
    //2016年
    public function nyushabi2016()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', ["2016-01-01", "2016-12-31"])
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

        $title = "2016年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }
    //2017年
    public function nyushabi2017()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', ["2017-01-01", "2017-12-31"])
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

        $title = "2017年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }
    //2018年
    public function nyushabi2018()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', ["2018-01-01", "2018-12-31"])
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

        $title = "2018年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }
    //2019年
    public function nyushabi2019()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', ["2019-01-01", "2019-12-31"])
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

        $title = "2019年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //2020年
    public function nyushabi2020()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', ["2020-01-01", "2020-12-31"])
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

        $title = "2020年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
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


        $title = "20代";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
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

        $title = "30代";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
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

        $title = "40代";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
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

        $title = "50代";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
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

        $title = "60代";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
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


        $title = "その他の年代";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }



    //有給基準月1月
    public function kijun_month01()
    {

        //入社日が7月の人は基準月が1月
        $employees = DB::table('employees')
            ->where('nyushabi', 'LIKE', "%-07-%")
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


        $title = "有給基準月 1月";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //有給基準月2月
    public function kijun_month02()
    {

        //入社日が8月の人は基準月が2月
        $employees = DB::table('employees')
            ->where('nyushabi', 'LIKE', "%-08-%")
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


        $title = "有給基準月 2月";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //有給基準月3月
    public function kijun_month03()
    {

        //入社日が9月の人は基準月が3月
        $employees = DB::table('employees')
            ->where('nyushabi', 'LIKE', "%-09-%")
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


        $title = "有給基準月 3月";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //有給基準月4月
    public function kijun_month04()
    {

        //入社日が10月の人は基準月が4月
        $employees = DB::table('employees')
            ->where('nyushabi', 'LIKE', "%-10-%")
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


        $title = "有給基準月 4月";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //有給基準月5月
    public function kijun_month05()
    {

        //入社日が11月の人は基準月が5月
        $employees = DB::table('employees')
            ->where('nyushabi', 'LIKE', "%-11-%")
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


        $title = "有給基準月 5月";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //有給基準月6月
    public function kijun_month06()
    {

        //入社日が12月の人は基準月が6月
        $employees = DB::table('employees')
            ->where('nyushabi', 'LIKE', "%-12-%")
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


        $title = "有給基準月 6月";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //有給基準月7月
    public function kijun_month07()
    {

        //入社日が1月の人は基準月が7月
        $employees = DB::table('employees')
            ->where('nyushabi', 'LIKE', "%-01-%")
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


        $title = "有給基準月 7月";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //有給基準月8月
    public function kijun_month08()
    {

        //入社日が2月の人は基準月が8月
        $employees = DB::table('employees')
            ->where('nyushabi', 'LIKE', "%-02-%")
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


        $title = "有給基準月 8月";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //有給基準月9月
    public function kijun_month09()
    {

        //入社日が3月の人は基準月が9月
        $employees = DB::table('employees')
            ->where('nyushabi', 'LIKE', "%-03-%")
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


        $title = "有給基準月 9月";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //有給基準月10月
    public function kijun_month10()
    {

        //入社日が4月の人は基準月が10月
        $employees = DB::table('employees')
            ->where('nyushabi', 'LIKE', "%-04-%")
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


        $title = "有給基準月 10月";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //有給基準月11月
    public function kijun_month11()
    {

        //入社日が5月の人は基準月が11月
        $employees = DB::table('employees')
            ->where('nyushabi', 'LIKE', "%-05-%")
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


        $title = "有給基準月 11月";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //有給基準月12月
    public function kijun_month12()
    {

        //入社日が6月の人は基準月が12月
        $employees = DB::table('employees')
            ->where('nyushabi', 'LIKE', "%-06-%")
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


        $title = "有給基準月 12月";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }



    //退職者
    public function retirement()
    {

        $employees = DB::table('employees')
            ->whereNotNull('taishokubi')
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


        $title = "退職者";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }


    //退社2016年
    public function taishokubi2016()
    {
        $employees = DB::table('employees')
            ->whereNotNull('taishokubi')
            ->whereBetween('taishokubi', ["2016-01-01", "2016-12-31"])
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

        $title = "2016年退社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }
    //退社2017年
    public function taishokubi2017()
    {
        $employees = DB::table('employees')
            ->whereNotNull('taishokubi')
            ->whereBetween('taishokubi', ["2017-01-01", "2017-12-31"])
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

        $title = "2017年退社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }
    //退社2018年
    public function taishokubi2018()
    {
        $employees = DB::table('employees')
            ->whereNotNull('taishokubi')
            ->whereBetween('taishokubi', ["2018-01-01", "2018-12-31"])
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

        $title = "2018年退社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //退社2019年
    public function taishokubi2019()
    {
        $employees = DB::table('employees')
            ->whereNotNull('taishokubi')
            ->whereBetween('taishokubi', ["2019-01-01", "2019-12-31"])
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

        $title = "2019年退社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    // 退社2020年
    public function taishokubi2020()
    {
        $employees = DB::table('employees')
            ->whereNotNull('taishokubi')
            ->whereBetween('taishokubi', ["2020-01-01", "2020-12-31"])
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

        $title = "2020年退社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }




    //全社員の平均年齢
    // public function all_avg()
    // {
    // // テーブルを指定
    // // $employees = DB::table('employees');
    // $employees = DB::table('employees');
    // $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

    // $all_avg = $employees
    //     ->whereNull('taishokubi')
    //     ->avg($birthday);

    // // $employees = Employee::all();
    // $employees = DB::table('employees')
    //     ->whereNull('taishokubi')
    //     ->get();

    // $select_nyusha_year = DB::table('employees')
    //     ->select(db::raw('distinct DATE_FORMAT(nyushabi, "%Y") as nyushanen'))
    //     ->whereNull('taishokubi')
    //     ->orderBy('nyushanen', 'asc')
    //     ->get();

    // $select_taishoku_year = DB::table('employees')
    //     ->select(db::raw('distinct DATE_FORMAT(taishokubi, "%Y") as taishokunen'))
    //     ->whereNotNull('taishokubi')
    //     ->orderBy('taishokunen', 'asc')
    //     ->get();

    // $title = "在籍者";

    //     return view('employees')->with([
    //         'all_avg' => round(($all_avg), 1, PHP_ROUND_HALF_UP),
    //         'title' => $title,
    //         'employees' => $employees,
    //         'select_nyusha_year' => $select_nyusha_year,
    //         'select_taishoku_year' => $select_taishoku_year,
    //     ]);
    // }

    public function all_avg()
    {
        // テーブルを指定
        // $employees = DB::table('employees');
        $employees = DB::table('employees');
        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        $all_avg = $employees
            ->whereNull('taishokubi')
            ->avg($birthday);

        $response = array();
        $response["all_avg"] = $all_avg;
        return Response::json($response);
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

        // $employees = Employee::all();
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->get();

        $title = "在籍者";

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

        return view('employees')->with([
            'department_avg1' => round(($department_avg1), 1, PHP_ROUND_HALF_UP),
            'department_avg2' => round(($department_avg2), 1, PHP_ROUND_HALF_UP),
            'department_avg3' => round(($department_avg3), 1, PHP_ROUND_HALF_UP),
            'department_avg4' => round(($department_avg4), 1, PHP_ROUND_HALF_UP),
            'department_avg5' => round(($department_avg5), 1, PHP_ROUND_HALF_UP),
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
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

        // $employees = Employee::all();
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->get();


        $title = "在籍者";

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

        return view('employees')->with([
            'gender_avg1' => round(($gender_avg1), 1, PHP_ROUND_HALF_UP),
            'gender_avg2' => round(($gender_avg2), 1, PHP_ROUND_HALF_UP),
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //全社員の人数
    public function all_count()
    {
        // テーブルを指定
        $all_count = DB::table('employees')
            ->whereNull('taishokubi')
            ->count();

        // $employees = Employee::all();
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->get();

        $title = "在籍者";

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

        return view('employees')->with([
            'all_count' => $all_count,
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
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

        // $employees = Employee::all();
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->get();

        $title = "在籍者";

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

        return view('employees')->with([
            'all_department1' => $all_department1,
            'all_department2' => $all_department2,
            'all_department3' => $all_department3,
            'all_department4' => $all_department4,
            'all_department5' => $all_department5,
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
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

        // $employees = Employee::all();
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->get();

        $title = "在籍者";

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

        return view('employees')->with([
            'all_gender1' => $all_gender1,
            'all_gender2' => $all_gender2,
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
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

        // $employees = Employee::all();
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->get();

        $title = "在籍者";

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

        return view('employees')->with([
            'age1' => $age1,
            'age2' => $age2,
            'age3' => $age3,
            'age4' => $age4,
            'age5' => $age5,
            'age6' => $age6,
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
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
