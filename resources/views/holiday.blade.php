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
                @if($kijunbi_year_month > $year_month)
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
                            <td> {{$array2[0][3]}}　日</td>
                            @else
                            <td> {{$array2[0][3]}}　日</td>
                            @endif
                        </tr>

                        <tr>
                            <th class="text-center">消化日数</th>
                            @if($array2[0][4] == 0)
                            <td> データがありません</td>
                            @else
                            <?php
                            $genzai_month = date("m");
                            ?>
                            @if($warning == $genzai_month AND $array2[0][4] <= 3) <td style="color:red"> {{$array2[0][4]}} 　日　<small> ※3日休んでいません！</small></td>
                                @else
                                <td> {{$array2[0][4]}} 　日　</td>
                                @endif
                                @endif

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
                            <td>データがありません</td>
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
                            @if($array[$array_count][4] == 0)
                            <td> データがありません</td>
                            @else
                            <?php
                            $genzai_month = date("m");
                            ?>
                            @if($warning == $genzai_month AND $array[$array_count][4] <= 3) <td style="color:red"> {{$array[$array_count][4]}} 　日　<small> ※3日休んでいません！</small></td>
                                @else
                                <td> {{$array[$array_count][4]}} 　日　</td>
                                @endif
                                @endif

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
                        @if(empty($array[$array_count][7]))
                        <tr>
                            <th class="text-center"></th>
                            <td>データがありません</td>
                        </tr>
                        @else
                        @foreach($array[$array_count][7] as $array)
                        <tr>
                            <th class="text-center"> {{$array->year}}年
                                {{$array->month}}月</th>
                            <td>{{$array->day}}日</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                @endif
                <th class="text-center"> </th>





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