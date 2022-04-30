<x-header-layout>
	<main class="container">
		<div class="text-white py-5 text-center">
			<div class="py-5">
				<h1 class="display-5 fw-bold text-white">Cервис обработки заявок</h1>
				<div class="col-lg-8 mx-auto">
					<p class="fs-5 mb-4 " style="white-space: pre-line">
						{{ App\Models\Option::find(1)->welcome_text }}</p>
					<div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
						<a type="button" class="btn btn-outline-info btn-lg px-4 me-sm-3 fw-bold" href="/requests/create">Создать
							заявку</a>
						<a type="button" class="btn btn-outline-light btn-lg px-4" href="/contacts">Контакты</a>
					</div>
				</div>
			</div>
		</div>
	</main>
</x-header-layout>
