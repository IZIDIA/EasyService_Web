<x-header-layout>
	<main class="container">
		<div class="text-white py-5 text-center">
			<div class="py-5">
				<div class="col-lg-8 mx-auto">
					<i class="bi bi-x-octagon fs-1" style="color: #aa0000"></i>
					<p class="fs-5 mb-4 text-warning">
						@if ($login)
							Cлишком много запросов на создание новой завявки с данного аккаунта (за текущий день). Повторите попытку на следующий день.
						@else
							Cлишком много запросов на создание новой завявки с данного ip адресса (за текущий день). Зарегестрируйтель или
							войдите в ранее созданный аккаунт для создания заявки.
						@endif
				</div>
			</div>
		</div>
	</main>
</x-header-layout>
