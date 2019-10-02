<?php

use App\Employee;
use App\Holiday;
use Illuminate\Http\Request;

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
Route::group(['middleware' => ['web']], function(){
// Route::group(['middlewareGroups' => ['web']], function () {

    //全ての表示
    Route::get('/', ['middleware' => 'auth', function () {
        //employeesテーブルのデータをemployees変数に入れる
        $employees = Employee::all();
        $title = "ALL";
        //view (employeesテンプレ)に渡す
        return view('employees', [
            'employees' => $employees,
            'title' => $title
        ]);
    }]);


    //削除するところ
    Route::delete('/delete/{employee}', function (Employee $employee) {
        $employee->delete();

        return redirect('/')->with('delete', '削除完了!');
    });

    // 詳細ボタンクリック→詳細画面表示
    Route::get('/show/{employee}', 'CRUDController@show');

    //編集ボタンクリック→編集画面表示
    Route::get('/edit/{employee}', 'CRUDController@edit');

    //有給取得日数明細ボタンクリック→有給明細
    // Route::get('/holiday/{employee}', 'HolidayController@holiday');
    Route::post('/holiday/{employee}', 'HolidayController@holiday');

    //更新ボタンクリック→更新完了の場合、トップページにリダイレクト
    Route::patch('/update/{id}', 'CRUDController@update');

    //新規登録ボタンクリック→新規登録画面の表示
    Route::get('/add', 'CRUDController@add');

    //新規登録画面の更新ボタンをクリック→エラーなしの場合、トップページにリダイレクト
    Route::post('/submit', 'CRUDController@submit');



    //ALLボタンクリック→在職者全員のテーブル表示
    Route::get('/all', 'ButtonController@all');


    //部門別ボタンクリック→代表取締役ボタンクリック→代表取締役のテーブル表示
    Route::get('/department1', 'ButtonController@department1');
    //部門別ボタンクリック→管理部ボタンクリック→管理部のテーブル表示
    Route::get('/department2', 'ButtonController@department2');
    //部門別ボタンクリック→営業部ボタンクリック→営業部のテーブル表示
    Route::get('/department3', 'ButtonController@department3');
    //部門別ボタンクリック→システム開発部ボタンクリック→システム開発部のテーブル表示
    Route::get('/department4', 'ButtonController@department4');
    //部門別ボタンクリック→研修生ボタンクリック→研修生のテーブル表示
    Route::get('/department5', 'ButtonController@department5');

    //入社年別ボタンクリック→2013年ボタンクリック→2013年入社のテーブル表示
    Route::get('/nyushabi2013', 'ButtonController@nyushabi2013');
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

    //退職者ボタンクリック→退職者のテーブル表示
    Route::get('/retirement', 'ButtonController@retirement');


    //平均年齢（ALL）ボタンクリック→平均年齢（ALL）表示
    Route::get('/all_avg', 'ButtonController@all_avg');
    //平均年齢（部門別）ボタンクリック→平均年齢（部門別）表示
    Route::get('/department_avg', 'ButtonController@department_avg');
    //平均年齢（男女別）ボタンクリック→平均年齢（男女別）表示
    Route::get('/gender_avg', 'ButtonController@gender_avg');

    //人数（ALL）ボタンクリック→人数（ALL）表示
    Route::get('/all_count', 'ButtonController@all_count');
    //人数（部門別）ボタンクリック→人数（部門別）表示
    Route::get('/department_count', 'ButtonController@department_count');
    //人数（男女別）ボタンクリック→人数（男女別）表示
    Route::get('/gender_count', 'ButtonController@gender_count');
    //人数（年代別）ボタンクリック→人数（年代別）表示
    Route::get('/age_count', 'ButtonController@age_count');


    //shainテーブルアップデートボタンクリック→shainテーブルのアップデート
    // Route::get('/shain_update', 'ButtonController@shain_update');

    //
    Route::auth();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

