@extends('layouts.app')

@section('content')

<!-- 未消化アラート画面 -->

<!-- 更新完了時に表示されるメッセージ -->
@if (session('status'))<div class="alert alert-success" role="alert" onclick="this.classList.add('hidden')">{{ session('status') }}</div>@endif
<!-- 削除完了時に表示されるメッセージ -->
@if (session('delete'))<div class="alert alert-success" role="alert" onclick="this.classList.add('hidden')">{{ session('delete') }}</div>@endif
<!-- 新規登録完了時に表示されるメッセージ -->
@if (session('create'))<div class="alert alert-success" role="alert" onclick="this.classList.add('hidden')">{{ session('create') }}</div>@endif

<div class="container">
	<div class="row">
		<div class="panel panel-default">

			<div class="panel-body">
				@include('common.errors')

				<!-- Button -->
				<p class="mt-3 mb-0 font-weight-bold">名簿表示</p>

				<div class="col-12 mt-2 d-inline-flex">


					<!-- 在籍者 -->
					<form action="/employee/public/all" method="GET">
						<input type="submit" name="all" value="在籍者" class="mr-2 mt-1 function-button table_reset">
					</form>


					<!-- 部門別 -->
					<div class="dropdown">
						<!-- 切替ボタンの設定 -->
						<button type="button" class="mr-2 mt-1 function-button dropdown-toggle" id="dropdownMenuButton_department" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							部門別
						</button>
						<!-- ドロップメニューの設定 -->
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton_department">
							<form action="/employee/public/department1" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="department" value="代表取締役" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/department2" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="department" value="管理部" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/department3" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="department" value="営業部" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/department4" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="department" value="システム開発部" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/department5" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="department" value="研修生" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
						</div><!-- /.dropdown-menu -->
					</div><!-- /.dropdown -->


					<!-- 入社年別 -->
					<div class="dropdown">
						<!-- 切替ボタンの設定 -->
						<button type="button" class="mr-2 mt-1 function-button dropdown-toggle" id="dropdownMenuButton_nyushabi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							入社年別
						</button>
						<!-- ドロップメニューの設定 -->
						<div class="dropdown-menu overflow-auto" style="height:250px;" aria-labelledby="dropdownMenuButton_nyushabi">

							@foreach ($select_nyusha_year as $select_nyusha_years)
							<form action="/employee/public/nyushabi{{$select_nyusha_years->nyushanen}}" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="nyushabi" value="{{$select_nyusha_years->nyushanen}}年" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							@endforeach

						</div><!-- /.dropdown-menu -->
					</div><!-- /.dropdown -->


					<!-- 年代別 -->
					<div class="dropdown">
						<!-- 切替ボタンの設定 -->
						<button type="button" class="mr-2 mt-1 function-button dropdown-toggle" id="dropdownMenuButton_age" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							年代別
						</button>
						<!-- ドロップメニューの設定 -->
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton_age">
							<form action="/employee/public/age20" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="age" value="20代" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/age30" method="GET" class="mt-2 table_reset">
								{{ csrf_field() }}
								<input type="submit" name="age" value="30代" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/age40" method="GET" class="mt-2 table_reset">
								{{ csrf_field() }}
								<input type="submit" name="age" value="40代" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/age50" method="GET" class="mt-2 table_reset">
								{{ csrf_field() }}
								<input type="submit" name="age" value="50代" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/age60" method="GET" class="mt-2 table_reset">
								{{ csrf_field() }}
								<input type="submit" name="age" value="60代" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/age_other" method="GET" class="mt-2 table_reset">
								{{ csrf_field() }}
								<input type="submit" name="age" value="その他の年代" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
						</div><!-- /.dropdown-menu -->
					</div><!-- /.dropdown -->


					<!-- 有給基準月別 -->
					<div class="dropdown">
						<!-- 切替ボタンの設定 -->
						<button type="button" class="mr-2 mt-1 function-button dropdown-toggle" id="dropdownMenuButton_kijun_month" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							有給基準月別
						</button>
						<!-- ドロップメニューの設定 -->
						<div class="dropdown-menu overflow-auto" style="height:250px;" aria-labelledby="dropdownMenuButton_kijun_month">
							<form action="/employee/public/kijun_month01" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="1月" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month02" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="2月" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month03" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="3月" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month04" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="4月" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month05" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="5月" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month06" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="6月" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month07" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="7月" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month08" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="8月" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month09" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="9月" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month10" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="10月" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month11" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="11月" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month12" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="12月" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>

						</div><!-- /.dropdown-menu -->
					</div><!-- /.dropdown -->


					<!-- 退職者 -->
					<form action="/employee/public/retirement" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="retirement" value="退職者" class="mr-2 mt-1 function-button table_reset">
					</form>


					<!-- 退職者年代別 -->
					<div class="dropdown">
						<!-- 切替ボタンの設定 -->
						<button type="button" class="mr-2 mt-1 function-button dropdown-toggle" id="dropdownMenuButton_taishokubi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							退社年別
						</button>
						<!-- ドロップメニューの設定 -->
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton_taishokubi">

							@foreach ($select_taishoku_year as $select_taishoku_years)
							<form action="/employee/public/taishokubi{{$select_taishoku_years->taishokunen}}" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="taishokubi" value="{{$select_taishoku_years->taishokunen}}年" class="mr-2 mt-1 table_reset" style="border:none; background-color:#fff">
							</form>
							@endforeach

						</div><!-- /.dropdown-menu -->
					</div><!-- /.dropdown -->

				</div>

				<!-- アラート -->
				<div class="col-12 mt-3 d-inline-flex">
					<!-- 未消化アラート -->
					<form action="/employee/public/mishouka" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="mishouka" value="未消化アラート一覧" class="mr-2 mt-1 function-button table_reset">
					</form>
					<!-- 残数僅少アラート -->
					<form action="/employee/public/zansu_kinshou" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="zansu_kinshou" value="残数僅少アラート一覧" class="mr-2 mt-1 function-button table_reset">
					</form>
					<!-- 時間外労働アラート -->
					<form action="/employee/public/overtime_working_alert" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="overtime_working_alert" value="時間外労働アラート" class="mr-2 mt-1 function-button table_reset">
					</form>
				</div>


				<p class="mt-4 mb-0 font-weight-bold">その他の機能</p>

				<div class="col-12 mt-2 d-inline-flex">
					<button type="submit" class="mr-2 mt-1 function-button" id="ajax_all_avg">平均年齢（在籍者）</button>

					<button type="submit" class="mr-2 mt-1 function-button" id="ajax_department_avg">平均年齢（部門別）</button>

					<button type="submit" class="mr-2 mt-1 function-button" id="ajax_gender_avg">平均年齢（男女別）</button>
				</div>


				<div class="col-12 mt-3 d-inline-flex">
					<button type="submit" class="mr-2 mt-1 function-button" id="ajax_all_count">人数（在籍者）</button>

					<button type="submit" class="mr-2 mt-1 function-button" id="ajax_department_count">人数（部門別）</button>

					<button type="submit" class="mr-2 mt-1 function-button" id="ajax_gender_count">人数（男女別）</button>

					<button type="submit" class="mr-2 mt-1 function-button" id="ajax_age_count">人数（年代別）</button>
				</div>


				<!-- その他機能ボタン押下時、ajax表示する場所 -->
				<div id="result_pre"></div>

			</div>
		</div>




		@if(isset($title))
		<div class="mt-5">
			<form action="/employee/public/add" method="POST">
				{{ csrf_field() }}
				<?php
				if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {
					$url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				} else {
					$url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . 'localhost/employee/public/';
				}
				?>
				<input type="hidden" name="url" value={{$url}}>
				<input type="hidden" name="scroll_top" value="" class="st">
				<button type="submit" class="btn btn-info btn-lg">新規作成</button>
			</form>
		</div>

		<div id="table-area">
			<div class="panel-heading font-weight-bold mt-5 text-center" style="font-size:30px; background-color:#F7F7EE;">
				{{ $title }}

				<p style="color:red; font-size:20px;" class="mt-2 mb-1">有給残日数が3日以下の人が対象です</p>
				<!-- DBのholidayテーブルに入力されている最新のデータ月 -->
				<p style="color:red; font-size:15px;">※{{$month}}月末時点のデータです</p>

				<p id="print" width="150" height="30"><a href="" class="btn btn-success btn-lg">このページを印刷</a></p>
			</div>
			@endif


			<!-- テーブル -->
			@if (count($employees2) > 0)
			<div class="panel panel-default mt-2">
				<div class="panel-body">
					<table class="table table-striped task-table" style="table-layout: fixed; width:100%;" id="data-teble">
						<thead>
							<tr>
								<th class="table-text hs-md-th1" style="min-width:30px">
									<div>操作</div>
								</th>
								<th class="table-text hs-md-th2" style="min-width:50px">
									<div>社員コード</div>
								</th>
								<th class="table-text hs-md-th3" style="min-width:50px">
									<div>社員名</div>
								</th>
								<!-- <th class="table-text" style="min-width:50px">
									<div>社員名（カナ）</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>社員名（ローマ字）</div>
								</th> -->
								<th class="table-text" style="min-width:50px">
									<div>メール</div>
								</th>
								<!-- <th class="table-text" style="min-width:50px">
									<div>性別</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>郵便番号</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>住所</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>住所（建物）</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>誕生日</div>
								</th> -->
								<th class="table-text" style="min-width:50px">
									<div>入社日</div>
								</th>
								<!-- <th class="table-text" style="min-width:50px">
									<div>正社員転換日</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>転籍日</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>退職日</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>社員携帯</div>
								</th> -->
								<!-- <th class="table-text">
									<div>電話番号</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>雇用保険番号</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>社会保険番号</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>基礎年金番号</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>月給</div>
								</th> -->
								<!-- <th class="table-text hs-md-th4" style="min-width:30px">
									<div>部門</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>名刺</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>idカード</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>扶養家族</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>入社試験点数</div>
								</th> -->
								<!-- <th class="table-text" style="min-width:50px">
									<div>備考</div>
								</th> -->
								<th class="table-text" style="min-width:50px">
									<div>基準月</div>
								</th>
								<th class="table-text" style="min-width:50px">
									<div>取得日数</div>
								</th>
								<th class="table-text" style="min-width:50px">
									<div>残日数</div>
								</th>
							</tr>
						</thead>
						<tbody>
							<!-- 対象データ分繰り返します -->
							@for ($i=0; $i < count($employees2); $i++) <tr>

								<td>
									<form action="/employee/public/show/{{$employees2[$i][0]->shain_cd}}" method="POST">
										{{ csrf_field() }}
										<?php
										if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {
											$url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
										} else {
											$url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . 'localhost/employee/public/';
										}
										?>
										<!-- 現在のURLとスクロール位置を次のページに送る -->
										<input type="hidden" name="url" value={{$url}}>
										<input type="hidden" name="scroll_top" value="" class="st">
										<button type="submit" class="btn btn-info mr-2">詳細</button>
									</form>


									<form action="/employee/public/delete/{{ $employees2[$i][0]->shain_cd }}" method="POST">
										{{ csrf_field() }}
										{{ method_field('DELETE') }}
										<input type="submit" name="delete" value="削除" onClick="delete_alert(event);return false;" class="btn btn-danger mt-2">
									</form>

								</td>

								<td class="table-text">
									<div>{{ $employees2[$i][0]->shain_cd }}</div>
								</td>
								<td class="table-text">
									<div>{{ $employees2[$i][0]->shain_mei }}</div>
								</td>
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->shain_mei_kana }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->shain_mei_romaji }}</div>
								</td> -->
								<td class="table-text">
									<div>{{ $employees2[$i][0]->shain_mail }}</div>
								</td>
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->gender }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->shain_zip_code }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->shain_jyusho }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->shain_jyusho_tatemono }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->shain_birthday }}</div>
								</td> -->
								<td class="table-text">
									<div>{{ $employees2[$i][0]->nyushabi }}</div>
								</td>
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->seishain_tenkanbi }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->tensekibi }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->taishokubi }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->shain_keitai }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->shain_tel }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->koyohoken_bango }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->shakaihoken_bango }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->kisonenkin_bango }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->monthly_saraly }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->department }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->name_card }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->id_card }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->fuyo_kazoku }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->test }}</div>
								</td> -->
								<!-- <td class="table-text">
									<div>{{ $employees2[$i][0]->remarks }}</div>
								</td> -->

								<!-- 基準月 -->
								<td class="table-text">
									<div>{{ $employees2_kijunbi[$i] }} 月</div>
								</td>
								<!-- 取得日数 -->
								<td class="table-text" style="color:red">
									<div>{{ $holidays[$i][4]}} 日</div>
								</td>
								<!-- 残日数 -->
								<td class="table-text" style="color:red">
									<div>{{$holidays[$i][5]}} 日</div>
								</td>

								</tr>

								@endfor
						</tbody>
					</table>

				</div>
			</div>
			@else
			<p class="mt-5 p-3" style="background-color: #F7F7EE">データはありません</p>
			@endif

		</div>

	</div>
</div>

<script>
	$('form').submit(function() {
		var scroll_top = $(window).scrollTop(); //送信時の位置情報を取得
		$('input.st', this).prop('value', scroll_top); //隠しフィールドに位置情報を設定
	});
	window.onload = function() {
		//ロード時に隠しフィールドから取得した値で位置をスクロール
		$(window).scrollTop(<?php echo @$_REQUEST['post_scroll_top']; ?>);
		var scroll_top = 0;
	}
</script>

@endsection