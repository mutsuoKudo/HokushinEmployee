@extends('layouts.app')

@section('content')

<!-- 有給情報画面 -->

<div class="container">
    <div class="col-12">
        <div class="panel panel-default">

            <div class="panel-body">

                @include('common.errors')

                <div class="mt-4 text-center">

                    <form action="{{$top_url}}" method="GET">
                        {{ csrf_field() }}
                        <!-- トップ画面から送られてきたトップ画面のURLとスクロール位置に戻る -->
                        <input type="hidden" name="post_scroll_top" value="{{$scroll_top}}">
                        <button type="submit" class="btn btn-success btn-lg" style="margin:20px;">トップに戻る</button>
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


        <div class="panel panel-default mt-5">
            <div class="panel-heading font-weight-bold text-center" style="font-size:40px; background-color:#F7F7EE;">
                扶養家族明細
                <div>
                    <!-- DBのholidayテーブルに入力されている最新のデータ月 -->
                    <p style="font-size:20px; color:red;">※{{ $year_month_a }}末時点のデータです。</p>
                </div>
            </div>



            <div class="panel-body">
                <div class="text-center mb-5">
                    <p>社員コード：　{{ $employee -> shain_cd }} </p>
                    <p>社員名：　{{ $employee -> shain_mei }} </p>
                </div>

                <table class="table table-striped task-table text-center" style="table-layout: fixed; width:100%;">

                    <thead>
                        <tr>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:60px">
                                <div></div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:60px">
                                <div>名前</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:60px">
                                <div>名前（カナ）</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:60px">
                                <div>性別</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:60px">
                                <div>誕生日</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:60px">
                                <div>基礎年金番号</div>
                            </th>
                            <th class="table-text hs-md-th1" style="min-width:20px; width:60px">
                                <div>資格取得日</div>
                            </th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($dependent_info as $dependent)
                        @if($dependent->haigusha == 1)
                        <tr>
                            <td>配偶者</td>

                            <td>{{ $dependent->name }}</td>


                            <td>{{ $dependent->name_kana }}</td>

                            <td>{{ $dependent->gender }}</td>

                            <td>{{ $dependent->birthday }}</td>

                            <td>{{ $dependent->kisonenkin_bango }}</td>

                            <td>{{ $dependent->shikakushutokubi }}</td>
                        </tr>
                        @else
                        <tr>
                            <td></td>
                       
                            <td>{{ $dependent->name }}</td>
                        
                            <td>{{ $dependent->name_kana }}</td>
                       
                            <td>{{ $dependent->gender }}</td>
                       
                            <td>{{ $dependent->birthday }}</td>
                       
                            <td>{{ $dependent->kisonenkin_bango }}</td>
                        
                            <td>{{ $dependent->shikakushutokubi }}</td>
                        </tr>
                        @endif
                        @endforeach

                    </tbody>
                </table>

            </div>

            <div class="mt-5 text-center">

                <!-- トップ画面から送られてきたトップ画面のURLとスクロール位置に戻る -->
                <form action="{{$top_url}}" method="GET">
                    {{ csrf_field() }}
                    <input type="hidden" name="post_scroll_top" value="{{$scroll_top}}">
                    <button type="submit" class="btn btn-success btn-lg" style="margin:20px;">トップに戻る</button>
                </form>

                <!-- トップ画面から送られてきたトップ画面のURLとスクロール位置を送る -->
                <form action="/employee/public/show/{{$employee->shain_cd}}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="url" value={{$top_url}}>
                    <input type="hidden" name="scroll_top" value="{{$scroll_top}}">
                    <button type="submit" class="btn btn-info btn-lg mt-2">詳細画面に戻る</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection