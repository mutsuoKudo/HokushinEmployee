@extends('layouts.app')

@section('content')



<div class="container">
    <div class="col-sm-12">
        <div class="panel panel-default">

            <div class="panel-body">

                <!-- Display Validation Errors -->
                @include('common.errors')

                <div class="mt-4 text-center">
                    <!-- トップに戻るボタン -->
                    <form action="{{$post_url}}" method="GET">
                    {{ csrf_field() }}
                        <input type="hidden" name="post_scroll_top" value="{{$scroll_top}}">
                        <button type="submit" class="btn btn-success btn-lg" style="margin:20px;">トップに戻る</button>
                    </form>
                    <!-- <a href={{$post_url}} class="btn btn-success btn-lg m-0" style="margin:20px;">トップに戻る</a> -->

                    <!-- 編集ボタン -->
                    <form action="/employee/public/edit/{{$employee->shain_cd}}" method="POST">
                        {{ csrf_field() }}
                        <!-- トップに戻る用url -->
                        <input type="hidden" name="top_url" value={{$post_url}}>
                        <input type="hidden" name="scroll_top2" value="{{$scroll_top}}" class="st">
                        <button type="submit" class="btn btn-primary btn-lg mt-3">編集</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Books -->
        <div class="panel panel-default mt-5">
            <div class="panel-heading font-weight-bold text-center" style="font-size:40px; background-color:#F7F7EE;">
                詳細表示
            </div>

            <div class="float-right mr-5">
                <form action="/employee/public/holiday/{{$employee->shain_cd}}" method="POST">

                    {{ csrf_field() }}
                    <select name='year'>
                        <?php

                        $year_month = date("Ym");
                        if ($year_month_b >= $nyushabi_year_month and $year_month_b < $kijunbi_year_month) {
                            echo '<option value="00" selected >初回基準月未満</option>';
                        } else {


                            //退職日が入力されている場合・・・
                            if (isset($employee->taishokubi)) {
                                //退職年までの選択
                                for ($i = $kijunbi_year; $i <= $taishokubi_year; $i++) {
                                    if ($i == $taishokubi_year) {
                                        //退職した年にselected
                                        echo '<option value="', $i, '" selected >', $i, '年度</option>';
                                    } elseif ($i < $taishokubi_year) {
                                        echo '<option value="', $i, '">', $i, '年度</option>';
                                    }
                                    echo 'ERROR';
                                }


                                //退職日が入力されていない場合・・・
                            } else {
                                //現在年月が基準年月に達していない場合・・・

                                if ($year_month_a2 < $kijunbi_month) {
                                    //基準日から現在年まで(現在年は含まない)(現在が2019/10、入社日が2016/4、基準日が10月の場合、2016年度・2017年度・2018年度まで表示する)
                                    //基準日から現在年まで(現在年は含まない)(現在が2019/10、入社日が2016/3、基準日が9月の場合、2016年度・2017年度・2018年度・2019年度まで表示する)
                                    for ($i = $kijunbi_year; $i < $year; $i++) {
                                        if ($i == $year - 1) {
                                            //現在の年にselected
                                            echo '<option value="', $i, '" selected >', $i, '年度</option>';
                                        } elseif ($i < $year) {
                                            echo '<option value="', $i, '">', $i, '年度</option>';
                                        }
                                        echo 'ERROR';
                                    }
                                } else {
                                    //基準日から現在年まで(現在年を含む)
                                    for ($i = $kijunbi_year; $i <= $year; $i++) {
                                        if ($i == $year) {
                                            //現在の年にselected
                                            echo '<option value="', $i, '" selected >', $i, '年度</option>';
                                        } elseif ($i < $year) {
                                            echo '<option value="', $i, '">', $i, '年度</option>';;
                                        }
                                        echo 'ERROR';
                                    }
                                }
                            }
                        }

                        ?>
                    </select>
                    <input type="hidden" name="top_url" value={{$post_url}}>
                    <input type="hidden" name="scroll_top2" value="{{$scroll_top}}" class="st">
                    <input type="submit" class="btn btn-info m-2" value="有給取得日明細">
                </form>


            </div>

            <div class="panel-body">
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
                <!-- トップに戻るボタン -->
                <!-- <a href={{$post_url}} class="btn btn-success btn-lg m-0" style="margin:20px;">トップに戻る</a> -->
                <!-- <button type="button" onclick=history.back() class="btn btn-success btn-lg m-0">トップに戻る</button> -->
                <form action="{{$post_url}}" method="GET">
                    {{ csrf_field() }}
                        <input type="hidden" name="post_scroll_top" value="{{$scroll_top}}">
                        <button type="submit" class="btn btn-success btn-lg" style="margin:20px;">トップに戻る</button>
                    </form>
                <!-- 編集ボタン -->
                <form action="/employee/public/edit/{{$employee->shain_cd}}" method="POST">
                        {{ csrf_field() }}
                        <!-- トップに戻る用url -->
                        <input type="hidden" name="top_url" value={{$post_url}}>
                        <input type="hidden" name="scroll_top2" value="{{$scroll_top}}" class="st">
                        <button type="submit" class="btn btn-primary btn-lg mt-3">編集</button>
                    </form>
            </div>

        </div>
    </div>
</div>
@endsection