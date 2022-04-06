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
						<div>
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
								<p style="color: red">
									@error('distributed_requests')
										{{ $message }}
									@enderror
								</p>
								<div class="input-group mb-3">
									<input id="time_to_work" name="time_to_work" type="text" class="form-control"
										value="{{ $options->time_to_work }}">
									<span style="overflow:hidden; text-overflow: ellipsis;" class="input-group-text w-75">Часы на выполнение
										заявки</span>
								</div>
								<p style="color: red">
									@error('time_to_work')
										{{ $message }}
									@enderror
								</p>
								<div class="input-group mb-3">
									<input id="time_to_accept_distributed" name="time_to_accept_distributed" type="text" class="form-control"
										value="{{ $options->time_to_accept_distributed }}">
									<span style="overflow:hidden; text-overflow: ellipsis;" class="input-group-text w-75">Часы на принятие
										распределённой
										заявки</span>
								</div>
								<p style="color: red">
									@error('time_to_accept_distributed')
										{{ $message }}
									@enderror
								</p>
								<div class="input-group mb-3">
									<input id="check_interval" name="check_interval" type="text" class="form-control"
										value="{{ $options->check_interval }}">
									<span style="overflow:hidden; text-overflow: ellipsis;" class="input-group-text w-75">Частота опроса сервера
										для уведомлений (мс)</span>
								</div>
								<p style="color: red">
									@error('check_interval')
										{{ $message }}
									@enderror
								</p>
								<div class="input-group mb-3">
									<textarea id="welcome_text" name="welcome_text" style="min-height: 250px" class="form-control" rows="10"
          maxlength="4000">{{ $options->welcome_text }}</textarea>
									<span class="input-group-text w-25 text-wrap">Текст на главной странице сайта</span>
								</div>
								<p style="color: red">
									@error('welcome_text')
										{{ $message }}
									@enderror
								</p>
								<div class="d-flex flex-row-reverse">
									@method('PATCH')
									@csrf
									<button @if (Session::has('autofocus')) ) autofocus @endif type="submit"
										class="shadow btn btn-primary">Сохранить</button>
								</div>
							</form>
						</div>
			@endif

		</div>

	</div>
</x-admin-layout>
