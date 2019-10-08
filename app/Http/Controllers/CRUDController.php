<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use \DB;

use App\Http\Requests\UpdatePost;
use App\Http\Requests\CreatePost;

class CRUDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function add()
    {
        return view('/create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function submit(CreatePost $request)
    {

        $employee = new Employee();
        $employee->shain_cd = $request->shain_cd;
        $employee->shain_mei = $request->shain_mei;
        $employee->shain_mei_kana = $request->shain_mei_kana;
        $employee->shain_mei_romaji = $request->shain_mei_romaji;
        $employee->shain_mail = $request->shain_mail;
        $employee->gender = $request->gender;
        $employee->shain_zip_code = $request->shain_zip_code;
        $employee->shain_jyusho = $request->shain_jyusho;
        $employee->shain_jyusho_tatemono = $request->shain_jyusho_tatemono;
        $employee->shain_birthday = $request->shain_birthday;
        $employee->nyushabi = $request->nyushabi;
        $employee->seishain_tenkanbi = $request->seishain_tenkanbi;
        $employee->tensekibi = $request->tensekibi;
        $employee->taishokubi = $request->taishokubi;
        $employee->shain_keitai = $request->shain_keitai;
        $employee->shain_tel = $request->shain_tel;
        $employee->koyohoken_bango = $request->koyohoken_bango;
        $employee->shakaihoken_bango = $request->shakaihoken_bango;
        $employee->kisonenkin_bango = $request->kisonenkin_bango;
        $employee->monthly_saraly = $request->monthly_saraly;
        $employee->department = $request->department;
        $employee->name_card = $request->name_card;
        $employee->id_card = $request->id_card;
        $employee->fuyo_kazoku = $request->fuyo_kazoku;
        $employee->test = $request->test;
        $employee->remarks = $request->remarks;

        $employee->save();

        return redirect('/')->with('create', '新規登録完了!');
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

        //基準日の計算(入社日に+6か月)　※ XXXX-XX-01　XXXX-XXの部分は個人で計算されます。
        $kijunbi = DB::table('employees')
            ->select(db::raw('ADDDATE( DATE_FORMAT(nyushabi, "%Y-%m-01") , INTERVAL +6 MONTH) AS "kijunbi"'))
            ->where('shain_cd', $id)
            ->first();
        // ->toSQL();

        // 基準年を抜き出す
        $kijunbi_year = substr($kijunbi->kijunbi, 0, 4);
        var_dump('基準年:' . $kijunbi_year);
        
        //退職年の計算
        $taishokubi = DB::table('employees')
        ->select('taishokubi')
        ->where('shain_cd', $id)
        ->first();
        
        // 退職年を抜き出す
        $taishokubi_year = substr($taishokubi->taishokubi, 0, 4);
        var_dump('退職年:' . $kijunbi_year);

        return view('/show')->with([
            'employee' => $employee,
            'kijunbi_year' => $kijunbi_year,
            'taishokubi_year' => $taishokubi_year,

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
        return view('/edit')->with('employee', $employee);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(UpdatePost $request, $id)
    {
        //
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

        return redirect('/')->with('status', 'UPDATE完了!');
    }
}
