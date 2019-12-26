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
                <div>
                    {{$post_year_month}} 年 時間外労働
                    <!-- DBのholidayテーブルに入力されている最新のデータ月 -->
                    <p style="font-size:20px; color:red;">※{{ $latest_year_month }}末時点のデータです。</p>
                </div>
            </div>



            <div class="panel-body">
                <div class="text-center mb-5">
                    <p>社員コード：　{{ $employee -> shain_cd }} </p>
                    <p>社員名：　{{ $employee -> shain_mei }} </p>
                </div>

                <div class="mb-5">
                    <table class="table table-striped task-table text-center" style="table-layout: fixed; width:100%;">
                        <tr>
                            <!-- <th class="text-center">所定労働時間</th> -->
                            <th class="text-center">基本時間外労働時間（日）</th>
                            <th class="text-center">基本時間外労働時間（月）</th>
                            <th class="text-center">基本時間外労働時間（年）</th>
                        </tr>

                        <tr>
                            <!-- <td>8時間</td> -->
                            <td>{{$base_working_overtime_day}}時間</td>
                            <td>{{$base_working_overtime_month}}時間</td>
                            <td>{{$base_working_overtime_year}}時間</td>

                        </tr>
                    </table>


                    <table class="table table-striped task-table text-center" style="table-layout: fixed; width:100%; margin-top: 5%;">
                        <tr>
                            <th class="text-center">例外時間外労働（月）</th>
                            <th class="text-center">例外時間外労働（年）</th>
                            <th class="text-center">休日労働（月）</th>
                            <th class="text-center">休日労働回数（月）</th>
                        </tr>

                        <tr>
                            <!-- 例外時間外労働（月） -->
                            @if($overtime_working_this_month >= 70)
                            <td style="color:red">{{$overtime_working_this_month}} / {{$exception_working_overtime_month}}時間</td>
                            @else
                            <td>{{$overtime_working_this_month}} / {{$exception_working_overtime_month}}時間</td>
                            @endif

                            <!-- 例外時間外労働（年） -->
                            @if($overtime_working_sum >= 700)
                            <td style="color:red">{{$overtime_working_sum}} / {{$exception_working_overtime_year}}時間</td>
                            @else
                            <td>{{$overtime_working_sum}} / {{$exception_working_overtime_year}}時間</td>
                            @endif

                            <!-- 休日労働（月） -->
                            <td>{{$holiday_working_this_month}} 時間</td>

                            <!-- 休日労働回数（月） -->
                            @if($holiday_working_this_month_count >= 1)
                            <td style="color:red">{{$holiday_working_this_month_count}} / 2回</td>
                            @else
                            <td>{{$holiday_working_this_month_count}} / 2回</td>
                            @endif
                        </tr>


                        <tr>
                            <th class="text-center">時間外労働45時間越えた回数</th>
                            <th class="text-center">時間外労働+休日労働<br>（月）</th>
                            <th class="text-center">例外時間外労働<br>（2か月平均）</th>
                            <th class="text-center">例外時間外労働<br>（3か月平均）</th>
                        </tr>

                        <tr>
                            <!-- 時間外労働45時間越えた回数 -->
                            @if($overtime_working_count_array_result >=4)
                            <td style="color:red">{{$overtime_working_count_array_result}} / 6回</td>
                            @else
                            <td>{{$overtime_working_count_array_result}} / 6回</td>
                            @endif

                            <!-- 時間外労働+休日労働（月） -->
                            @if($overtime_and_holiday_working_sum >= 90)
                            <td style="color:red">{{$overtime_and_holiday_working_sum}} / {{$overtime_and_holiday_working}}時間</td>
                            @else
                            <td>{{$overtime_and_holiday_working_sum}} / {{$overtime_and_holiday_working}}時間</td>
                            @endif

                            <!-- 例外時間外労働（2か月平均） -->
                            @if($two_months_average >= 70)
                            <td style="color:red">{{$two_months_average}} / {{$overtime_working_average}}時間</td>
                            @else
                            <td>{{$two_months_average}} / {{$overtime_working_average}}時間</td>
                            @endif

                            <!-- 例外時間外労働（3か月平均） -->
                            @if($three_months_average >= 70)
                            <td style="color:red">{{$three_months_average}} / {{$overtime_working_average}}時間</td>
                            @else
                            <td>{{$three_months_average}} / {{$overtime_working_average}}時間</td>
                            @endif
                        </tr>


                        <tr>
                            <th class="text-center">例外時間外労働<br>（4か月平均）</th>
                            <th class="text-center">例外時間外労働<br>（5か月平均）</th>
                            <th class="text-center">例外時間外労働<br>（6か月平均）</th>
                            <th class="text-center"></th>
                        </tr>

                        <tr>
                            <!-- 例外時間外労働（4か月平均） -->
                            @if($four_months_average >= 70)
                            <td style="color:red">{{$four_months_average}} / {{$overtime_working_average}}時間</td>
                            @else
                            <td>{{$four_months_average}} / {{$overtime_working_average}}時間</td>
                            @endif

                            <!-- 例外時間外労働（5か月平均） -->
                            @if($five_months_average >= 70)
                            <td style="color:red">{{$five_months_average}} / {{$overtime_working_average}}時間</td>
                            @else
                            <td>{{$five_months_average}} / {{$overtime_working_average}}時間</td>
                            @endif

                            <!-- 例外時間外労働（6か月平均） -->
                            @if($six_months_average >= 70)
                            <td style="color:red">{{$six_months_average}} / {{$overtime_working_average}}時間</td>
                            @else
                            <td>{{$six_months_average}} / {{$overtime_working_average}}時間</td>
                            @endif

                            <td style="color:red"></td>
                        </tr>

                        </tbody>
                    </table>
                </div>


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