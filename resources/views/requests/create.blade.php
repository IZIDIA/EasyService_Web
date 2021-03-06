<x-header-layout>
	<div class="container">
		<main class="noCopy">
			<div class="my-4 text-center">
				@if (!Auth::check())
					<p class="lead text-warning">Внимание: вы создаёте заявку без предварительной регистрации и не сможете
						отслеживать подробную информацию, только статус выполнения.</p>
				@endif
				<h2>Форма для отправки заявки</h2>
			</div>
			<div class="row g-5">
				<div class="col-md-8 offset-md-2">
					<form class="needs-validation" novalidate action="/requests" method="POST" enctype="multipart/form-data">
						<div class="row g-3">
							<div class="my-3">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="anonym" name="anonym" @checked(false)
										@if (old('anonym') == 'on') checked @endif onclick="enableAnonym()">
									<label class="form-check-label" for="anonym">Анонимная заявка <span class="text-muted">(Администратор
											не сможет связаться с вами, в случае необходимости)</span></label>
								</div>
							</div>
							<hr class="my-0">
							<div class="col-sm-6" id="first_name_div">
								<label for="first_name" class="form-label required-label">Имя</label>
								@if (Auth::check())
									<input maxlength="40" type="text" class="form-control" id="first_name" name="first_name"
										value="{{ old('first_name') ?? Str::before(Auth::user()->name, ' ') }}" required="false">
								@else
									<input maxlength="40" type="text" class="form-control" id="first_name" name="first_name" required
										value="{{ old('first_name') }}">
								@endif
								<div class="invalid-feedback">
									Требуется действительное имя.
								</div>
								@error('first_name')
									<div class="mt-1" style="color: #d93025">
										Требуется действительное имя.
									</div>
								@enderror
							</div>
							<div class="col-sm-6" id="last_name_div">
								<label for="last_name" class="form-label required-label">Фамилия</label>
								@if (Auth::check())
									<input maxlength="40" type="text" class="form-control" id="last_name" name="last_name"
										value="{{ old('last_name') ?? Str::after(Auth::user()->name, ' ') }}" required>
								@else
									<input maxlength="40" type="text" class="form-control" id="last_name" name="last_name" required
										value="{{ old('last_name') }}">
								@endif
								<div class="invalid-feedback">
									Требуется действующая фамилия.
								</div>
								@error('last_name')
									<div class="mt-1" style="color: #d93025">
										Требуется действующая фамилия.
									</div>
								@enderror
							</div>
							<div class="col-12" id="email_div">
								<label for="email" class="form-label required-label">Email</label>
								@if (Auth::check())
									<input maxlength="64" type="email" class="form-control" id="email" name="email"
										value="{{ old('email') ?? Auth::user()->email }}" required>
								@else
									<input maxlength="64" type="email" class="form-control" id="email" name="email" required
										value="{{ old('email') }}">
								@endif
								<div class="invalid-feedback">
									Пожалуйста, введите действующий адрес электронной почты для обратной связи.
								</div>
								@error('email')
									<div class="mt-1" style="color: #d93025">
										Пожалуйста, введите действующий адрес электронной почты для обратной связи.
									</div>
								@enderror
							</div>
							<div class="col-12">
								<label for="location" class="form-label required-label">Местонахождение оборудования</label>
								<input maxlength="128" type="text" class="form-control" id="location" name="location"
									placeholder="Здание, комната (кабинет)..." required value="{{ old('location') }}">
								<div class="invalid-feedback">
									Пожалуйста, введите адрес расположения оборудования.
								</div>
								@error('location')
									<div class="mt-1" style="color: #d93025">
										Пожалуйста, введите адрес расположения оборудования.
									</div>
								@enderror
							</div>
							<div class="col-12" id="phone_call_number_div">
								<label for="phone_call_number" class="form-label required-label">Контактный номер</label><span
									class="text-muted"> (Пример:
									89991234455 или 79991234455)</span>
								<input maxlength="11" pattern="^\d+" type="tel" class="form-control" id="phone_call_number"
									name="phone_call_number" placeholder="Мобильный или рабочий..." required
									value="{{ old('phone_call_number') }}">
								<div class="invalid-feedback">
									Пожалуйста, введите номер телефона состоящий только из цифр.
								</div>
								@error('phone_call_number')
									<div class="mt-1" style="color: #d93025">
										Пожалуйста, введите номер телефона состоящий только из цифр.
									</div>
								@enderror
							</div>
							<div class="col-12">
								<label for="inventory_number" class="form-label">Инвентарный номер оборудования <span
										class="text-muted">(Если
										имеется)</span></label>
								<input maxlength="64" type="tel" class="form-control" id="inventory_number" name="inventory_number"
									placeholder="Номер, для ведения учета..." value="{{ old('inventory_number') }}">
								@error('inventory_number')
									<div class="mt-1" style="color: #d93025">
										Максимальная длина номера 64 символа.
									</div>
								@enderror
							</div>
							<hr class="mt-4">
							<div class="my-2" id="work_time_div">
								<h4 class="mb-2">Решить проблему в вашем присутствии?</h4>
								<div class="form-check">
									<input value="1" id="anyway_with" name="solution_with_me" type="radio" class="form-check-input" checked
										required onclick="showDataTableFunction()">
									<label class="form-check-label" for="anyway_with">Неважно</label>
								</div>
								<div class="form-check">
									<input value="2" id="yes_with" name="solution_with_me" type="radio" class="form-check-input" required
										{{ old('solution_with_me') == '2' ? 'checked' : '' }} onclick="showDataTableFunction()">
									<label class="form-check-label" for="yes_with">Да, решить проблему в моём присутствии</label>
								</div>
								<div class="form-check">
									<input value="3" id="no_with" name="solution_with_me" type="radio" class="form-check-input" required
										{{ old('solution_with_me') == '3' ? 'checked' : '' }} onclick="showDataTableFunction()">
									<label class="form-check-label" for="no_with">Нет, решить проблему в моё отсутствие</label>
								</div>
							</div>
							<div id="dataDiv" style="display: @if (old('solution_with_me') == '2' || old('solution_with_me') == '3') block @else none @endif ">
								<h4 class="mb-2">График вашей работы: <span class="text-muted fs-5">(Если
										график не получается задать по шаблону, сообщите о нём в тексте заявки)</span></h4>
								<div class="form-check ">
									<span style="display: inline-block;  width: 1px;">
										<input type="checkbox" class="form-check-input" name="monday" id="monday"
											onchange="showOrHide('monday', 'time_monday');"
											@if (old('monday') == 'on') checked
											@else
													@if (old('solution_with_me') == null)
													checked @endif
											@endif>
										<label class="form-check-label" for="monday">ПН</label>
									</span>
									<span id='time_monday' @if (old('solution_with_me') != null && old('monday') != 'on') style="visibility: hidden" @endif>
										<label class="ms-5" for="from_monday">С:</label>
										<input class="ms-1" type="time" id="from_monday" name="from_monday"
											value="{{ old('from_monday') ?? '09:00' }}">
										<label class="ms-1" for="to_monday">До:</label>
										<input class="ms-1" type="time" id="to_monday" name="to_monday"
											value="{{ old('to_monday') ?? '18:00' }}">
									</span>
								</div>
								<div class="form-check ">
									<span style="display: inline-block;  width: 1px;">
										<input type="checkbox" class="form-check-input" name="tuesday" id="tuesday"
											onchange="showOrHide('tuesday', 'time_tuesday');"
											@if (old('tuesday') == 'on') checked
											@else
													@if (old('solution_with_me') == null)
													checked @endif
											@endif>
										<label class="form-check-label" for="tuesday">ВТ</label>
									</span>
									<span id='time_tuesday' @if (old('solution_with_me') != null && old('tuesday') != 'on') style="visibility: hidden" @endif>
										<label class="ms-5" for="from_tuesday">С:</label>
										<input class="ms-1" type="time" id="from_tuesday" name="from_tuesday"
											value="{{ old('from_tuesday') ?? '09:00' }}">
										<label class="  ms-1" for="to_tuesday">До:</label>
										<input class="ms-1" type="time" id="to_tuesday" name="to_tuesday"
											value="{{ old('to_tuesday') ?? '18:00' }}">
									</span>
								</div>
								<div class="form-check">
									<span style="display: inline-block;  width: 1px;">
										<input type="checkbox" class="form-check-input" name="wednesday" id="wednesday"
											onchange="showOrHide('wednesday', 'time_wednesday');"
											@if (old('wednesday') == 'on') checked
											@else
													@if (old('solution_with_me') == null)
													checked @endif
											@endif>
										<label class="form-check-label" for="wednesday">СР</label>
									</span>
									<span id='time_wednesday' @if (old('solution_with_me') != null && old('wednesday') != 'on') style="visibility: hidden" @endif>
										<label class="ms-5" for="from_wednesday">С:</label>
										<input class="ms-1" type="time" id="from_wednesday" name="from_wednesday"
											value="{{ old('from_wednesday') ?? '09:00' }}">
										<label class="  ms-1" for="to_wednesday">До:</label>
										<input class="ms-1" type="time" id="to_wednesday" name="to_wednesday"
											value="{{ old('to_wednesday') ?? '18:00' }}">
									</span>
								</div>
								<div class="form-check">
									<span style="display: inline-block;  width: 1px;">
										<input type="checkbox" class="form-check-input" name="thursday" id="thursday"
											onchange="showOrHide('thursday', 'time_thursday');"
											@if (old('thursday') == 'on') checked
											@else
													@if (old('solution_with_me') == null)
													checked @endif
											@endif>
										<label class="form-check-label" for="thursday">ЧТ</label>
									</span>
									<span id='time_thursday' @if (old('solution_with_me') != null && old('thursday') != 'on') style="visibility: hidden" @endif>
										<label class="ms-5" for="from_thursday">С:</label>
										<input class="ms-1" type="time" id="from_thursday" name="from_thursday"
											value="{{ old('from_thursday') ?? '09:00' }}">
										<label class="   ms-1" for="to_thursday">До:</label>
										<input class="ms-1" type="time" id="to_thursday" name="to_thursday"
											value="{{ old('to_thursday') ?? '18:00' }}">
									</span>
								</div>
								<div class="form-check">
									<span style="display: inline-block;  width: 1px;">
										<input type="checkbox" class="form-check-input" name="friday" id="friday"
											onchange="showOrHide('friday', 'time_friday');"
											@if (old('friday') == 'on') checked
											@else
													@if (old('solution_with_me') == null)
													checked @endif
											@endif>
										<label class="form-check-label" for="friday">ПТ</label>
									</span>
									<span id='time_friday' @if (old('solution_with_me') != null && old('friday') != 'on') style="visibility: hidden" @endif>
										<label class="ms-5" for="from_friday">С:</label>
										<input class="ms-1" type="time" id="from_friday" name="from_friday"
											value="{{ old('from_friday') ?? '09:00' }}">
										<label class="   ms-1" for="to_friday">До:</label>
										<input class="ms-1" type="time" id="to_friday" name="to_friday"
											value="{{ old('to_friday') ?? '18:00' }}">
									</span>
								</div>
								<div class="form-check">
									<span style="display: inline-block;  width: 1px;">
										<input type="checkbox" class="form-check-input" name="saturday" id="saturday"
											onchange="showOrHide('saturday', 'time_saturday');" @if (old('saturday') == 'on') checked @endif>
										<label class="form-check-label" for="saturday">СБ</label>
									</span>
									<span id='time_saturday' @if (old('saturday') != 'on') style="visibility: hidden" @endif>
										<label class="ms-5" for="from_saturday">С:</label>
										<input class="ms-1" type="time" id="from_saturday" name="from_saturday"
											value="{{ old('from_saturday') ?? '09:00' }}">
										<label class="   ms-1" for="to_saturday">До:</label>
										<input class="ms-1" type="time" id="to_saturday" name="to_saturday"
											value="{{ old('to_saturday') ?? '18:00' }}">
									</span>
								</div>
								<div class="form-check">
									<span style="display: inline-block;  width: 1px;">
										<input type="checkbox" class="form-check-input" name="sunday" id="sunday"
											onchange="showOrHide('sunday', 'time_sunday');" @if (old('sunday') == 'on') checked @endif>
										<label class="form-check-label" for="sunday">ВС</label>
									</span>
									<span id='time_sunday' @if (old('sunday') != 'on') style="visibility: hidden" @endif>
										<label class="ms-5" for="from_sunday">С:</label>
										<input class="ms-1" type="time" id="from_sunday" name="from_sunday"
											value="{{ old('from_sunday') ?? '09:00' }}">
										<label class="   ms-1" for="to_sunday">До:</label>
										<input class="ms-1" type="time" id="to_sunday" name="to_sunday"
											value="{{ old('to_sunday') ?? '18:00' }}">
									</span>
								</div>
							</div>
							<hr class="my-1" id="work_time_hr">
							<div class="my-3" id="problem_with_pc_div">
								<h4 class="mb-2">Проблема с текущим рабочим ПК?</h4>
								<div class="form-check mb-3">
									<input type="checkbox" class="form-check-input" id="zCheck" name="problem_with_my_pc"
										@if (old('problem_with_my_pc') == 'on') checked @endif onclick="showPasswordInputFunction()">
									<label class="form-check-label" for="zCheck">Да, проблема с текущим ПК</label>
								</div>
								<div class="col-sm-4" id="zDiv"
									style="display: @if (old('problem_with_my_pc') == 'on') block @else none @endif">
									<label for="user_password">Пароль пользователя: <span class="text-muted">(Если
											имеется)</span></label> <br>
									<input class="form-control" type="text" name="user_password" id="user_password" maxlength="64"
										placeholder="Для входа в систему..." value="{{ old('user_password') }}" />
								</div>
								@error('user_password')
									<div class="mt-1" style="color: #d93025">
										{{ $message }}
									</div>
								@enderror
							</div>
							<hr class="my-1" id="problem_with_pc_hr">
							<div class="col-12 mb-3">
								<h4 class="required-label">Тема</h4>
								<input maxlength="64" type="text" class="form-control" id="topic" name="topic"
									placeholder="Например: Мерцает монитор..." required value="{{ old('topic') }}">
								<div class="invalid-feedback">
									Требуется тема, кратко описывающая вашу проблему.
								</div>
								@error('topic')
									<div class="mt-1" style="color: #d93025">
										Требуется тема, кратко описывающая вашу проблему.
									</div>
								@enderror
							</div>
							<div class="mb-2">
								<h4 class="required-label">Сообщение</h4>
								<textarea class="form-control mb-1" id="text" name="text" rows="10" maxlength="4000" minlength="1"
         placeholder="Опишите вашу проблему..." required>{{ old('text') }}</textarea>
								<div class="invalid-feedback">
									Требуется описание вашей проблемы.
								</div>
								@error('text')
									<div class="mt-1" style="color: #d93025">
										Требуется описание вашей проблемы.
									</div>
								@enderror
								<div class="text-end me-1" id="textarea_count" name="textarea_count">
									{{ old('textarea_count') ?? '0/4000' }}</div>
							</div>
							<script>
							 let input = document.getElementById('text');
							 input.oninput = function() {
							  let element = document.getElementById('text').value.length;
							  document.getElementById('textarea_count').innerHTML = element + "/4000";
							 };
							</script>
							<div class="col-md-6 offset-md-3 text-center">
								<label class="mb-3" for="file">Фотография или скриншот проблемы (1) <span class="text-muted">(По
										желанию)</span></label>
								<input @error('photo') autofocus @enderror class="form-control mb-1" type="file" name="photo" id="file"
									accept=".jpg, .jpeg, .png" onchange="return fileValidation()">
								<small class="error_message" @error('photo') style="color: #d93025" @enderror>Файл должен быть менее 10 МБ.
									Разрешенные типы файлов: jpg, jpeg, png</small>
							</div>
							@if ($errors->any())
								<script>
								 charCount();
								 enableAnonym();
								</script>
							@endif
							<hr class="my-4">
							@csrf
							<button class="w-100 btn btn-primary btn-lg" type="submit">Отправить заявку</button>
					</form>
				</div>
			</div>
			<script>
			 window.addEventListener("pageshow", function(event) {
			  var historyTraversal = event.persisted ||
			   (typeof window.performance != "undefined" &&
			    window.performance.navigation.type === 2);
			  if (historyTraversal) {
			   window.location.reload();
			  }
			 });
			</script>
		</main>
</x-header-layout>
