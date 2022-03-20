<x-admin-layout>

	<div class="container px-5">

		<div class="my-3 p-3 rounded shadow-sm text-white fs-5" style="background-color: #1A202C">
			<div class="d-flex justify-content-between mb-2">
				<span class="border-bottom pb-2 mb-0">Список всех заявок:</span>
			</div>

			@forelse ($all_requests as $all_request)
				<a href="/admin/requests/{{ $all_request->id }}" class="requestlink rounded-3 d-flex pt-3"
					style="text-decoration: none;">
					@switch($all_request->status)
						@case('В обработке')
							<i class="bi bi-clock-history me-3 ms-3" style="font-size: 2rem; color: rgb(0, 255, 255);"></i>
						@break

						@case('В работе')
							<i class="bi bi-wrench-adjustable-circle me-3 ms-3" style="font-size: 2rem; color: rgb(255, 157, 0);"></i>
						@break

						@case('Завершено')
							<i class="bi bi-check-circle me-3 ms-3" style="font-size: 2rem; color: rgb(0, 255, 0);"></i>
						@break

						@case('Отменено')
							<i class="bi bi-x-circle me-3 ms-3" style="font-size: 2rem; color: rgb(173, 0, 0);"></i>
						@break

						@default
							<i class="bi bi-question-circle me-3 ms-3" style="font-size: 2rem; color: white"></i>
					@endswitch
					<div class="pb-3 mb-0 lh-sm w-100">
						<div class="d-flex justify-content-between">
							<div>
								<strong
									class="text-gray-dark text-warning">{{ '№' . $all_request->id . ' ' . Str::limit($all_request->topic, 25) }}
								</strong>
								<span class="d-block text-white">{{ $all_request->location }}</span>
							</div>
							<div class="me-3 text-end">
								<div class="text-white fst-italic">
									{{ $all_request->name }}
								</div>
								<div class="text-white fst-italic">
									{{ $all_request->created_at->format('d.m.y H:i') }}
								</div>
							</div>
						</div>
					</div>
				</a>
				@empty
					<div class="pt-3 ms-2 text-warning">Пусто</div>
				@endforelse

			</div>

			{{ $all_requests->links() }}



		</div>

	</x-admin-layout>
