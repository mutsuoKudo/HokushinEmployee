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
                <?php
                print $_POST['year'];
                ?>
                年度　有給取得日明細
            </div>

            <div class="panel-body">
                <div class="text-center mb-5">
                    <p>社員コード：　{{ $employee -> shain_cd }} </p>
                    <p>社員名：　{{ $employee -> shain_mei }} </p>
                </div>


                <table class="table table-striped task-table" style="table-layout: fixed; width:100%;">
                    <tbody>
                        <tr>
                            <th class="text-center">有給基準月</th>
                            @if(empty($kijunbi_month))
                            <td>データがありません</td>
                            @else
                            <td>{{ $kijunbi_month }}　月</td>
                            @endif
                        </tr>

                        <tr>
                            <th class="text-center">期首残高</th>
                            <td>{{$kisyu_nokori}}　日</td>
                        </tr>

                        <tr>
                            <th class="text-center">消化日数</th>
                            @foreach($holiday_count as $counts)
                                @if(isset($counts->sumday))
                                <?php $cure_month = date('Ym');
                                    // var_dump("現在の年月：" .$cure_month);
                                    ?>
                                    <!-- {{$warning}} -->
                                    @if($warning == $cure_month AND $counts->sumday <= 3)
                                    <td style="color:red">{{ $counts->sumday }}　日　<small>※3日休んでいません！</small></td>
                                    @else
                                    <td>{{ $counts->sumday }}　日</td>
                                    @endif
                                @else
                                <td>データがありません</td>
                                @endif
                            @endforeach
                                </tr>

                                <tr>
                                    <th class="text-center">消化残</th>
                                    @if($nokori <= 2 AND $nokori > 0)
                                    <td style="color:green">{{$nokori}}　日　<small>※有給残り僅かです！</small></td>
                                    @elseif($nokori <= 0)
                                    <td style="color:red">{{$nokori}}　日　<small>※有給なくなりました！</small></td>
                                    @else
                                    <td>{{$nokori}}　日</td>
                                    @endif
                                </tr>
                                
                                <tr>
                            <th class="text-center">月別有給取得日数</th>
                            <td></td>
                        </tr>
                        @foreach ($get_holiday as $get_holidays)
                        <tr>
                            <th class="text-center"> {{ $get_holidays->year}}年
                                {{ $get_holidays->month}}月</th>
                            <td>{{ $get_holidays->day}}日</td>
                        </tr>
                        @endforeach
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