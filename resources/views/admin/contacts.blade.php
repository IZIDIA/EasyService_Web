<x-admin-layout>


	<div class="album py-5">
		<div class="container">



			<div class="container px-5">
				<div class="col-xl-6 mx-auto">
					<div class="d-flex shadow-sm"
						style="overflow:hidden; border-radius: 10px; background-color:#283141; height: 100%;">

						<div class="mt-4 px-3 d-flex shadow-sm"
							style="overflow:hidden; border-radius: 10px; background-color:#283141; height: 100%;">
							<div class="icon-square bg-dark text-light flex-shrink-0 me-3">
								<i class="bi bi-telephone-plus"></i>
							</div>
							<div class="" style="width: 400px">
								<h2 class="pt-2 mb-3" style="color: #00aaff">Новый контакт:</h2>
								<div>

									<div class="mt-3">
										<label class="form-check-label required-label" for="check_max_load_gpu">Заголовок:</label>
										<div class="d-flex align-items-center">
											<input  class="form-control w-100">
										</div>
									</div>

									<div class="mt-3">
										<label class="form-check-label required-label" for="check_max_load_gpu">Номер телефона:</label>
										<div class="d-flex align-items-center">
											<input  class="form-control w-100">
										</div>
									</div>

									<label class="form-check-label mt-3" for="check_autoload_programs">Описание контакта:</label>
									<div>
										<textarea style="resize:none;" class="form-control mt-2 w-100" rows="4" name="required_autoload_programs"></textarea>
									</div>

									<div class="d-flex flex-row-reverse">
										<button type="submit" class="shadow btn btn-primary mt-3">Создать</button>
									</div>

									<div class="mt-3"></div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>







			<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 mt-3">
				<div class="col">
					<div class="card shadow-sm" style="background: #1A202C">
						<div class="p-4 d-flex flex-column justify-content-center align-items-center fs-3" style="height: 180px">
							<p>Начальник IT отдела</p>
							<p>+79995290788</p>
						</div>
						<div class="card-body">
							<p class="card-text" style="height: 100px">Семён Игоревич Самойлов. Начальник управления информационных
								технологий. Обращаться по
								вопросам продления лицензий и согласования заявлений.</p>
							<div class="d-flex justify-content-between align-items-center">
								<div class="btn-group">
									<button type="button" class="btn btn-sm btn-outline-secondary">Удалить</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col">
					<div class="card shadow-sm" style="background: #1A202C">
						<div class="p-4 d-flex flex-column justify-content-center align-items-center fs-3" style="height: 180px">
							<p>Техническая помощь</p>
							<p>+79801796423</p>
						</div>
						<div class="card-body">
							<p class="card-text" style="height: 100px">Пётр Васильевич Веселов. Решение технических проблем, связанных с
								офисным оборудованием.
								Замена картриджей принтеров.</p>
							<div class="d-flex justify-content-between align-items-center">
								<div class="btn-group">
									<button type="button" class="btn btn-sm btn-outline-secondary">Удалить</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col">
					<div class="card shadow-sm" style="background: #1A202C">
						<div class="p-4 d-flex flex-column justify-content-center align-items-center fs-3" style="height: 180px">
							<p>Программная поддержка</p>
							<p>+79995427844</p>
						</div>
						<div class="card-body">
							<p class="card-text" style="height: 100px">Соловьев Павел Дмитриевич. Пётр Васильевич Веселов. Решение проблем
								с программным
								обеспечением. Установка нового ПО и систем.</p>
							<div class="d-flex justify-content-between align-items-center">
								<div class="btn-group">
									<button type="button" class="btn btn-sm btn-outline-secondary">Удалить</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col">
					<div class="card shadow-sm" style="background: #1A202C">
						<div class="p-4 d-flex flex-column justify-content-center align-items-center fs-3" style="height: 180px">
							<p>Cопровождение 1C</p>
							<p>+79989674522</p>
						</div>
						<div class="card-body">
							<p class="card-text" style="height: 100px">Андреева Полина Евгеньевна. Устранение неполадок. Обновление
								конфигураций и форм отчетности.
							</p>
							<div class="d-flex justify-content-between align-items-center">
								<div class="btn-group">
									<button type="button" class="btn btn-sm btn-outline-secondary">Удалить</button>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>


</x-admin-layout>
