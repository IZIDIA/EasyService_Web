<x-admin-layout>

	<div class="container px-5">

		<div class="d-flex gap-2 mt-3" style="word-break: break-all;">
			@if (!request()->is('admin/requests'))
				<a href="{{ url('admin/requests') }}" class="link-light">Все</a>
			@endif
			@if (!request()->is('admin/requests/completed'))
				<a href="{{ url('admin/requests/completed') }}" class="link-success">Завершённые</a>
			@endif
			@if (!request()->is('admin/requests/in_work'))
				<a href="{{ url('admin/requests/in_work') }}" class="link-warning">В работе</a>
			@endif
			@if (!request()->is('admin/requests/in_processing'))
				<a href="{{ url('admin/requests/in_processing') }}" class="link-info">В обработке</a>
			@endif
			@if (!request()->is('admin/requests/canceled'))
				<a href="{{ url('admin/requests/canceled') }}" class="link-danger">Отменённые</a>
			@endif
		</div>

		<div class="mb-3 mt-1 p-3 rounded shadow-sm text-white fs-5" style="background-color: #1A202C">
			<div class="d-flex justify-content-between mb-2">
				<span class="border-bottom pb-2 mb-0">Список заявок ({{ $type }}):</span>
				<div class="d-flex">
					<form class="me-2 ps-2">
						<input size="30" type="search" class="form-control form-control-dark" placeholder="Содержимое заявки"
							aria-label="Search">
					</form>
					<a type="button" class="btn btn-outline-info fw-bold" href="/">Поиск</a>
				</div>
			</div>

			@forelse ($requests as $request)
				<a href="/admin/requests/{{ $request->id }}" class="requestlink rounded-3 d-flex py-2 shadow-sm"
					style="text-decoration: none; ">
					@switch($request->status)
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
							<strong class="text-warning">{{ '№' . $request->id . ' ' . Str::limit($request->topic, 25) }}
							</strong>
							<span class="d-block text-white">{{ Str::limit($request->location, 25) }}</span>
						</div>
						@if (isset($request->admin_id))
							<div class="d-none d-xxl-flex col-5 align-items-center">
								<span class="text-secondary">Исполнитель:
									{{ App\Models\User::firstWhere('id', $request->admin_id)->name }}</span>
							</div>
						@else
							@if (App\Models\Option::find(1)->distributed_requests && ($distributed_request = App\Models\AdminQueue::firstWhere('request_id', $request->id)) != null)
								<div class="d-none d-xxl-flex col-5 align-items-center">
									<span style="color: #927000">Распределена для:
										{{ App\Models\User::firstWhere('id', $distributed_request->admin_id)->name }}
									</span>
								</div>
							@endif
						@endif
						<div class="col me-3 d-flex flex-column text-end justify-content-center">
							<div class="text-white fst-italic">
								{{ $request->name }}
							</div>
							<div class="text-white fst-italic">
								{{ $request->created_at->format('d.m.y H:i') }}
							</div>
						</div>
					</div>
				</a>
				@empty
					<div class="pt-3 ms-2 text-warning">Пусто</div>
				@endforelse

			</div>

			{{ $requests->links() }}



		</div>

	</x-admin-layout>
