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

        //employeesテーブルのデータを詳細テーブルで表示していた社員コードの分取得
        $employee = Employee::find($id);

        //詳細ページのプルダウンで選択された年度
        $post_year = $_POST['year'];
        // var_dump('何年の有給についてか:'.$post_year);


        //基準日の計算(入社日に+6か月)　※ XXXX-XX-01　XXXX-XXの部分は個人で計算されます。
        $kijunbi = DB::table('employees')
            ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "kijunbi"'))
            ->where('shain_cd', $id)
            ->first();
        // ->toSQL();
        // var_dump($kijunbi);

        // 基準月を抜き出す
        $kijunbi_month = substr($kijunbi->kijunbi, 5, 2);
        // var_dump('基準月:' . $kijunbi_month);
        // var_dump($kijunbi_month);

        // 基準年を抜き出す
        $kijunbi_year = substr($kijunbi->kijunbi, 0, 4);
        var_dump('基準年:' . $kijunbi_year);

        //入社日の取得
        $nyushabi = DB::table('employees')
            ->select('nyushabi')
            ->where('shain_cd', $id)
            ->first();

        //入社年月の抜き出し
        $nyushabi_year_month = substr($nyushabi->nyushabi, 0, 6);

        // 選択年度最後の月（基準月+11ヶ月）の計算(入社月　+6 -1)
        $kijunbi_max_pre = DB::table('employees')
            ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +5 MONTH) AS "kijunbi_max_pre"'))
            ->where('shain_cd', $id)
            ->first();
        // ->toSQL();
        // var_dump($kijunbi_max_pre);

        // 選択年度最後の月を抜き出す
        $kijunbi_max_month = substr($kijunbi_max_pre->kijunbi_max_pre, 5, 2);
        // var_dump('選択年度今期最後の月:' . $kijunbi_max_month);

        // 選択された年数に＋1年する (=今期最後の月で使用する年数)
        $day_max_year = $post_year + 1;

        //今期最後の月
        $day_max = $day_max_year . $kijunbi_max_month;

        // 勤続年数を計算
        $kinzoku_year = $post_year - $kijunbi_year;
        var_dump('勤続年数:' . $kinzoku_year);

















        $year = date("Y");
        for ($i = $kijunbi_year; $i <= $year; $i++) {
            ${'select_year' . $i} = $i;
            var_dump('選択可能な年度:' .${'select_year' . $i});


            if (${'select_year' . $i} == $kijunbi_year) {
                
                // var_dump('調査');
                // var_dump(${'select_year' . $i});
                // var_dump($kijunbi_year);

                $huyo_holiday = 10;
                $carry_over = 0;
                $kisyu_nokori = $huyo_holiday + $carry_over;
                
                $holiday_count = DB::table('holidays')
                ->select(DB::raw('sum(day) AS sumday'))
                //入社日から～
                ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $nyushabi_year_month)
                ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
                ->where('shain_cd', $id)
                ->get();
                // ->toSQL();
                // var_dump('初年度の消化日数:');
                // var_dump($holiday_count);
                
                //配列で取得された消化日数の一番目を変数にいれる
                foreach ($holiday_count as $counts) {
                    // $holiday_count_int = $counts[0];
                    $holiday_count_int = $counts->sumday;
                    // var_dump('holiday_count_int:'.$holiday_count_int);
                }
                
                //消化残（期首残高-消化日数）
            $nokori = $kisyu_nokori - $holiday_count_int;
            var_dump('消化残:' . $kisyu_nokori . '-' . $holiday_count_int);
            
            $get_holiday = DB::table('holidays')
            ->select('year', 'day', 'month')
            ->join('employees', 'holidays.shain_cd', '=', 'employees.shain_cd')
            ->where('employees.shain_cd', $id)
            //入社日から～
            ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '>=', $nyushabi_year_month)
            ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
            ->get();
            // ->toSQL();
            // var_dump($get_holiday);
            
            $max_carry_over = 10;
            
            //付与日数・前期繰越・期首残高・消化日数・消化残・月別消化日数・最大繰越日数
            $array = [
                [$huyo_holiday, $carry_over, $kisyu_nokori, $holiday_count_int, $nokori, $get_holiday, $max_carry_over]
            ];
            // var_dump($array);

        }elseif (${'select_year' . $i} >= $kijunbi_year + 1){
            if($kinzoku_year <= 1){
                

            }
        }
    }
    
        
        

        
        


















        $warning = 0;





        return view('/holiday')->with([
            //社員名で使用する
            'employee' => $employee,
            //社員コードで使用する
            'shain_cd' => $id,
            //選択年度
            'post_year' => $post_year,
            //基準月
            'kijunbi_month' => $kijunbi_month,
            //期首残高
            'kisyu_nokori' => $kisyu_nokori,
            //消化日数
            'holiday_count' => $holiday_count,
            //消化残
            'nokori' => $nokori,
            //消化日数月別
            'get_holiday' => $get_holiday,
            //警告
            'warning' => $warning,


        ]);
    }
}
