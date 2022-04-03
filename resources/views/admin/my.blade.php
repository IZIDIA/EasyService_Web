<x-admin-layout>

	<div class="container px-5">

		@if (App\Models\Option::find(1)->distributed_requests)
			<div class="my-3 p-3 rounded shadow-sm text-white fs-5" style="background-color: #1A202C">
				<div class="d-flex align-items-center mb-2 justify-content-between">
					<div class="d-flex">
						@if (Auth::user()->admin->get_recommendation)
							<i data-bs-toggle="tooltip" data-bs-placement="top" title="Вкл" class="bi bi-bookmark-check-fill me-1"
								style="color: rgb(0, 170, 0);"></i>
						@else
							<i data-bs-toggle="tooltip" data-bs-placement="top" title="Выкл" class="bi bi-bookmark-x-fill me-1"
								style="color: rgb(170, 0, 0);"></i>
						@endif
						<div class="border-bottom pb-2 mb-0">Распределённая заявка:</div>
					</div>
					@if (!is_null($distributed_request))
						<div class="text-warning me-3" id="countdown">
							00d 00h 00m 00s
						</div>
						<script>
						 let yourDateToGo = new Date('{{ $distributed_request->admin_queue->updated_at }}');
						 yourDateToGo.setHours(yourDateToGo.getHours() + {{ $distributed_request->admin_queue->distributed_lifetime }});
						 let timing =
						  setInterval(
						   function() {
						    let currentDate = new Date().getTime();
						    let timeLeft = yourDateToGo - currentDate;
						    let days = Math.floor(timeLeft / (86400000));
						    if (days < 10) days = "0" +
						     days;
						    let hours = Math.floor((timeLeft % (86400000)) / (3600000));
						    if (hours < 10) hours = "0" + hours;
						    let minutes = Math.floor((timeLeft % (3600000)) / (60000));
						    if (minutes < 10) minutes = "0" + minutes;
						    let seconds = Math.floor((timeLeft % (60000)) / 1000);
						    if (seconds < 10) seconds = "0" + seconds;
						    document.getElementById("countdown").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds +
						     "s";
						    if (timeLeft <= 0) {
						     clearInterval(timing);
						     document.getElementById("countdown").innerHTML =
						      "Время истекло...";
						    }
						   }, 1000);
						</script>
					@endif
				</div>
				@if (is_null($distributed_request))
					<div class="pt-3 ms-2 text-warning">Пусто</div>
				@else
					<a href="/admin/requests/{{ $distributed_request->id }}" class="requestlink rounded-3 d-flex py-2 shadow-sm"
						style="text-decoration: none;">
						<i class="bi bi-clock-history mx-3 d-flex align-items-center"
							style="font-size: 2rem; color: rgb(0, 255, 255);"></i>
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
						<div class="col d-flex flex-column justify-content-center">
							<strong class="text-warning">{{ '№' . $my_request->id . ' ' . Str::limit($my_request->topic, 25) }}
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
				</a>
				@empty
					<div class="pt-3 ms-2 text-warning">Пусто</div>
				@endforelse
			</div>

			{{ $my_requests->links() }}

		</div>

	</x-admin-layout>
