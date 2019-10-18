<?php

namespace App\Http\Controllers;

use App\Holiday;
use App\Employee;
use DB;
use Illuminate\Support\Carbon;

use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;

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
            ->orWhere('nyushabi', 'LIKE', '%-0' . $before3_2 . '-%')
            ->orWhere('nyushabi', 'LIKE', '%-0' . $before3_3 . '-%')
            ->get();
        // ->toSQL();

        // var_dump($employees);
        var_dump($before3_1);
        var_dump($before3_2);
        var_dump($before3_3);

        //今年度始まりの月の計算
        //基準日の計算(入社日に+6か月)　※ XXXX-XX-01　XXXX-XXの部分は個人で計算されます。
        $kijunbi = DB::table('employees')
            ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "kijunbi"'))
            ->whereNull('taishokubi')
            ->get();

        $kijunbi_count = DB::table('employees')
            ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "kijunbi"'))
            ->whereNull('taishokubi')
            ->count();



        for ($i = 0; $i <= $kijunbi_count - 1; $i++) {
            // 基準年を抜き出す
            $kijunbi_year = substr($kijunbi[$i]->kijunbi, 0, 4);

            

            // はいれつにいれる
            // $kijunbi_year_array[] = (int)[$kijunbi_year];
            $kijunbi_year_array[] = array_map('intval', [$kijunbi_year]);
          
        }
        // var_dump("ここから");
        // var_dump($kijunbi_year_array);

        // $kijunbi_year_intArray = array();

        // foreach ($kijunbi_year_array as $value){
        //     $kijunbi_year_intArray[] = intval($value);
        // }
        // var_dump($kijunbi_year_intArray);

        // 基準月を抜き出す
        // $kijunbi_month = substr($kijunbi, 5, 2);

        //勤続年数の計算
        $year_pre2 = date('Ym') - 2;
        $year_pre1 = substr($year_pre2, 0, 4);
        $year = (int)$year_pre1;


        // var_dump($year);
        for ($i = 0; $i <= $kijunbi_count - 1; $i++) {
            var_dump("ここから");
            var_dump($year);
            var_dump($kijunbi_year_array[$i]);
            var_dump(extract($kijunbi_year_array[$i]));

  
            $kinzoku_year = $year - extract($kijunbi_year_array[$i]);


            // var_dump("インとイヤー");
            // var_dump((int)$year);
            // var_dump("インと基準日");
            // var_dump($kijunbi_year_array[$i]);
            // var_dump("計算結果");
            // var_dump($kinzoku_year);
            // $kinzoku_year_array[] = [$kinzoku_year];
        }
        // var_dump($kinzoku_year_array);


        for ($i = 0; $i <= $kijunbi_count - 1; $i++) {
            var_dump($kijunbi_year_array[$i]);
            var_dump($kinzoku_year);

            if (($kijunbi_year_array[$i] + (array)$kinzoku_year) <= date('Ym')) {
                $this_year_kijunbi = $kijunbi_year_array[$i] + $kinzoku_year;
            } else {
                $this_year_kijunbi = $kijunbi_year_array[$i] + $kinzoku_year - 1;
            }

            $this_year_kijunbi_array[] = [$kijunbi_year];
        }



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
