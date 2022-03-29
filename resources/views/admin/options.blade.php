<x-admin-layout>
	<div class="container px-5">

		<div class="col-lg-6 mx-auto">
			<div class="mt-5 p-3 d-flex shadow-sm" style="border-radius: 10px; background-color:#283141; height: 100%;">
				<div class="icon-square bg-dark text-light flex-shrink-0 me-3">
					<i class="bi bi-gear"></i>
				</div>
				<div style="width: 100%">
					<h2 class="pt-2 mb-3"> Ваши настройки:</h2>
					<div>
						<div class="form-check form-switch">
							<input class="form-check-input toggle-class" type="checkbox" id="flexSwitchCheck"
								{{ $admin->get_recommendation ? 'checked' : '' }}>
							<label class="form-check-label" for="flexSwitchCheck">Получать распределённые заявки</label>
						</div>
						<label class="text-warning" style="font-size: 0.8rem">Если вы не будете принимать распределённые заявки, данная
							функция автоматически отключится через: {{ $options->time_to_accept_distributed }} ч. после появления
							распределённой заявки</label>
					</div>
					<script src="{{ asset('js/jquery.js') }}"></script>
					<script type="text/javascript">
					 $.ajaxSetup({
					  headers: {
					   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					  }
					 });
					 $('.toggle-class').change(function(e) {
					  e.preventDefault();
					  var status = $(this).prop('checked') == true ? 1 : 0;
					  $.ajax({
					   url: "/admin/options/recommendation",
					   type: "POST",
					   data: {
					    status: status,
					   },
					   success: function(response) {
					    console.log(response);
					   },
					  });
					 });
					</script>
				</div>
			</div>
		</div>


	</div>
</x-admin-layout>
