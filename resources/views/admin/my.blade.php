<x-admin-layout>

	<div class="container px-5">

		@if (App\Models\Option::find(1)->distributed_requests)
			<div class="my-3 p-3 rounded shadow-sm text-white fs-5" style="background-color: #1A202C">
				<div class="d-flex align-items-center mb-2 justify-content-between">
					<div class="d-flex">
						@if (Auth::user()->admin->get_recommendation)
							<i data-bs-toggle="tooltip" data-bs-placement="top" title="Вкл" class="bi bi-bookmark-check-fill me-1"
								style="color: #00aa00;"></i>
						@else
							<i data-bs-toggle="tooltip" data-bs-placement="top" title="Выкл" class="bi bi-bookmark-x-fill me-1"
								style="color: #aa0000;"></i>
						@endif
						<div class="border-bottom pb-2 mb-0">Распределённая заявка:</div>
					</div>
					@if (!is_null($distributed_request))
						<div class="text-warning me-3" id="countdown">
							00d 00h 00m 00s
						</div>
						<script>
						 setTimer('{{ $distributed_request->admin_queue->updated_at }}',
						  {{ $distributed_request->admin_queue->distributed_lifetime }}, 'countdown');
						</script>
					@endif
				</div>
				@if (is_null($distributed_request))
					<div class="pt-3 ms-2 text-warning">Пусто</div>
				@else
					<a href="/admin/requests/{{ $distributed_request->id }}" class="requestlink rounded-3 d-flex py-2 shadow-sm"
						style="text-decoration: none;">
						<i class="bi bi-clock-history mx-3 d-flex align-items-center"
							style="font-size: 2rem; color: #00ffff;"></i>
						<div class="mb-0 lh-sm w-100 row">
							<div class="col d-flex flex-column justify-content-center">
								<strong
									class="text-warning">{{ '№' . $distributed_request->id . ' ' . Str::limit($distributed_request->topic, 25) }}
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
					</a>
				@endif
			</div>
		@endif

		<div class="d-flex gap-2 mt-3" style="word-break: break-all;">
			@if (!request()->is('admin/my'))
				<a href="{{ url('admin/my') }}" class="link-light">Все</a>
			@endif
			@if (!request()->is('admin/my/completed'))
				<a href="{{ url('admin/my/completed') }}" class="link-success">Завершённые</a>
			@endif
			@if (!request()->is('admin/my/in_work'))
				<a href="{{ url('admin/my/in_work') }}" class="link-warning">В работе</a>
			@endif
		</div>

		<div class="mb-3 mt-1 p-3 rounded shadow-sm text-white fs-5" style="background-color: #1A202C">
			<div class="d-flex justify-content-between mb-2">
				<span class="border-bottom pb-2 mb-0">Ваши заявки ({{ $type }}):</span>
			</div>
			@forelse ($my_requests as $my_request)
				<a href="/admin/requests/{{ $my_request->id }}" class="requestlink rounded-3 d-flex py-2 shadow-sm"
					style="text-decoration: none;">
					@switch($my_request->status)
						@case('В обработке')
							<i class="bi bi-clock-history mx-3 d-flex align-items-center"
								style="font-size: 2rem; color: #00ffff;"></i>
						@break

						@case('В работе')
							<i class="bi bi-wrench-adjustable-circle mx-3 d-flex align-items-center"
								style="font-size: 2rem; color: #ff9d00;"></i>
						@break

						@case('Завершено')
							<i class="bi bi-check-circle mx-3 d-flex align-items-center" style="font-size: 2rem; color: #00ff00;"></i>
						@break

						@case('Отменено')
							<i class="bi bi-x-circle mx-3 d-flex align-items-center" style="font-size: 2rem; color: #ad0000;"></i>
						@break

						@default
							<i class="bi bi-question-circle mx-3 d-flex align-items-center" style="font-size: 2rem; color: #ffffff"></i>
					@endswitch
					<div class="mb-0 lh-sm w-100 row">
						<div class="col-xl-4 col d-flex flex-column justify-content-center">
							<strong class="text-warning">{{ '№' . $my_request->id . ' ' . Str::limit($my_request->topic, 25) }}
							</strong>
							<span class="d-block text-white">{{ Str::limit($my_request->location, 25) }}</span>
						</div>
						@if ($my_request->status == 'В работе')
							<div class="d-none d-xxl-flex col-5 justify-content-center align-items-center">
								<div class="text-warning me-3" id="countdown{{ $my_request->id }}">
									00d 00h 00m 00s
								</div>
							</div>
							<script>
							 setTimer('{{ $my_request->accepted_at }}',
							  {{ $my_request->time_remaining }}, 'countdown{{ $my_request->id }}');
							</script>
						@endif
						<div class="col me-3 d-flex flex-column text-end justify-content-center">
							<div class="text-white fst-italic">
								{{ $my_request->name }}
							</div>
							<div class="text-white fst-italic">
								{{ $my_request->created_at->format('d.m.y H:i') }}
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
