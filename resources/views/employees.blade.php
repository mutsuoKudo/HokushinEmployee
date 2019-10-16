@extends('layouts.app')

@section('content')

<!-- 更新完了時に表示されるメッセージ -->
@if (session('status'))<div class="alert alert-success" role="alert" onclick="this.classList.add('hidden')">{{ session('status') }}</div>@endif
<!-- 削除完了時に表示されるメッセージ -->
@if (session('delete'))<div class="alert alert-success" role="alert" onclick="this.classList.add('hidden')">{{ session('delete') }}</div>@endif
<!-- 新規登録完了時に表示されるメッセージ -->
@if (session('create'))<div class="alert alert-success" role="alert" onclick="this.classList.add('hidden')">{{ session('create') }}</div>@endif

<div class="container">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<!-- <div class="panel-heading">
					New Book
				</div> -->

			<div class="panel-body">
				<!-- Display Validation Errors -->
				@include('common.errors')

				<!-- Button -->
				<p class="mt-3 mb-0 font-weight-bold">名簿表示</p>

				<div class="col-12 mt-2 d-inline-flex">


					<!-- 在籍者 -->
					<form action="/employee/public/all" method="GET">

						<input type="submit" name="all" value="在籍者" class="mr-2 mt-1 function-button" id="reset">
					</form>


					<!-- 部門別 -->
					<div class="dropdown">
						<!-- 切替ボタンの設定 -->
						<button type="button" class="mr-2 mt-1 function-button dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							部門別
						</button>
						<!-- ドロップメニューの設定 -->
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
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
						<button type="button" class="mr-2 mt-1 function-button dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							入社年別
						</button>
						<!-- ドロップメニューの設定 -->
						<div class="dropdown-menu overflow-auto" style="height:250px;" aria-labelledby="dropdownMenuButton">

							@foreach ($select_nyusha_year as $select_nyusha_years)
							<form action="/employee/public/nyushabi{{$select_nyusha_years->nyushanen}}" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="nyushabi" value="{{$select_nyusha_years->nyushanen}}年" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							@endforeach

						</div><!-- /.dropdown-menu -->
					</div><!-- /.dropdown -->


					<!-- 年代別 -->
					<div class="dropdown">
						<!-- 切替ボタンの設定 -->
						<button type="button" class="mr-2 mt-1 function-button dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							年代別
						</button>
						<!-- ドロップメニューの設定 -->
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<form action="/employee/public/age20" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="age" value="20代" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/age30" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="age" value="30代" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/age40" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="age" value="40代" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/age50" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="age" value="50代" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/age60" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="age" value="60代" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/age_other" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="age" value="その他の年代" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
						</div><!-- /.dropdown-menu -->
					</div><!-- /.dropdown -->


					<!-- 有給基準月別 -->
					<div class="dropdown">
						<!-- 切替ボタンの設定 -->
						<button type="button" class="mr-2 mt-1 function-button dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							有給基準月別
						</button>
						<!-- ドロップメニューの設定 -->
						<div class="dropdown-menu overflow-auto" style="height:250px;" aria-labelledby="dropdownMenuButton">
							<form action="/employee/public/kijun_month01" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="1月" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month02" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="2月" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month03" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="3月" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month04" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="4月" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month05" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="5月" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month06" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="6月" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month07" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="7月" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month08" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="8月" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month09" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="9月" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month10" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="10月" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month11" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="11月" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/kijun_month12" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="kijun_month" value="12月" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>

						</div><!-- /.dropdown-menu -->
					</div><!-- /.dropdown -->


					<!-- 退職者 -->
					<form action="/employee/public/retirement" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="retirement" value="退職者" class="mr-2 mt-1 function-button">
					</form>

					<!-- 退職者年代別 -->
					<div class="dropdown">
						<!-- 切替ボタンの設定 -->
						<button type="button" class="mr-2 mt-1 function-button dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							退社年別
						</button>
						<!-- ドロップメニューの設定 -->
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

							@foreach ($select_taishoku_year as $select_taishoku_years)
							<form action="/employee/public/taishokubi{{$select_taishoku_years->taishokunen}}" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="taishokubi" value="{{$select_taishoku_years->taishokunen}}年" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							@endforeach

						</div><!-- /.dropdown-menu -->
					</div><!-- /.dropdown -->

				</div>


				<p class="mt-4 mb-0 font-weight-bold">その他の機能</p>

				<div class="col-12 mt-2 d-inline-flex">
					<form action="/employee/public/all_avg" method="GET">
						{{ csrf_field() }}
						<button type="submit" class="mr-2 mt-1 function-button">平均年齢（在籍者）</button>
					</form>

					<form action="/employee/public/department_avg" method="GET">
						{{ csrf_field() }}
						<button type="submit" class="mr-2 mt-1 function-button">平均年齢（部門別）</button>
					</form>

					<form action="/employee/public/gender_avg" method="GET">
						{{ csrf_field() }}
						<button type="submit" class="mr-2 mt-1 function-button">平均年齢（男女別）</button>
					</form>
				</div>


				<div class="col-12 mt-2 d-inline-flex mt-3">
					<form action="/employee/public/all_count" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="all_count" value="人数（在籍者）" class="mr-2 mt-1 function-button">
					</form>

					<form action="/employee/public/department_count" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="all_department" value="人数（部門別）" class="mr-2 mt-1 function-button">
					</form>

					<form action="/employee/public/gender_count" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="all_gender" value="人数（男女別）" class="mr-2 mt-1 function-button">
					</form>

					<form action="/employee/public/age_count" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="age_count" value="人数（年代別）" class="mr-2 mt-1 function-button">
					</form>
				</div>


				<div class="col-12 mt-2 d-inline-flex mt-3">
					<form action="/employee/public/mishouka" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="all_count" value="未消化アラート一覧" class="mr-2 mt-1 function-button">
					</form>

					<form action="/employee/public/zansu_kinshou" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="all_department" value="残数僅少アラート一覧" class="mr-2 mt-1 function-button">
					</form>
				</div>


				<!-- shainテーブルに上書き -->
				<!-- <div class="col-12 mt-2 d-inline-flex">
					<form action="/shain_update" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="shain_update" value="shainテーブルアップデート" class="mr-2 mt-3 function-button2">
					</form>
				</div> -->

				<!-- 平均年齢（在籍者） -->
				@if(isset($all_avg))
				<p class="mt-5 p-3" style="background-color: #F7F7EE">全在籍者の平均年齢は　{{ $all_avg }}　歳です</p>
				@endif

				<!-- 平均年齢（部門別） -->
				@if(isset($department_avg1))
				<p class="mt-5 p-3" style="background-color: #F7F7EE">代表取締役の平均年齢は　{{ $department_avg1 }}　歳です</p>
				@endif
				@if(isset($department_avg2))
				<p class="p-3" style="background-color: #F7F7EE">管理部の平均年齢は　{{ $department_avg2 }}　歳です</p>
				@endif
				@if(isset($department_avg3))
				<p class="p-3" style="background-color: #F7F7EE">営業部の平均年齢は　{{ $department_avg3 }}　歳です</p>
				@endif
				@if(isset($department_avg4))
				<p class="p-3" style="background-color: #F7F7EE">システム開発部の平均年齢は　{{ $department_avg4 }}　歳です</p>
				@endif
				@if(isset($department_avg5))
				<p class="p-3" style="background-color: #F7F7EE">研修生の平均年齢は　{{ $department_avg5 }}　歳です</p>
				@endif

				<!-- 平均年齢（男女別） -->
				@if(isset($gender_avg1))
				<p class="mt-5 p-3" style="background-color: #F7F7EE">男性の平均年齢は　{{ $gender_avg1 }}　歳です</p>
				@endif
				@if(isset($gender_avg2))
				<p class="p-3" style="background-color: #F7F7EE">女性の平均年齢は　{{ $gender_avg2 }}　歳です</p>
				@endif


				<!-- 人数（在籍者） -->
				@if(isset($all_count))
				<p class="mt-5 p-3" style="background-color: #F7F7EE">全在籍者の人数は　{{ $all_count }}　人です</p>
				@endif

				<!-- 人数（部門別） -->
				@if(isset($all_department1))
				<p class="mt-5 p-3" style="background-color: #F7F7EE">代表取締役の人数は　{{ $all_department1 }}　人です</p>
				@endif
				@if(isset($all_department2))
				<p class="p-3" style="background-color: #F7F7EE">管理部の人数は　{{ $all_department2 }}　人です</p>
				@endif
				@if(isset($all_department3))
				<p class="p-3" style="background-color: #F7F7EE">営業部の人数は　{{ $all_department3 }}　人です</p>
				@endif
				@if(isset($all_department4))
				<p class="p-3" style="background-color: #F7F7EE">システム開発部の人数は　{{ $all_department4 }}　人です</p>
				@endif
				@if(isset($all_department5))
				<p class="p-3" style="background-color: #F7F7EE">研修生の人数は　{{ $all_department5 }}　人です</p>
				@endif

				<!-- 人数（男女別） -->
				@if(isset($all_gender1))
				<p class="mt-5 p-3" style="background-color: #F7F7EE">男性の人数は　{{ $all_gender1 }}　人です</p>
				@endif
				@if(isset($all_gender2))
				<p class="p-3" style="background-color: #F7F7EE">女性の人数は　{{ $all_gender2 }}　人です</p>
				@endif


				@if(isset($age1))
				<p class="mt-5 p-3" style="background-color: #F7F7EE">20代の人数は　{{ $age1 }}　人です</p>
				@endif
				@if(isset($age2))
				<p class="p-3" style="background-color: #F7F7EE">30代の人数は　{{ $age2 }}　人です</p>
				@endif
				@if(isset($age3))
				<p class="p-3" style="background-color: #F7F7EE">40代の人数は　{{ $age3 }}　人です</p>
				@endif
				@if(isset($age4))
				<p class="p-3" style="background-color: #F7F7EE">50代の人数は　{{ $age4 }}　人です</p>
				@endif
				@if(isset($age5))
				<p class="p-3" style="background-color: #F7F7EE">60代の人数は　{{ $age5 }}　人です</p>
				@endif
				@if(isset($age6))
				<p class="p-3" style="background-color: #F7F7EE">その他の人数は　{{ $age6 }}　人です</p>
				@endif

			</div>
		</div>

		<!-- テーブル -->
		@if(isset($title))
		<div class="mt-5">
			<form action="/employee/public/add" method="POST">
				{{ csrf_field() }}
				<?php
				$url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
				?>
				<input type="hidden" name="url" value={{$url}}>
				<input type="hidden" name="scroll_top" value="" class="st">
				<button type="submit" class="btn btn-info btn-lg">新規作成</button>
			</form>
			<div class="panel-heading font-weight-bold mt-5 text-center" style="font-size:30px; background-color:#F7F7EE;">
				{{ $title }}
			</div>
		</div>
		@endif

		@if (count($employees) > 0)
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
							<th class="table-text" style="min-width:50px">
								<div>社員名（カナ）</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>社員名（ローマ字）</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>メール</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>性別</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>郵便番号</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>住所</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>住所（建物）</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>誕生日</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>入社日</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>正社員転換日</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>転籍日</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>退職日</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>社員携帯</div>
							</th>
							<th class="table-text">
								<div>電話番号</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>雇用保険番号</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>社会保険番号</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>基礎年金番号</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>月給</div>
							</th>
							<th class="table-text hs-md-th4" style="min-width:30px">
								<div>部門</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>名刺</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>idカード</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>扶養家族</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>入社試験点数</div>
							</th>
							<th class="table-text" style="min-width:50px">
								<div>備考</div>
							</th>

						</tr>
					</thead>
					<tbody>
						@foreach ($employees as $employee)
						<tr>
							<!-- Task Delete Button -->
							<td>
								<form action="/employee/public/show/{{$employee->shain_cd}}" method="POST">
									{{ csrf_field() }}
									<?php
									$url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
									?>
									<input type="hidden" name="url" value={{$url}}>
									<input type="hidden" name="scroll_top" value="" class="st">
									<button type="submit" class="btn btn-info mr-2">詳細</button>
								</form>


								<form action="/employee/public/delete/{{ $employee->shain_cd }}" method="POST">
									{{ csrf_field() }}
									{{ method_field('DELETE') }}
									<input type="submit" name="delete" value="削除" onClick="delete_alert(event);return false;" class="btn btn-danger mt-2">
								</form>

							</td>

							<td class="table-text">
								<div>{{ $employee->shain_cd }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->shain_mei }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->shain_mei_kana }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->shain_mei_romaji }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->shain_mail }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->gender }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->shain_zip_code }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->shain_jyusho }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->shain_jyusho_tatemono }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->shain_birthday }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->nyushabi }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->seishain_tenkanbi }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->tensekibi }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->taishokubi }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->shain_keitai }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->shain_tel }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->koyohoken_bango }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->shakaihoken_bango }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->kisonenkin_bango }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->monthly_saraly }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->department }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->name_card }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->id_card }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->fuyo_kazoku }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->test }}</div>
							</td>
							<td class="table-text">
								<div>{{ $employee->remarks }}</div>
							</td>

						</tr>
						@endforeach
					</tbody>
				</table>

			</div>
		</div>
		@else
		<p class="mt-5 p-3" style="background-color: #F7F7EE">データはありません</p>
		@endif

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
	// document.getElementById('reset').onclick = function() {
	// 	window.alert("クリックしたよ");
	// 	$('#data-teble').DataTable().state.clear();
	// 	window.location.reload();
	// }

	// 	$("#reset").on("click", function(){
	//   $('#data-teble').DataTable().state.clear();
	//   window.alert("クリックしたよ");
	// });

</script>

@endsection