<x-admin-layout>

	<div class="container">
		<main>
			<div class="my-4 d-flex justify-content-center">
				<div class="d-flex align-items-center fw-bold me-2 fs-3">
					Заявка №{{ $request_info->id }}
				</div>
				<div class="fs-5 d-flex align-items-center" style="color: #96FBFE"> [Исполнитель:
					{{ App\Models\User::firstWhere('id', $request_info->admin_id)->name ?? 'Не назначен' }}]
				</div>
			</div>

			<div class="container" id="hanging-icons" style="word-break: break-all;">

				<div class="pb-1 px-1 border-bottom row row-cols-1 row-cols-lg-2">
					<div class="d-flex align-items-center fs-2 gap-2">Статус: @switch($request_info->status)
							@case('В обработке')
								<span class="fw-bold" style="color: rgb(0, 255, 255)">{{ $request_info->status }}</span>
							@break

							@case('В работе')
								<span class="fw-bold" style="color: rgb(255, 157, 0)">{{ $request_info->status }}</span>
							@break

							@case('Завершено')
								<span class="fw-bold" style="color: rgb(0, 255, 0)">{{ $request_info->status }}</span>
							@break

							@case('Отменено')
								<span class="fw-bold" style="color: rgb(173, 0, 0)">{{ $request_info->status }}</span>
							@break

							@default
								<span class="fw-bold" style="color: white">{{ $request_info->status }}</span>
						@endswitch
					</div>
					<div class="d-flex align-items-center flex-row-reverse gap-2 fs-5" style="color: #96FBFE">
						@if (!($request_info->status == 'Отменено' || $request_info->status == 'Завершено'))
							<form action="/admin/requests/{{ $request_info->id }}/cancel" method="POST">
								@method('PATCH')
								@csrf
								<button type="submit" class="shadow btn btn-danger">Отменить</button>
							</form>
						@endif
						@if ($request_info->status == 'В обработке')
							<form action="/admin/requests/{{ $request_info->id }}/accept" method="POST">
								@method('PATCH')
								@csrf
								<button type="submit" class="shadow btn btn-success">Взять</button>
							</form>
						@endif
						@if ($request_info->status == 'В работе')
							<form action="/admin/requests/{{ $request_info->id }}/deny" method="POST">
								@method('PATCH')
								@csrf
								<button type="submit" class="shadow btn btn-warning">Отказаться</button>
							</form>
						@endif
						@if ($request_info->status == 'В работе')
							<form action="/admin/requests/{{ $request_info->id }}/complete" method="POST">
								@method('PATCH')
								@csrf
								<button type="submit" class="shadow btn btn-info">Завершить</button>
							</form>
						@endif
						@if ($request_info->status == 'Отменено' || $request_info->status == 'Завершено')
							<form action="/admin/requests/{{ $request_info->id }}/restore" method="POST">
								@method('PATCH')
								@csrf
								<button type="submit" class="shadow btn btn-secondary">Восстановить</button>
							</form>
						@endif
					</div>
				</div>

				<div class="row gx-2 pt-2 ">
					<div class="pt-3 col-lg-8 align-items-start">
						<div class="p-3 d-flex shadow-sm" style="border-radius: 10px; background-color:#283141; height: 190px;">
							@if ($comments !== null)
								<pre id="comments" class="rounded" style="
								width: 100%;
								height:100%; 
								white-space: pre-wrap;
								font-family: Consolas, Roboto;
								font-size: 14px;
								">
