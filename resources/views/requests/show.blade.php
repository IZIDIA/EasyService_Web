<x-header-layout>
	<div class="container">
		<main>
			<div class="my-4 text-center">
				@if (!Auth::check())
					<p class="lead text-warning">Заявка создана без предварительной регистрации. Вы больше не сможете открыть
						данную страницу с подробной информацией. Запомните номер заявки, для дальнейшего отслеживания статуса.</p>
				@endif
				<h2 class="fw-bold">Заявка №{{ $request_info->id }}</h2>
			</div>


			<div class="container" id="hanging-icons" style="word-break: break-all;">
				<h2 class="pb-2 border-bottom">Статус: @switch($request_info->status)
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
				</h2>
				<div class="row gx-2 py-2 row-cols-1 row-cols-lg-3">
					<div class="pt-3 col align-items-start">
						<div class="p-3 d-flex" style="border-radius: 10px; background-color:#283141; height: 100%;">
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
						<div class="p-3 d-flex" style="border-radius: 10px; background-color:#283141; height: 100%;">
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
					<div class="pt-3 col align-items-start ">
						<div class="p-3 d-flex" style="border-radius: 10px; background-color:#283141; height: 100%;">
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


				<div class="col p-3 mt-1 fs-5" style="border-radius: 10px; background-color:#283141; height: 100%;">
					<div class="col-lg-8 mx-auto">
						<div class="text-center mb-4">
							<p><strong>Тема: </strong>{{ $request_info->topic }}</p>
						</div>
						<div class="mb-3">
							<strong>Сообщение:</strong>
							<textarea class="form-control" rows="15" minlength="1" readonly>{{ $request_info->text }}</textarea>
						</div>
					</div>

				</div>


			</div>



		</main>
	</div>

</x-header-layout>
