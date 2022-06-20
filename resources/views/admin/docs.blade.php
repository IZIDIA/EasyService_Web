<x-admin-layout>

	<div class="container px-5 mt-3">
		<div class="col-xl-6 mx-auto">
			<div class="d-flex shadow-sm"
				style="overflow:hidden; border-radius: 10px; background-color:#283141; height: 100%;">

				<div class="mt-4 px-3 d-flex shadow-sm"
					style="overflow:hidden; border-radius: 10px; background-color:#283141; height: 100%;">
					<div class="icon-square bg-dark text-light flex-shrink-0 me-3">
						<i class="bi bi-file-earmark-diff"></i>
					</div>
					<div class="" style="width: 400px">
						<h2 class="pt-2 mb-3" style="color: #00aaff">Новый документ:</h2>
						<div>

							<div>
								<input @error('photo') autofocus @enderror class="form-control mb-1" type="file" name="photo" id="file">
							</div>

							<label class="form-check-label mt-3" for="check_autoload_programs">Описание документа:</label>
							<div>
								<textarea style="resize:none;" class="form-control mt-2 w-100" rows="3" name="required_autoload_programs"></textarea>
							</div>

							<div class="d-flex flex-row-reverse">
								<button type="submit" class="shadow btn btn-primary mt-3">Добавить</button>
							</div>

							<div class="mt-3"></div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<main class="container">
		<div class="my-3 p-3 rounded shadow-sm text-white fs-5" style="background-color: #1A202C">

			<div class="d-flex py-2 shadow-sm" style="text-decoration: none;">
				<i class="bi bi-file-earmark-text mx-3 d-flex align-items-center" style="font-size: 2rem; color: white"></i>
				<div class="mb-0 lh-sm w-100 row">
					<div class="col-xl-4 col d-flex flex-column justify-content-center">
						<strong class="text-warning">Заявление 2021_23.docx
						</strong>
						<span class="d-block text-white fst-italic">Заявка на ремонт оборудования</span>
					</div>
					<div class="col d-flex align-items-center flex-row-reverse">
						<span class="me-3" style="color: rgb(176, 0, 0)">Удалить</span>
						<span class="me-3 text-decoration-underline" style="color: rgb(224, 228, 255)">Скачать</span>
					</div>
				</div>
			</div>

			<div class="d-flex py-2 shadow-sm" style="text-decoration: none;">
				<i class="bi bi-file-earmark-text mx-3 d-flex align-items-center" style="font-size: 2rem; color: white"></i>
				<div class="mb-0 lh-sm w-100 row">
					<div class="col-xl-4 col d-flex flex-column justify-content-center">
						<strong class="text-warning">Общие положения 2022.docx
						</strong>
						<span class="d-block text-white fst-italic">Общие положения. Полное наименование структурных подразделений.</span>
					</div>
					<div class="col d-flex align-items-center flex-row-reverse">
						<span class="me-3" style="color: rgb(176, 0, 0)">Удалить</span>
						<span class="me-3 text-decoration-underline" style="color: rgb(224, 228, 255)">Скачать</span>
					</div>
				</div>
			</div>


		</div>
	</main>
	
</x-admin-layout>
