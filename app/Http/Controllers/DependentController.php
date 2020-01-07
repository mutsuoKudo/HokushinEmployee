<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Employee;
use App\Dependent_info;
use App\Library\BaseClass;

use App\Http\Requests\Dependent_infoPost;

class DependentController extends Controller
{
    //
    public function dependent_info(Request $request, $id)
    {

        // トップページに戻るボタン押下時のスクロール位置とトップページURL
        $top_url = $request->top_url;
        $scroll_top = $request->scroll_top2;

        //employeesテーブルのデータを詳細テーブルで表示していた社員コードの分取得
        $employee = Employee::find($id);

        // 扶養家族の情報を取得
        $dependent_info = DB::table('dependent_infos')
            ->where('shain_cd', $id)
            ->orderBy('birthday', 'asc')
            ->get();



        return view('/dependent_info')->with([
            'top_url' => $top_url,
            'scroll_top' => $scroll_top,
            'employee' => $employee,
            'dependent_info' => $dependent_info,
        ]);
    }


    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // 扶養家族編集画面の表示
    public function dependent_info_edit(Request $request,$id)
    {
        $employee = Employee::find($id);

        // 扶養家族の情報を取得
        $dependent_info = DB::table('dependent_infos')
            // ->where('shain_cd', $id)
            ->where('shain_cd', 202020)
            ->orderBy('birthday', 'asc')
            ->get();

        // $top_url = '/employee/public/';
        // $scroll_top = 0;
        $top_url = $request->url;
        $scroll_top = $request->scroll_top;

        return view('/dependent_info_edit')->with([
            'employee' => $employee,
            'dependent_info' => $dependent_info,
            'top_url' => $top_url,
            'scroll_top' => $scroll_top,

        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // 扶養家族データ更新処理と編集画面に戻す
    // public function dependent_info_update(Dependent_infoPost $request, $id)
    public function dependent_info_update(Request $request, $id)
    {

        // 必須項目が一つでもみ入力であれば、入力画面にリダイレクト（入力値保持できない…）
        if (is_null($request->shain_cd) or is_null($request->name) or is_null($request->name_kana) or is_null($request->gender) or is_null($request->birthday) or is_null($request->haigusha) or is_null($request->shikakushutokubi)) {

            $employee = Employee::find($request->shain_cd);

            $top_url = '/employee/public/';
            $scroll_top = 0;

            // 扶養家族の情報を取得
            $dependent_info = DB::table('dependent_infos')
                ->where('shain_cd', $request->shain_cd)
                ->orderBy('birthday', 'asc')
                ->get();

            $dependent_info_submit_error = 'update_error';

            return view('/dependent_info_edit')
                ->with([
                    'dependent_info_submit_error' => $dependent_info_submit_error,
                    'employee' => $employee,
                    'dependent_info' => $dependent_info,
                    'top_url' => $top_url,
                    'scroll_top' => $scroll_top,


                ]);

        } else {


            $employee = Employee::find($request->shain_cd);

            $top_url = '/employee/public/';
            $scroll_top = 0;

            $dependent_info_update = Dependent_info::find($id);

            $dependent_info_update->shain_cd = $request->input('shain_cd');
            $dependent_info_update->name = $request->input('name');
            $dependent_info_update->name_kana = $request->input('name_kana');
            $dependent_info_update->gender = $request->input('gender');
            $dependent_info_update->birthday = $request->input('birthday');
            $dependent_info_update->haigusha = $request->input('haigusha');
            $dependent_info_update->kisonenkin_bango = $request->input('kisonenkin_bango');
            $dependent_info_update->shikakushutokubi = $request->input('shikakushutokubi');

            $dependent_info_update->save();

            $update_success = '更新完了!';

            // 扶養家族の情報を取得
            $dependent_info = DB::table('dependent_infos')
                ->where('shain_cd', $request->shain_cd)
                ->orderBy('birthday', 'asc')
                ->get();


            return view('/dependent_info_edit')
                // return view('/edit')

                ->with([
                    'update_success' => $update_success,
                    'employee' => $employee,
                    'dependent_info' => $dependent_info,
                    'top_url' => $top_url,
                    'scroll_top' => $scroll_top,

                ]);
        }
    }


    public function dependent_info_add($id)
    {

        $employee = Employee::find($id);

        return view('/dependent_info_create')->with([
            'employee' => $employee
        ]);
    }


    public function dependent_info_submit(Request $request, $id)
    {

        // 必須項目が一つでもみ入力であれば、入力画面にリダイレクト（入力値保持できない…）
        if (is_null($request->shain_cd) or is_null($request->name) or is_null($request->name_kana) or is_null($request->gender) or is_null($request->birthday) or is_null($request->haigusha) or is_null($request->shikakushutokubi)) {
            $employee = Employee::find($request->shain_cd);
            // 扶養家族の情報を取得
            $dependent_info = DB::table('dependent_infos')
                ->where('shain_cd', $request->shain_cd)
                ->orderBy('birthday', 'asc')
                ->get();

            // トップページに戻るボタン押下時のスクロール位置とトップページURL
            $top_url = $request->top_url;
            $scroll_top = $request->scroll_top2;

            //  未入力エラーをdependent_info_createビューに表示
            $dependent_info_submit_error = 'error';

            return view('/dependent_info_create')->with([

                'employee' => $employee,
                'dependent_info_submit_error' => $dependent_info_submit_error,
            ]);
        } else {

            // 扶養家族の情報を取得
            $dependent_info_before = DB::table('dependent_infos')
                ->where('shain_cd', $request->shain_cd)
                ->orderBy('birthday', 'asc')
                ->get();

            // 入力した値とすでに保存されている値が同じ時保存されないようにする
            foreach ($dependent_info_before as $before) {
                if ($before->name == $request->name and $before->name_kana == $request->name_kana and $before->gender == $request->gender and $before->birthday == $request->birthday and $before->haigusha == $request->haigusha and $before->kisonenkin_bango == $request->kisonenkin_bango and $before->shikakushutokubi == $request->shikakushutokubi) {

                    $employee = Employee::find($request->shain_cd);

                    // 扶養家族の情報を取得
                    $dependent_info = DB::table('dependent_infos')
                        ->where('shain_cd', $request->shain_cd)
                        ->orderBy('birthday', 'asc')
                        ->get();

                    // トップページに戻るボタン押下時のスクロール位置とトップページURL
                    $top_url = $request->top_url;
                    $scroll_top = $request->scroll_top2;

                    // データの重複エラーをdependent_info_editビューに表示
                    $dependent_info_submit_error = 'duplicate_error';


                    return view('/dependent_info_edit')
                        ->with([
                            'dependent_info_submit_error' => $dependent_info_submit_error,
                            'employee' => $employee,
                            'dependent_info' => $dependent_info,
                            'top_url' => $top_url,
                            'scroll_top' => $scroll_top,


                        ]);
                }
            }

            $employee = Employee::find($request->shain_cd);

            $dependent_info_create = new Dependent_info();
            $dependent_info_create->shain_cd = $request->shain_cd;
            $dependent_info_create->name = $request->name;
            $dependent_info_create->name_kana = $request->name_kana;
            $dependent_info_create->gender = $request->gender;
            $dependent_info_create->birthday = $request->birthday;
            $dependent_info_create->haigusha = $request->haigusha;
            $dependent_info_create->kisonenkin_bango = $request->kisonenkin_bango;
            $dependent_info_create->shikakushutokubi = $request->shikakushutokubi;

            $dependent_info_create->save();

            // 扶養家族の情報を取得
            $dependent_info = DB::table('dependent_infos')
                ->where('shain_cd', $request->shain_cd)
                ->orderBy('birthday', 'asc')
                ->get();

            // トップページに戻るボタン押下時のスクロール位置とトップページURL
            $top_url = $request->top_url;
            $scroll_top = $request->scroll_top2;

            $create_success = '新規登録完了!';

            // return redirect('/')->with('create', '新規登録完了!');
            return view('/dependent_info_edit')->with([

                'create_success' => $create_success,
                'employee' => $employee,
                'dependent_info' => $dependent_info,

                'top_url' => $top_url,
                'scroll_top' => $scroll_top,


            ]);
        }
    }

    public function dependent_info_delete(Request $request, $id)
    {

        // トップページに戻るボタン押下時のスクロール位置とトップページURL
        $top_url = '/employee/public';
        $scroll_top = '0';
        // $top_url = $request->top_url;
        // $scroll_top = $request->scroll_top2;


        //employeesテーブルのデータを詳細テーブルで表示していた社員コードの分取得
        $employee = Employee::find($id);

        // 扶養家族の情報を削除
        \DB::table('dependent_infos')
            ->where('id', $request->id)
            ->delete();

        // 扶養家族の情報を取得
        $dependent_info = DB::table('dependent_infos')
            ->where('shain_cd', $id)
            ->orderBy('birthday', 'asc')
            ->get();

        $delete_success = 'success';



        return view('/dependent_info')->with([
            'top_url' => $top_url,
            'scroll_top' => $scroll_top,
            'employee' => $employee,
            'dependent_info' => $dependent_info,
            'delete_success' => $delete_success,
        ]);
    }


    public function dependent_info_delete_to_edit(Request $request, $id)
    {

        // トップページに戻るボタン押下時のスクロール位置とトップページURL
        $top_url = '/employee/public';
        $scroll_top = '0';
        // $top_url = $request->top_url;
        // $scroll_top = $request->scroll_top2;

        //employeesテーブルのデータを詳細テーブルで表示していた社員コードの分取得
        $employee = Employee::find($id);

        // 扶養家族の情報を削除
        \DB::table('dependent_infos')
            ->where('id', $request->id)
            ->delete();

        // 扶養家族の情報を取得
        $dependent_info = DB::table('dependent_infos')
            ->where('shain_cd', $id)
            ->orderBy('birthday', 'asc')
            ->get();

        $delete_success = 'success';



        return view('/dependent_info_edit')->with([
            'employee' => $employee,
            'dependent_info' => $dependent_info,
            'top_url' => $top_url,
            'scroll_top' => $scroll_top,
            'delete_success' => $delete_success,
        ]);
    }
}
