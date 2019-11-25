<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/add.js') }}" defer></script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/add.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


    <!-- DataTablesプラグイン -->
    <link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css" />

    <style>
        .off {
            display: none;
        }
    </style>



</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('image/logo.png') }}" alt="" width="20%" class="d-none d-md-block">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent" style="width:35%">
                    <!-- Left Side Of Navbar -->
                    <!-- <ul class="navbar-nav mr-auto">

                    </ul> -->

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item" style="margin-right:8rem">
                            <a class="nav-link" href="./employee_doc/employee_doc.html" target="_blank" style="font-size:12px">仕様及び使い方</a>
                        </li>
                        <!-- Authentication Links -->
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        <!-- @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif -->
                        @else


                        <!-- 退職者年代別 -->
                        <div class="dropdown">
                            <!-- 切替ボタンの設定 -->
                            <button type="button" class="dropdown-toggle" id="dropdownMenuButton_login_user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            {{ Auth::user()->name }}
                            </button>
                            <!-- ドロップメニューの設定 -->
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_login_user">

                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>


                            </div><!-- /.dropdown-menu -->
                        </div><!-- /.dropdown -->








                        <!-- <li class="nav-item dropdown">
                            <a id="navbarDropdown_login" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}

                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown_login">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li> -->
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')

        </main>
    </div>
    <script>

    </script>

    <script>
        //dataTablesの設定
        jQuery(function($) {
            // デフォルトの設定を変更
            $.extend($.fn.dataTable.defaults, {
                language: {
                    url: "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Japanese.json"
                }
            });

            $("#data-teble").DataTable({

                stateSave: true,
                stateDuration: -1,

                columnDefs: [

                    {
                        // 操作・社員名・メール・備考のソート機能をオフ
                        "orderable": false,
                        "targets": [0, 2, 3, 5]
                    }

                ]
            });
        });

        $('.table_reset').click(function() {
            // window.alert("クリックしたよ");
            $('#data-teble').DataTable().state.clear();
            // window.alert("クリアしたよ");
            // $('#data-teble').DataTable().destroy();
            // window.alert("破壊したよ");
            // $('#data-teble').empty();
            // window.alert("空にしたよ");
            // window.location.reload();
        })







        jQuery(document).ready(function($) {
            // 前在籍者の平均年齢ajax
            $("#ajax_all_avg").clickToggle(
                function() {
                    $.ajax({
                            type: 'get',
                            datatype: 'json',
                            url: '/employee/public/all_avg'
                        })
                        .done(function(data) { //ajaxの通信に成功した場合
                            // alert("success!");
                            $("#result_pre").before("<p id='result_all_avg'></p>");
                            $("#ajax_all_avg").css("color", "#D9534F");
                            $("#result_all_avg").append("<p class='mt-5 p-3 mb-0' style='background-color: #F7F7EE; font-weight:bold;'>≪全在籍者の平均年齢≫</p>");
                            $("#result_all_avg").append("<p class='pl-3 pr-3 pb-3 pt-0' style='background-color: #F7F7EE'>全在籍者の平均年齢は" + data['all_avg'] + "歳です</p>");
                        })
                        .fail(function(data) { //ajaxの通信に失敗した場合
                            alert("error!");
                        });

                },
                function() {
                    $("#ajax_all_avg").css("color", "black");
                    $("#result_all_avg").remove();
                }
            );

            // 部門別の平均年齢ajax
            $("#ajax_department_avg").clickToggle(
                function() {
                    $.ajax({
                            type: 'get',
                            datatype: 'json',
                            url: '/employee/public/department_avg'
                        })
                        .done(function(data) { //ajaxの通信に成功した場合
                            // alert("success!");
                            $("#result_pre").before("<p id='result_department_avg'></p>");
                            $("#ajax_department_avg").css("color", "#D9534F");
                            $("#result_department_avg").append("<p class='mt-5 p-3 mb-0' style='background-color: #F7F7EE; font-weight:bold;'>≪部門別の平均年齢≫</p>");
                            $("#result_department_avg").append("<p class='pl-3 pr-3 pb-3 pt-0 mb-0' style='background-color: #F7F7EE'>代表取締役の平均年齢は" + data['department_avg1'] + "歳です</p>");
                            $("#result_department_avg").append("<p class='p-3 mb-0' style='background-color: #F7F7EE'>管理部の平均年齢は" + data['department_avg2'] + "歳です</p>");
                            $("#result_department_avg").append("<p class='p-3 mb-0' style='background-color: #F7F7EE'>営業部の平均年齢は　" + data['department_avg3'] + "歳です</p>");
                            $("#result_department_avg").append("<p class='p-3 mb-0' style='background-color: #F7F7EE'>システム開発部の平均年齢は　" + data['department_avg4'] + "歳です</p>");
                        })
                        .fail(function(data) { //ajaxの通信に失敗した場合
                            alert("error!");
                        });

                },
                function() {
                    $("#ajax_department_avg").css("color", "black");
                    $("#result_department_avg").remove();
                }
            );

            // 男女の平均年齢ajax
            $("#ajax_gender_avg").clickToggle(
                function() {
                    $.ajax({
                            type: 'get',
                            datatype: 'json',
                            url: '/employee/public/gender_avg'
                        })
                        .done(function(data) { //ajaxの通信に成功した場合
                            // alert("success!");
                            $("#result_pre").before("<p id='result_gender_avg'></p>");
                            $("#ajax_gender_avg").css("color", "#D9534F");
                            $("#result_gender_avg").append("<p class='mt-5 p-3 mb-0' style='background-color: #F7F7EE; font-weight:bold;'>≪男女別の平均年齢≫</p>");
                            $("#result_gender_avg").append("<p class='p-3 mb-0' style='background-color: #F7F7EE'>男性の平均年齢は" + data['gender_avg1'] + "歳です</p>");
                            $("#result_gender_avg").append("<p class='p-3' style='background-color: #F7F7EE'>女性の平均年齢は" + data['gender_avg2'] + "歳です </p>");
                        })
                        .fail(function(data) { //ajaxの通信に失敗した場合
                            alert("error!");
                        });

                },
                function() {
                    $("#ajax_gender_avg").css("color", "black");
                    $("#result_gender_avg").remove();
                }
            );

            // 前在籍者の人数ajax
            $("#ajax_all_count").clickToggle(
                function() {
                    $.ajax({
                            type: 'get',
                            datatype: 'json',
                            url: '/employee/public/all_count'
                        })
                        .done(function(data) { //ajaxの通信に成功した場合
                            // alert("success!");
                            $("#result_pre").before("<p id='result_all_count'></p>");
                            $("#ajax_all_count").css("color", "#D9534F");
                            $("#result_all_count").append("<p class='mt-5 p-3 mb-0' style='background-color: #F7F7EE; font-weight:bold;'>≪全在籍者の人数≫</p>");
                            $("#result_all_count").append("<p class='p-3' style='background-color: #F7F7EE'>全在籍者の人数は" + data['all_count'] + "人です</p>");
                        })
                        .fail(function(data) { //ajaxの通信に失敗した場合
                            alert("error!");
                        });

                },
                function() {
                    $("#ajax_all_count").css("color", "black");
                    $("#result_all_count").remove();
                }
            );

            // 部門別の人数ajax
            $("#ajax_department_count").clickToggle(
                function() {
                    $.ajax({
                            type: 'get',
                            datatype: 'json',
                            url: '/employee/public/department_count'
                        })
                        .done(function(data) { //ajaxの通信に成功した場合
                            // alert("success!");
                            $("#result_pre").before("<p id='result_department_count'></p>");
                            $("#ajax_department_count").css("color", "#D9534F");
                            $("#result_department_count").append("<p class='mt-5 p-3 mb-0' style='background-color: #F7F7EE; font-weight:bold;'>≪部門別の人数≫</p>");
                            $("#result_department_count").append("<p class='pl-3 pr-3 pb-3 pt-0 mb-0' style='background-color: #F7F7EE'>代表取締役の人数は" + data['all_department1'] + "人です</p>");
                            $("#result_department_count").append("<p class='p-3 mb-0' style='background-color: #F7F7EE'>管理部の人数は" + data['all_department2'] + "人です</p>");
                            $("#result_department_count").append("<p class='p-3 mb-0' style='background-color: #F7F7EE'>営業部の人数は　" + data['all_department3'] + "人です</p>");
                            $("#result_department_count").append("<p class='p-3 mb-0' style='background-color: #F7F7EE'>システム開発部の人数は　" + data['all_department4'] + "人です</p>");
                        })
                        .fail(function(data) { //ajaxの通信に失敗した場合
                            alert("error!");
                        });

                },
                function() {
                    $("#ajax_department_count").css("color", "black");
                    $("#result_department_count").remove();
                }
            );

            // 男女別の人数ajax
            $("#ajax_gender_count").clickToggle(
                function() {
                    $.ajax({
                            type: 'get',
                            datatype: 'json',
                            url: '/employee/public/gender_count'
                        })
                        .done(function(data) { //ajaxの通信に成功した場合
                            // alert("success!");
                            $("#result_pre").before("<p id='result_gender_count'></p>");
                            $("#ajax_gender_count").css("color", "#D9534F");
                            $("#result_gender_count").append("<p class='mt-5 p-3 mb-0' style='background-color: #F7F7EE; font-weight:bold;'>≪男女別の人数≫</p>");
                            $("#result_gender_count").append("<p class='pl-3 pr-3 pb-3 pt-0 mb-0' style='background-color: #F7F7EE'>男性の人数は" + data['all_gender1'] + "人です</p>");
                            $("#result_gender_count").append("<p class='p-3 mb-0' style='background-color: #F7F7EE'>女性の人数は" + data['all_gender2'] + "人です</p>");
                        })
                        .fail(function(data) { //ajaxの通信に失敗した場合
                            alert("error!");
                        });

                },
                function() {
                    $("#ajax_gender_count").css("color", "black");
                    $("#result_gender_count").remove();
                }
            );

            // 年代別の人数ajax
            $("#ajax_age_count").clickToggle(
                function() {
                    $.ajax({
                            type: 'get',
                            datatype: 'json',
                            url: '/employee/public/age_count'
                        })
                        .done(function(data) { //ajaxの通信に成功した場合
                            // alert("success!");
                            $("#result_pre").before("<p id='result_age_count'></p>");
                            $("#ajax_age_count").css("color", "#D9534F");
                            $("#result_age_count").append("<p class='mt-5 p-3 mb-0' style='background-color: #F7F7EE; font-weight:bold;'>≪年代別の人数≫</p>");
                            $("#result_age_count").append("<p class='pl-3 pr-3 pb-3 pt-0 mb-0' style='background-color: #F7F7EE'>20代の人数は　" + data['age1'] + "人です</p>");
                            $("#result_age_count").append("<p class='p-3 mb-0' style='background-color: #F7F7EE'>30代の人数は　" + data['age2'] + "人です</p>");
                            $("#result_age_count").append("<p class='p-3 mb-0' style='background-color: #F7F7EE'>40代の人数は　" + data['age3'] + "人です</p>");
                            $("#result_age_count").append("<p class='p-3 mb-0' style='background-color: #F7F7EE'>50代の人数は　" + data['age4'] + "人です</p>");
                            $("#result_age_count").append("<p class='p-3 mb-0' style='background-color: #F7F7EE'>60代の人数は　" + data['age5'] + "人です</p>");
                            $("#result_age_count").append("<p class='p-3 mb-0' style='background-color: #F7F7EE'>その他の人数は　" + data['age6'] + "人です</p>");
                        })
                        .fail(function(data) { //ajaxの通信に失敗した場合
                            alert("error!");
                        });

                },
                function() {
                    $("#ajax_age_count").css("color", "black");
                    $("#result_age_count").remove();
                }
            );

        });

        // ajaxで使用するボタンがトグルになるよう設定
        $.fn.clickToggle = function(a, b) {
            return this.each(function() {
                var clicked = false;
                $(this).on('click', function() {
                    clicked = !clicked;
                    if (clicked) {
                        return a.apply(this, arguments);
                    }
                    return b.apply(this, arguments);
                });
            });
        };


        $(function() {

            //個別印刷ボタンをクリックした時
            $('#print').on('click', function() {
                //プリントしたいエリアの取得
                var printPage = $(this).closest('#table-area').html();

                //プリント用の要素「#print」を作成
                $('body').append('<div id="print-alert"></div>');
                $('#print-alert').append(printPage);

                //「#print-alert」以外の要素に非表示用のclass「off」を指定
                $('body > :not(#print-alert)').addClass('off');
                window.print();

                //window.print()の実行後、作成した「#print-alert」と、非表示用のclass「off」を削除
                $('#print-alert').remove();
                $('.off').removeClass('off');
            });

            //一括印刷ボタンをクリックした時
            $('.print-all').on('click', function() {
                window.print();
            });

        });


        // スムーズにスクロールする
        $('a[href^="#"]').click(function() {
            var speed = 400;
            var href = $(this).attr("href");
            var target = $(href == "#" || href == "" ? 'html' : href);
            var position = target.offset().top - 100;
            $('body,html').animate({
                scrollTop: position
            }, speed, 'swing');
            return false;
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>