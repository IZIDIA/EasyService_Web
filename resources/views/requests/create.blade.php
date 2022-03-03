<x-header-layout>

	<div class="container">
		<main class="noCopy">
			<div class="py-5 text-center">
				<h2>Форма для отправки заявки</h2>
				<p class="lead text-warning">Если проблема связанна с вашим компьютером или оборудованием, подключённым к нему,
					желательно заполнить и отправить заявку в приложении, установленном на вашем компьютере.</p>
			</div>



			<div class="row g-5">

				<div class="col-md-8 offset-md-2">
					<form class="needs-validation" novalidate>
						<div class="row g-3">

							<div class="col-sm-6">
								<label for="firstName" class="form-label">Имя</label>
								@if (Auth::check())
									<input type="text" class="form-control" id="firstName" value="{{ Str::before(Auth::user()->name, ' ') }}"
										required>
								@else
									<input type="text" class="form-control" id="firstName" required>
								@endif
								<div class="invalid-feedback">
									Требуется действительное имя.
								</div>
							</div>

							<div class="col-sm-6">
								<label for="lastName" class="form-label">Фамилия</label>
								@if (Auth::check())
									<input type="text" class="form-control" id="lastName" value="{{ Str::after(Auth::user()->name, ' ') }}"
										required>
								@else
									<input type="text" class="form-control" id="lastName" required>
								@endif
								<div class="invalid-feedback">
									Требуется действующая фамилия.
								</div>
							</div>

							<div class="col-12">
								<label for="email" class="form-label">Email</label>
								@if (Auth::check())
									<input type="email" class="form-control" id="email" value="{{ Auth::user()->email }}" required>
								@else
									<input type="email" class="form-control" id="email" required>
								@endif
								<div class="invalid-feedback">
									Пожалуйста, введите действующий адрес электронной почты для обратной связи.
								</div>
							</div>

							<div class="col-12">
								<label for="location" class="form-label">Местонахождение оборудования</label>
								<input type="text" class="form-control" id="location" placeholder="Здание, комната (кабинет)..." required>
								<div class="invalid-feedback">
									Пожалуйста, введите адрес расположения оборудования.
								</div>
							</div>

							<div class="col-12">
								<label for="phone_call_number" class="form-label">Контактный номер</label>
								<input pattern="^\d+" type="tel" class="form-control" id="phone_call_number"
									placeholder="Мобильный или рабочий..." required>
								<div class="invalid-feedback">
									Пожалуйста, введите номер телефона состоящий только из цифр.
								</div>
							</div>

							<div class="col-12">
								<label for="phone_call_number" class="form-label">Инвентарный номер оборудования <span
										class="text-muted">(Если
										имеется)</span></label>
								<input type="tel" class="form-control" id="phone_call_number" placeholder="Номер, для ведения учета...">
							</div>

							<hr class="my-4">

							<h4 class="mb-1">Решить проблему в вашем присутствии?</h4>
							<div class="my-2">
								<div class="form-check">
									<input id="anyway_with" name="solution_with_me" type="radio" class="form-check-input" checked required
										onclick="showDataTableFunction()">
									<label class="form-check-label" for="anyway_with">Неважно</label>
								</div>
								<div class="form-check">
									<input id="yes_with" name="solution_with_me" type="radio" class="form-check-input" required
										onclick="showDataTableFunction()">
									<label class="form-check-label" for="yes_with">Да, решить проблему в моём присутствии</label>
								</div>
								<div class="form-check">
									<input id="no_with" name="solution_with_me" type="radio" class="form-check-input" required
										onclick="showDataTableFunction()">
									<label class="form-check-label" for="no_with">Нет, решить проблему в моё отсутствие</label>
								</div>
							</div>

							<div id="dataDiv" style="visibility: hidden">
								<h4 class="mb-2">График вашей работы: <span class="text-muted fs-5">(Если
										график не получается задать по шаблону, сообщите о нём в тексте заявки)</span></h4>
								<div class="form-check ">
									<span style="display: inline-block;  width: 1px;">
										<input type="checkbox" class="form-check-input" id="monday" checked
											onchange="showOrHide('monday', 'time_monday');">
										<label class="form-check-label" for="monday">ПН</label>
									</span>
									<span id='time_monday'>
										<label class="ms-5" for="from_monday">С:</label>
										<input class="ms-1" type="time" id="from_monday" value="09:00">
										<label class="ms-1" for="to_monday">До:</label>
										<input class="ms-1" type="time" id="to_monday" value="18:00">
									</span>
								</div>
								<div class="form-check ">
									<span style="display: inline-block;  width: 1px;">
										<input type="checkbox" class="form-check-input" id="tuesday" checked
											onchange="showOrHide('tuesday', 'time_tuesday');">
										<label class="form-check-label" for="tuesday">ВТ</label>
									</span>
									<span id='time_tuesday'>
										<label class="ms-5" for="from_tuesday">С:</label>
										<input class="ms-1" type="time" id="from_tuesday" value="09:00">
										<label class="ms-1" for="to_tuesday">До:</label>
										<input class="ms-1" type="time" id="to_tuesday" value="18:00">
									</span>
								</div>
								<div class="form-check">
									<span style="display: inline-block;  width: 1px;">
										<input type="checkbox" class="form-check-input" id="wednesday" checked
											onchange="showOrHide('wednesday', 'time_wednesday');">
										<label class="form-check-label" for="wednesday">СР</label>
									</span>
									<span id='time_wednesday'>
										<label class="ms-5" for="from_wednesday">С:</label>
										<input class="ms-1" type="time" id="from_wednesday" value="09:00">
										<label class="ms-1" for="to_wednesday">До:</label>
										<input class="ms-1" type="time" id="to_wednesday" value="18:00">
									</span>
								</div>
								<div class="form-check">
									<span style="display: inline-block;  width: 1px;">
										<input type="checkbox" class="form-check-input" id="thursday" checked
											onchange="showOrHide('thursday', 'time_thursday');">
										<label class="form-check-label" for="thursday">ЧТ</label>
									</span>
									<span id='time_thursday'>
										<label class="ms-5" for="from_thursday">С:</label>
										<input class="ms-1" type="time" id="from_thursday" value="09:00">
										<label class="ms-1" for="to_thursday">До:</label>
										<input class="ms-1" type="time" id="to_thursday" value="18:00">
									</span>
								</div>
								<div class="form-check">
									<span style="display: inline-block;  width: 1px;">
										<input type="checkbox" class="form-check-input" id="friday" checked
											onchange="showOrHide('friday', 'time_friday');">
										<label class="form-check-label" for="friday">ПТ</label>
									</span>
									<span id='time_friday'>
										<label class="ms-5" for="from_friday">С:</label>
										<input class="ms-1" type="time" id="from_friday" value="09:00">
										<label class="ms-1" for="to_friday">До:</label>
										<input class="ms-1" type="time" id="to_friday" value="18:00">
									</span>
								</div>
								<div class="form-check">
									<span style="display: inline-block;  width: 1px;">
										<input type="checkbox" class="form-check-input" id="saturday"
											onchange="showOrHide('saturday', 'time_saturday');">
										<label class="form-check-label" for="saturday">СБ</label>
									</span>
									<span id='time_saturday' style="visibility: hidden">
										<label class="ms-5" for="from_saturday">С:</label>
										<input class="ms-1" type="time" id="from_saturday" value="09:00">
										<label class="ms-1" for="to_saturday">До:</label>
										<input class="ms-1" type="time" id="to_saturday" value="18:00">
									</span>
								</div>
								<div class="form-check">
									<span style="display: inline-block;  width: 1px;">
										<input type="checkbox" class="form-check-input" id="sunday" onchange="showOrHide('sunday', 'time_sunday');">
										<label class="form-check-label" for="sunday">ВС</label>
									</span>
									<span id='time_sunday' style="visibility: hidden">
										<label class="ms-5" for="from_sunday">С:</label>
										<input class="ms-1" type="time" id="from_sunday" value="09:00">
										<label class="ms-1" for="to_sunday">До:</label>
										<input class="ms-1" type="time" id="to_sunday" value="18:00">
									</span>
								</div>
							</div>

							<hr class="my-1">

							<div class="my-3">
								<h4 class="mb-3">Проблема с вашим рабочим ПК?</h4>
								<div class="form-check mb-3">
									<input type="checkbox" class="form-check-input" id="zCheck" onclick="showPasswordInputFunction()">
									<label class="form-check-label" for="same-address">Да, проблема с моим ПК</label>
								</div>
								<div class="col-12" id="zDiv" style="visibility: hidden">
									<label for="user_password">Пароль пользователя: <span class="text-muted">(Если
											имеется)</span></label> <br>
									<input class="mt-2" type="password" name="password" id="user_password" />
									<i class="bi bi-eye-slash" id="togglePassword"></i>
								</div>
							</div>

							<script>
							 const togglePassword = document.querySelector("#togglePassword");
							 const password = document.querySelector("#user_password");
							 togglePassword.addEventListener("click", function() {
							  const type = password.getAttribute("type") === "password" ? "text" : "password";
							  password.setAttribute("type", type);
							  this.classList.toggle("bi-eye");
							 });
							 const form = document.querySelector("form");
							 form.addEventListener('submit', function(e) {
							  e.preventDefault();
							 });
							</script>

							<hr class="my-1">

							<h4>Сообщение</h4>
							<div>
								<textarea class="form-control" id="textarea" onkeyup="charCount();" name="textarea_description" rows="10"
									maxlength="4000" minlength="1" placeholder="Опишите вашу проблему..." required></textarea>
								<span class="textarea_count" id="textarea_count">0/4000</span>
							</div>

							<script type="text/javascript">
							 function charCount() {
							  var element = document.getElementById('textarea').value.length;
							  document.getElementById('textarea_count').innerHTML = element + "/4000";
							 }
							</script>

							<hr class="my-4">

							<button class="w-100 btn btn-primary btn-lg" type="submit">Отправить заявку</button>

					</form>
				</div>
			</div>
		</main>

</x-header-layout>
