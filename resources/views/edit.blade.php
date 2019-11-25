@extends('layouts.app')

@section('content')

<!-- 編集画面 -->
<?php
$top_url_edit = $top_url;
$top_scroll_top = $scroll_top;
?>

<div class="container">
    <div class="col-12">
        <div class="panel panel-default">


            <div class="panel-body">
                <div class="col-12 mt-2 text-center">
                    <!-- トップ画面から送られてきたトップ画面のURLとスクロール位置に戻る -->
                    <form action="{{$top_url}}" method="GET">
                        {{ csrf_field() }}
                        <input type="hidden" name="post_scroll_top" value="{{$scroll_top}}">
                        <button type="submit" class="btn btn-success btn-lg" style="margin:20px;">トップに戻る</button>
                    </form>

                    <!-- トップ画面から送られてきたトップ画面のURLとスクロール位置を渡す -->
                    <form action="/employee/public/show/{{$employee->shain_cd}}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="url" value={{$top_url}}>
                        <input type="hidden" name="scroll_top" value="{{$scroll_top}}">
                        <button type="submit" class="btn btn-info btn-lg mt-2">詳細画面に戻る</button>
                    </form>

                </div>
            </div>
        </div>


        <div class="panel panel-default mt-5">

            @if(isset($file_extension_error))
            <div class="text-center mt-5 mb-5">
                <p style="color:red">写真の拡張子がjpg・png以外だったので更新できませんでした</p>
            </div>
            @endif



            <div class="panel-heading font-weight-bold text-center" style="font-size:40px; background-color:#F7F7EE;">
                編集
                <p style="font-size:20px">社員名：{{ $employee->shain_mei }}</p>
            </div>

            <div class="panel-body">

                <div class="ml-5">


                    <div class="mb-4 text-center">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        {{-- 隠しフィールド --}}
                        @if(is_null($employee->pic))
                        <p>現在使用中の写真はありません</p>
                        <div class="mb-2">
                            <img src="{{ asset('image/nodata.jpg') }}" alt="" width="20%">
                        </div>

                        <a href="#pic_edit" class="btn btn-primary mt-2">写真変更</a>
                        @else

                        <div class="mb-2">
                            <p>現在使用中の写真</p>
                            <img src="../storage/post_images/{{ $employee->pic }}" style="width: 10%;">
                        </div>

                        <div style="display:inline-block" class="mr-2">
                            <form action="/employee/public/pic_delete/{{ $employee->shain_cd }}" method="POST" id="form-pic_delete">
                                {{ csrf_field() }}
                                <input type="hidden" name="top_url_edit" value="{{$top_url_edit}}">
                                <input type="submit" name="delete" value="写真削除" class="btn btn-danger mt-2" form="form-pic_delete" onClick="delete_alert(event);return false;">　
                            </form>
                        </div>

                        <a href="#pic_edit" class="btn btn-primary mt-2">写真変更</a>

                        <!-- <div class="form-image_url mt-4">
                            input type="hidden" name="_method" value="PATCH">
                            <input type="file" name="pic" value="{{ $employee->pic }}" style="margin:0 auto;">
                        </div> -->

                        @endif

                        {{-- バリデーション --}}
                        @if($errors->has('remarks'))
                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('pic') }}</p>
                        @endif
                    </div>



                    <!-- 更新ボタン -->
                    <form class="form-signin" role="form" method="post" action="/employee/public/update/{{ $employee->shain_cd }}" enctype="multipart/form-data">
                        <input type="hidden" name="top_url_edit" value="{{$top_url_edit}}">
                        <input type="hidden" name="top_scroll_top" value="{{$top_scroll_top}}">
                        <table class="table table-striped task-table" style="table-layout: fixed; width:100%;">
                            <thead>

                            </thead>
                            <tbody>
                                {{ csrf_field() }}
                                <tr>
                                    <th>社員コード<small class="float-right text-danger">※必須</small></th>
                                    <td class="width:50%">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="shain_cd" value="{{ $employee->shain_cd }}" class="form-control" placeholder="例）2019123456">
                                        {{-- バリデーション --}}
                                        @if($errors->has('shain_cd'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shain_cd') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>社員名<small class="float-right text-danger">※必須</small></th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="shain_mei" value="{{ $employee->shain_mei }}" class="form-control" placeholder="例）ホクシン太郎">
                                        {{-- バリデーション --}}
                                        @if($errors->has('shain_mei'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shain_mei') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>社員名（カナ）<small class="float-right text-danger">※必須</small></th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="shain_mei_kana" value="{{ $employee->shain_mei_kana }}" class="form-control" placeholder="例）ホクシンタロウ">
                                        {{-- バリデーション --}}
                                        @if($errors->has('shain_mei_kana'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shain_mei_kana') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>社員名（ローマ字）<small class="float-right text-danger">※必須</small></th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="shain_mei_romaji" value="{{ $employee->shain_mei_romaji }}" class="form-control" placeholder="例）Hokushin Tarou">
                                        {{-- バリデーション --}}
                                        @if($errors->has('shain_mei_romaji'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shain_mei_romaji') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>メールアドレス</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="shain_mail" value="{{ $employee->shain_mail }}" class="form-control" placeholder="例）tarou.hokushin@hokusys.jp">
                                        {{-- バリデーション --}}
                                        @if($errors->has('shain_mail'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shain_mail') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>性別<small class="float-right text-danger">※必須</small></th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="gender" value="{{ $employee->gender }}" class="form-control" placeholder="例）男">
                                        {{-- バリデーション --}}
                                        @if($errors->has('gender'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('gender') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>郵便番号</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="shain_zip_code" value="{{ $employee->shain_zip_code }}" class="form-control" placeholder="例）123-456">
                                        {{-- バリデーション --}}
                                        @if($errors->has('shain_zip_code'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shain_zip_code') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>住所</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="shain_jyusho" value="{{ $employee->shain_jyusho }}" class="form-control" placeholder="例）北海道札幌市中央区南３条東２丁目１番地">
                                        {{-- バリデーション --}}
                                        @if($errors->has('shain_jyusho'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shain_jyusho') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>住所（建物）</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="shain_jyusho_tatemono" value="{{ $employee->shain_jyusho_tatemono }}" class="form-control" placeholder="例）サンシャインビル３階">
                                        {{-- バリデーション --}}
                                        @if($errors->has('shain_jyusho_tatemono'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shain_jyusho_tatemono') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>誕生日</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="shain_birthday" value="{{ $employee->shain_birthday }}" class="form-control" placeholder="例）2019-01-01">
                                        {{-- バリデーション --}}
                                        @if($errors->has('shain_birthday'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shain_birthday') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>入社日</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="nyushabi" value="{{ $employee->nyushabi }}" class="form-control" placeholder="例）2019-01-01">
                                        {{-- バリデーション --}}
                                        @if($errors->has('nyushabi'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('nyushabi') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>正社員転換日</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="seishain_tenkanbi" value="{{ $employee->seishain_tenkanbi }}" class="form-control" placeholder="例）2019-01-01">
                                        {{-- バリデーション --}}
                                        @if($errors->has('seishain_tenkanbi'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('seishain_tenkanbi') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>転籍日</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="tensekibi" value="{{ $employee->tensekibi }}" class="form-control" placeholder="例）2019-01-01">
                                        {{-- バリデーション --}}
                                        @if($errors->has('tensekibi'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('tensekibi') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>退職日</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="taishokubi" value="{{ $employee->taishokubi }}" class="form-control" placeholder="例）2019-01-01">
                                        {{-- バリデーション --}}
                                        @if($errors->has('taishokubi'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('taishokubi') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>社員携帯</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="shain_keitai" value="{{ $employee->shain_keitai }}" class="form-control" placeholder="例）090-1234-5678">
                                        {{-- バリデーション --}}
                                        @if($errors->has('shain_keitai'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shain_keitai') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>電話番号</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="shain_tel" value="{{ $employee->shain_tel }}" class="form-control" placeholder="例）090-1234-5678">
                                        {{-- バリデーション --}}
                                        @if($errors->has('shain_tel'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shain_tel') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>雇用保険番号</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="koyohoken_bango" value="{{ $employee->koyohoken_bango }}" class="form-control" placeholder="例）1234-567891-2">
                                        {{-- バリデーション --}}
                                        @if($errors->has('koyohoken_bango'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('koyohoken_bango') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>社会保険番号</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="shakaihoken_bango" value="{{ $employee->shakaihoken_bango }}" class="form-control" placeholder="例）2">
                                        {{-- バリデーション --}}
                                        @if($errors->has('shakaihoken_bango'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shakaihoken_bango') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>基礎年金番号</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="kisonenkin_bango" value="{{ $employee->kisonenkin_bango }}" class="form-control" placeholder="例）1234-567891">
                                        {{-- バリデーション --}}
                                        @if($errors->has('kisonenkin_bango'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('kisonenkin_bango') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>月給</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="monthly_saraly" value="{{ $employee->monthly_saraly }}" class="form-control" placeholder="例）123456">
                                        {{-- バリデーション --}}
                                        @if($errors->has('monthly_saraly'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('monthly_saraly') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>部門</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="department" value="{{ $employee->department }}" class="form-control" placeholder="例）04">
                                        {{-- バリデーション --}}
                                        @if($errors->has('department'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('department') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>名刺</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="name_card" value="{{ $employee->name_card }}" class="form-control" placeholder="例）1">
                                        {{-- バリデーション --}}
                                        @if($errors->has('name_card'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('name_card') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>IDカード</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="id_card" value="{{ $employee->id_card }}" class="form-control" placeholder="例）1">
                                        {{-- バリデーション --}}
                                        @if($errors->has('id_card'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('id_card') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>扶養家族</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="fuyo_kazoku" value="{{ $employee->fuyo_kazoku }}" class="form-control" placeholder="例）1">
                                        {{-- バリデーション --}}
                                        @if($errors->has('fuyo_kazoku'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('fuyo_kazoku') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>入社試験点数</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="text" name="test" value="{{ $employee->test }}" class="form-control" placeholder="例）100">
                                        {{-- バリデーション --}}
                                        @if($errors->has('test'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('test') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th id="pic_edit">写真</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <div class="form-image_url">
                                            <input type="file" name="pic" value="{{ $employee->pic }}">
                                            <p class="mt-2" style="color:red">※ファイル形式 jpg/png です。</p>
                                        </div>

                                        {{-- バリデーション --}}
                                        @if($errors->has('remarks'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('pic') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>備考</th>
                                    <td>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        {{-- 隠しフィールド --}}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <textarea name="remarks" value="{{ $employee->remarks }}" class="form-control" placeholder="ご自由にご入力ください">{{ $employee->remarks }}</textarea>
                                        {{-- バリデーション --}}
                                        @if($errors->has('remarks'))
                                        <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('remarks') }}</p>
                                        @endif
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <div class="text-center">
                            <button class="btn btn-lg btn-primary mt-5" type="submit">更新</button>
                        </div>
                    </form>


                </div>

            </div>
        </div>

        <div class="mt-3 p-0 text-center">
            <form action="{{$top_url}}" method="GET">
                {{ csrf_field() }}
                <!-- トップ画面から送られてきたトップ画面のURLとスクロール位置に戻る -->
                <input type="hidden" name="post_scroll_top" value="{{$scroll_top}}">
                <button type="submit" class="btn btn-success btn-lg" style="margin:20px;" id="edit_bottom_top_button">トップに戻る</button>
            </form>

            <form action="/employee/public/show/{{$employee->shain_cd}}" method="POST">
                {{ csrf_field() }}
                <!-- トップ画面から送られてきたトップ画面のURLとスクロール位置を渡す -->
                <input type="hidden" name="url" value={{$top_url}}>
                <input type="hidden" name="scroll_top" value="{{$scroll_top}}">
                <button type="submit" class="btn btn-info btn-lg mt-2">詳細画面に戻る</button>
            </form>
        </div>
    </div>
</div>
@endsection