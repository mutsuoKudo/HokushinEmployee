@extends('layouts.app')

@section('content')

<!-- 扶養家族画面 -->


<!-- 扶養家族データ削除完了時に表示されるメッセージ -->
@if (isset($delete_success))
<p class="alert alert-success">削除完了！</p>
@endif

<div class="container">
    <div class="col-12">
        <div class="panel panel-default">

            <div class="panel-body">

                @include('common.errors')

                <div class="text-center">

                    <form action="/employee/public/show/{{$employee->shain_cd}}" method="POST" class="mt-4">
                        {{ csrf_field() }}
                        <!-- トップ画面から送られてきたトップ画面のURLとスクロール位置を渡す -->
                        <input type="hidden" name="url" value={{$top_url}}>
                        <input type="hidden" name="scroll_top" value="{{$scroll_top}}">
                        <button type="submit" class="btn btn-info btn-lg">詳細画面に戻る</button>
                    </form>

                </div>
            </div>
        </div>


        <div class="panel panel-default mt-5">
            <div class="panel-heading font-weight-bold text-center" style="font-size:40px; background-color:#F7F7EE;">
                扶養家族明細
            </div>



            <div class="panel-body">
                <div class="text-center mb-5">
                    <p>社員コード：　{{ $employee -> shain_cd }} </p>
                    <p>社員名：　{{ $employee -> shain_mei }} </p>
                </div>

                <table class="table table-striped task-table" style="table-layout: fixed; width:100%;">

                    <thead>
                        <tr>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:30px">
                                <div></div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:40px">
                                <div>名前</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:40px">
                                <div>名前（カナ）</div>
                            </th>
                            <th class="table-text hs-md-th1 text-center" style="min-width:20px; width:30px">
                                <div>性別</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:40px">
                                <div>誕生日</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:40px">
                                <div>基礎年金番号</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:40px">
                                <div>資格取得日</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:30px">
                                <div></div>
                            </th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($dependent_info as $dependent)
                        @if($dependent->haigusha == 1)
                        <tr>
                            <td class="text-center font-weight-bold">配偶者</td>

                            <td>{{ $dependent->name }}</td>

                            <td>{{ $dependent->name_kana }}</td>

                            <td class="text-center">{{ $dependent->gender }}</td>

                            <td>{{ $dependent->birthday }}</td>

                            <td>{{ $dependent->kisonenkin_bango }}</td>

                            <td>{{ $dependent->shikakushutokubi }}</td>

                            <td class="text-center font-weight-bold">
                                <form action="/employee/public/dependent_info_delete/{{ $employee->shain_cd }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{ $dependent->id }}">
                                    <input type="submit" name="delete" value="削除" onClick="delete_alert(event);return false;" class="btn btn-danger">
                                </form>
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td></td>

                            <td>{{ $dependent->name }}</td>

                            <td>{{ $dependent->name_kana }}</td>

                            <td class="text-center">{{ $dependent->gender }}</td>

                            <td>{{ $dependent->birthday }}</td>

                            <td>{{ $dependent->kisonenkin_bango }}</td>

                            <td>{{ $dependent->shikakushutokubi }}</td>

                            <td class="text-center font-weight-bold">
                                <form action="/employee/public/dependent_info_delete/{{ $employee->shain_cd }}" method="POST">
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

            </div>

            <!-- <div class="mt-5 mb-5 text-center">
                    <form action="/employee/public/dependent_info_add/{{$employee->shain_cd}}" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-warning btn-lg">扶養家族追加</button>
                    </form>
                </div> -->

        </div>
    </div>
</div>
@endsection