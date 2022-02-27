<x-header-layout>

	<main class="container ">

		<a type="button" class="d-flex justify-content-center mt-3 btn btn-primary btn-lg fw-bold fs-4"
			href="/requests/create">Создать заявку</a>


		<div class="my-3 p-3 rounded shadow-sm text-white fs-5" style="background-color: #1A202C">
			<h5 class="border-bottom pb-2 mb-0">Ваши заявки:</h5>
			@forelse ($request_infos as $request_info)
				<a href="/" class="requestlink rounded-3 d-flex pt-3" style="text-decoration: none;">

					@switch($request_info->status)
						@case('Ожидает обработки')
							<i class="bi bi-info-square-fill me-2 ms-2" style="font-size: 2rem; color: rgb(0, 255, 255);"></i>
						@break

						@case('В процессе')
							<i class="bi bi-person-circle me-2 ms-2" style="font-size: 2rem; color: rgb(255, 157, 0);"></i>
						@break

						@case('Завершено')
							<i class="bi bi-check-circle-fill me-2 ms-2" style="font-size: 2rem; color: rgb(0, 255, 0);"></i>
						@break

						@case('Отменено')
							<i class="bi bi-x-octagon-fill me-2 ms-2" style="font-size: 2rem; color: rgb(173, 0, 0);"></i>
						@break

						@default
							<i class="bi bi-patch-question-fill me-2 ms-2" style="font-size: 2rem; color: white"></i>
					@endswitch




					<div class="pb-3 mb-0 lh-sm w-100">
						<div class="d-flex justify-content-between">
							<strong class="text-gray-dark text-warning">{{ $request_info->topic }}</strong>

							@switch($request_info->status)
								@case('Ожидает обработки')
									<span class="me-2" style="color: rgb(0, 255, 255)">{{ $request_info->status }}</span>
								@break

								@case('В процессе')
									<span class="me-2" style="color: rgb(255, 157, 0)">{{ $request_info->status }}</span>
								@break

								@case('Завершено')
									<span class="me-2" style="color: rgb(0, 255, 0)">{{ $request_info->status }}</span>
								@break

								@case('Отменено')
									<span class="me-2" style="color: rgb(173, 0, 0)">{{ $request_info->status }}</span>
								@break

								@default
									<span class="me-2" style="color: white">{{ $request_info->status }}</span>
							@endswitch

						</div>
						<span class="d-block text-white fst-italic">{{ $request_info->date_create->format('d.m.y') }}</span>
					</div>
				</a>
				@empty
					<div class="pt-3 ms-2 text-warning">Нет созданных вами заявок</div>
				@endforelse
			</div>

		</main>

	</x-header-layout>
