@extends('layouts.app')

@section('content')

<!-- 扶養家族編集画面 -->
<!-- 扶養家族データ更新完了時に表示されるメッセージ -->
@if (isset($update_success))
<p class="alert alert-success">扶養家族データ更新完了！</p>
@endif
<!-- 扶養家族データ削除完了時に表示されるメッセージ -->
@if (isset($delete_success))
<p class="alert alert-success">扶養家族データ削除完了！</p>
@endif
<!-- 扶養家族データ新規登録完了時に表示されるメッセージ -->
@if (isset($create_success))
<p class="alert alert-success">扶養家族データ新規登録完了！</p>
@endif

@if(isset($dependent_info_submit_error))
@if($dependent_info_submit_error == 'duplicate_error')
<p class="alert alert-danger">同じデータが存在しています。データを確認後、再度追加してください。
    <button type="button" onclick="history.back()">先ほどの画面に戻る</button></p>
@else
<p class="alert alert-danger">必須項目が未入力でしたので、更新できませんでした。</p>
@endif
@endif

<div class="container">
    <div class="col-12">
        <div class="panel panel-default w-100">

            <div class="panel-body">

                @include('common.errors')

                <div class="mt-4 text-center">
                    <form action="/employee/public/show/{{$employee->shain_cd}}" method="POST" class="mt-4">
                        {{ csrf_field() }}
                        <!-- トップ画面から送られてきたトップ画面のURLとスクロール位置を渡す -->
                        <input type="hidden" name="url" value={{$top_url}}>
                        <input type="hidden" name="scroll_top" value="{{$scroll_top}}">
                        <button type="submit" class="btn btn-success btn-lg">社員情報詳細画面に戻る</button>
                    </form>

                    <form action="/employee/public/edit/{{$employee->shain_cd}}" method="POST" class="mt-4">
                        {{ csrf_field() }}
                        <input type="hidden" name="top_url" value={{$top_url}}>
                        <input type="hidden" name="scroll_top2" value="{{$scroll_top}}">
                        <button type="submit" class="btn btn-info btn-lg">社員情報編集画面に戻る</button>
                    </form>
                    <!-- <button onclick="history.back()" class="btn btn-info btn-lg mt-2">社員情報編集画面に戻る</button> -->
                </div>
            </div>
        </div>


        <div class="panel panel-default mt-5 w-100">
            <div class="panel-heading font-weight-bold text-center" style="font-size:40px; background-color:#F7F7EE;">
                扶養家族編集
            </div>



            <div class="panel-body">
                <div class="text-center mb-5">
                    <p>社員コード：　{{ $employee -> shain_cd }} </p>
                    <p>社員名：　{{ $employee -> shain_mei }} </p>
                    <p style="color:red">※配偶者欄は、配偶者の場合1を、それ以外の場合は0を選択してください。</p>
                </div>


                <table class="table table-striped task-table" style="table-layout: fixed; width:100%;">
                    <thead>
                        <tr>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:20px">
                                <div>配偶者</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:50px">
                                <div>社員コード</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:50px">
                                <div>名前</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:60px">
                                <div>名前（カナ）</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:25px">
                                <div>性別</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:45px">
                                <div>誕生日</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:60px">
                                <div>基礎年金番号</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:45px">
                                <div>資格取得日</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:25px">
                                <div></div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:25px">
                                <div></div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dependent_info as $dependent)

                        @if($dependent->haigusha == 1)

                        <!-- 更新ボタン -->
                        <form class="form-signin" role="form" method="post" action="/employee/public/dependent_info_update/{{ $dependent->id }}">
                            {{ csrf_field() }}

                            <tr>
                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <select name="haigusha" class="form-control p-0">
                                        <option value="0">0</option>
                                        <option value="1" selected>1</option>
                                    </select>
                                    {{-- バリデーション --}}
                                    @if($errors->has('haigusha'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('haigusha') }}</p>
                                    @endif
                                </td>

                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="text" name="shain_cd" value="{{ $dependent->shain_cd }}" class="form-control" placeholder="例）0">
                                    {{-- バリデーション --}}
                                    @if($errors->has('shain_cd'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shain_cd') }}</p>
                                    @endif
                                </td>

                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="text" name="name" value="{{ $dependent->name }}" class="form-control" placeholder="例）ホクシン太郎">
                                    {{-- バリデーション --}}
                                    @if($errors->has('name'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('name') }}</p>
                                    @endif
                                </td>


                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="text" name="name_kana" value="{{ $dependent->name_kana }}" class="form-control" placeholder="例）ホクシンタロウ">
                                    {{-- バリデーション --}}
                                    @if($errors->has('name_kana'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('name_kana') }}</p>
                                    @endif
                                </td>

                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="text" name="gender" value="{{ $dependent->gender }}" class="form-control" placeholder="例）男">
                                    {{-- バリデーション --}}
                                    @if($errors->has('gender'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('gender') }}</p>
                                    @endif
                                </td>

                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="text" name="birthday" value="{{ $dependent->birthday }}" class="form-control" placeholder="例）2019-01-01">
                                    {{-- バリデーション --}}
                                    @if($errors->has('birthday'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('birthday') }}</p>
                                    @endif
                                </td>

                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="text" name="kisonenkin_bango" value="{{ $dependent->kisonenkin_bango }}" class="form-control" placeholder="例）0000-000000">
                                    {{-- バリデーション --}}
                                    @if($errors->has('kisonenkin_bango'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('kisonenkin_bango') }}</p>
                                    @endif
                                </td>

                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="text" name="shikakushutokubi" value="{{ $dependent->shikakushutokubi }}" class="form-control" placeholder="例）2019-01-01">
                                    {{-- バリデーション --}}
                                    @if($errors->has('shikakushutokubi'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shikakushutokubi') }}</p>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-primary" type="submit">更新</button>
                                </td>
                        </form>

                        <td class="text-center">
                            <form action="/employee/public/dependent_info_delete_to_edit/{{ $employee->shain_cd }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $dependent->id }}">
                                <input type="submit" name="delete" value="削除" onClick="delete_alert(event);return false;" class="btn btn-danger">
                            </form>
                        </td>
                        </tr>

                        @else
                        <form class="form-signin" role="form" method="post" action="/employee/public/dependent_info_update/{{ $dependent->id }}">
                            <tr>
                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <!-- <input type="text" name="haigusha" value="{{ $dependent->haigusha }}" class="form-control" placeholder="例）0"> -->
                                    <select name="haigusha" class="form-control p-0">
                                        <option value="0" selected>0</option>
                                        <option value="1">1</option>
                                    </select>
                                    {{-- バリデーション --}}
                                    @if($errors->has('haigusha'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('haigusha') }}</p>
                                    @endif
                                </td>

                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="text" name="shain_cd" value="{{ $dependent->shain_cd }}" class="form-control" placeholder="例）0">
                                    {{-- バリデーション --}}
                                    @if($errors->has('shain_cd'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shain_cd') }}</p>
                                    @endif
                                </td>

                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="text" name="name" value="{{ $dependent->name }}" class="form-control" placeholder="例）ホクシン太郎">
                                    {{-- バリデーション --}}
                                    @if($errors->has('name'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('name') }}</p>
                                    @endif
                                </td>

                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="text" name="name_kana" value="{{ $dependent->name_kana }}" class="form-control" placeholder="例）ホクシンタロウ">
                                    {{-- バリデーション --}}
                                    @if($errors->has('name_kana'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('name_kana') }}</p>
                                    @endif
                                </td>

                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="text" name="gender" value="{{ $dependent->gender }}" class="form-control" placeholder="例）男">
                                    {{-- バリデーション --}}
                                    @if($errors->has('gender'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('gender') }}</p>
                                    @endif
                                </td>

                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="text" name="birthday" value="{{ $dependent->birthday }}" class="form-control" placeholder="例）2019-01-01">
                                    {{-- バリデーション --}}
                                    @if($errors->has('birthday'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('birthday') }}</p>
                                    @endif
                                </td>

                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="text" name="kisonenkin_bango" value="{{ $dependent->kisonenkin_bango }}" class="form-control" placeholder="例）2019123456">
                                    {{-- バリデーション --}}
                                    @if($errors->has('kisonenkin_bango'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('kisonenkin_bango') }}</p>
                                    @endif
                                </td>

                                <td>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    {{-- 隠しフィールド --}}
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="text" name="shikakushutokubi" value="{{ $dependent->shikakushutokubi }}" class="form-control" placeholder="例）2019-01-01">
                                    {{-- バリデーション --}}
                                    @if($errors->has('shikakushutokubi'))
                                    <p class="text-danger" style="margin-bottom: 30px;">{{ $errors->first('shikakushutokubi') }}</p>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-primary" type="submit">更新</button>
                                </td>
                        </form>
                        <td class="text-center">
                            <form action="/employee/public/dependent_info_delete_to_edit/{{ $employee->shain_cd }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $dependent->id }}">
                                <input type="submit" name="delete" value="削除" onClick="delete_alert(event);return false;" class="btn btn-danger">
                            </form>
                        </td>
                        </tr>
                        @endif
                        @endforeach

                    </tbody>
                </table>

                <div class="mt-5 mb-5 text-center">
                    <form action="/employee/public/dependent_info_add/{{$employee->shain_cd}}" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-warning btn-lg">扶養家族追加</button>
                    </form>
                </div>


            </div>

            <div class="mt-5 text-center">

            </div>

        </div>
    </div>
</div>
@endsection