<x-admin-layout>

	<div class="container px-5">

		<div class="my-3 p-3 rounded shadow-sm text-white fs-5" style="background-color: #1A202C">
			<div class="d-flex justify-content-between mb-2">
				<span class="border-bottom pb-2 mb-0">Распределённая заявка:</span>
			</div>
			@if (is_null($distributed_request))
				<div class="pt-3 ms-2 text-warning">Пусто</div>
			@else
				<a href="/admin/requests/{{ $distributed_request->id }}" class="requestlink rounded-3 d-flex py-2 shadow-sm"
					style="text-decoration: none;">
					<i class="bi bi-clock-history mx-3 d-flex align-items-center" style="font-size: 2rem; color: rgb(0, 255, 255);"></i>
					<div class="mb-0 lh-sm w-100">
						<div class="row">
							<div class="col d-flex flex-column justify-content-center">
								<strong
									class="text-gray-dark text-warning">{{ '№' . $distributed_request->id . ' ' . Str::limit($distributed_request->topic, 25) }}
								</strong>
								<span class="d-block text-white">{{ Str::limit($distributed_request->location, 25) }}</span>
							</div>
							<div class="col me-3 d-flex flex-column text-end justify-content-center">
								<div class="text-white fst-italic">
									{{ $distributed_request->name }}
								</div>
								<div class="text-white fst-italic">
									{{ $distributed_request->created_at->format('d.m.y H:i') }}
								</div>
							</div>
						</div>
					</div>
				</a>
			@endif
		</div>

		<div class="my-3 p-3 rounded shadow-sm text-white fs-5" style="background-color: #1A202C">
			<div class="d-flex justify-content-between mb-2">
				<span class="border-bottom pb-2 mb-0">Выполняемые вами заявки:</span>
			</div>
			@forelse ($my_requests as $my_request)
				<a href="/admin/requests/{{ $my_request->id }}" class="requestlink rounded-3 d-flex py-2 shadow-sm"
					style="text-decoration: none;">
					@switch($my_request->status)
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
					<div class="mb-0 lh-sm w-100">
						<div class="row">
							<div class="col d-flex flex-column justify-content-center">
								<strong
									class="text-gray-dark text-warning">{{ '№' . $my_request->id . ' ' . Str::limit($my_request->topic, 25) }}
								</strong>
								<span class="d-block text-white">{{ Str::limit($my_request->location, 25) }}</span>
							</div>
							<div class="col me-3 d-flex flex-column text-end justify-content-center">
								<div class="text-white fst-italic">
									{{ $my_request->name }}
								</div>
								<div class="text-white fst-italic">
									{{ $my_request->created_at->format('d.m.y H:i') }}
								</div>
							</div>
						</div>
					</div>
				</a>
				@empty
					<div class="pt-3 ms-2 text-warning">Пусто</div>
				@endforelse
			</div>

			{{ $my_requests->links() }}

		</div>

	</x-admin-layout>
