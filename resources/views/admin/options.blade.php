<x-admin-layout>
	<div class="container px-5">
		<div class="col-xl-6 mx-auto">
			<div class="mt-5 p-3 d-flex shadow-sm"
				style="overflow:hidden; border-radius: 10px; background-color:#283141; height: 100%;">
				<div class="icon-square bg-dark text-light flex-shrink-0 me-3">
					<i class="bi bi-gear"></i>
				</div>
				<div>
					<h2 class="pt-2"> Ваши настройки:</h2>
					<div>
						<div class="form-check form-switch">
							<input class="form-check-input toggle-class1" type="checkbox" id="flexSwitchCheck"
								{{ $admin->sound_notification ? 'checked' : '' }}>
							<label class="form-check-label" for="flexSwitchCheck">Получать звуковые уведомления</label>
							<span data-bs-toggle="tooltip" data-bs-placement="top" title="Звуковое уведомление при создании новой заявки">
								<i class="bi bi-question-circle ms-1"></i>
							</span>
						</div>
						<div class="text-danger d-none" id="error_switch1">
							Ошибка
						</div>
						<script type="text/javascript">
						 $.ajaxSetup({
						  headers: {
						   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						  }
						 });
						 $('.toggle-class1').change(function(e) {
						  let status = $(this).prop('checked') == true ? 1 : 0;
						  let _this = $(this);
						  _this.prop('disabled', true);
						  $.ajax({
						   url: "/admin/options/notification",
						   type: "POST",
						   data: {
						    status: status,
						   },
						   success: function(response) {
						    console.log(response);
						    let error_switch = document.getElementById("error_switch1");
						    error_switch.classList.add('d-none');
						    _this.prop('disabled', false);
						    location.reload();
						   },
						   error: function(error) {
						    let error_switch = document.getElementById("error_switch1");
						    error_switch.classList.remove('d-none');
						    _this.prop('disabled', false);
						   },
						  });
						 });
						</script>
						<div class="form-check form-switch mt-2">
							@if ($options->distributed_requests)
								<input class="form-check-input toggle-class2" type="checkbox" id="flexSwitchCheck"
									{{ $admin->get_recommendation ? 'checked' : '' }}>
							@else
								<input disabled class="form-check-input toggle-class" type="checkbox">
							@endif
							<label class="form-check-label" for="flexSwitchCheck">Получать распределённые заявки</label>
							<span data-bs-toggle="tooltip" data-bs-placement="top"
								title="Вы будете получать распределённые заявки, при условии, что у вас нет исполняемых заявок">
								<i class="bi bi-question-circle ms-1"></i>
							</span>
						</div>
						<div class="text-danger d-none" id="error_switch2">
							Ошибка
						</div>
						<label class="text-warning" style="font-size: 0.8rem">Если вы не будете принимать распределённые заявки, данная
							функция автоматически отключится через: {{ $options->time_to_accept_distributed }} ч. после появления
							распределённой заявки</label>
						<script type="text/javascript">
						 $('.toggle-class2').change(function(e) {
						  let status = $(this).prop('checked') == true ? 1 : 0;
						  let _this = $(this);
						  _this.prop('disabled', true);
						  $.ajax({
						   url: "/admin/options/recommendation",
						   type: "POST",
						   data: {
						    status: status,
						   },
						   success: function(response) {
						    console.log(response);
						    let error_switch = document.getElementById("error_switch2");
						    error_switch.classList.add('d-none');
						    _this.prop('disabled', false);
						   },
						   error: function(error) {
						    let error_switch = document.getElementById("error_switch2");
						    error_switch.classList.remove('d-none');
						    _this.prop('disabled', false);
						   },
						  });
						 });
						</script>
					</div>
				</div>
			</div>
			@if (Auth::User()->admin->is_master)
				<div class="mt-4 p-3 d-flex shadow-sm"
					style="overflow:hidden; border-radius: 10px; background-color:#283141; height: 100%;">
					<div class="icon-square bg-dark text-light flex-shrink-0 me-3">
						<i class="bi bi-globe2"></i>
					</div>
					<div>
						<h2 class="pt-2 mb-3" style="color: #bc13fe"> Глобальные настройки:</h2>
						<div class="w-100">
							<form action="/admin/options/global" method="POST">
								<div class="form-check form-switch mb-3">
									<input id="distributed_requests" name="distributed_requests" class="form-check-input toggle-class"
										type="checkbox" {{ $options->distributed_requests ? 'checked' : '' }}>
									<label class="form-check-label" for="distributed_requests">Распределённые заявки</label>
									<span data-bs-toggle="tooltip" data-bs-placement="top"
										title="Глобальная функция автоматического распределения заявок">
										<i class="bi bi-question-circle ms-1"></i>
									</span>
								</div>
								@error('distributed_requests')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror
								<div class="input-group mb-3">
									<input id="time_to_work" name="time_to_work" type="text" class="form-control"
										value="{{ $options->time_to_work }}">
									<span style="overflow:hidden; text-overflow: ellipsis;" class="input-group-text w-75">Часы на выполнение
										заявки</span>
								</div>
								@error('time_to_work')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror
								<div class="input-group mb-3">
									<input id="time_to_accept_distributed" name="time_to_accept_distributed" type="text" class="form-control"
										value="{{ $options->time_to_accept_distributed }}">
									<span style="overflow:hidden; text-overflow: ellipsis;" class="input-group-text w-75">Часы на принятие
										распределённой
										заявки</span>
								</div>
								@error('time_to_accept_distributed')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror
								<div class="input-group mb-3">
									<input id="check_interval" name="check_interval" type="text" class="form-control"
										value="{{ $options->check_interval }}">
									<span style="overflow:hidden; text-overflow: ellipsis;" class="input-group-text w-75">Частота опроса сервера
										для уведомлений (мс)</span>
								</div>
								@error('check_interval')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror
								<div class="input-group mb-3">
									<textarea id="welcome_text" name="welcome_text" style="min-height: 250px" class="form-control" rows="10"
          maxlength="2000">{{ $options->welcome_text }}</textarea>
									<span class="input-group-text w-25 text-wrap">Текст на главной странице сайта</span>
								</div>
								@error('welcome_text')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror
								<div class="input-group mb-3">
									<textarea id="welcome_text_app" name="welcome_text_app" style="min-height: 250px" class="form-control" rows="10"
          maxlength="1000">{{ $options->welcome_text_app }}</textarea>
									<span class="input-group-text w-25 text-wrap">Текст на странице приложения</span>
								</div>
								@error('welcome_text_app')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror
								<div class="d-flex flex-row-reverse">
									@method('PATCH')
									@csrf
									<button @if (Session::has('autofocus1')) autofocus @endif type="submit"
										class="shadow btn btn-primary">Сохранить</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="mt-4 p-3 d-flex shadow-sm"
					style="overflow:hidden; border-radius: 10px; background-color:#283141; height: 100%;">
					<div class="icon-square bg-dark text-light flex-shrink-0 me-3">
						<i class="bi bi-list-check"></i>
					</div>
					<div class="w-100">
						<h2 class="pt-2 mb-3" style="color: #bc13fe">Критерии:</h2>
						<div>
							<form action="/admin/options/criterions" method="POST">
								<div class="form-check form-switch mb-3">
									<input id="ethernet" name="ethernet" class="form-check-input toggle-class" type="checkbox"
										{{ $criterions->ethernet ? 'checked' : '' }}>
									<label class="form-check-label" for="ethernet">Проверка доступа в интернет</label>
								</div>
								@error('ethernet')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror

								<div class="form-check form-switch mb-3">
									<input id="gpu_install" name="gpu_install" class="form-check-input toggle-class" type="checkbox"
										{{ $criterions->gpu_install ? 'checked' : '' }}>
									<label class="form-check-label" for="gpu_install">Проверка наличия дискретной видеокарты</label>
								</div>
								@error('gpu_install')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror

								<div class="form-check form-switch mb-3">
									<input id="disk_status" name="disk_status" class="form-check-input toggle-class" type="checkbox"
										{{ $criterions->disk_status ? 'checked' : '' }}>
									<label class="form-check-label" for="disk_status">Проверка статуса HDD/SSD</label>
								</div>
								@error('disk_status')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror

								<hr class="">

								<div class="">
									<div class="form-check form-switch">
										<input id="check_max_temp_cpu" name="check_max_temp_cpu" class="form-check-input toggle-class" type="checkbox"
											{{ $criterions->check_max_temp_cpu ? 'checked' : '' }}>
										<label class="form-check-label required-label" for="check_max_temp_cpu">Верхний порог температуры CPU:</label>
									</div>
									<div class="d-flex align-items-center">
										<input type="number" maxlength="3" min="0" max="999" type="max_temp_cpu" class="form-control w-25"
											id="max_temp_cpu" name="max_temp_cpu" value="{{ $criterions->max_temp_cpu }}" required>
										<div class="ms-1">°C</div>
									</div>
								</div>
								@error('max_temp_cpu')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror

								<div class="mt-2">
									<div class="form-check form-switch">
										<input id="check_max_temp_gpu" name="check_max_temp_gpu" class="form-check-input toggle-class" type="checkbox"
											{{ $criterions->check_max_temp_gpu ? 'checked' : '' }}>
										<label class="form-check-label required-label" for="check_max_temp_gpu">Верхний порог температуры GPU:</label>
									</div>
									<div class="d-flex align-items-center">
										<input type="number" maxlength="3" min="0" max="999" type="max_temp_gpu" class="form-control w-25"
											id="max_temp_gpu" name="max_temp_gpu" value="{{ $criterions->max_temp_gpu }}" required>
										<div class="ms-1">°C</div>
									</div>
								</div>
								@error('max_temp_gpu')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror

								<div class="mt-2">
									<div class="form-check form-switch">
										<input id="check_max_load_cpu" name="check_max_load_cpu" class="form-check-input toggle-class" type="checkbox"
											{{ $criterions->check_max_load_cpu ? 'checked' : '' }}>
										<label class="form-check-label required-label" for="check_max_load_cpu">Верхний порог загруженности
											CPU:</label>
									</div>
									<div class="d-flex align-items-center">
										<input type="number" maxlength="3" min="0" max="100" type="max_load_cpu" class="form-control w-25"
											id="max_load_cpu" name="max_load_cpu" value="{{ $criterions->max_load_cpu }}" required>
										<div class="ms-1">%</div>
									</div>
								</div>
								@error('max_load_cpu')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror

								<div class="mt-2">
									<div class="form-check form-switch">
										<input id="check_max_load_gpu" name="check_max_load_gpu" class="form-check-input toggle-class" type="checkbox"
											{{ $criterions->check_max_load_gpu ? 'checked' : '' }}>
										<label class="form-check-label required-label" for="check_max_load_gpu">Верхний порог загруженности
											GPU:</label>
									</div>
									<div class="d-flex align-items-center">
										<input type="number" maxlength="3" min="0" max="100" type="max_load_gpu" class="form-control w-25"
											id="max_load_gpu" name="max_load_gpu" value="{{ $criterions->max_load_gpu }}" required>
										<div class="ms-1">%</div>
									</div>
								</div>
								@error('max_load_gpu')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror

								<div class="mt-2">
									<div class="form-check form-switch">
										<input id="check_max_load_ram" name="check_max_load_ram" class="form-check-input toggle-class" type="checkbox"
											{{ $criterions->check_max_load_ram ? 'checked' : '' }}>
										<label class="form-check-label required-label" for="check_max_load_ram">Верхний порог загруженности
											ОЗУ:</label>
									</div>
									<div class="d-flex align-items-center">
										<input type="number" maxlength="3" min="0" max="100" type="max_load_ram" class="form-control w-25"
											id="max_load_ram" name="max_load_ram" value="{{ $criterions->max_load_ram }}" required>
										<div class="ms-1">%</div>
									</div>
								</div>
								@error('max_load_ram')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror

								<div class="mt-2">
									<div class="form-check form-switch">
										<input id="check_min_cores_count" name="check_min_cores_count" class="form-check-input toggle-class"
											type="checkbox" {{ $criterions->check_min_cores_count ? 'checked' : '' }}>
										<label class="form-check-label required-label" for="check_min_cores_count">Минимально необходимое кол-во логических
											ядер:</label>
									</div>
									<input type="number" maxlength="3" min="1" max="999" type="min_cores_count" class="form-control w-25"
										id="min_cores_count" name="min_cores_count" value="{{ $criterions->min_cores_count }}" required>
								</div>
								@error('min_cores_count')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror

								<div class="mt-2">
									<div class="form-check form-switch">
										<input id="check_min_ram_size" name="check_min_ram_size" class="form-check-input toggle-class"
											type="checkbox" {{ $criterions->check_min_ram_size ? 'checked' : '' }}>
										<label class="form-check-label required-label" for="check_min_ram_size">Минимально необходимый объём ОЗУ:</label>
									</div>
									<div class="d-flex align-items-center">
										<input type="number" maxlength="3" min="1" max="999" type="min_ram_size" class="form-control w-25"
											id="min_ram_size" name="min_ram_size" value="{{ $criterions->min_ram_size }}" required>
										<div class="ms-1">ГБ</div>
									</div>
								</div>
								@error('min_ram_size')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror

								<hr class="">

								<div class="form-check form-switch m-0">
									<input id="check_active_processes" name="check_active_processes" class="form-check-input toggle-class"
										type="checkbox" {{ $criterions->check_active_processes ? 'checked' : '' }}>
									<label class="form-check-label" for="check_active_processes">Проверка наличия активных процессов</label>
								</div>
								@error('check_active_processes')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror
								<div>
									<span class="text-muted">(Перечесление программ через запятую)</span>
									<textarea style="resize:none;" class="form-control mt-2" rows="5"
          name="required_active_processes">{{ implode(',', json_decode($criterions->required_active_processes, true)) }}</textarea>
								</div>

								<div class="form-check form-switch mt-3">
									<input id="check_installed_programs" name="check_installed_programs" class="form-check-input toggle-class"
										type="checkbox" {{ $criterions->check_installed_programs ? 'checked' : '' }}>
									<label class="form-check-label" for="check_installed_programs">Проверка наличия установленных программ</label>
								</div>
								@error('check_installed_programs')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror
								<div>
									<span class="text-muted">(Перечесление программ через запятую)</span>
									<textarea style="resize:none;" class="form-control mt-2" rows="5"
          name="required_installed_programs">{{ implode(',', json_decode($criterions->required_installed_programs, true)) }}</textarea>
								</div>

								<div class="form-check form-switch mt-3">
									<input id="check_autoload_programs" name="check_autoload_programs" class="form-check-input toggle-class"
										type="checkbox" {{ $criterions->check_autoload_programs ? 'checked' : '' }}>
									<label class="form-check-label" for="check_autoload_programs">Проверка наличия программ в автозапуске</label>
								</div>
								@error('check_autoload_programs')
									<p style="color: #d93025">
										{{ $message }}
									</p>
								@enderror
								<div>
									<span class="text-muted">(Перечесление программ через запятую)</span>
									<textarea style="resize:none;" class="form-control mt-2" rows="5"
          name="required_autoload_programs">{{ implode(',', json_decode($criterions->required_autoload_programs, true)) }}</textarea>
								</div>

								<div class="d-flex flex-row-reverse">
									@method('PATCH')
									@csrf
									<button @if (Session::has('autofocus2')) autofocus @endif type="submit"
										class="shadow btn btn-primary mt-3">Сохранить</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			@endif
</x-admin-layout>
