<x-header-layout>
	<main class="container">
		<a type="button" class="shadow d-flex justify-content-center mt-3 btn btn-primary btn-lg fw-bold fs-4"
			href="/requests/create">Создать заявку</a>
		<div class="my-3 p-3 rounded shadow-sm text-white fs-5" style="background-color: #1A202C">
			<div class="d-flex justify-content-between mb-2">
				<span class="border-bottom pb-2 mb-0">Ваши заявки:</span>
				<form class="d-flex align-items-center" action="/requests/search" method="GET">
					<input maxlength="10" size="10" type="search" name="query" class="form-control form-control-dark me-2"
						placeholder="№ заявки" aria-label="Search" required>
					<button type="submit" type="button" class="btn btn-outline-info fw-bold">Поиск</button>
				</form>
			</div>
			@if (Auth::check())
				@forelse ($request_infos as $request_info)
					<a href="/requests/{{ $request_info->id }}" class="requestlink rounded-3 d-flex py-2 shadow-sm"
						style="text-decoration: none;">
						@switch($request_info->status)
							@case('В обработке')
								<i class="bi bi-clock-history mx-3 d-flex align-items-center"
									style="font-size: 2rem; color: rgb(0, 255, 255);"></i>
							@break

							@case('В работе')
								<i class="bi bi-wrench-adjustable-circle mx-3 d-flex align-items-center"
									style="font-size: 2rem; color: rgb(255, 157, 0);"></i>
							@break

							@case('Завершено')
								<i class="bi bi-check-circle mx-3 d-flex align-items-center" style="font-size: 2rem; color: rgb(0, 255, 0);"></i>
							@break

							@case('Отменено')
								<i class="bi bi-x-circle mx-3 d-flex align-items-center" style="font-size: 2rem; color: rgb(173, 0, 0);"></i>
							@break

							@default
								<i class="bi bi-question-circle mx-3 d-flex align-items-center" style="font-size: 2rem; color: white"></i>
						@endswitch
						<div class="mb-0 lh-sm w-100 row">
							<div class="col-xl-4 col d-flex flex-column justify-content-center">
								<strong class="text-warning">{{ '№' . $request_info->id . ' ' . Str::limit($request_info->topic, 25) }}
								</strong>
								<span class="d-block text-white fst-italic">{{ $request_info->created_at->format('d.m.y H:i') }}</span>
							</div>
							@if (isset($request_info->admin_id))
								<div class="d-none d-xl-flex col-5 align-items-center">
									<span class="text-secondary">Исполнитель:
										{{ App\Models\User::firstWhere('id', $request_info->admin_id)->name }}</span>
								</div>
							@endif
							<div class="col d-flex align-items-center flex-row-reverse">
								@switch($request_info->status)
									@case('В обработке')
										<span class="me-3" style="color: rgb(0, 255, 255)">{{ $request_info->status }}</span>
									@break

									@case('В работе')
										<span class="me-3" style="color: rgb(255, 157, 0)">{{ $request_info->status }}</span>
									@break

									@case('Завершено')
										<span class="me-3" style="color: rgb(0, 255, 0)">{{ $request_info->status }}</span>
									@break

									@case('Отменено')
										<span class="me-3" style="color: rgb(173, 0, 0)">{{ $request_info->status }}</span>
									@break

									@default
										<span class="me-3" style="color: white">{{ $request_info->status }}</span>
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
			@if (Auth::check())
				{{ $request_infos->links() }}
			@endif
		</main>
	</x-header-layout>
