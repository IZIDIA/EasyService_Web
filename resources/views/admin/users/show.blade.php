<x-admin-layout>
	<div class="container px-5">

		<div class="col-lg-6 mx-auto">
			<div class="mt-5 p-3 d-flex shadow-sm" style="border-radius: 10px; background-color:#283141; height: 100%;">
				<div class="icon-square bg-dark text-light flex-shrink-0 me-3">
					<i class="bi bi-person-lines-fill"></i>
				</div>
				<div style="width: 100%">
					<h2 class="pt-2 mb-3"> {{ $user->name }}</h2>
					<p><strong>ID:</strong> {{ $user->id }}</p>
					<div class="d-flex">
						<strong class="me-1">Статус:</strong>
						@if (isset($user->admin->is_master))
							@if ($user->admin->is_master != true)
								<p style="color:#ffd700">Администратор</p>
							@else
								<p style="color:#bc13fe">Мастер</p>
							@endif
						@else
							<p>Пользователь</p>
						@endif
					</div>
					<p><strong>Email:</strong> {{ $user->email }}</p>
					<p><strong>Создано заявок:</strong> {{ $created }}</p>
					<p><strong>Дата регистрации:</strong> {{ $user->created_at->format('d.m.y H:i') }}</p>
					@if ($user->is_admin)
						<div class="border-bottom mb-2"></div>
						<p><strong>Статус:</strong>
							@if ($user->admin->free)
								Свободен
							@else
								Работает
							@endif
						</p>
						<p><strong>Выполнено заявок:</strong> {{ $done }}</p>
						<p><strong>Распределённые заявки:</strong>
							@if ($user->admin->get_recommendation)
								Вкл.
							@else
								Выкл.
							@endif
						</p>
						<p><strong>Рабочее время за текущую неделю:</strong> {{ $user->admin->week_time }} мин.</p>
					@endif
				</div>
			</div>
			@if (1 == 1)
				<div class="mt-3 d-flex justify-content-between">
					<div class="d-flex gap-2">
						@if (Auth::user()->admin->is_master)
							@if ($user->is_admin)
								@if (!$user->admin->is_master)
									<form action="/admin/users/{{ $user->id }}/master" method="POST">
										@method('PATCH')
										@csrf
										<button type="submit" class="shadow btn btn-info">Сделать мастером</button>
									</form>
								@endif
								<form action="/admin/users/{{ $user->id }}/downgrade" method="POST">
									@method('PATCH')
									@csrf
									<button type="submit" class="shadow btn btn-secondary">Понизить</button>
								</form>
							@else
								<form action="/admin/users/{{ $user->id }}/admin" method="POST">
									@method('PATCH')
									@csrf
									<button type="submit" class="shadow btn btn-warning">Сделать
										администратором</button>
								</form>
							@endif
						@endif
					</div>
					@if (Auth::user()->admin->is_master)
						<form action="/admin/users/{{ $user->id }}/delete" method="POST">
							@method('DELETE')
							@csrf
							<button type="submit" class="shadow btn btn-outline-danger">Удалить аккаунт</button>
						</form>
					@endif
				</div>
			@endif
		</div>


	</div>
</x-admin-layout>
