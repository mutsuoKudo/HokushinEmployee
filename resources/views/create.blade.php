@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-5">
        <div class="panel panel-default">

            <div class="panel-body">
                <div class="mt-2 text-center">
                    <!-- トップに戻るボタン -->
                    <!-- <a href="/employee/public" class="btn btn-success btn-lg m-0" style="margin:20px;">トップに戻る</a> -->
                    <button type="button" onclick=history.back() class="btn btn-success btn-lg m-0">トップに戻る</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Books -->
    <div class="panel panel-default mt-5 col-12">
        <div class="panel-heading font-weight-bold text-center" style="font-size:40px; background-color:#F7F7EE;">
            新規登録
        </div>

        <div class="panel-body">
            <table class="table table-striped task-table" style="table-layout: fixed; width:100%;">
                <thead>

                </thead>
                <tbody>
                    <!-- 新規登録ボタン -->
                    <form class="form-signin" role="form" method="post" action="/employee/public/submit">
                        {{ csrf_field() }}
                        <tr>
                            <th>社員コード<small class="float-right text-danger">※必須</small></th>
                            <td class="width:50%">
                                <input type="text" name="shain_cd" class="form-control" placeholder="例）2019123456" value="{{old('shain_cd')}}">
                                @if($errors->has('shain_cd'))<br><span class="error">{{ $errors->first('shain_cd') }}</span> @endif
                            </td>
                        </tr>
                        <tr>
                            <th>社員名<small class="float-right text-danger">※必須</small></th>
                            <td>
                                <input type="text" name="shain_mei" class="form-control" placeholder="例）ホクシン太郎" value="{{old('shain_mei')}}">
                                @if($errors->has('shain_mei'))<br><span class="error">{{ $errors->first('shain_mei') }}</span> @endif
                            </td>
                        </tr>
                        <tr>
                            <th>社員名（カナ）<small class="float-right text-danger">※必須</small></th>
                            <td>
                                <input type="text" name="shain_mei_kana" class="form-control" placeholder="例）ホクシンタロウ" value="{{old('shain_mei_kana')}}">
                                @if($errors->has('shain_mei_kana'))<br><span class="error">{{ $errors->first('shain_mei_kana') }}</span> @endif
                            </td>
                        </tr>
                        <tr>
                            <th>社員名（ローマ字）<small class="float-right text-danger">※必須</small></th>
                            <td>
                                <input type="text" name="shain_mei_romaji" class="form-control" placeholder="例）Hokushin Tarou" value="{{old('shain_mei_romaji')}}">
                                @if($errors->has('shain_mei_romaji'))<br><span class="error">{{ $errors->first('shain_mei_romaji') }}</span> @endif
                            </td>
                        </tr>
                        <tr>
                            <th>メールアドレス</th>
                            <td>
                                <input type="text" name="shain_mail" class="form-control" placeholder="例）tarou.hokushin@hokusys.jp" value="{{old('shain_mail')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>性別<small class="float-right text-danger">※必須</small></th>
                            <td>
                                <input type="text" name="gender" class="form-control" placeholder="例）男" value="{{old('gender')}}">
                                @if($errors->has('gender'))<br><span class="error">{{ $errors->first('gender') }}</span> @endif
                            </td>
                        </tr>
                        <tr>
                            <th>郵便番号</th>
                            <td>
                                <input type="text" name="shain_zip_code" class="form-control" placeholder="例）123-456" value="{{old('shain_zip_code')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>住所</th>
                            <td>
                                <input type="text" name="shain_jyusho" class="form-control" placeholder="例）北海道札幌市中央区南３条東２丁目１番地" value="{{old('shain_jyusho')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>住所（建物）</th>
                            <td>
                                <input type="text" name="shain_jyusho_tatemono" class="form-control" placeholder="例）サンシャインビル３階" value="{{old('shain_jyusho_tatemono')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>誕生日</th>
                            <td>
                                <input type="text" name="shain_birthday" class="form-control" placeholder="例）2019-01-01" value="{{old('shain_birthday')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>入社日</th>
                            <td>
                                <input type="text" name="nyushabi" class="form-control" placeholder="例）2019-01-01" value="{{old('nyushabi')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>正社員転換日</th>
                            <td>
                                <input type="text" name="seishain_tenkanbi" class="form-control" placeholder="例）2019-01-01" value="{{old('seishain_tenkanbi')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>転籍日</th>
                            <td>
                                <input type="text" name="tensekibi" class="form-control" placeholder="例）2019-01-01" value="{{old('tensekibi')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>退職日</th>
                            <td>
                                <input type="text" name="taishokubi" class="form-control" placeholder="例）2019-01-01" value="{{old('taishokubi')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>社員携帯</th>
                            <td>
                                <input type="text" name="shain_keitai" class="form-control" placeholder="例）090-1234-5678" value="{{old('shain_keitai')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>電話番号</th>
                            <td>
                                <input type="text" name="shain_tel" class="form-control" placeholder="例）090-1234-5678" value="{{old('shain_tel')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>雇用保険番号</th>
                            <td>
                                <input type="text" name="koyohoken_bango" class="form-control" placeholder="例）1234-567891-2" value="{{old('koyohoken_bango')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>社会保険番号</th>
                            <td>
                                <input type="text" name="shakaihoken_bango" class="form-control" placeholder="例）2" value="{{old('shakaihoken_bango')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>基礎年金番号</th>
                            <td>
                                <input type="text" name="kisonenkin_bango" class="form-control" placeholder="例）1234-567891" value="{{old('kisonenkin_bango')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>月給</th>
                            <td>
                                <input type="text" name="monthly_saraly" class="form-control" placeholder="例）123456" value="{{old('monthly_saraly')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>部門</th>
                            <td>
                                <input type="text" name="department" class="form-control" placeholder="例）04" value="{{old('department')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>名刺</th>
                            <td>
                                <input type="text" name="name_card" class="form-control" placeholder="例）1" value="{{old('name_card')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>IDカード</th>
                            <td>
                                <input type="text" name="id_card" class="form-control" placeholder="例）1" value="{{old('id_card')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>扶養家族</th>
                            <td>
                                <input type="text" name="fuyo_kazoku" class="form-control" placeholder="例）1" value="{{old('fuyo_kazoku')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>入社試験点数</th>
                            <td>
                                <input type="text" name="test" class="form-control" placeholder="例）100" value="{{old('test')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>備考</th>
                            <td>
                                <textarea name="remarks" class="form-control" placeholder="ご自由にご入力ください">{{old('remarks')}}</textarea>
                            </td>
                        </tr>

                </tbody>
            </table>
            <div class="text-center">
                <button class="btn btn-lg btn-primary mt-4" type="submit">登録</button>
            </div>
            </form>
        </div>
    </div>

    <div class="mt-3 text-center">
        <!-- トップに戻るボタン -->
        <!-- <a href="/employee/public" class="btn btn-success btn-lg m-0" style="margin:20px;">トップに戻る</a> -->
        <button type="button" onclick=history.back() class="btn btn-success btn-lg m-0">トップに戻る</button>
    </div>
</div>
</div>
@endsection