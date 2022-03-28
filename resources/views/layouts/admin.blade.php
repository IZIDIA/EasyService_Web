<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Easy Service</title>
	<link rel="shortcut icon" href="{{ url('favicon.ico') }}">
	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}" defer></script>
	<script src="{{ asset('js/jquery.js') }}" defer></script>
	<style>

	</style>

</head>

<body class="text-white" style="background-color: #2D3748">

	<header class="sticky-top shadow p-2 text-white " style="background: radial-gradient(#152e5d, #1A202C);">

		<div class="container">

			<div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
				<a href="/" class="d-none d-lg-block d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="4 4 38 38" class="bi me-2" width="60" height="60" role="img"
						aria-label="Bootstrap">
						<path fill="#0d47a1"
							d="M34.249,42H13.751C9.47,42,6,38.53,6,34.249V13.751C6,9.47,9.47,6,13.751,6h20.497	C38.53,6,42,9.47,42,13.751v20.497C42,38.53,38.53,42,34.249,42z" />
						<path fill="#1565c0"
							d="M42,13.751C42,9.47,38.53,6,34.249,6H13.751C9.47,6,6,9.47,6,13.751v9.028	c3,3.221,8.502,7.14,13.646,7.106C26.494,29.84,36.654,27.166,42,21.407V13.751z" />
						<path fill="#1e88e5"
							d="M42,13.751C42,9.47,38.53,6,34.249,6H13.751C9.47,6,6,9.47,6,13.751v8.75	c3,3.499,7.219,4.858,13.694,4.905C30.143,27.482,38.328,22.577,42,17.992V13.751z" />
						<path fill="#29b6f6"
							d="M42,13.751C42,9.47,38.53,6,34.249,6H13.751C9.47,6,6,9.47,6,13.751v8.18	c4.408,2.669,9.077,3.262,12.674,3.336C29.161,25.327,38.611,20.031,42,13.869V13.751z" />
						<path fill="#00e5ff"
							d="M34.249,6H13.751C9.47,6,6,9.47,6,13.751v5.98c4.167,2.708,8.472,3.482,12.917,3.446	c7.194,0,18.718-3.832,22.265-12.878C39.911,7.753,37.288,6,34.249,6z" />
						<path fill="#18ffff"
							d="M34.249,6H13.751C9.47,6,6,9.47,6,13.751v3.691c4.748,3.197,10.543,4.603,17.778,2.867	c8.943-2.146,14.011-6.927,15.83-12.149C38.216,6.825,36.33,6,34.249,6z" />
						<path fill="#448aff"
							d="M20.535,6h-6.784C9.47,6,6,9.47,6,13.751v9.534l0.063,0.089c4.25,6,14.75,9.875,21.625,3.563	c4-5,1.688-13.188-1.313-15.875C24.868,9.712,22.51,7.684,20.535,6z" />
						<path fill="#40c4ff"
							d="M20.973,6h-7.222C9.47,6,6,9.47,6,13.751v9.718c5.079,3.953,13.005,5.51,18.563,0.406	c4-5,1.688-13.188-1.313-15.875C22.613,7.43,21.821,6.735,20.973,6z" />
						<path fill="#00e5ff"
							d="M13.751,6C9.47,6,6,9.47,6,13.751v7.932c4.686,1.975,10.315,1.966,14.563-1.933	C23.787,15.72,22.908,9.622,20.875,6H13.751z" />
						<path fill="#18ffff"
							d="M13.751,6C9.47,6,6,9.47,6,13.751v4.726c3.476,0.382,7.055-0.459,9.979-3.144	C18.098,12.684,18.439,9.145,17.85,6H13.751z" />
						<path fill="#212121"
							d="M12.608,14.87c0,0,6.833,10.327,12.578,13.82c-2.174,0.699-6.894,3.555-15.124-1.492	c2.096,2.873,5.807,8.635,14.891,8.092c3.183-0.155,5.124-1.165,6.91-1.863s3.494-0.621,4.969,2.019	c0.311-2.407,0.388-4.659-2.096-7.221c0,0,2.456-9.363-8.001-16.751c2.174,4.348,3.433,6.8,2.411,12.791	c-2.329-1.553-10.931-8.48-12.872-10.732c1.398,2.485,5.419,7.936,6.816,9.334C20.606,21.159,12.608,14.87,12.608,14.87z" />
					</svg>
				</a>
				<ul class="nav me-sm-auto justify-content-center mb-md-0 fs-5">
					<li><a href="/" class="nav-link px-2 text-white me-1 rounded headerlink">· Главная</a></li>
					<li><a href="/contacts" class="d-none d-lg-block nav-link px-2 text-white me-1 rounded headerlink">· Контакты</a>
					</li>
					<li><a href="/requests" class="d-none d-lg-block nav-link px-2 text-white me-1 rounded headerlink">· Заявки</a></li>
					<li><a href="/docs" class="d-none d-lg-block nav-link px-2 text-white me-1 rounded headerlink">· Документы</a></li>
					@if (Auth::check() && Auth::user()->is_admin)
						<li><a href="/admin" class="d-none d-lg-block nav-link px-2 text-warning rounded headerlink">· Админ-панель</a>
						</li>
					@endif
				</ul>
				<div class="text-end" style="font-size: ">
					@if (Route::has('login'))
						@auth
							<nav class="navbar navbar-expand-lg navbar-dark py-0">
								<div class="container-fluid">
									<a class="nav-link dropdown-toggle text-white fw-bold py-0 rounded headerlink" href="#" id="navbarDropdown"
										role="button" data-bs-toggle="dropdown" aria-expanded="false">
										<div class="me-2" style="display: inline-block;">
											<div>{{ Auth::user()->name }}</div>
											<div> {{ Auth::user()->email }}</div>
										</div>
										@if (isset(Auth::user()->admin->is_master))
											@if (!Auth::user()->admin->is_master)
												<i class="bi bi-cpu d-inline-flex" style="font-size: 30px; color:#ffd700"></i>
											@else
												<i class="bi bi-eye d-inline-flex" style="font-size: 30px; color:#bc13fe"></i>
											@endif
										@else
											<i class="bi bi-person-fill d-inline-flex" style="font-size: 30px;"></i>
										@endif
									</a>
									<ul class="shadow me-2 mt-lg-3 mt-2 dropdown-menu dropdown-menu-dark dropdown-menu-end"
										aria-labelledby="navbarDropdown">
										<form method="POST" action="{{ route('logout') }}">
											@csrf
											<a class="dropdown-item text-white" href="route('logout')"
												onclick="event.preventDefault(); this.closest('form').submit();">Выйти</a>
										</form>
									</ul>
								</div>
							</nav>
						@else
							<a type="button" class="btn btn-outline-light me-2" href="{{ route('login') }}">Войти</a>
							@if (Route::has('register'))
								<a type="button" class="btn btn-warning" href="{{ route('register') }}">Регистрация</a>
							@endif
						@endauth
					@endif
				</div>
			</div>
		</div>

		<div class="navbar-dark ">
			<button style="right: 10px; margin-top:15px; padding:0;" class="navbar-toggler position-absolute d-md-none collapsed"
				type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
				aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
		</div>

	</header>

	<div class="container-fluid">
		<div class="row">
			<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse shadow" style="background-color: #1A202C">
				<div class="position-sticky pt-5">
					<ul class="nav flex-column fs-5">
						<li class="nav-item">
							<a class="nav-link {{ request()->is('admin/my') ? 'active' : 'text-white' }} text-nowrap" href="/admin/my">
								<i class="bi bi-pen-fill feather"></i>
								Мои заявки
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link {{ request()->is('admin/requests') ? 'active' : 'text-white' }} text-nowrap"
								href="/admin/requests">
								<i class="bi bi-archive-fill feather"></i>
								Все заявки
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link text-white {{ request()->is('admin/users') ? 'active' : 'text-white' }} text-nowrap"
								href="/admin/users">
								<i class="bi bi-people-fill feather"></i>
								Пользователи
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link text-white text-nowrap" href="#">
								<i class="bi bi-telephone-fill feather"></i>
								Контакты
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link text-white text-nowrap" href="#">
								<i class="bi bi-file-text-fill feather"></i>
								Документы
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link text-white text-nowrap" href="#">
								<i class="bi bi-bar-chart-line-fill feather"></i>
								Статистика
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link text-white text-nowrap" href="#">
								<i class="bi bi-gear-fill feather"></i>
								Настройки
							</a>
						</li>
					</ul>
				</div>
			</nav>

			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

				{{ $slot }}

				<footer class="my-2 pt-5 text-muted text-center text-small">
					<p class="mb-1"> {{ App\Models\Option::find(1)->value('company_name') }}</p>
				</footer>

			</main>
		</div>
	</div>

</body>

</html>
