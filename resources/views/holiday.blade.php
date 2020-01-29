@extends('layouts.app')

@section('content')

<!-- 有給情報画面 -->

<div class="container">
    <div class="col-12">
        <div class="panel panel-default w-100">

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


        <div class="panel panel-default mt-5 w-100">
            <div class="panel-heading font-weight-bold text-center" style="font-size:40px; background-color:#F7F7EE;">
                <!-- 初回基準月未満の人は00が送られてくる -->
                @if($post_year ==00)
                初回基準月未満
                @else
                {{$post_year}} 年度　有給取得日明細
                @endif
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

                <!-- 準社員の場合 -->
                @if($jun_shain == "準社員")
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

                <!-- 初回基準月に達していない場合 -->
                @elseif($post_year == 00)
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
                            <td> {{$array2[0][3]}}　日</td>

                        </tr>

                        <tr>
                            <th class="text-center">消化日数</th>
                            <td> {{$array2[0][4]}} 　日　</td>

                        </tr>

                        <tr>
                            <th class="text-center">消化残</th>
                            <!-- 消化残が0以上2以下の時 -->
                            @if($array2[0][5] < 2 AND $array2[0][5]>0)
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

                <!-- 初回基準月以降かつ正社員の場合 -->
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
                            <td> {{$array[$array_count][3]}}　日</td>
                        </tr>

                        <tr>
                            <th class="text-center">消化日数</th>
                            <!-- 基準月が3ヶ月以内に来る人で、5日以上有給を取得していない場合、未消化アラートを表示-->
                            @if($mishouka_alert == "yes")
                            <td style="color:red"> {{$array[$array_count][4]}} 　日　
                                <small> ※残り期間({{$first_day_max_month}}月末まで)で最低5日取得する必要があります！</small>
                            </td>
                            @else
                            <td> {{$array[$array_count][4]}} 　日　</td>
                            @endif

                        </tr>


                        <tr>
                            <th class="text-center">消化残</th>
                            <!-- 有給算日数が3日以下になった場合、残数僅少アラートを表示 -->
                            @if($array[$array_count][5] <=3 AND $array[$array_count][5]>0)
                                <td style="color:green;">{{$array[$array_count][5]}}　日 <small> ※有給残り僅かです！</small></td>
                                <!-- 有給算日数が0日になった場合、なくなりましたアラートを表示 -->
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