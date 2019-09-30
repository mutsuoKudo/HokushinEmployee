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
                    <a href="/employee/public" class="btn btn-success btn-lg m-0" style="margin:20px;">トップに戻る</a>
                    <!-- 詳細画面に戻るボタン -->
                    <form action="/employee/public/show/{{$employee->shain_cd}}" method="GET">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-info btn-lg mt-2">詳細画面に戻る</button>
                </form>
            </div>
            </div>
        </div>

        <!-- Books -->
        <div class="panel panel-default mt-5">
            <div class="panel-heading font-weight-bold text-center" style="font-size:40px; background-color:#F7F7EE;">
                有給取得日明細
            </div>



            <div class="panel-body">
                <div class="text-center mb-5">
                    <p>社員コード：　{{ $holiday->shain_cd }}</p>
                    <!-- <p>社員名：　{{ $employee->shain_mei }}</p> -->
                    <p>社員名：　{{ $employee->shain_mei }}</p>
                </div>

                <table class="table table-striped task-table" style="table-layout: fixed; width:100%;">
                    <thead>

                    </thead>
                    <tbody>
                        <tr>
                            <th>有給基準日</th>
                            <td>{{ $holiday->shain_cd }}　日</td>
                        </tr>
                        <tr>
                            <th>期首有給残高</th>
                            <td>{{ $holiday->shain_cd }}　日</td>
                        </tr>
                        <tr>
                            <th>今期有給消化日数</th>
                            <td>{{ $holiday->shain_cd }}　日</td>
                        </tr>
                        <tr>
                            <th>今期有給消化残日数</th>
                            <td>{{ $holiday->shain_cd }}　日</td>
                        </tr>
                        <tr>
                            <th rowspan="3">今期有給消化日</th>
                            <td>{{ $holiday->shain_cd }}</td>
                        </tr>
                        <tr>
                            <td>{{ $holiday->shain_cd }}</td>
                        </tr>
                        <td>{{ $holiday->shain_cd }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div class="mt-5 text-center">
                <!-- トップに戻るボタン -->
                <a href="/employee/public" class="btn btn-success btn-lg m-0" style="margin:20px;">トップに戻る</a>
                <!-- 詳細画面に戻るボタン -->
                <form action="/employee/public/show/{{$employee->shain_cd}}" method="GET">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-info btn-lg mt-2">詳細画面に戻る</button>
            </form>
        </div>

        </div>
    </div>
</div>
@endsection