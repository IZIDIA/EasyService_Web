<x-admin-layout>
	<div class="container px-5">
		
		<div class="my-3 p-3 rounded shadow-sm text-white fs-5 col-lg-8 mx-auto" style="background-color: #1A202C">
			<div class="d-flex justify-content-between mb-2">
				<span class="border-bottom pb-2 mb-0">Пользователи:</span>
				<div class="d-flex">
					<form class="me-2 ps-2">
						<input size="30" type="search" class="form-control form-control-dark" placeholder="Имя или Email" aria-label="Search">
					</form>
					<a type="button" class="btn btn-outline-info fw-bold" href="/">Поиск</a>
				</div>
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

					<div class="mb-0 lh-sm w-100 row align-items-center">
						<div class="col d-flex align-items-center">
							<span class="text-white me-1">{{ $user->id }} -
							</span>
							<span style="color: #96FBFE">{{ $user->name }}</span>
						</div>
						<div class="col me-3 text-end">
							<div class="text-white fst-italic">
								{{ $user->email }}
							</div>
						</div>

					</div>
				</a>
			@empty
				<div class="pt-3 ms-2 text-warning">Пусто</div>
			@endforelse

		</div>
	</div>
</x-admin-layout>
