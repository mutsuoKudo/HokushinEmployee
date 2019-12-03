<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \DB;
use App\Employee;
use App\Library\BaseClass;

class DependentController extends Controller
{
    //
    public function dependent_info(Request $request, $id)
    {

        // トップページに戻るボタン押下時のスクロール位置とトップページURL
        $top_url = $request->top_url;
        $scroll_top = $request->scroll_top2;

        // クラスのインスタンス化
        $class = new BaseClass();

        //employeesテーブルのデータを詳細テーブルで表示していた社員コードの分取得
        $employee = Employee::find($id);
        
        list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $class->year_month();
        //一番最近のデータの年月（0000年00月）
        $year_month_a = $year_month_a_pre;

        // 扶養家族の情報を取得
        $dependent_info = DB::table('dependent_info')
            ->where('shain_cd', $id)
            ->orderBy('birthday', 'asc')
            ->get();



        return view('/dependent_info')->with([
            'top_url' => $top_url,
            'scroll_top' => $scroll_top,
            'employee' => $employee,
            'dependent_info' => $dependent_info,
            'year_month_a' => $year_month_a,
        ]);
    }
}
