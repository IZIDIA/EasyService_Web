@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">{{ __('Регистрация') }}</div>
					<div class="card-body">
						<form method="POST" action="{{ route('register') }}">
							@csrf
							<div class="mb-3 row">
								<label for="first_name" class="col-md-4 col-form-label text-end">
									{{ __('Имя') }} :
								</label>
								<div class="col-md-6">
									<input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror"
										name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>
									@error('first_name')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="mb-3 row">
								<label for="last_name" class="col-md-4 col-form-label text-end">
									{{ __('Фамилия') }} :
								</label>
								<div class="col-md-6">
									<input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name"
										value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>
									@error('last_name')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="mb-3 row">
								<label for="email" class="col-md-4 col-form-label text-end">
									{{ __('E-Mail') }} :
								</label>
								<div class="col-md-6">
									<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
										value="{{ old('email') }}" required autocomplete="email" autofocus>
									@error('email')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="mb-3 row">
								<label for="password" class="col-md-4 col-form-label text-end">
									{{ __('Пароль') }} :
								</label>
								<div class="col-md-6">
									<input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
										name="password" required autocomplete="new-password">
									@error('password')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="mb-3 row">
								<label for="password-confirm" class="col-md-4 col-form-label text-end">
									{{ __('Повторите пароль') }} :
								</label>
								<div class="col-md-6">
									<input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror"
										name="password_confirmation" required autocomplete="new-password">
								</div>
							</div>
							<div class="mb-3 row">
								<div class="col-md-6 offset-md-4">
									<button type="submit" class="btn btn-primary">
										{{ __('Создать аккаунт') }}
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection