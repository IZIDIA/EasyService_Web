<x-admin-layout>

	<div class="container px-5">

		<div class="my-3 p-3 rounded shadow-sm text-white fs-5" style="background-color: #1A202C">
			<div class="d-flex justify-content-between mb-2">
				<span class="border-bottom pb-2 mb-0">Распределённая заявка:</span>
			</div>
			@if (is_null($recommend_request))
				<div class="pt-3 ms-2 text-warning">Пусто</div>
			@else
				<a href="/admin/requests/{{ $recommend_request->id }}" class="requestlink rounded-3 d-flex pt-3"
					style="text-decoration: none;">
					<i class="bi bi-clock-history me-3 ms-3" style="font-size: 2rem; color: rgb(0, 255, 255);"></i>
					<div class="pb-3 mb-0 lh-sm w-100">
						<div class="d-flex justify-content-between">
							<div>
								<strong
									class="text-gray-dark text-warning">{{ '№' . $recommend_request->id . ' ' . Str::limit($recommend_request->topic, 25) }}
								</strong>
								<div class="text-white ">
									{{ $recommend_request->location }}
								</div>
							</div>
							<div class="me-3 text-end">
								<div class="text-white fst-italic">
									{{ $recommend_request->name }}
								</div>
								<div class="text-white fst-italic">
									{{ $recommend_request->created_at->format('d.m.y H:i') }}
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
				<a href="/admin/requests/{{ $my_request->id }}" class="requestlink rounded-3 d-flex pt-3"
					style="text-decoration: none;">
					<i class="bi bi-wrench-adjustable-circle me-3 ms-3" style="font-size: 2rem; color: rgb(255, 157, 0);"></i>
					<div class="pb-3 mb-0 lh-sm w-100">
						<div class="d-flex justify-content-between">
							<div>
								<strong
									class="text-gray-dark text-warning">{{ '№' . $my_request->id . ' ' . Str::limit($my_request->topic, 25) }}
								</strong>
								<span class="d-block text-white">{{ $my_request->location }}</span>
							</div>
							<div class="me-3 text-end">
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

		{!! $my_requests->links() !!}

	</div>

</x-admin-layout>
