@extends('layouts.app')

@section('content')

<!-- 時間外労働画面 -->

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
                <div>
                    {{$post_year_month}} 時間外労働
                    <p style="font-size:20px;">協定期間： {{ $post_year }}年 4月 ～ {{ $post_year + 1}}年 3月</p>
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
                    @if(isset($overtime_working_error))
                    <p class="mt-5 p-3 font-weight-bold text-center" style="background-color: #F7F7EE">最新データ年月よりも未来を選択しちゃってます。<br>
                        <small class="mt-5 p-3 font-weight-bold text-center" style="font-size:15px; color:red">詳細画面に戻って最初からセレクトされている年月が時間外労働の最新データになります。</small></p>

                    @elseif(isset($overtime_working_error2))
                    <p class="mt-5 p-3 font-weight-bold text-center" style="background-color: #F7F7EE">2019年4月（時間外労働の上限規制初回協定期間）以前の年月を選択しています。<br>
                        <small class="mt-5 p-3 font-weight-bold text-center" style="font-size:15px; color:red">詳細画面に戻って最初からセレクトされている年月が時間外労働の最新データになります。</small></p>


                    @else
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
                            <?php
                            $exception_working_overtime_month_10 = $exception_working_overtime_month-10;
                            ?>
                            @if($overtime_working_this_month >= $exception_working_overtime_month_10)
                            <td style="color:red">{{$overtime_working_this_month}} / {{$exception_working_overtime_month}}時間</td>
                            @else
                            <td>{{$overtime_working_this_month}} / {{$exception_working_overtime_month}}時間</td>
                            @endif

                            <!-- 例外時間外労働（年） -->
                            <?php
                            $exception_working_overtime_year_20 = $exception_working_overtime_year-20;
                            ?>
                            @if($overtime_working_sum >= $exception_working_overtime_year_20)
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
                            <?php
                            $overtime_and_holiday_working_10 = $overtime_and_holiday_working-10;
                            ?>
                            @if($overtime_and_holiday_working_sum >= $overtime_and_holiday_working_10)
                            <td style="color:red">{{$overtime_and_holiday_working_sum}} / {{$overtime_and_holiday_working}}時間</td>
                            @else
                            <td>{{$overtime_and_holiday_working_sum}} / {{$overtime_and_holiday_working}}時間</td>
                            @endif

                            <!-- 例外時間外労働（2か月平均） -->
                            <?php
                            $overtime_working_average_10 = $overtime_working_average-10;
                            var_dump($overtime_working_average_10);
                            ?>
                            @if($two_months_average >= $overtime_working_average_10)
                            <td style="color:red">{{$two_months_average}} / {{$overtime_working_average}}時間</td>
                            @else
                            <td>{{$two_months_average}} / {{$overtime_working_average}}時間</td>
                            @endif

                            <!-- 例外時間外労働（3か月平均） -->
                            @if($three_months_average >= $overtime_working_average_10)
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
                            @if($four_months_average >= $overtime_working_average_10)
                            <td style="color:red">{{$four_months_average}} / {{$overtime_working_average}}時間</td>
                            @else
                            <td>{{$four_months_average}} / {{$overtime_working_average}}時間</td>
                            @endif

                            <!-- 例外時間外労働（5か月平均） -->
                            @if($five_months_average >= $overtime_working_average_10)
                            <td style="color:red">{{$five_months_average}} / {{$overtime_working_average}}時間</td>
                            @else
                            <td>{{$five_months_average}} / {{$overtime_working_average}}時間</td>
                            @endif

                            <!-- 例外時間外労働（6か月平均） -->
                            @if($six_months_average >= $overtime_working_average_10)
                            <td style="color:red">{{$six_months_average}} / {{$overtime_working_average}}時間</td>
                            @else
                            <td>{{$six_months_average}} / {{$overtime_working_average}}時間</td>
                            @endif

                            <td style="color:red"></td>
                        </tr>

                        </tbody>
                    </table>
                </div>


                @endif

                <th class="text-center"> </th>


                <div class="mt-5">
                    <ul style="list-style:none">
                        <li>※時間外労働45時間越えた回数について<br>
                            選択した年月が属する、協定期間開始（協定期間はテーブル上部に表示されています）から選択した年月までの間で、45時間以上の時間外労働をした月が何回あったかを表します。</li>
                        <li>※2～6ヶ月平均について
                            前年度の36協定の対象期間の時間数についても2～6か月平均の算定時間に含みます。<br>
                            例）2020年4月について計算するためには、直前の五か月分（2019年11月～2020年3月）の実績も必要になります。</li>
                    </ul>
                </div>


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