<?php
// app/Library/BaseClass.php
namespace app\Library;

use DB;


class BaseClass
{

    //employeesテーブルのtaishokubiが入力されていないデータ全て（＝在籍者）
    public function all()
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->get();

        return $employees;
    }



    //スタッフ全員の入社年と退職年を算出
    public function nyusya_taishoku_year()
    {

        $select_nyusha_year_pre = DB::table('employees')
            ->select(db::raw('distinct DATE_FORMAT(nyushabi, "%Y") as nyushanen'))
            ->whereNull('taishokubi')
            ->orderBy('nyushanen', 'asc')
            ->get();

        $select_taishoku_year_pre = DB::table('employees')
            ->select(db::raw('distinct DATE_FORMAT(taishokubi, "%Y") as taishokunen'))
            ->whereNotNull('taishokubi')
            ->orderBy('taishokunen', 'asc')
            ->get();

        return [$select_nyusha_year_pre, $select_taishoku_year_pre];
    }

    //部門ごとのスタッフ情報
    public function department($department)
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('department', $department)
            ->get();

        return $employees;
    }

    //入社年ごとのスタッフ情報
    public function nyushabi($start_nyushabi, $end_nyushabi)
    {
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween('nyushabi', [$start_nyushabi, $end_nyushabi])
            ->get();

        return $employees;
    }

    //年代ごとのスタッフ情報
    public function age($start_age, $end_age)
    {
        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->whereBetween($birthday, [$start_age, $end_age])
            ->get();

        return $employees;
    }

    //その他の年代のスタッフ情報
    public function age_other($start_age, $end_age)
    {
        $birthday = DB::raw('(year(curdate()) - year(shain_birthday) ) - ( right(curdate(),5) < right(shain_birthday,5))');

        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->where($birthday, '<', $start_age)
            ->where($birthday, '>', $end_age)
            ->get();

        return $employees;
    }

    //基準月ごとのスタッフ情報
    public function kijun_month($kijun_month)
    {
        //入社日が7月の人は基準月が1月
        $employees = DB::table('employees')
            ->where('nyushabi', 'LIKE', $kijun_month)
            ->whereNull('taishokubi')
            ->get();

        return $employees;
    }

    //退職したスタッフ情報
    public function retirement()
    {
        $employees = DB::table('employees')
            ->whereNotNull('taishokubi')
            ->get();

        return $employees;
    }

    //退職した年ごとのスタッフ情報
    public function taishokubi($start_taishokubi, $end_taishokubi)
    {
        $employees = DB::table('employees')
            ->whereNotNull('taishokubi')
            ->whereBetween('taishokubi', [$start_taishokubi, $end_taishokubi])
            ->get();

        return $employees;
    }




    //基準日を求める
    public function kijunbi($id)
    {
        //基準日の計算(入社日に+6か月)　※ XXXX-XX-01　XXXX-XXの部分は個人で計算されます。
        $kijunbi = DB::table('employees')
            ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "kijunbi"'))
            ->where('shain_cd', $id)
            ->first();
        // ->toSQL();
        // var_dump($kijunbi);

        // 基準年を抜き出す
        $kijunbi_year_pre = substr($kijunbi->kijunbi, 0, 4);
        // var_dump('基準年:' . $kijunbi_year);

        // 基準月を抜き出す
        $kijunbi_month_pre = substr($kijunbi->kijunbi, 5, 2);
        // var_dump('基準月:' . $kijunbi_month);

        // 基準年月を抜き出す
        $kijunbi_year_month_pre = $kijunbi_year_pre . $kijunbi_month_pre;
        // var_dump('基準年月:' . $kijunbi_year_month);

        return [$kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre];
    }

    //初年度最後の月計算(年・月・まとめたもの))
    public function first_day_max_2($id)
    {
        $day_max_pre = DB::table('employees')
            ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +17 MONTH) AS "day_max_pre"'))
            ->where('shain_cd', $id)
            ->first();

        $first_day_max_year_pre = substr($day_max_pre->day_max_pre, 0, 4);
        $first_day_max_month_pre = substr($day_max_pre->day_max_pre, 5, 2);
        //年度最後の年月
        $first_day_max_pre = $first_day_max_year_pre . $first_day_max_month_pre;


        return [$first_day_max_year_pre, $first_day_max_month_pre, $first_day_max_pre];
    }

    //初年度最後の月計算(まとめたもののみ)
    public function first_day_max($id)
    {
        $day_max_pre = DB::table('employees')
            ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +17 MONTH) AS "day_max_pre"'))
            ->where('shain_cd', $id)
            ->first();

        $day_max1 = substr($day_max_pre->day_max_pre, 0, 4);
        $day_max2 = substr($day_max_pre->day_max_pre, 5, 2);
        //年度最後の年月
        $first_day_max = $day_max1 . $day_max2;


        return $first_day_max;
    }

    //年度終わりから３ヶ月前（=warning）を計算
    public function warning($kijunbi_before3, $i)
    {
        //年度終わりから３ヶ月前の年を抜き出す
        $kijunbi_before3_year = substr($kijunbi_before3->kijunbi_before3, 0, 4);
        // var_dump('基準日の３ヶ月前の年:' . $kijunbi_before3_year);

        //年度終わりから３ヶ月前の月を抜き出す
        $kijunbi_before3_month = substr($kijunbi_before3->kijunbi_before3, 5, 2);
        // var_dump('基準日の３ヶ月前の月:' . $kijunbi_before3_month);

        //年度終わりから３ヶ月前（=warning）
        $warning = $kijunbi_before3_year + $i . $kijunbi_before3_month;

        return $warning;
    }

    // 期首残高（付与日数+前期繰越）
    public function kisyu_nokori($huyo_holiday, $carry_over)
    {
        $kisyu_nokori = $huyo_holiday + $carry_over;

        return $kisyu_nokori;
    }

    //消化日数を計算
    public function holiday_count_int($nyushabi_year_month, $day_max, $id)
    {
        $holiday_count = DB::table('holidays')
            ->select(DB::raw('sum(day) AS sumday'))
            //入社日から～基準日に1年度分（11ヶ月）足したもの(ex:2016/10/1入社なら、2016/10/1~（基準日が2017/4/1なので）2018/3/1まで))
            ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $nyushabi_year_month)
            ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
            ->where('shain_cd', $id)
            ->get();
        // ->toSQL();

        //配列で取得された消化日数の一番目を変数にいれる
        foreach ($holiday_count as $counts) {

            if (is_null($counts->sumday)) {
                $holiday_count_int = 0;
            } else {
                $holiday_count_int = $counts->sumday;
            }
        }

        return $holiday_count_int;
    }

    //消化残（期首残高-消化日数）
    public function nokori($kisyu_nokori, $holiday_count_int)
    {
        $nokori = $kisyu_nokori - $holiday_count_int;

        return $nokori;
    }

    //繰越日数
    public function carry_over_count($nokori, $max_carry_over)
    {
        if ($nokori > $max_carry_over) {
            $carry_over_count = $max_carry_over;
        } else {
            $carry_over_count = $nokori;
        }
        return $carry_over_count;
    }

    //月別消化日数
    public function get_holiday($id, $nyushabi_year_month, $day_max)
    {
        //月別消化日数
        $get_holiday = DB::table('holidays')
            ->select('year', 'day', 'month')
            ->join('employees', 'holidays.shain_cd', '=', 'employees.shain_cd')
            ->where('employees.shain_cd', $id)
            //入社日から～基準日に1年度分（11ヶ月）足したもの(ex:2016/10/1入社なら、2016/10/1~（基準日が2017/4/1なので）2018/3/1まで))
            ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '>=', $nyushabi_year_month)
            ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        return $get_holiday;
    }

    //初年度以降の前期繰越
    public function carry_over($array, $i, $max_carry_over)
    {
        //前期繰越（1年目～の前期繰越は前年度で求めた繰り越し日数と同じなので、前年度の繰り越し日数を代入）
        if ($array[$i - 1][6] > $max_carry_over) {
            $carry_over = $max_carry_over;
        } else {
            $carry_over = $array[$i - 1][6];
        }

        return $carry_over;
    }

    //初年度以降の年度初め
    public function day_min($kijunbi_year, $i, $kijunbi_month)
    {
        $day_min_pre = (int)$kijunbi_year + $i;
        $day_min = $day_min_pre . $kijunbi_month;

        return $day_min;
    }

    //初年度以降の年度終わり
    public function day_max($first_day_max, $i)
    {
        $day_max_year = substr($first_day_max, 0, 4);
        $day_max_month = substr($first_day_max, 4, 2);

        $day_max = (int)$day_max_year + $i . $day_max_month;

        return $day_max;
    }

    //一番最近のデータの年月(0000-00)を作成(=現在日時になる（◎月時点のデータですとか）)
    public function year_month()
    {

        $year_month_a_pre = DB::table('holidays')
            // ->select('year', 'month')
            ->select(db::raw('year,lpad(month, 2, "0") as month'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
        // var_dump($year_month_a_pre);

        //一番最近のデータの年
        $year_month_a1_pre = $year_month_a_pre->year;
        //一番最近のデータの月
        $year_month_a2_pre = $year_month_a_pre->month;
        //一番最近のデータの年月（0000年00月）
        $year_month_a_pre = $year_month_a1_pre . "年" . $year_month_a2_pre . "月";
        //一番最近のデータの年月（000000：文字なし）
        $year_month_b_pre = $year_month_a1_pre . $year_month_a2_pre;

        return [$year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre];
    }



    // 主にアラートで使用
    //IDで抜き出す社員情報
    public function employees($id)
    {

        // employeesテーブルに入っている情報
        $employees = DB::table('employees')
            ->whereNull('taishokubi')
            ->where('shain_cd', $id)
            ->get();
        // ->toSQL();

        return [$employees];
    }

    //年度の終わりを計算
    public function end_kijunbi($id)
    {

        //本年度の終わりを計算
        $end_kijunbi_pre = DB::table('employees')
            ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y%m01") , INTERVAL +17 MONTH) AS "end_kijunbi"'))
            ->where('shain_cd', $id)
            ->get();


        foreach ($end_kijunbi_pre as $end_kijunbi2) {

            // echo ('<pre>');
            // var_dump($end_kijunbi2);
            // echo ('</pre>');

            $end_kijunbi_year_pre = substr($end_kijunbi2->end_kijunbi, 0, 4);
            $end_kijunbi_month_pre = substr($end_kijunbi2->end_kijunbi, 5, 2);
            $end_kijunbi_pre = $end_kijunbi_year_pre . $end_kijunbi_month_pre;
        }


        return [$end_kijunbi_year_pre, $end_kijunbi_month_pre, $end_kijunbi_pre];
    }

    //消化日数を計算
    public function holiday_count($day_min, $day_max, $id)
    {
        // 本年度の有給取得数を計算
        $holiday_count = DB::table('holidays')
            ->select(DB::raw('sum(day) AS sumday'))
            //基準日から一年分
            ->where(DB::raw('CONCAT(year, lpad(month, 2, "0"))'), '>=', $day_min)
            ->where(DB::raw('CONCAT(year,lpad(month, 2, "0"))'), '<=', $day_max)
            ->where('shain_cd', $id)
            ->get();


        return $holiday_count;
    }

    //入社年・入社月・入社年月の取得
    // IDで抜き出す
    public function nyushabi_year_month($id)
    {
        //入社日の取得
        $nyushabi = DB::table('employees')
            ->select('nyushabi')
            ->where('shain_cd', $id)
            ->whereNull('taishokubi')
            ->get();

        // var_dump('入社日');
        // var_dump($id);
        // var_dump($nyushabi);

        //入社年月の抜き出し
        $nyushabi_year_pre = substr($nyushabi[0]->nyushabi, 0, 4);
        $nyushabi_month_pre = substr($nyushabi[0]->nyushabi, 5, 2);
        $nyushabi_year_month_pre = $nyushabi_year_pre . $nyushabi_month_pre;


        return [$nyushabi_year_pre, $nyushabi_month_pre, $nyushabi_year_month_pre];
    }

    //全在籍社員の入社年・入社月・入社年月(20191001)・入社年月(2019-10-01)の取得
    // データ数指定
    public function all_nyushabi_year_month($i)
    {
        //入社日の取得
        $nyushabi_pre = DB::table('employees')
            ->select('nyushabi')
            ->whereNull('taishokubi')
            ->get();

        // echo ('<pre>');
        // var_dump('入社日');
        // var_dump($nyushabi_pre);
        // echo ('</pre>');

        //入社年月の抜き出し
        $nyushabi_year_pre = substr($nyushabi_pre[$i]->nyushabi, 0, 4);
        $nyushabi_month_pre = substr($nyushabi_pre[$i]->nyushabi, 5, 2);
        $nyushabi_year_month_pre = $nyushabi_year_pre . $nyushabi_month_pre;


        return [$nyushabi_year_pre, $nyushabi_month_pre, $nyushabi_year_month_pre, $nyushabi_pre];
    }




    // CRUDControllerで使用
    // 退職日取得
    public function retirement_id($id)
    {
        //退職日取得
        $taishokubi_pre = DB::table('employees')
            ->select('taishokubi')
            ->where('shain_cd', $id)
            ->first();


        //退職年月の抜き出し
        $taishokubi_year_pre = substr($taishokubi_pre->taishokubi, 0, 4);
        $taishokubi_month_pre = substr($taishokubi_pre->taishokubi, 5, 2);
        $taishokubi_year_month_pre = $taishokubi_year_pre . $taishokubi_month_pre;


        return [$taishokubi_year_pre, $taishokubi_month_pre, $taishokubi_year_month_pre, $taishokubi_pre];
    }
}
