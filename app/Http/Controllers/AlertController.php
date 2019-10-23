<?php

namespace App\Http\Controllers;

use App\Holiday;
use App\Employee;
use DB;
use Illuminate\Support\Carbon;

use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;
use SebastianBergmann\Environment\Console;

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

        // 現在の月（現在10月ならデータは8月分なので8月とする）から3か月前の月を計算
        // $before3_1_pre = date('m') - 2 - 3;
        // $before3_1 = str_pad($before3_1_pre, 2, 0, STR_PAD_LEFT);
        // var_dump('3か月前' . $before3_1);

        // $before3_2_pre = date('m') - 2 + 1 - 3;
        // $before3_2 = str_pad($before3_2_pre, 2, 0, STR_PAD_LEFT);
        // var_dump('2か月前' . $before3_2);

        // $before3_3_pre = date('m') - 2 + 2 - 3;
        // $before3_3 = str_pad($before3_3_pre, 2, 0, STR_PAD_LEFT);
        // var_dump('1か月前' . $before3_3);







        $employees_pre = DB::table('employees')
            ->whereNull('taishokubi')
            ->Where(function ($query) {

                $before3_1_pre = date('m') - 2 - 5;
                $before3_1 = str_pad($before3_1_pre, 2, 0, STR_PAD_LEFT);
                // var_dump('入社月が' . $before3_1 . '月のひと');

                $before3_2_pre = date('m') - 2 - 4;
                $before3_2 = str_pad($before3_2_pre, 2, 0, STR_PAD_LEFT);
                // var_dump('入社月が' . $before3_2 . '月のひと');

                $before3_3_pre = date('m') - 2 - 3;
                $before3_3 = str_pad($before3_3_pre, 2, 0, STR_PAD_LEFT);
                // var_dump('入社月が' . $before3_3 . '月のひと');

                $query->orwhere('nyushabi', 'LIKE', "%" . $before3_1 . "%")
                    ->orwhere('nyushabi', 'LIKE', "%" . $before3_2 . "%")
                    ->orWhere('nyushabi', 'LIKE', "%" . $before3_3 . "%");
            })
            ->get();
        // ->toSQL();
        // dd($employees_pre);
        // var_dump($employees_pre);


        // var_dump("date" . $year);

        //  SELECT * FROM `employees`WHERE ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) > 20190801

        // select nyushabi ,ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH), shain_mei from `employees` WHERE taishokubi is NULL AND ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) > '2019-08-01'

        // select * from `employees` where ( `nyushabi` LIKE "%-05-%" or `nyushabi` LIKE "%-06-%" or `nyushabi` LIKE "%-07-%") AND taishokubi is Null


        // 基準月が現在（10月時点でデータが8月末分までなので8月とする）から3か月以内に来る人
        foreach ($employees_pre as $employee) {
            $select_shain_cd[] = [$employee->shain_cd];
        }

        // echo ('<pre>');
        // var_dump("3ヶ月以内に基準月がくる人");
        // var_dump($select_shain_cd);
        // echo ('</pre>');


        // echo ('<pre>');
        // var_dump("3ヶ月以内に基準月がくる人の数" . count($select_shain_cd));
        // echo ('</pre>');

        // 3ヶ月以内に基準月がくる人の数分繰り返す
        for ($i = 0; $i < count($select_shain_cd); $i++) {

            //基準日を求める
            $kijunbi1 = DB::table('employees')
                ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "kijunbi_first"'))
                ->where('shain_cd', $select_shain_cd[$i])
                ->get();

            // 基準月を抜き出す
            foreach ($kijunbi1 as $kijunbi_month_pre1) {
                $kijunbi_month1 = substr($kijunbi_month_pre1->kijunbi_first, 5, 2);
            }

            //本年度の始まりを作成
            $day_min = date('Y') - 1 . $kijunbi_month1;


            //本年度の終わりを計算
            $kijunbi2 = DB::table('employees')
                ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y%m01") , INTERVAL +17 MONTH) AS "kijunbi_end"'))
                ->where('shain_cd', $select_shain_cd[$i])
                ->get();

            // 本年度の終わりの年と月を抜き出す
            foreach ($kijunbi2 as $kijunbi_month_pre2) {
                $kijunbi_month2 = substr($kijunbi_month_pre2->kijunbi_end, 5, 2);
            }

            //本年度終わりの年を計算
            if ($kijunbi_month1 >= 7) {
                $kijunbi_year = date('Y') - 1 + 1;
            } else {
                $kijunbi_year = date('Y') - 1;
            }

            //本年度の始まりを作成
            $day_max = $kijunbi_year . $kijunbi_month2;


            // var_dump("本年度の始まり" . $day_min);
            // var_dump("本年度の終わり" . $day_max);

            // 本年度の有給取得数を計算
            $holiday_count = DB::table('holidays')
                ->select(DB::raw('sum(day) AS sumday'))
                //基準日から一年分
                ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
                ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                ->where('shain_cd', $select_shain_cd[$i])
                ->get();
            // dd($holiday_count);
            // ->toSQL();


            // 1日も休んでいない場合、0を代入。休んでいる場合はその日数を取得。
            foreach ($holiday_count as $counts) {
                if (is_null($counts->sumday)) {
                    $holiday_count_int = "0";
                    // $test = "中身からっぽだ！";
                } else {
                    $holiday_count_int = $counts->sumday;
                    // $test = "中身はいってる！";
                }
            }

            // var_dump($test);
            // var_dump("取得日数" . $holiday_count_int);

            // 取得日数が5日未満の人は配列に社員コードを入れ、5日以上取得している人は0を入れる。
            if ($holiday_count_int < 5) {
                $select_employee[] = $select_shain_cd[$i];
                // var_dump("休んでない人1");
            } else {
                $select_employee[] = "0";
                // var_dump("休んでる人1");
            }

            // echo ('<pre>');
            // var_dump("休んでない人は社員コード入れる");
            // var_dump($select_employee[$i]);
            // echo ('</pre>');
        }


        for ($i = 0; $i < count($select_shain_cd); $i++) {
            // var_dump("select_employee");
            // var_dump($select_employee[$i]);
            if ($select_employee[$i] == 0) {
                // var_dump("休んでる人");
            } else {
                // var_dump("休んでる人");
                $select_shain_cd2[] = $select_employee[$i];
            }
        }

        // var_dump("休んでない人の社員コード");
        // var_dump($select_shain_cd2);



        for ($i = 0; $i < count($select_shain_cd2); $i++) {
            if ($select_shain_cd2[$i] == 0) {
                // var_dump("休んでる人なのでWHEREにいれない");
            } else {


                $first_kijunbi = DB::table('employees')
                    ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y%m01") , INTERVAL +6 MONTH) AS "first_kijunbi"'))
                    ->where('shain_cd', $select_shain_cd2[$i])
                    ->get();
                    var_dump("セレクト");
                    var_dump($select_shain_cd2[$i]);
                    var_dump($first_kijunbi);

                    if($first_kijunbi > '2016-08-01'){
                        $select_shain_cd3 = $select_shain_cd2[$i];
                    }
               
                    

                $employees_prepre = DB::table('employees')
                    ->whereNull('taishokubi')
                    ->where('shain_cd', $select_shain_cd2[$i])
                    ->get();
                // ->toSQL();

            }

            $employees2[] = [$employees_prepre,$first_kijunbi];

            

            
        }

        
        echo ('<pre>');
        // var_dump($employee_pre2);
        var_dump($employees2[0][0][0]->shain_cd);
        var_dump($employees2[0][1][0]->first_kijunbi);
        // var_dump($employees2);
        echo ('</pre>');

        // echo ('<pre>');
        // var_dump("休んでない人の情報");
        // var_dump($employees);
        // echo ('</pre>');


        // echo ('<pre>');
        // var_dump("休んでない人の情報ここみろ");
        // var_dump($employees2[0][0]->shain_cd);
        // echo ('</pre>');






















        return view('/alert')->with([
            'title' => $title,
            'employees2' => $employees2,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
            'select_shain_cd2' => $select_shain_cd2,

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
