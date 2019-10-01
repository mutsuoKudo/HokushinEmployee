@extends('layouts.app')

@section('content')
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
					<p class="mt-3">名簿表示</p>
					<div class="col-12 mt-2">
						<button class="mr-2 mt-1 function-button">ALL</button>
						<button class="mr-2 mt-1 function-button">部門別</button>
						<button class="mr-2 mt-1 function-button">入社年別</button>
						<button class="mr-2 mt-1 function-button">年代別</button>
					</div>

					<p class="mt-3">その他の機能</p>
					<div class="col-12 mt-2">
						<button class="mr-2 mt-1 function-button">平均年齢（ALL）</button>
						<button class="mr-2 mt-1 function-button">平均年齢（部門別）</button>
						<button class="mr-2 mt-1 function-button">平均年齢（男女別）</button>
					</div>

					<div class="col-12 mt-2">
						<button class="mr-2 mt-1 function-button">人数（ALL）</button>
						<button class="mr-2 mt-1 function-button">人数（部門別）</button>
						<button class="mr-2 mt-1 function-button">人数（男女別）</button>
						<button class="mr-2 mt-1 function-button">人数（年代別）</button>
					</div>

					<!-- New Book Form -->
					<!-- <form action="/employee" method="POST" class="form-horizontal">
						{{ csrf_field() }} -->

						<!-- Book Name -->
						<!-- <div class="form-group">
							<label for="task-name" class="col-sm-3 control-label">Book</label>

							<div class="col-sm-6">
								<input type="text" name="name" id="employee-name" class="form-control" value="{{ old('employee') }}">
							</div>
						</div> -->

						<!-- Add Book Button -->
						<!-- <div class="form-group">
							<div class="col-sm-offset-3 col-sm-6">
								<button type="submit" class="btn btn-default">
									<i class="fa fa-plus"></i>本を追加する
								</button>
							</div>
						</div>
					</form> -->
				</div>
			</div>

			<!-- Books -->
			@if (count($employees) > 0)
				<div class="panel panel-default mt-5">
					<div class="panel-heading">
						社員一覧
					</div>

					<div class="panel-body">
						<table class="table table-striped task-table" style="table-layout: fixed; width:100%;">
							<thead>
								<th>ALL</th>
								<th>&nbsp;</th>
							</thead>
							<tbody>
								<tr>
									<th class="table-text" width="20% !important"><div>操作</div></th>
									<th class="table-text" width="4%"><div>社員コード</div></th>
									<th class="table-text" width="4%"><div>社員名</div></th>
									<th class="table-text" width="4%"><div>社員名（カナ）</div></th>
									<!-- <th class="table-text" width="4%"><div>社員名（ローマ字）</div></th> -->
									<th class="table-text" width="4%"><div>メール</div></th>
									<!-- <th class="table-text" width="4%"><div>性別</div></th> -->
									<!-- <th class="table-text" width="4%"><div>ジップコード</div></th> -->
									<!-- <th class="table-text" width="4%"><div>住所</div></th> -->
									<!-- <th class="table-text" width="4%"><div>住所（建物）</div></th> -->
									<!-- <th class="table-text" width="4%"><div>誕生日</div></th> -->
									<!-- <th class="table-text" width="4%"><div>入社日</div></th> -->
									<!-- <th class="table-text" width="4%"><div>正社員転換日</div></th> -->
									<!-- <th class="table-text" width="4%"><div>転籍日</div></th> -->
									<!-- <th class="table-text" width="4%"><div>退職日</div></th> -->
									<!-- <th class="table-text" width="4%"><div>社員携帯</div></th> -->
									<th class="table-text" width="4%"><div>電話番号</div></th>
									<!-- <th class="table-text" width="4%"><div>雇用保険番号</div></th> -->
									<!-- <th class="table-text" width="4%"><div>社会保険番号</div></th> -->
									<!-- <th class="table-text" width="4%"><div>基礎年金番号</div></th> -->
									<!-- <th class="table-text" width="4%"><div>月給</div></th> -->
									<th class="table-text" width="4%"><div>部門</div></th>
									<!-- <th class="table-text" width="4%"><div>名刺？</div></th> -->
									<!-- <th class="table-text" width="4%"><div>idカード</div></th> -->
									<!-- <th class="table-text" width="4%"><div>扶養家族</div></th> -->
									<th class="table-text" width="4%"><div>備考</div></th>
									
								</tr>
								@foreach ($employees as $employee)
								<tr>
										<!-- Task Delete Button -->
										<td>
											<!-- <form action="/employee/{{ $employee->shain_cd }}" method="POST">
												{{ csrf_field() }}
												{{ method_field('DELETE') }} -->
		
												<!-- <button type="submit" class="btn btn-info"> -->
												<button a href="{{ route('employees.edit', ['id' => $employee->shain_cd]) }}" data-toggle="modal" class="edit-icon">
													<i class="fa fa-trash"></i>詳細
												</button>
											<!-- </form> -->
											<form action="/employee/{{ $employee->shain_cd }}" method="POST">
												{{ csrf_field() }}
												{{ method_field('DELETE') }}
		
												<button type="submit" class="btn btn-danger mt-2">
													<i class="fa fa-trash"></i>削除
												</button>
											</form>
											
										</td>

										<td class="table-text"><div>{{ $employee->shain_cd }}</div></td>
										<td class="table-text"><div>{{ $employee->shain_mei }}</div></td>
										<td class="table-text"><div>{{ $employee->shain_mei_kana }}</div></td>
										<!-- <td class="table-text"><div>{{ $employee->shain_mei_romaji }}</div></td> -->
										<td class="table-text"><div>{{ $employee->shain_mail }}</div></td>
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
										<td class="table-text"><div>{{ $employee->shain_tel }}</div></td>
										<!-- <td class="table-text"><div>{{ $employee->koyohoken_bango }}</div></td>
										<td class="table-text"><div>{{ $employee->shakaihoken__bango }}</div></td>
										<td class="table-text"><div>{{ $employee->kisonenkin__bango }}</div></td>
										<td class="table-text"><div>{{ $employee->monthly_saraly }}</div></td> -->
										<td class="table-text"><div>{{ $employee->department }}</div></td>
										<!-- <td class="table-text"><div>{{ $employee->name_card }}</div></td>
										<td class="table-text"><div>{{ $employee->id_card }}</div></td>
										<td class="table-text"><div>{{ $employee->fuyo_kazoku }}</div></td> -->
										<td class="table-text"><div>{{ $employee->remarks }}</div></td>

									</tr>
								@endforeach
							</tbody>
						</table>

					</div>
				</div>
			@endif
		</div>
	</div>
@endsection