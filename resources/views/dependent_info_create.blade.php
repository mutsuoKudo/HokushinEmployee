@extends('layouts.app')

@section('content')

<!-- 新規登録画面 -->

@include('common.errors')

@if(isset($dependent_info_submit_error))
@if($dependent_info_submit_error == 'error')
<p class="alert alert-danger">必須項目が未入力です。お手数ですが、再度入力してください。<br>

    @endif
    @endif

    <div class="container">
        <div class="mb-5">
            <div class="panel panel-default w-100">

                <div class="panel-body">
                    <div class="mt-2 text-center">
                        <!-- <form action="/employee/public/dependent_info/{{$employee->shain_cd}}" method="POST" class="mt-4"> -->
                        <form action="/employee/public/dependent_info_edit/{{$employee->shain_cd}}" method="POST" class="mt-4">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-info btn-lg">扶養家族編集画面に戻る</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default mt-5 w-100">

            <!-- 登録しようとした画像がjpg/png以外の場合エラー -->
            @if(isset($file_extension_error))
            <div class="text-center mt-5 mb-5">
                <p style="color:red">写真の拡張子がjpg・png以外だったので新規登録できませんでした</p>
            </div>
            @endif


            <div class="panel-heading font-weight-bold text-center" style="font-size:40px; background-color:#F7F7EE;">
                扶養家族新規登録
                <div class="text-danger" style="font-size:15px;">※配偶者・社員コード・名前・名前（カナ）・性別・誕生日・資格取得日は必須項目です</div>
            </div>

            <div class="panel-body">
                <!-- 新規登録ボタン -->
                <form class="form-signin" role="form" method="post" action="/employee/public/dependent_info_submit/{{$employee->shain_cd}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <table class="table table-striped task-table" style="table-layout: fixed; width:100%;">
                        <thead>

                        </thead>
                        <tbody>
                            <tr>
                                <th>配偶者<small class="float-right text-danger">※必須</small></th>
                                <td>
                                    <!-- <input type="text" name="haigusha" class="form-control" placeholder="例）2019123456" value="{{old('haigusha')}}"> -->
                                    <select name="haigusha" class="form-control p-0">
                                        <option selected>選択してください</option>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                    </select>
                                    @if($errors->has('haigusha'))<br><span class="error">{{ $errors->first('haigusha') }}</span> @endif
                                </td>
                            </tr>
                            <tr>
                                <th>社員コード<small class="float-right text-danger">※必須</small></th>
                                <td>
                                    <input type="text" name="shain_cd" class="form-control" placeholder="例）2019123456" value="{{$employee->shain_cd}}">
                                    @if($errors->has('shain_cd'))<br><span class="error">{{ $errors->first('shain_cd') }}</span> @endif
                                </td>
                            </tr>
                            <tr>
                                <th>名前<small class="float-right text-danger">※必須</small></th>
                                <td>
                                    <input type="text" name="name" class="form-control" placeholder="例）ホクシン太郎" value="{{old('name')}}">
                                    @if($errors->has('name'))<br><span class="error">{{ $errors->first('name') }}</span> @endif
                                </td>
                            </tr>
                            <tr>
                                <th>名前（カナ）<small class="float-right text-danger">※必須</small></th>
                                <td>
                                    <input type="text" name="name_kana" class="form-control" placeholder="例）ホクシンタロウ" value="{{old('name_kana')}}">
                                    @if($errors->has('name_kana'))<br><span class="error">{{ $errors->first('name_kana') }}</span> @endif
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
                                <th>誕生日<small class="float-right text-danger">※必須</small></th>
                                <td>
                                    <input type="text" name="birthday" class="form-control" placeholder="例）0000-00-00" value="{{old('birthday')}}">
                                    @if($errors->has('birthday'))<br><span class="error">{{ $errors->first('birthday') }}</span> @endif
                                </td>
                            </tr>
                            <tr>
                                <th>基礎年金番号</th>
                                <td>
                                    <input type="text" name="kisonenkin_bango" class="form-control" placeholder="例）0000-000000" value="{{old('kisonenkin_bango')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>資格取得日<small class="float-right text-danger">※必須</small></th>
                                <td>
                                    <input type="text" name="shikakushutokubi" class="form-control" placeholder="例）0000-00-00" value="{{old('shikakushutokubi')}}">
                                    @if($errors->has('shikakushutokubi'))<br><span class="error">{{ $errors->first('shikakushutokubi') }}</span> @endif
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
            <!-- トップ画面から送られてきたトップ画面のURLとスクロール位置に戻る -->

        </div>
    </div>
    </div>
    @endsection