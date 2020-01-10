@extends('layouts.app')

@section('content')

<!-- 詳細画面 -->

<div class="container">
    <div class="col-12">
        <div class="panel panel-default w-100">

            <div class="panel-body">
                @include('common.errors')

                <div class="mt-4 text-center">

                    <!-- トップ画面から送られてきたトップ画面のURLとスクロール位置に戻る -->
                    <form action="{{$post_url}}" method="GET">
                        {{ csrf_field() }}
                        <input type="hidden" name="post_scroll_top" value="{{$scroll_top}}">
                        <button type="submit" class="btn btn-success btn-lg" style="margin:20px;">トップに戻る</button>
                    </form>

                    <form action="/employee/public/edit/{{$employee->shain_cd}}" method="POST">
                        {{ csrf_field() }}
                        <!-- トップ画面から送られてきたトップ画面のURLとスクロール位置を送る -->
                        <input type="hidden" name="top_url" value={{$post_url}}>
                        <input type="hidden" name="scroll_top2" value="{{$scroll_top}}" class="st">
                        <button type="submit" class="btn btn-primary btn-lg mt-3" id="show_top_edit_button">編集</button>
                    </form>
                </div>
            </div>
        </div>







        <div class="panel panel-default mt-5 w-100">
            <div class="panel-heading font-weight-bold text-center" style="font-size:40px; background-color:#F7F7EE;">
                詳細表示
            </div>



            <div class="panel-body">



                <div class="float-right mb-5">
                    <div class="float-right">

                        <!-- 有給取得日明細 -->
                        <div class="float-right mr-5">
                            @if(is_null($employee->nyushabi))
                            <select name="year" style="background-color:lightblue">
                                <option selected>入社日が登録されていません</option>
                            </select>
                            <input type="submit" class="btn btn-info m-2" value="有給取得日明細" disabled>
                            @else

                            <form action="/employee/public/holiday/{{$employee->shain_cd}}" method="POST" class="mb-0">
                                {{ csrf_field() }}

                                <select name='year' style="background-color:lightblue">
                                    <?php
                                    //  DBのholidayテーブルに入力されている最新データ月より入社月が大きいか、同じのとき　かつ　最新データ年より入社年が大きいとき＝初回基準月未満
                                    // if ($year_month_b >= $nyushabi_year_month and $year_month_b < $kijunbi_year_month) {
                                        if (((int)$year_month_a1 == (int)$kijunbi_year and (int)$year_month_a2 < (int)$kijunbi_month) or ((int)$year_month_a1 < (int)$kijunbi_year)){
                                        echo '<option value="00" selected >初回基準月未満</option>';
                                    } else {

                                        //退職日が入力されている場合・・・
                                        if (isset($employee->taishokubi)) {
                                            // 初回基準日未満で退職した人(退職日より基準日のほうが大きい))
                                            if ($taishokubi_year_month < $kijunbi_year_month) {
                                                // echo '<option value="01" selected>', $taishokubi_year, '年度</option>';
                                                echo '<option value="00" selected >初回基準月未満</option>';
                                            } else {

                                                //退職年までの選択
                                                if ($taishokubi_year - 1 < $kijunbi_year) {
                                                    for ($i = $kijunbi_year; $i <= $kijunbi_year; $i++) {

                                                        echo '<option value="', $i, '" selected >', $i, '年度</option>';
                                                    }
                                                } else {
                                                    if ($taishokubi_year - $nyushabi_year == 2) {
                                                        for ($i = $kijunbi_year; $i <= $taishokubi_year; $i++) {
                                                            if ($i == $taishokubi_year) {
                                                                //退職した年にselected
                                                                echo '<option value="', $i, '" selected >', $i, '年度</option>';
                                                            } elseif ($i < $taishokubi_year) {
                                                                echo '<option value="', $i, '">', $i, '年度</option>';
                                                            }
                                                            echo 'ERROR';
                                                        }
                                                    } else {
                                                        for ($i = $kijunbi_year; $i <= $taishokubi_year - 1; $i++) {
                                                            if ($i == $taishokubi_year - 1) {
                                                                //退職した年にselected
                                                                echo '<option value="', $i, '" selected >', $i, '年度</option>';
                                                            } elseif ($i < $taishokubi_year) {
                                                                echo '<option value="', $i, '">', $i, '年度</option>';
                                                            }
                                                            echo 'ERROR';
                                                        }
                                                    }
                                                }
                                            }


                                            //退職日が入力されていない場合・・・
                                        } else {
                                            //DBのholidayテーブルに入力されている最新データ年月が基準年月に達していない場合　＝最新データ年未満
                                            if ($year_month_a2 < $kijunbi_month) {
                                                //基準日からDBのholidayテーブルに入力されている最新データ年まで(最新データ年は含まない)
                                                for ($i = $kijunbi_year; $i < $year_month_a1; $i++) {
                                                    if ($i == $year_month_a1 - 1) {
                                                        //最新データ年にselected
                                                        echo '<option value="', $i, '" selected >', $i, '年度</option>';
                                                    } elseif ($i < $year_month_a1) {
                                                        echo '<option value="', $i, '">', $i, '年度</option>';
                                                    }
                                                }
                                            } else {

                                                //基準日からDBのholidayテーブルに入力されている最新データ年まで（最新データ年は含む）
                                                for ($i = $kijunbi_year; $i <= $year_month_a1; $i++) {
                                                    if ($i == $year_month_a1) {
                                                        //最新データ年にselected
                                                        echo '<option value="', $i, '" selected >', $i, '年度</option>';
                                                    } elseif ($i < $year_month_a1) {
                                                        echo '<option value="', $i, '">', $i, '年度</option>';
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    ?>
                                </select>
                                <!-- トップのURLとスクロール位置を次のページに送る -->
                                <input type="hidden" name="top_url" value={{$post_url}}>
                                <input type="hidden" name="scroll_top2" value="{{$scroll_top}}" class="st">
                                <input type="submit" class="btn btn-info m-2" value="有給取得日明細">
                            </form>
                            @endif
                        </div>


                        <!-- 時間外労働明細 -->
                        <!-- 入社日が入力されていない場合、退職日が入力されている場合は明細の表示ができません。
                    時間外労働テーブルに一つもデータがない場合は、休日労働のテーブルを参照します。休日労働のテーブルにもデータが一つもない場合は明細の表示ができません-->
                        @if(is_null($employee->nyushabi))
                        <div class="mr-5">
                            <select name="year_month" style="background-color:lightgoldenrodyellow">
                                <option selected>入社日が登録されていません</option>
                            </select>
                            <input type="submit" class="btn  btn-success m-2" value="時間外労働明細" disabled>
                        </div>
                        @elseif(isset($employee->taishokubi))
                        <div class="mr-5">
                            <select name="year_month" style="background-color:lightgoldenrodyellow">
                                <option selected>退職者は選択できません</option>
                            </select>
                            <input type="submit" class="btn  btn-success m-2" value="時間外労働明細" disabled>
                        </div>
                        @elseif($overtime_working_latest_year == 0 and $overtime_working_latest_month == 0)
                        <div class="mr-5">
                            <select name="year_month" style="background-color:lightgoldenrodyellow">
                                <option selected>時間外労働のデータがありません</option>
                            </select>
                            <input type="submit" class="btn  btn-success m-2" value="時間外労働明細" disabled>
                        </div>
                        @else

                        <div class="mr-5">
                            <form action="/employee/public/over_time_working/{{$employee->shain_cd}}" method="POST">
                                {{ csrf_field() }}
                                <select name='year' style="background-color:lightgoldenrodyellow">
                                    <?php
                                    //基準日からDBの時間外労働テーブルに入力されている最新データ年まで（最新データ年は含む）
                                    for ($i = 2019; $i <= $overtime_working_latest_year; $i++) {
                                        // for ($i = 2019; $i <= 2020; $i++) {
                                        if ($i == $overtime_working_latest_year) {
                                            //最新データ年にselected
                                            echo '<option value="', $i, '" selected >', $i, '年</option>';
                                        } else {
                                            echo '<option value="', $i, '">', $i, '年</option>';
                                        }
                                    }
                                    ?>
                                </select>

                                <select name='month' style="background-color:lightgoldenrodyellow">
                                    <?php
                                    //基準日からDBの時間外労働テーブルに入力されている最新データ月まで（最新データ月は含む）
                                    for ($i = 1; $i <= 12; $i++) {
                                        if ($i == $overtime_working_latest_month) {
                                            //最新データ年にselected
                                            echo '<option value="', $i, '" selected >', $i, '月</option>';
                                        } else {
                                            echo '<option value="', $i, '">', $i, '月</option>';
                                        }
                                    }
                                    ?>
                                </select>

                                <!-- トップのURLとスクロール位置を次のページに送る -->
                                <input type="hidden" name="top_url" value={{$post_url}}>
                                <input type="hidden" name="scroll_top2" value="{{$scroll_top}}" class="st">
                                <input type="submit" class="btn  btn-success m-2" value="時間外労働明細">
                            </form>
                        </div>
                        @endif
                        <p style="font-size:13px">※時間外労働の上限規制適用は2019年4月～ </p>

                        <!-- 扶養家族明細 -->
                        <!-- 扶養家族欄が1になっている社員のみ表示されます。 -->
                        @if($employee->fuyo_kazoku !== 0)
                        <form action="/employee/public/dependent_info/{{$employee->shain_cd}}" method="POST" class="float-right mr-5">
                            {{ csrf_field() }}
                            <!-- トップのURLとスクロール位置を次のページに送る -->
                            <input type="hidden" name="top_url" value={{$post_url}}>
                            <input type="hidden" name="scroll_top2" value="{{$scroll_top}}" class="st">
                            <input type="submit" class="btn btn-warning m-2" value="扶養家族明細">
                        </form>

                        <!-- ※扶養家族明細ボタンを表示するときは写真のトップにマージン16％ -->
                    </div>
                    <div class="text-center mb-3" style="margin-top:16%;">
                        @else
                        <!-- ※扶養家族明細ボタンを表示しないときは写真のトップにマージン12％ -->
                    </div>
                    <div class="text-center mb-3" style="margin-top:13%;">
                        @endif

                        @if ($employee->pic)
                        <p>使用中の写真</p>
                        <img src="../storage/post_images/{{ $employee->pic }}" style="width: 20%;">
                        @else
                        <p>使用中の写真はありません</p>
                        <img src="{{ asset('image/nodata.jpg') }}" alt="" width="20%">
                        @endif
                    </div>

                    <table class="table table-striped task-table" style="table-layout: fixed; width:100%;">
                        <thead>

                        </thead>
                        <tbody>
                            <tr>
                                <th>社員コード</th>
                                <td>{{ $employee->shain_cd }}</td>
                            </tr>
                            <tr>
                                <th>社員名</th>
                                <td>{{ $employee->shain_mei }}</td>
                            </tr>
                            <tr>
                                <th>社員名（かな）</th>
                                <td>{{ $employee->shain_mei_kana }}</td>
                            </tr>
                            <tr>
                                <th>社員名（ローマ字）</th>
                                <td>{{ $employee->shain_mei_romaji }}</td>
                            </tr>
                            <tr>
                                <th>メールアドレス</th>
                                <td>{{ $employee->shain_mail }}</td>
                            </tr>
                            <tr>
                                <th>性別</th>
                                <td>{{ $employee->gender }}</td>
                            </tr>
                            <tr>
                                <th>郵便番号</th>
                                <td>{{ $employee->shain_zip_code }}</td>
                            </tr>
                            <tr>
                                <th>住所</th>
                                <td>{{ $employee->shain_jyusho }}</td>
                            </tr>
                            <tr>
                                <th>住所（建物）</th>
                                <td>{{ $employee->shain_jyusho_tatemono }}</td>
                            </tr>
                            <tr>
                                <th>誕生日</th>
                                <td>{{ $employee->shain_birthday }}</td>
                            </tr>
                            <tr>
                                <th>入社日</th>
                                <td>{{ $employee->nyushabi }}</td>
                            </tr>
                            <tr>
                                <th>正社員転換日</th>
                                <td>{{ $employee->seishain_tenkanbi }}</td>
                            </tr>
                            <tr>
                                <th>転籍日</th>
                                <td>{{ $employee->tensekibi }}</td>
                            </tr>
                            <tr>
                                <th>退職日</th>
                                <td>{{ $employee->taishokubi }}</td>
                            </tr>
                            <tr>
                                <th>社員携帯</th>
                                <td>{{ $employee->shain_keitai }}</td>
                            </tr>
                            <tr>
                                <th>電話番号</th>
                                <td>{{ $employee->shain_tel }}</td>
                            </tr>
                            <tr>
                                <th>雇用保険番号</th>
                                <td>{{ $employee->koyohoken_bango }}</td>
                            </tr>
                            <tr>
                                <th>社会保険番号</th>
                                <td>{{ $employee->shakaihoken_bango }}</td>
                            </tr>
                            <tr>
                                <th>基礎年金番号</th>
                                <td>{{ $employee->kisonenkin_bango }}</td>
                            </tr>
                            <tr>
                                <th>月給</th>
                                <td>{{ $employee->monthly_saraly }}</td>
                            </tr>
                            <tr>
                                <th>部門</th>
                                <td>{{ $employee->department }}</td>
                            </tr>
                            <tr>
                                <th>名刺</th>
                                <td>{{ $employee->name_card }}</td>
                            </tr>
                            <tr>
                                <th>IDカード</th>
                                <td>{{ $employee->id_card }}</td>
                            </tr>
                            <tr>
                                <th>扶養家族</th>
                                <td>{{ $employee->fuyo_kazoku }}</td>
                            </tr>
                            <tr>
                                <th>入社試験点数</th>
                                <td>{{ $employee->test }}</td>
                            </tr>
                            <tr>
                                <th>備考</th>
                                <td>{{ $employee->remarks }}</td>
                            </tr>

                        </tbody>
                    </table>

                </div>

                <div class="mt-5 text-center mb-5">
                    <!-- トップ画面から送られてきたトップ画面のURLとスクロール位置に戻る -->
                    <form action="{{$post_url}}" method="GET">
                        {{ csrf_field() }}
                        <input type="hidden" name="post_scroll_top" value="{{$scroll_top}}">
                        <button type="submit" class="btn btn-success btn-lg" style="margin:20px;">トップに戻る</button>
                    </form>

                    <form action="/employee/public/edit/{{$employee->shain_cd}}" method="POST">
                        {{ csrf_field() }}
                        <!-- トップ画面から送られてきたトップ画面のURLとスクロール位置を送る -->
                        <input type="hidden" name="top_url" value={{$post_url}}>
                        <input type="hidden" name="scroll_top2" value="{{$scroll_top}}" class="st">
                        <button type="submit" class="btn btn-primary btn-lg mt-3" id="show_bottom_edit_button">編集</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    @endsection