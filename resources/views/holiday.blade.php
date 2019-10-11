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
                    <!-- <a href="/employee/public" class="btn btn-success btn-lg m-0" style="margin:20px;">トップに戻る</a> -->
                    <button type="button" onclick=history.back() class="btn btn-success btn-lg m-0">トップに戻る</button>
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
                @if($post_year ==00)
                初回基準月未満
                @else
                {{$post_year}} 年度　有給取得日明細
                @endif


                <?php
                if ($year_month_b >= $nyushabi_year_month and $year_month_b < $kijunbi_year_month) {
                    echo '<div>
                        <p style="font-size:20px; color:red;">※'  .$year_month_a . '末時点のデータです。</p>
                        </div>';
                } else {
                    if($latest_year == 0000 AND $latest_month == 00 AND $latest_day == 00){
                        echo '<div>
                        <p style="font-size:20px; color:red;">※' .$year_month_a . '末時点のデータです。</p>
                        </div>';
                    }
                    $count_get_holiday_pre = count($array[$array_count][7]) - 1;
                    $count_get_holiday = $array[$array_count][7][$count_get_holiday_pre];

                    if ($latest_year == $count_get_holiday->year and $latest_month == $count_get_holiday->month and $latest_day == $count_get_holiday->day) {
                        echo '<div>
                        <p style="font-size:20px; color:red;">※' .$year_month_a . '末時点のデータです。</p>
                        </div>';

                    } else {
                        echo '<div>
                            <p style="font-size:20px; color:red;"></p>
                        </div>';
                    }
                }
                ?>



            </div>


            
            <div class="panel-body">
                <div class="text-center mb-5">
                    <p>社員コード：　{{ $employee -> shain_cd }} </p>
                    <p>社員名：　{{ $employee -> shain_mei }} </p>
                </div>
                <!-- 初回基準月に達していない場合 -->
                <!-- 基準日が現在の月-2よりも大きい場合・・・　現在の月が2019/10、基準日が10月だとすると　10 > 8 -->

                @if($year_month_b >= $nyushabi_year_month AND $year_month_b < $kijunbi_year_month) <table class="table table-striped task-table" style="table-layout: fixed; width:100%;">
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
                            @if($post_year == $kijunbi_year)
                            <td> {{$array2[0][3]}}　日</td>
                            @else
                            <td> {{$array2[0][3]}}　日</td>
                            @endif
                        </tr>

                        <tr>
                            <th class="text-center">消化日数</th>
                            <td> {{$array2[0][4]}} 　日　</td>

                        </tr>

                        <tr>
                            <th class="text-center">消化残</th>
                            @if($array2[0][5] <=2 AND $array2[0][5]>0)
                                <td style="color:green;">{{$array2[0][5]}}　日 <small> ※有給残り僅かです！</small></td>
                                @elseif($array2[0][5] <=0) <td style="color:red;">{{$array2[0][5]}}　日 <small> ※有給なくなりました！</small></td>
                                    @else
                                    <td>{{$array2[0][5]}}　日</td>
                                    @endif
                        </tr>

                        <tr>
                            <th class="text-center">月別有給取得日数</th>
                            <td></td>
                        </tr>

                        @if(empty($array2[0][7]))
                        <tr>
                            <th class="text-center"></th>
                            <td></td>
                        </tr>
                        @else
                        @foreach($array2[0][7] as $array2)
                        <tr>
                            <th class="text-center"> {{$array2->year}}年
                                {{$array2->month}}月</th>
                            <td>{{$array2->day}}日</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                    </table>

                    <!-- 準社員の場合 -->
                    @elseif($jun_shain == true)
                    <table class="table table-striped task-table" style="table-layout: fixed; width:100%;">
                        <p style="color:red" class="text-center">※準社員には有給がつきません。</p>
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
                                <td> 0　日</td>
                            </tr>

                            <tr>
                                <th class="text-center">消化日数</th>
                                <td> 0　日　</td>
                            </tr>


                            <tr>
                                <th class="text-center">消化残</th>
                                <td>0　日</td>
                            </tr>

                            <tr>
                                <th class="text-center">月別有給取得日数</th>
                                <td></td>
                            </tr>

                            <tr>
                                <th class="text-center"> </th>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>


                    @else
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
                                @if($post_year == $kijunbi_year)
                                <td> {{$array[$array_count][3]}}　日</td>
                                @else
                                <td> {{$array[$array_count][3]}}　日</td>
                                @endif
                            </tr>


                            <tr>
                                <th class="text-center">消化日数</th>

                                @if($array[$array_count][9] <= $year_month_b AND $array[$array_count][4] <=5) <td style="color:red"> {{$array[$array_count][4]}} 　日　<small> ※残り期間({{$kijunbi_month}}月まで)で最低5日取得する必要があります！</small></td>
                                    @else
                                    <td> {{$array[$array_count][4]}} 　日　</td>
                                    @endif


                            </tr>

                            </tr>

                            <tr>
                                <th class="text-center">消化残</th>
                                @if($array[$array_count][5] <=2 AND $array[$array_count][5]>0)
                                    <td style="color:green;">{{$array[$array_count][5]}}　日 <small> ※有給残り僅かです！</small></td>
                                    @elseif($array[$array_count][5] <=0) <td style="color:red;">{{$array[$array_count][5]}}　日 <small> ※有給なくなりました！</small></td>
                                        @else
                                        <td>{{$array[$array_count][5]}}　日</td>
                                        @endif

                            </tr>

                            <tr>
                                <th class="text-center">月別有給取得日数</th>
                                <td></td>
                            </tr>
                            @foreach($array[$array_count][7] as $array)
                            <tr>
                                <th class="text-center"> {{$array->year}}年
                                    {{$array->month}}月</th>
                                <td>{{$array->day}}日</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                    <th class="text-center"> </th>




            </div>

            <div class="mt-5 text-center">
                <!-- トップに戻るボタン -->
                <!-- <a href="/employee/public" class="btn btn-success btn-lg m-0" style="margin:20px;">トップに戻る</a> -->
                <button type="button" onclick=history.back() class="btn btn-success btn-lg m-0">トップに戻る</button>
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