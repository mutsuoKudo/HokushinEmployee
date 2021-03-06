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


					<!-- ALL -->
					<form action="/all" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="all" value="ALL" class="mr-2 mt-1 function-button">
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
								<input type="submit" name="department" value="代表取締役" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/department2" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="department" value="管理部" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/department3" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="department" value="営業部" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/department4" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="department" value="システム開発部" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/department5" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="department" value="研修生" class="mr-2 mt-1" style="border:none; background-color:#fff">
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
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<form action="/employee/public/nyushabi2013" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="nyushabi" value="2013年" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/nyushabi2014" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="nyushabi" value="2014年" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/nyushabi2015" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="nyushabi" value="2015年" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/nyushabi2016" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="nyushabi" value="2016年" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/nyushabi2017" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="nyushabi" value="2017年" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/nyushabi2018" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="nyushabi" value="2018年" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/nyushabi2019" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="nyushabi" value="2019年" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
							<form action="/employee/public/nyushabi2020" method="GET" class="mt-2">
								{{ csrf_field() }}
								<input type="submit" name="nyushabi" value="2020年" class="mr-2 mt-1" style="border:none; background-color:#fff">
							</form>
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


					<!-- 退職者 -->
					<form action="/employee/public/retirement" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="retirement" value="退職者" class="mr-2 mt-1 function-button">
					</form>

				</div>


				<p class="mt-4 mb-0 font-weight-bold">その他の機能</p>

				<div class="col-12 mt-2 d-inline-flex">
					<form action="/employee/public/all_avg" method="GET">
						{{ csrf_field() }}
						<button type="submit" class="mr-2 mt-1 function-button">平均年齢（ALL）</button>
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


				<div class="col-12 mt-2 d-inline-flex">
					<form action="/employee/public/all_count" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="all_count" value="人数（ALL）" class="mr-2 mt-1 function-button">
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


				<!-- shainテーブルに上書き -->
				<!-- <div class="col-12 mt-2 d-inline-flex">
					<form action="/shain_update" method="GET">
						{{ csrf_field() }}
						<input type="submit" name="shain_update" value="shainテーブルアップデート" class="mr-2 mt-3 function-button2">
					</form>
				</div> -->

				<!-- 平均年齢（ALL） -->
				@if(isset($all_avg))
				<p class="mt-5 p-3" style="background-color: #F7F7EE">全社員の平均年齢は　{{ $all_avg }}　歳です</p>
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


				<!-- 人数（ALL） -->
				@if(isset($all_count))
				<p class="mt-5 p-3" style="background-color: #F7F7EE">全社員の人数は　{{ $all_count }}　人です</p>
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
			<form action="/employee/public/add" method="GET">
				{{ csrf_field() }}
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
				<table class="table table-striped task-table" style="table-layout: fixed; width:100%;">
					<tbody>
						<tr>
							<th class="table-text hs-md-th1" width="10%">
								<div>操作</div>
							</th>
							<th class="table-text hs-md-th2" width="12%">
								<div>社員コード</div>
							</th>
							<th class="table-text hs-md-th3">
								<div>社員名</div>
							</th>
							<th class="table-text">
								<div>社員名（カナ）</div>
							</th>
							<!-- <th class="table-text" width="4%"><div>社員名（ローマ字）</div></th> -->
							<th class="table-text">
								<div>メール</div>
							</th>
							<!-- <th class="table-text" width="4%"><div>性別</div></th> -->
							<!-- <th class="table-text" width="4%"><div>郵便番号</div></th> -->
							<!-- <th class="table-text" width="4%"><div>住所</div></th> -->
							<!-- <th class="table-text" width="4%"><div>住所（建物）</div></th> -->
							<!-- <th class="table-text" width="4%"><div>誕生日</div></th> -->
							<!-- <th class="table-text" width="4%"><div>入社日</div></th> -->
							<!-- <th class="table-text" width="4%"><div>正社員転換日</div></th> -->
							<!-- <th class="table-text" width="4%"><div>転籍日</div></th> -->
							<!-- <th class="table-text" width="4%"><div>退職日</div></th> -->
							<!-- <th class="table-text" width="4%"><div>社員形態</div></th> -->
							<th class="table-text">
								<div>電話番号</div>
							</th>
							<!-- <th class="table-text" width="4%"><div>雇用保険番号</div></th> -->
							<!-- <th class="table-text" width="4%"><div>社会保険番号</div></th> -->
							<!-- <th class="table-text" width="4%"><div>基礎年金番号</div></th> -->
							<!-- <th class="table-text" width="4%"><div>月給</div></th> -->
							<th class="table-text hs-md-th4" width="8%">
								<div>部門</div>
							</th>
							<!-- <th class="table-text" width="4%"><div>名刺</div></th> -->
							<!-- <th class="table-text" width="4%"><div>idカード</div></th> -->
							<!-- <th class="table-text" width="4%"><div>扶養家族</div></th> -->
							<th class="table-text">
								<div>備考</div>
							</th>

						</tr>
						@foreach ($employees as $employee)
						<tr>
							<!-- Task Delete Button -->
							<td>
								<form action="/employee/public/show/{{$employee->shain_cd}}" method="GET">
									{{ csrf_field() }}
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
							<!-- <td class="table-text"><div>{{ $employee->shain_mei_romaji }}</div></td> -->
							<td class="table-text">
								<div>{{ $employee->shain_mail }}</div>
							</td>
							<!-- <td class="table-text"><div>{{ $employee->gender }}</div></td>
										<td class="table-text"><div>{{ $employee->shain_zip_code }}</div></td>
										<td class="table-text"><div>{{ $employee->shain_jyusho }}</div></td>
										<td class="table-text"><div>{{ $employee->shain_jyusho_tatemono }}</div></td>
										<td class="table-text"><div>{{ $employee->shain_birthday }}</div></td>
										<td class="table-text"><div>{{ $employee->nyushabi }}</div></td>
										<td class="table-text"><div>{{ $employee->seishain_tenkanbi }}</div></td>
										<td class="table-text"><div>{{ $employee->tensekibi }}</div></td>
										<td class="table-text"><div>{{ $employee->taishokubi }}</div></td>
										<td class="table-text"><div>{{ $employee->shain_keitai }}</div></td> -->
							<td class="table-text">
								<div>{{ $employee->shain_tel }}</div>
							</td>
							<!-- <td class="table-text"><div>{{ $employee->koyohoken_bango }}</div></td>
										<td class="table-text"><div>{{ $employee->shakaihoken_bango }}</div></td>
										<td class="table-text"><div>{{ $employee->kisonenkin_bango }}</div></td>
										<td class="table-text"><div>{{ $employee->monthly_saraly }}</div></td> -->
							<td class="table-text">
								<div>{{ $employee->department }}</div>
							</td>
							<!-- <td class="table-text"><div>{{ $employee->name_card }}</div></td>
										<td class="table-text"><div>{{ $employee->id_card }}</div></td>
										<td class="table-text"><div>{{ $employee->fuyo_kazoku }}</div></td> -->
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
@endsection