@foreach ($comments as $item)
>[{{ $item['Time'] }}] {{ $item['Name'] }}: {{ $item['Message'] }}
@endforeach
</pre>
							@endif
							<script type="text/javascript">
							 var block = document.getElementById("comments");
							 block.scrollTop = block.scrollHeight;
							</script>
						</div>
					</div>
					<div class="pt-3 col-lg-4 align-items-start">
						<div class="p-3 shadow-sm" style="border-radius: 10px; background-color:#283141; height: 100%;">
							<strong>Коментарий к заявке:</strong>
							<form action="/admin/requests/{{ $request_info->id }}/comment" method="POST">
								<textarea maxlength="512" style="resize:none;" class="form-control mt-1" rows="3" minlength="1" name="comment_text"
         id="comment_text"></textarea>
								<div class="d-flex justify-content-end mt-2">
									@method('PATCH')
									@csrf
									<button disabled id="comment_btn" class="shadow btn btn-primary">Добавить</button>
								</div>
							</form>
							<script>
							 var input = document.getElementById('comment_text');
							 input.oninput = function() {
							  var element = document.getElementById('comment_text').value.length;
							  if (element == 0)
							   document.getElementById('comment_btn').disabled = true;
							  else
							   document.getElementById('comment_btn').disabled = false;
							 };
							</script>
						</div>
					</div>
				</div>

				<div class="row gx-2 row-cols-1 row-cols-lg-3">
					<div class="pt-3 col align-items-start">
						<div class="p-3 d-flex shadow-sm" style="border-radius: 10px; background-color:#283141; height: 100%;">
							<div class="icon-square bg-dark text-light flex-shrink-0 me-3">
								<i class="bi bi-person-fill"></i>
							</div>
							<div>
								<h2 class="pt-2 mb-3">Личные данные:</h2>
								<p><strong> Заявитель:</strong> {{ $request_info->name }}</p>
								<p><strong>Email:</strong> {{ $request_info->email }}</p>
								<p><strong>Номер:</strong> {{ $request_info->phone_call_number }}</p>
								<p><strong>IP-адрес:</strong> {{ $request_info->ip_address }}</p>
								<p><strong>Дата создания:</strong> {{ $request_info->created_at->format('d.m.y H:i') }}</p>
								@if ($request_info->closed_at !== null)
									<p><strong>Дата завершения:</strong> {{ $request_info->closed_at }}</p>
								@endif
							</div>
						</div>
					</div>
					<div class="pt-3 col align-items-start ">
						<div class="p-3 d-flex shadow-sm" style="border-radius: 10px; background-color:#283141; height: 100%;">
							<div class="icon-square bg-dark text-light flex-shrink-0 me-3">
								<i class="bi bi-geo-alt-fill"></i>
							</div>
							<div>
								<h2 class="pt-2 mb-3">Локация:</h2>
								<p><strong>Местонахождение:</strong> {{ $request_info->location }}</p>
								<p><strong>Инвентарный номер:</strong> {{ $request_info->inventory_number }}</p>
								<p><strong>Отправлено из приложения:</strong> @switch($request_info->from_pc)
										@case(1)
											Да
										@break

										@case(0)
											Нет
										@break

										@default
											Неизвестно
									@endswitch
								</p>
							</div>
						</div>
					</div>
					<div class="pt-3 col align-items-start">
						<div class="p-3 d-flex shadow-sm" style="border-radius: 10px; background-color:#283141; height: 100%;">
							<div class="icon-square bg-dark text-light flex-shrink-0 me-3">
								<i class="bi bi-toggles"></i>
							</div>
							<div>
								<h2 class="pt-2 mb-3">Опциально:</h2>
								<p><strong>Работа в присутствии заявителя:</strong>
									@if ($request_info->solution_with_me !== null)
										@switch($request_info->solution_with_me)
											@case(1)
												Да
											@break

											@case(0)
												Нет
											@break

											@default
												Неизвестно
										@endswitch
								</p>
								<strong>График работы:</strong>
								<pre>{{ $request_info->work_time }}</pre>
							@else
								Неважно
								@endif

								<p><strong>Проблема с ПК заявителя: </strong>
									@switch($request_info->problem_with_my_pc)
										@case(1)
											Да
										@break

										@case(0)
											Нет
										@break

										@default
											Неизвестно
									@endswitch
								</p>
								@if ($request_info->user_password !== null)
									<p><strong>Пароль пользователя: </strong>{{ $request_info->user_password }}</p>
								@endif
							</div>
						</div>
					</div>
				</div>

				@if ($request_info->photo == null)
					<div class="pt-3">
						<div class="col p-3 mt-1 fs-5 shadow-sm"
							style="border-radius: 10px; background-color:#283141; height: 100%; min-height: 396px">
							<div class="col-lg-8 mx-auto">
								<div class="text-center ">
									<p class="m-0"><strong>Тема: </strong>{{ $request_info->topic }}</p>
								</div>
								<div class="mb-3">
									<strong>Текст заявки:</strong>
									<textarea style="resize:none; background-color: #212529" class="form-control text-white" rows="12" minlength="1"
          readonly>{{ $request_info->text }}</textarea>
								</div>
							</div>
						</div>
					</div>
				@else
					<div class="row gx-2">
						<div class="pt-3 col-lg-8 align-items-start">
							<div class="p-3 shadow-sm"
								style="border-radius: 10px; background-color:#283141; height: 100%; min-height: 396px">
								<div class="col-lg-10 mx-auto fs-5">
									<div class="text-center">
										<p class="m-0"><strong>Тема: </strong>{{ $request_info->topic }}</p>
									</div>
									<div class="pb-3">
										<strong>Текст заявки:</strong>
										<textarea style="resize:none; background-color: #212529;" class="form-control text-white" rows="12" minlength="1"
           readonly>{{ $request_info->text }}</textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="pt-3 col-lg-4 align-items-start">
							<div class="p-3 d-flex flex-column shadow-sm"
								style="border-radius: 10px; background-color:#283141; height: 100%; max-height: 396px">
								<div class="d-flex">
									<div class="icon-square bg-dark text-light flex-shrink-0 me-3">
										<i class="bi bi-image"></i>
									</div>
									<div>
										<h2 class="pt-2 mb-3">Изображение:</h2>
									</div>
								</div>
								<div class="d-flex justify-content-center align-items-center" style="flex-grow: 1;">
									<img src="{{ asset('storage/' . $request_info->photo) }}" style="max-height: 305px"
										class="rounded img-fluid shadow zoom-dark" alt="...">
								</div>
							</div>
						</div>
					</div>
				@endif

			</div>

		</main>
	</div>

</x-admin-layout>