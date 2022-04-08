<x-admin-layout>
	<div class="container px-5">
		<div class="my-3 p-3 rounded shadow-sm text-white fs-5 col-lg-8 mx-auto" style="background-color: #1A202C">
			<div class="d-flex justify-content-between mb-2">
				<span class="border-bottom pb-2 mb-0">Результаты поиска:</span>
				<form class="d-flex align-items-center ms-2" action="/admin/users/search" method="GET">
					<input value="{{$search}}" maxlength="100" size="40" type="search" name="query" class="form-control form-control-dark me-2"
						placeholder="Имя или Email" aria-label="Search" required>
					<button type="submit" type="button" class="btn btn-outline-info fw-bold">Поиск</button>
				</form>
			</div>
			@forelse ($users as $user)
				<a href="/admin/users/{{ $user->id }}" class="requestlink rounded-3 d-flex py-2 shadow-sm"
					style="text-decoration: none; ">
					@if (!isset($user->admin))
						<i class="bi bi-person-fill text-white mx-3 d-flex align-items-center" style="font-size: 2rem;"></i>
					@else
						@if ($user->admin->is_master != true)
							<i class="bi bi-cpu mx-3 d-flex align-items-center" style="font-size: 2rem; color:#c3a600"></i>
						@else
							<i class="bi bi-eye mx-3 d-flex align-items-center" style="font-size: 30px; color:#920fc5"></i>
						@endif
					@endif
					<div class="lh-sm d-flex align-items-center">
						<span class="text-white me-2">{{ $user->id }}</span>
					</div>
					<div class="lh-sm w-100">
						<div class="h-100 row row-cols-1 row-cols-xl-2 d-flex align-items-center">
							<div class="col">
								<span style="color: #96FBFE">{{ $user->name }}</span>
							</div>
							<div class="col text-xl-end pe-xl-4">
								<div class="text-white fst-italic">
									{{ $user->email }}
								</div>
							</div>
						</div>
					</div>
				</a>
			@empty
				<div class="pt-3 ms-2 text-warning">Пусто</div>
			@endforelse
		</div>
		<div class="col-lg-8 mx-auto">
		</div>
	</div>
</x-admin-layout>
