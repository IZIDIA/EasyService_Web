<x-header-layout>

	<main class="container ">

		<a type="button" class="d-flex justify-content-center mt-3 btn btn-primary btn-lg fw-bold fs-4"
			href="/requests/create">Создать заявку</a>

		<div class="my-3 p-3 rounded shadow-sm text-white fs-5" style="background-color: #1A202C">
			<div class="d-flex justify-content-between mb-2">
				<span class="border-bottom pb-2 mb-0">Ваши заявки:</span>
				<div class="d-flex">
					<form class="  me-2 ">
						<input size="10" type="search" class="form-control form-control-dark" placeholder="№ заявки" aria-label="Search">
					</form>
					<a type="button" class="btn btn-outline-info fw-bold" href="/">Узнать статус</a>
				</div>
			</div>


			@if (Auth::check())


				@forelse ($request_infos as $request_info)
					<a href="/requests/{{ $request_info->id }}" class="requestlink rounded-3 d-flex pt-3" style="text-decoration: none;">

						@switch($request_info->status)
							@case('В обработке')
								<i class="bi bi-info-square-fill me-3 ms-2" style="font-size: 2rem; color: rgb(0, 255, 255);"></i>
							@break

							@case('В работе')
								<i class="bi bi-person-circle me-3 ms-2" style="font-size: 2rem; color: rgb(255, 157, 0);"></i>
							@break

							@case('Завершено')
								<i class="bi bi-check-circle-fill me-3 ms-2" style="font-size: 2rem; color: rgb(0, 255, 0);"></i>
							@break

							@case('Отменено')
								<i class="bi bi-x-octagon-fill me-3 ms-2" style="font-size: 2rem; color: rgb(173, 0, 0);"></i>
							@break

							@default
								<i class="bi bi-patch-question-fill me-3 ms-2" style="font-size: 2rem; color: white"></i>
						@endswitch


						<div class="pb-3 mb-0 lh-sm w-100">
							<div class="d-flex justify-content-between">
								<div>
									<strong
										class="text-gray-dark text-warning">{{ '№' . $request_info->id . ' ' . Str::limit($request_info->topic, 25) }}
									</strong>
									<span class="d-block text-white fst-italic">{{ $request_info->created_at->format('d.m.y H:i') }}</span>
								</div>
								@switch($request_info->status)
									@case('В обработке')
										<span class="me-2 mt-2" style="color: rgb(0, 255, 255)">{{ $request_info->status }}</span>
									@break

									@case('В работе')
										<span class="me-2 mt-2" style="color: rgb(255, 157, 0)">{{ $request_info->status }}</span>
									@break

									@case('Завершено')
										<span class="me-2 mt-2" style="color: rgb(0, 255, 0)">{{ $request_info->status }}</span>
									@break

									@case('Отменено')
										<span class="me-2 mt-2" style="color: rgb(173, 0, 0)">{{ $request_info->status }}</span>
									@break

									@default
										<span class="me-2 mt-2" style="color: white">{{ $request_info->status }}</span>
								@endswitch

							</div>

						</div>
					</a>
					@empty
						<div class="pt-3 ms-2 text-warning">Нет созданных вами заявок</div>
					@endforelse
				@else
					<div class="pt-3 ms-2 text-warning">Зарегистрируйтесь для удобного просмотра статуса заявок</div>
				@endif
			</div>

		</main>

	</x-header-layout>
