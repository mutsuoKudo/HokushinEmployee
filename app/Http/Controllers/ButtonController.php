<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use DB;
use Response;
use App\Library\BaseClass;


class ButtonController extends Controller
{

    //在籍者
    public function all()
    {
        // クラスのインスタンス化
        $class = new BaseClass();

        // 社員情報取得
        $employees = $class->all();

        // 入社日取得
        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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

        $class = new BaseClass();

        // 社員情報（部門＝1）取得
        $employees = $class->department(1);

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;


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

        $class = new BaseClass();

        // 社員情報（部門＝2）取得
        $employees = $class->department(2);

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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

        $class = new BaseClass();

        // 社員情報（部門＝3）取得
        $employees = $class->department(3);

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;


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

        $class = new BaseClass();

        // 社員情報（部門＝4）取得
        $employees = $class->department(4);

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

        $title = "システム開発部";

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
        $class = new BaseClass();

        // 社員情報（入社日が2007年度）取得
        $employees = $class->nyushabi("2007-01-01", "2007-12-31");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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
        $class = new BaseClass();

        // 社員情報（入社日が2014年度）取得
        $employees = $class->nyushabi("2014-01-01", "2014-12-31");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;


        $title = "2014年入社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }

    //2015年
    public function nyushabi2015()
    {
        $class = new BaseClass();

        // 社員情報（入社日が2015年度）取得
        $employees = $class->nyushabi("2015-01-01", "2015-12-31");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

        $title = "2015年入社";

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

        $class = new BaseClass();

        // 社員情報（入社日が2016年度）取得
        $employees = $class->nyushabi("2016-01-01", "2016-12-31");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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
        $class = new BaseClass();

        // 社員情報（入社日が2017年度）取得
        $employees = $class->nyushabi("2017-01-01", "2017-12-31");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;


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

        $class = new BaseClass();

        // 社員情報（入社日が2018年度）取得
        $employees = $class->nyushabi("2018-01-01", "2018-12-31");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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
        $class = new BaseClass();

        // 社員情報（入社日が2019年度）取得
        $employees = $class->nyushabi("2019-01-01", "2019-12-31");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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
        $class = new BaseClass();

        // 社員情報（入社日が2020年度）取得
        $employees = $class->nyushabi("2020-01-01", "2020-12-31");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;


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

        $class = new BaseClass();

        // 社員情報（20代）取得
        $employees = $class->age(20, 29);

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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

        $class = new BaseClass();

        // 社員情報（30代）取得
        $employees = $class->age(30, 39);

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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

        $class = new BaseClass();

        // 社員情報（40代）取得
        $employees = $class->age(40, 49);

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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

        $class = new BaseClass();

        // 社員情報（50代）取得
        $employees = $class->age(50, 59);

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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
        $class = new BaseClass();

        // 社員情報（60代）取得
        $employees = $class->age(60, 69);

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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
        $class = new BaseClass();

        // 社員情報（20代以下60代以上）取得
        $employees = $class->age_other(20, 69);

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;


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
        $class = new BaseClass();

        // 社員情報（7月入社）取得
        $employees = $class->kijun_month("%-07-%");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;


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
        $class = new BaseClass();

        // 社員情報（8月入社）取得
        $employees = $class->kijun_month("%-08-%");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;


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

        $class = new BaseClass();

        // 社員情報（9月入社）取得
        $employees = $class->kijun_month("%-09-%");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;



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
        $class = new BaseClass();

        // 社員情報（10月入社）取得
        $employees = $class->kijun_month("%-10-%");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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


        $class = new BaseClass();

        // 社員情報（11月入社）取得
        $employees = $class->kijun_month("%-11-%");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;


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
        $class = new BaseClass();

        // 社員情報（12月入社）取得
        $employees = $class->kijun_month("%-12-%");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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
        $class = new BaseClass();

        // 社員情報（1月入社）取得
        $employees = $class->kijun_month("%-01-%");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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

        $class = new BaseClass();

        // 社員情報（2月入社）取得
        $employees = $class->kijun_month("%-02-%");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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
        $class = new BaseClass();

        // 社員情報（3月入社）取得
        $employees = $class->kijun_month("%-03-%");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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

        $class = new BaseClass();

        // 社員情報（4月入社）取得
        $employees = $class->kijun_month("%-04-%");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;


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

        $class = new BaseClass();

        // 社員情報（5月入社）取得
        $employees = $class->kijun_month("%-05-%");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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

        $class = new BaseClass();

        // 社員情報（6月入社）取得
        $employees = $class->kijun_month("%-06-%");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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

        $class = new BaseClass();

        // 社員情報（退職日が入力されている）取得
        $employees = $class->retirement();

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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
        $class = new BaseClass();

        // 社員情報（退職日が入力されていて、退職日が2016年代の人）取得
        $employees = $class->taishokubi("2016-01-01", "2016-12-31");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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
        $class = new BaseClass();

        // 社員情報（退職日が入力されていて、退職日が2017年代の人）取得
        $employees = $class->taishokubi("2017-01-01", "2017-12-31");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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
        $class = new BaseClass();

        // 社員情報（退職日が入力されていて、退職日が2018年代の人）取得
        $employees = $class->taishokubi("2018-01-01", "2018-12-31");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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
        $class = new BaseClass();

        // 社員情報（退職日が入力されていて、退職日が2019年代の人）取得
        $employees = $class->taishokubi("2019-01-01", "2019-12-31");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

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
        $class = new BaseClass();

        // 社員情報（退職日が入力されていて、退職日が2020年代の人）取得
        $employees = $class->taishokubi("2020-01-01", "2020-12-31");

        list($select_nyusha_year_pre, $select_taishoku_year_pre) = $class->nyusya_taishoku_year();
        $select_nyusha_year = $select_nyusha_year_pre;
        $select_taishoku_year = $select_taishoku_year_pre;

        $title = "2020年退社";

        return view('employees')->with([
            'title' => $title,
            'employees' => $employees,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
        ]);
    }




    //↓ここから下はajax表示のためタイトルやemployeesデータなどいらない↓

    //全社員の平均年齢
    public function all_avg()
    {

        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        $all_avg = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', '!=' , '05')
            ->avg($birthday);

        $response = array();
        $response["all_avg"] = round(($all_avg), 1, PHP_ROUND_HALF_UP);
        return Response::json($response);
    }


    //部門別の平均年齢
    public function department_avg()
    {

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

        $response = array();
        $response["department_avg1"] = round(($department_avg1), 1, PHP_ROUND_HALF_UP);
        $response["department_avg2"] = round(($department_avg2), 1, PHP_ROUND_HALF_UP);
        $response["department_avg3"] = round(($department_avg3), 1, PHP_ROUND_HALF_UP);
        $response["department_avg4"] = round(($department_avg4), 1, PHP_ROUND_HALF_UP);

        return Response::json($response);
    }

    //男女別の平均年齢
    public function gender_avg()
    {
        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        $gender_avg1 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('gender', '男')
            ->where('department', '!=' , '05')
            ->avg($birthday);

        $gender_avg2 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', '!=' , '05')
            ->where('gender', '女')
            ->avg($birthday);


        $response = array();
        $response["gender_avg1"] = round(($gender_avg1), 1, PHP_ROUND_HALF_UP);
        $response["gender_avg2"] = round(($gender_avg2), 1, PHP_ROUND_HALF_UP);

        return Response::json($response);
    }

    //全社員の人数
    public function all_count()
    {
        // テーブルを指定
        $all_count = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', '!=' , '05')
            ->count();

        $response = array();
        $response["all_count"] = $all_count;

        return Response::json($response);
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


        $response = array();
        $response["all_department1"] = $all_department1;
        $response["all_department2"] = $all_department2;
        $response["all_department3"] = $all_department3;
        $response["all_department4"] = $all_department4;

        return Response::json($response);
    }


    //男女別の人数
    public function gender_count()
    {

        $all_gender1 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', '!=' , '05')
            ->where('gender', '男')

            ->count();

        $all_gender2 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', '!=' , '05')
            ->where('gender', '女')
            ->count();

        $response = array();
        $response["all_gender1"] = $all_gender1;
        $response["all_gender2"] = $all_gender2;

        return Response::json($response);
    }

    //年代別の人数
    public function age_count()
    {

        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        //20代
        $age1 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', '!=' , '05')
            ->whereBetween($birthday, [20, 29])
            ->count();

        //30代
        $age2 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', '!=' , '05')
            ->whereBetween($birthday, [30, 39])
            ->count();

        //40代
        $age3 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', '!=' , '05')
            ->whereBetween($birthday, [40, 49])
            ->count();

        //50代
        $age4 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', '!=' , '05')
            ->whereBetween($birthday, [50, 59])
            ->count();

        //60代
        $age5 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', '!=' , '05')
            ->whereBetween($birthday, [60, 69])
            ->count();

        //その他
        $age6 = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', '!=' , '05')
            ->where($birthday, '=>', 70)
            ->count();


        $response = array();

        $response["age1"] = $age1;
        $response["age2"] = $age2;
        $response["age3"] = $age3;
        $response["age4"] = $age4;
        $response["age5"] = $age5;
        $response["age6"] = $age6;

        return Response::json($response);
    }



         
}
