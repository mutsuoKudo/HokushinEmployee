<?php

use App\Dependent_info;
use App\Employee;
use App\Holiday;
use Illuminate\Http\Request;

use App\Library\BaseClass;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// //ルート全体の定義
// Route::get('/', function () {
//     return view('employees');
// });


//インターフェースがWebのとき
Route::group(['middleware' => ['web']], function () {
    // Route::group(['middlewareGroups' => ['web']], function () {

    //全ての表示
    Route::get('/', ['middleware' => 'auth', function () {
        //employeesテーブルのデータをemployees変数に入れる
        // クラスのインスタンス化
        $class = new BaseClass();

        // 社員情報取得
        $employees = $class->all();

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

        $test = db::table('employees')
            ->select(db::raw('(CAST( pic AS CHAR( 1000 ) CHARACTER SET utf8 )) AS img'))
            ->whereNull('taishokubi')
            ->where('shain_cd', '00')
            ->get();

        // var_dump($select_nyusha_year[0]->nyushanen);   
        // var_dump($test[0]->img);




        //view (employeesテンプレ)に渡す
        return view('employees', [
            'employees' => $employees,
            'title' => $title,
            'select_nyusha_year' => $select_nyusha_year,
            'select_taishoku_year' => $select_taishoku_year,
            'test' => $test,
        ]);
    }]);


    //削除するところ
    Route::delete('/delete/{employee}', function (Employee $employee) {
        $employee->delete();

        // クラスのインスタンス化（BaseClaee）
        $class = new BaseClass();

        // 同じ名前が含まれた写真を削除（＝旧データを削除）
        $class->pic_file_delete($employee->shain_cd, $employee->shain_mei_romaji);

        return redirect('/')->with('delete', '削除完了!');
    });


    // 詳細ボタンクリック→詳細画面表示
    // Route::get('/show/{employee}', 'CRUDController@show');
    Route::post('/show/{employee}', 'CRUDController@show');

    //編集ボタンクリック→編集画面表示
    Route::post('/edit/{employee}', 'CRUDController@edit');
    Route::get('/edit2/{employee}', 'CRUDController@edit2');

    //扶養家族編集ボタンクリック→扶養家族編集画面表示
    Route::post('/dependent_info_edit/{employee}', 'DependentController@dependent_info_edit');
    
    //扶養家族編集画面で更新ボタンクリック→詳細画面に戻る？
    Route::patch('/dependent_info_update/{dependent}', 'DependentController@dependent_info_update');
    
    //有給取得日数明細ボタンクリック→有給明細
    // Route::get('/holiday/{employee}', 'HolidayController@holiday');
    Route::post('/holiday/{employee}', 'HolidayController@holiday');

    //時間外労働明細ボタンクリック→有給明細
    // Route::get('/holiday/{employee}', 'HolidayController@holiday');
    Route::post('/over_time_working/{employee}', 'OverTimeWorkingController@over_time');

    //扶養家族明細ボタンクリック→扶養家族明細
    Route::post('/dependent_info/{employee}', 'DependentController@dependent_info');

    //扶養家族明細画面で追加ボタンクリック→扶養家族追加画面表示
    Route::post('/dependent_info_add/{employee}', 'DependentController@dependent_info_add');

    //扶養家族明細画面で削除ボタンクリック→選択データを削除
    Route::post('/dependent_info_delete/{employee}', 'DependentController@dependent_info_delete');

    //扶養家族編集画面で削除ボタンクリック→選択データを削除
    Route::post('/dependent_info_delete_to_edit/{employee}', 'DependentController@dependent_info_delete_to_edit');

    //扶養家族追加画面で登録ボタンクリック→扶養家族追加画面表示
    Route::post('/dependent_info_submit/{employee}', 'DependentController@dependent_info_submit');
    

    //更新ボタンクリック→更新完了の場合、トップページにリダイレクト
    Route::patch('/update/{id}', 'CRUDController@update');

    //写真削除ボタンクリック→削除完了の場合、トップページにリダイレクト
    Route::post('/pic_delete/{id}', 'CRUDController@pic_delete');


    //新規登録ボタンクリック→新規登録画面の表示
    // Route::get('/add', 'CRUDController@add');
    // Route::match(array('GET', 'POST'), '/add', 'CRUDController@add');
    Route::post('/add', 'CRUDController@add');
    Route::get('/add2', 'CRUDController@add2');

    //新規登録画面の更新ボタンをクリック→エラーなしの場合、トップページにリダイレクト
    // Route::post('/submit', 'CRUDController@submit')->name('/add');
    Route::post('/submit', 'CRUDController@submit');



    //在籍者ボタンクリック→在職者全員のテーブル表示
    Route::get('/all', 'ButtonController@all');


    //部門別ボタンクリック→代表取締役ボタンクリック→代表取締役のテーブル表示
    Route::get('/department1', 'ButtonController@department1');
    //部門別ボタンクリック→管理部ボタンクリック→管理部のテーブル表示
    Route::get('/department2', 'ButtonController@department2');
    //部門別ボタンクリック→営業部ボタンクリック→営業部のテーブル表示
    Route::get('/department3', 'ButtonController@department3');
    //部門別ボタンクリック→システム開発部ボタンクリック→システム開発部のテーブル表示
    Route::get('/department4', 'ButtonController@department4');


    //入社年別ボタンクリック→2007年ボタンクリック→2007年入社のテーブル表示
    Route::get('/nyushabi2007', 'ButtonController@nyushabi2007');
    //入社年別ボタンクリック→2014年ボタンクリック→2014年入社のテーブル表示
    Route::get('/nyushabi2014', 'ButtonController@nyushabi2014');
    //入社年別ボタンクリック→2015年ボタンクリック→2015年入社のテーブル表示
    Route::get('/nyushabi2015', 'ButtonController@nyushabi2015');
    //入社年別ボタンクリック→2016年ボタンクリック→2016年入社のテーブル表示
    Route::get('/nyushabi2016', 'ButtonController@nyushabi2016');
    //入社年別ボタンクリック→2017年ボタンクリック→2017年入社のテーブル表示
    Route::get('/nyushabi2017', 'ButtonController@nyushabi2017');
    //入社年別ボタンクリック→2018年ボタンクリック→2018年入社のテーブル表示
    Route::get('/nyushabi2018', 'ButtonController@nyushabi2018');
    //入社年別ボタンクリック→2019年ボタンクリック→2019年入社のテーブル表示
    Route::get('/nyushabi2019', 'ButtonController@nyushabi2019');
    //入社年別ボタンクリック→2020年ボタンクリック→2020年入社のテーブル表示
    Route::get('/nyushabi2020', 'ButtonController@nyushabi2020');

    //年代別ボタンクリック→20代ボタンクリック→20代のテーブル表示
    Route::get('/age20', 'ButtonController@age20');
    //年代別ボタンクリック→30代ボタンクリック→30代のテーブル表示
    Route::get('/age30', 'ButtonController@age30');
    //年代別ボタンクリック→40代ボタンクリック→40代のテーブル表示
    Route::get('/age40', 'ButtonController@age40');
    //年代別ボタンクリック→50代ボタンクリック→50代のテーブル表示
    Route::get('/age50', 'ButtonController@age50');
    //年代別ボタンクリック→60代ボタンクリック→60代のテーブル表示
    Route::get('/age60', 'ButtonController@age60');
    //年代別ボタンクリック→その他ボタンクリック→その他のテーブル表示
    Route::get('/age_other', 'ButtonController@age_other');

    //有給基準月別ボタンクリック→1月ボタンクリック→有給基準月1月の人のテーブル表示
    Route::get('/kijun_month01', 'ButtonController@kijun_month01');
    //有給基準月別ボタンクリック→2月ボタンクリック→有給基準月2月の人のテーブル表示
    Route::get('/kijun_month02', 'ButtonController@kijun_month02');
    //有給基準月別ボタンクリック→3月ボタンクリック→有給基準月3月の人のテーブル表示
    Route::get('/kijun_month03', 'ButtonController@kijun_month03');
    //有給基準月別ボタンクリック→4月ボタンクリック→有給基準月4月の人のテーブル表示
    Route::get('/kijun_month04', 'ButtonController@kijun_month04');
    //有給基準月別ボタンクリック→5月ボタンクリック→有給基準月5月の人のテーブル表示
    Route::get('/kijun_month05', 'ButtonController@kijun_month05');
    //有給基準月別ボタンクリック→6月ボタンクリック→有給基準月6月の人のテーブル表示
    Route::get('/kijun_month06', 'ButtonController@kijun_month06');
    //有給基準月別ボタンクリック→7月ボタンクリック→有給基準月7月の人のテーブル表示
    Route::get('/kijun_month07', 'ButtonController@kijun_month07');
    //有給基準月別ボタンクリック→8月ボタンクリック→有給基準月8月の人のテーブル表示
    Route::get('/kijun_month08', 'ButtonController@kijun_month08');
    //有給基準月別ボタンクリック→9月ボタンクリック→有給基準月9月の人のテーブル表示
    Route::get('/kijun_month09', 'ButtonController@kijun_month09');
    //有給基準月別ボタンクリック→10月ボタンクリック→有給基準月10月の人のテーブル表示
    Route::get('/kijun_month10', 'ButtonController@kijun_month10');
    //有給基準月別ボタンクリック→11月ボタンクリック→有給基準月11月の人のテーブル表示
    Route::get('/kijun_month11', 'ButtonController@kijun_month11');
    //有給基準月別ボタンクリック→12月ボタンクリック→有給基準月12月の人のテーブル表示
    Route::get('/kijun_month12', 'ButtonController@kijun_month12');

    //退職者ボタンクリック→退職者のテーブル表示
    Route::get('/retirement', 'ButtonController@retirement');

    //退社年別ボタンクリック→2016年ボタンクリック→2016年退社のテーブル表示
    Route::get('/taishokubi2016', 'ButtonController@taishokubi2016');
    //退社年別ボタンクリック→2017年ボタンクリック→2017年退社のテーブル表示
    Route::get('/taishokubi2017', 'ButtonController@taishokubi2017');
    //退社年別ボタンクリック→2018年ボタンクリック→2018年退社のテーブル表示
    Route::get('/taishokubi2018', 'ButtonController@taishokubi2018');
    //退社年別ボタンクリック→2019年ボタンクリック→2019年退社のテーブル表示
    Route::get('/taishokubi2019', 'ButtonController@taishokubi2019');
    //入社退社年別ボタンクリック→2020年ボタンクリック→2020年入社のテーブル表示
    Route::get('/taishokubi2020', 'ButtonController@taishokubi2020');

    //平均年齢（在籍者）ボタンクリック→平均年齢（在籍者）表示
    Route::get('/all_avg', 'ButtonController@all_avg');

    //平均年齢（部門別）ボタンクリック→平均年齢（部門別）表示
    Route::get('/department_avg', 'ButtonController@department_avg');
    //平均年齢（男女別）ボタンクリック→平均年齢（男女別）表示
    Route::get('/gender_avg', 'ButtonController@gender_avg');

    //人数（在籍者）ボタンクリック→人数（在籍者）表示
    Route::get('/all_count', 'ButtonController@all_count');
    //人数（部門別）ボタンクリック→人数（部門別）表示
    Route::get('/department_count', 'ButtonController@department_count');
    //人数（男女別）ボタンクリック→人数（男女別）表示
    Route::get('/gender_count', 'ButtonController@gender_count');
    //人数（年代別）ボタンクリック→人数（年代別）表示
    Route::get('/age_count', 'ButtonController@age_count');


    //未消化アラート一覧ボタンクリック→有給未消化アラート表示
    Route::get('/mishouka', 'AlertController@mishouka');
    //残数僅少アラート一覧ボタンクリック→残数僅少アラート表示
    Route::get('/zansu_kinshou', 'AlertController@zansu_kinshou');
    //時間外労働アラート一覧ボタンクリック→時間外労働アラート表示
    Route::get('/overtime_working_alert', 'AlertController@overtime_working_alert');
    //時間外労働ランキングボタンクリック→時間外労働ランキング表示
    Route::get('/overtime_working_ranking', 'OverTimeWorkingController@over_time_ranking');



    // Route::post('/back', function () {
    //     return back()->withInput();
    // });

    // Route::get('/employee_doc/employee_doc.html', function () {
    //     return \File::get(public_path() . '/employee_doc/employee_doc.html');
    //     // return \File::get(public_path() . '/employee_doc/employee_doc.html');
    // });


    //shainテーブルアップデートボタンクリック→shainテーブルのアップデート
    // Route::get('/shain_update', 'ButtonController@shain_update');


    Route::auth();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
