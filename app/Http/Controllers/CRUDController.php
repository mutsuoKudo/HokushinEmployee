<?php

namespace App\Http\Controllers;

use App\Employee;
use \DB;

use App\Http\Requests\UpdatePost;
use App\Http\Requests\CreatePost;

use App\Library\BaseClass;

use  App\Http\Controllers\Request;



class CRUDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     //
    // }

    public function add()
    {

        if (isset($_POST['url'])) {
            $post_url = $_POST['url'];
            $scroll_top = $_POST['scroll_top'];
        } else {
            $post_url = 'http://localhost/employee/public/';
            $scroll_top = '0';
        }

        // $post_url = $_POST['url'];
        // $scroll_top = $_POST['scroll_top'];





        return view('/create')->with([
            'post_url' => $post_url,
            'scroll_top' => $scroll_top,


        ]);
    }

    // 新規作成でバリデーションに引っ掛かった時に使用
    public function add2()
    {

        $post_url = '/employee/public/';
        $scroll_top = 0;




        return view('/create')->with([
            'post_url' => $post_url,
            'scroll_top' => $scroll_top,


        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    // public function submit(CreatePost $request)
    public function submit(CreatePost $request)

    {

        // $post_url_create = $_POST['post_url_create'];


        $post_url_create = $request->post_url_create;
        $scroll_top = $request->top_scroll_top;
        

        // dd($post_url_create);
        // var_dump($scroll_top);





        if (isset($request->pic)) {

            // public/post_imagesに　現在年月日時間＿社員コードjpgでストレージに保存する
            $time = date("Ymdhi");

            // クラスのインスタンス化（BaseClaee）
            $class = new BaseClass();



            // 写真の拡張子を取得
            $file_extension =  $request->pic->getClientOriginalExtension();

            // jpgなら、名前を変更して（DBとストレージに）保存する際、拡張子はjpgで
            if ($file_extension == 'jpg' or $file_extension == 'jpeg' or $file_extension == 'JPG' or $file_extension == 'JPEG') {

                // 入力した内容を新規登録
                $class->employee_create($request);

                // 同じ名前が含まれた写真を削除（＝旧データを削除）
                $class->pic_file_delete($request->shain_cd, $request->shain_mei_romaji);

                // ストレージに保存
                $request->pic->storeAs('public/post_images', $time . '_' . $request->shain_cd . '_' . $request->shain_mei_romaji . '.jpg');

                // データベースにpublic/post_imagesのパスを保存する
                $class->pic_file_db_save($request->shain_cd, $time, $request->shain_cd, $request->shain_mei_romaji, 'jpg');


                // pngなら、名前を変更して（DBとストレージに）保存する際、拡張子はpngで
            } elseif ($file_extension == 'png') {

                // 入力した内容を新規登録
                $class->employee_create($request);

                // 同じ名前が含まれた写真を削除（＝旧データを削除）
                $class->pic_file_delete($request->shain_cd, $request->shain_mei_romaji);

                // ストレージに保存
                $request->pic->storeAs('public/post_images', $time . '_' . $request->shain_cd . '_' . $request->shain_mei_romaji . '.png');

                // データベースにpublic/post_imagesのパスを保存する
                $class->pic_file_db_save($request->shain_cd, $time, $request->shain_cd, $request->shain_mei_romaji, 'png');


                // jpgとpng以外だったら、エラーを返して編集画面に戻る
            } else {

                $file_extension_error = 'error';
                $employee = Employee::find($request->shain_cd);

                return view('/create')->with([
                    'file_extension_error' => $file_extension_error,
                    'employee' => $employee,
                    'post_url' => $post_url_create,
                    'scroll_top' => $scroll_top,
                ]);
            }
        } else {
            // クラスのインスタンス化（BaseClaee）
            $class = new BaseClass();
            // 入力した内容を新規登録
            $class->employee_create($request);
        }




        return redirect($post_url_create)->with('create', '新規登録完了!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $employee = Employee::find($id);

        $class = new BaseClass();
        //基準日の計算(入社日に+6か月)　※ XXXX-XX-01　XXXX-XXの部分は個人で計算されます。
        list($kijunbi_year_pre, $kijunbi_month_pre, $kijunbi_year_month_pre) = $class->kijunbi($id);
        // 基準年を抜き出す
        $kijunbi_year = $kijunbi_year_pre;
        // var_dump('基準年:' . $kijunbi_year);

        // 基準月を抜き出す
        $kijunbi_month = $kijunbi_month_pre;
        // // var_dump('基準年:' . $kijunbi_year);

        // 基準年月を抜き出す
        $kijunbi_year_month = $kijunbi_year_month_pre;

        //退職年の計算
        list($taishokubi_year_pre, $taishokubi_month_pre, $taishokubi_year_month_pre, $taishokubi_pre) = $class->retirement_id($id);

        // 退職年を抜き出す
        $taishokubi_year = $taishokubi_year_pre;
        // 退職年月を抜き出す
        $taishokubi_year_month = $taishokubi_year_month_pre;
        // var_dump('退職年:' . $kijunbi_year);

        //入社日の取得

        $nyushabi = DB::table('employees')
            ->select('nyushabi')
            ->where('shain_cd', $id)
            ->first();

        //入社年の抜き出し
        $nyushabi_year = substr($nyushabi->nyushabi, 0, 4);

        //入社月の抜き出し
        $nyushabi_month = substr($nyushabi->nyushabi, 5, 2);

        //入社年月の作成
        $nyushabi_year_month = $nyushabi_year . $nyushabi_month;


        //一番最近のデータの年月(0000-00)を作成(=現在日時になる)
        list($year_month_a1_pre, $year_month_a2_pre, $year_month_a_pre, $year_month_b_pre) = $class->year_month();

        //一番最近のデータの年
        $year_month_a1 = $year_month_a1_pre;
        //一番最近のデータの月
        $year_month_a2 = $year_month_a2_pre;
        //一番最近のデータの年月（000000：文字なし）
        $year_month_b = $year_month_b_pre;


        // $post_url = $_POST['url'];
        // $scroll_top = $_POST['scroll_top'];

        if (isset($_POST['url'])) {
            $post_url = $_POST['url'];
            $scroll_top = $_POST['scroll_top'];
        } else {
            $post_url = 'http://localhost/employee/public/';
            $scroll_top = '0';
        }
        


        return view('/show')->with([
            'employee' => $employee,
            'kijunbi_year' => $kijunbi_year,
            'taishokubi_year_month' => $taishokubi_year_month,
            'taishokubi_year' => $taishokubi_year,
            'nyushabi_year_month' => $nyushabi_year_month,
            'nyushabi_year' => $nyushabi_year,
            'kijunbi_year_month' => $kijunbi_year_month,
            'kijunbi_month' => $kijunbi_month,
            'year_month_a1' => $year_month_a1,
            'year_month_a2' => $year_month_a2,
            'year_month_b' => $year_month_b,
            'post_url' => $post_url,
            'scroll_top' => $scroll_top,

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $employee = Employee::find($id);


        // $top_url = $_POST['top_url'];
        // $scroll_top = $_POST['scroll_top2'];

        

        if (isset($_POST['top_url'])) {
            $top_url = $_POST['top_url'];
            $scroll_top = $_POST['scroll_top2'];
        } else {
            $top_url = 'http://localhost/employee/public/';
            $scroll_top = '0';
        }


        return view('/edit')->with([
            'employee' => $employee,
            'top_url' => $top_url,
            'scroll_top' => $scroll_top,

        ]);
    }

    public function edit2($id)
    {
        //
        $employee = Employee::find($id);


        $top_url = '/employee/public/';
        $scroll_top = 0;



        return view('/edit')->with([
            'employee' => $employee,
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

    public function update(UpdatePost $request, $id)
    // public function update(Request $request, $id)
    {

        $employee = Employee::find($id);

        $employee->shain_cd = $request->input('shain_cd');
        $employee->shain_mei = $request->input('shain_mei');
        $employee->shain_mei_kana = $request->input('shain_mei_kana');
        $employee->shain_mei_romaji = $request->input('shain_mei_romaji');
        $employee->shain_mail = $request->input('shain_mail');
        $employee->gender = $request->input('gender');
        $employee->shain_zip_code = $request->input('shain_zip_code');
        $employee->shain_jyusho = $request->input('shain_jyusho');
        $employee->shain_jyusho_tatemono = $request->input('shain_jyusho_tatemono');
        $employee->shain_birthday = $request->input('shain_birthday');
        $employee->nyushabi = $request->input('nyushabi');
        $employee->seishain_tenkanbi = $request->input('seishain_tenkanbi');
        $employee->tensekibi = $request->input('tensekibi');
        $employee->taishokubi = $request->input('taishokubi');
        $employee->shain_keitai = $request->input('shain_keitai');
        $employee->shain_tel = $request->input('shain_tel');
        $employee->koyohoken_bango = $request->input('koyohoken_bango');
        $employee->shakaihoken_bango = $request->input('shakaihoken_bango');
        $employee->kisonenkin_bango = $request->input('kisonenkin_bango');
        $employee->monthly_saraly = $request->input('monthly_saraly');
        $employee->department = $request->input('department');
        $employee->name_card = $request->input('name_card');
        $employee->id_card = $request->input('id_card');
        $employee->fuyo_kazoku = $request->input('fuyo_kazoku');
        $employee->test = $request->input('test');
        $employee->remarks = $request->input('remarks');
        $employee->save();


        $top_url_edit = $_POST['top_url_edit'];

        // 画像はjpgとpngだけの対応で。
        if (isset($request->pic)) {

            // public/post_imagesに　現在年月日時間＿社員コードjpgでストレージに保存する
            $time = date("Ymdhi");

            // クラスのインスタンス化（BaseClaee）
            $class = new BaseClass();

            // 写真の拡張子を取得
            $file_extension =  $request->pic->getClientOriginalExtension();

            // jpgなら、名前を変更して（DBとストレージに）保存する際、拡張子はjpgで
            if ($file_extension == 'jpg' or $file_extension == 'jpeg' or $file_extension == 'JPG' or $file_extension == 'JPEG') {

                // 同じ名前が含まれた写真を削除（＝旧データを削除）
                $class->pic_file_delete($employee->shain_cd, $employee->shain_mei_romaji);

                // ストレージに保存
                $request->pic->storeAs('public/post_images', $time . '_' . $employee->shain_cd . '_' . $employee->shain_mei_romaji . '.jpg');

                // データベースにpublic/post_imagesのパスを保存する
                $class->pic_file_db_save($id, $time, $employee->shain_cd, $employee->shain_mei_romaji, 'jpg');


                // pngなら、名前を変更して（DBとストレージに）保存する際、拡張子はpngで
            } elseif ($file_extension == 'png') {

                // 同じ名前が含まれた写真を削除（＝旧データを削除）
                $class->pic_file_delete($employee->shain_cd, $employee->shain_mei_romaji);

                // ストレージに保存
                $request->pic->storeAs('public/post_images', $time . '_' . $employee->shain_cd . '_' . $employee->shain_mei_romaji . '.png');

                // データベースにpublic/post_imagesのパスを保存する
                $class->pic_file_db_save($id, $time, $employee->shain_cd, $employee->shain_mei_romaji, 'png');


                // jpgとpng以外だったら、エラーを返して編集画面に戻る
            } else {


                $file_extension_error = 'error';
                $employee = Employee::find($id);

                $top_url = $_POST['top_url_edit'];
                $scroll_top = $_POST['top_scroll_top'];

                return view('/edit')->with([
                    'file_extension_error' => $file_extension_error,
                    'employee' => $employee,
                    'top_url' => $top_url,
                    'scroll_top' => $scroll_top,
                ]);
            }
        }

        return redirect($top_url_edit)->with('status', '更新完了!');
    }


    // 編集画面に表示される写真の削除ボタンの処理
    public function pic_delete($id)
    {

        // データベースのpicカラムをnullにする
        \DB::table('employees')
            ->where('shain_cd', $id)
            ->update([
                'pic' => null
            ]);

        $employee = Employee::find($id);


        // 写真が入っているフォルダのファイルをすべて取得
        $pic_file = glob('C:\xampp\htdocs\employee\public\storage\post_images\*');


        // ファイル名に社員コードが入っているものがあれば削除
        foreach ($pic_file as $p_file_id) {
            if (strpos($p_file_id, $employee->shain_mei_romaji)) {
                // if (strpos($p_file_id, $id)) {
                unlink($p_file_id);
            }
        }

        // $top_url = $_POST['top_url_edit'];
        // $scroll_top = 10;

        if (isset($_POST['top_url'])) {
            $top_url = $_POST['top_url'];
            $scroll_top = $_POST['scroll_top'];
        } else {
            $top_url = 'http://localhost/employee/public/';
            $scroll_top = '0';
        }


        return view('/edit')->with([
            'employee' => $employee,
            'top_url' => $top_url,
            'scroll_top' => $scroll_top,

        ]);
    }
}
