<x-header-layout>
	<div class="container">
		<main>
			<div class="py-5 text-center">
				@if (!Auth::check())
					<p class="lead text-warning mx-5 pb-2">Заявка создана без предварительной регистрации. Вы больше не сможете открыть
						данную страницу с подробной информацией заявки. Запомните номер заявки, для дальнейшего отслеживания статуса.</p>
				@endif
				<h2 class="fw-bold">Заявка №{{ $request_info->id }}</h2>
			</div>


			<div class="container" id="hanging-icons">
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
							<div class="fs-6">
								<h2 class="pt-2 mb-3">Личные данные:</h2>
								<p><strong> Заявитель:</strong> {{ $request_info->name }}</p>
								<p><strong>Email:</strong> {{ $request_info->email }}</p>
								<p><strong>Номер:</strong> {{ $request_info->phone_call_number }}</p>
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

							</div>
						</div>
					</div>
				</div>
			</div>



		</main>
	</div>

</x-header-layout>
