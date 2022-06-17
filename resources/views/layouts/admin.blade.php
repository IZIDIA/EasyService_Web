<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ config('app.name') }}</title>
	<link rel="shortcut icon" href="{{ url('favicon.ico') }}">
	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}" defer></script>
	<script src="{{ asset('js/mediumZoom.js') }}" defer></script>
	<script src="{{ asset('js/syncScripts.js') }}"></script>
	<script src="{{ asset('js/jquery.js') }}"></script>
	<style>
		.check-requests:after {
			content: "new";
			color: #00f7ff;
			font-size: 0.7rem;
		}
	</style>

</head>

<body class="text-white" style="background-color: #2D3748">

	<header class="sticky-top shadow p-2 text-white " style="background: radial-gradient(#152e5d, #1A202C);">

		<div class="container">

			<div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
				<a href="/" class="d-none d-lg-block d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
					<svg xmlns="http://www.w3.org/2000/svg" class="bi me-2s" viewBox="0 0 64 64" width="64px" height="64px">
						<linearGradient id="S2L9LrDNrtfq~_Da3oq6fa" x1="32" x2="32" y1="12.106" y2="52.587"
							gradientUnits="userSpaceOnUse">
							<stop offset="0" stop-color="#8cc5fc" />
							<stop offset="1" stop-color="#d5a8fb" />
						</linearGradient>
						<path fill="url(#S2L9LrDNrtfq~_Da3oq6fa)"
							d="M38,40H26c-1.105,0-2-0.895-2-2V26c0-1.105,0.895-2,2-2h12c1.105,0,2,0.895,2,2v12 C40,39.105,39.105,40,38,40z" />
						<linearGradient id="S2L9LrDNrtfq~_Da3oq6fb" x1="32" x2="32" y1="12.106" y2="52.587"
							gradientUnits="userSpaceOnUse">
							<stop offset="0" stop-color="#8cc5fc" />
							<stop offset="1" stop-color="#d5a8fb" />
						</linearGradient>
						<path fill="url(#S2L9LrDNrtfq~_Da3oq6fb)"
							d="M50,52H14c-1.103,0-2-0.897-2-2V14c0-1.103,0.897-2,2-2h36c1.103,0,2,0.897,2,2v36 C52,51.103,51.103,52,50,52z M14,14v36h36.002L50,14H14z" />
						<g>
							<linearGradient id="S2L9LrDNrtfq~_Da3oq6fc" x1="32" x2="32" y1="7.511" y2="54.514"
								gradientUnits="userSpaceOnUse">
								<stop offset="0" stop-color="#1a6dff" />
								<stop offset="1" stop-color="#c822ff" />
							</linearGradient>
							<path fill="url(#S2L9LrDNrtfq~_Da3oq6fc)"
								d="M51,8H13c-2.757,0-5,2.243-5,5v38c0,2.757,2.243,5,5,5h38c2.757,0,5-2.243,5-5V13 C56,10.243,53.757,8,51,8z M54,51c0,1.654-1.346,3-3,3H13c-1.654,0-3-1.346-3-3V13c0-1.654,1.346-3,3-3h38c1.654,0,3,1.346,3,3V51z" />
							<linearGradient id="S2L9LrDNrtfq~_Da3oq6fd" x1="32" x2="32" y1="7.511" y2="54.514"
								gradientUnits="userSpaceOnUse">
								<stop offset="0" stop-color="#1a6dff" />
								<stop offset="1" stop-color="#c822ff" />
							</linearGradient>
							<path fill="url(#S2L9LrDNrtfq~_Da3oq6fd)"
								d="M46,16H18c-1.103,0-2,0.897-2,2v28c0,1.103,0.897,2,2,2h28c1.103,0,2-0.897,2-2V18 C48,16.897,47.103,16,46,16z M18,46V18h28l0.002,28H18z" />
						</g>
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
									<a class="nav-link dropdown-toggle text-white fw-bold py-0 rounded headerlink d-flex align-items-center"
										href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
										<div class="me-2" style="display: inline-block;">
											<div style="max-width:180px; overflow:hidden;text-overflow: ellipsis;">{{ Auth::user()->name }}</div>
											<div style="max-width:180px; overflow:hidden;text-overflow: ellipsis;"> {{ Auth::user()->email }}</div>
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
									<ul class="shadow me-2 dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarDropdown">
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
							<a
								class="nav-link {{ request()->is('admin/my', 'admin/my/completed', 'admin/my/in_work') ? 'active' : 'text-white' }} text-nowrap"
								href="/admin/my">
								<i class="bi bi-pen-fill feather"></i>
								Мои заявки
							</a>
						</li>
						<li class="nav-item">
							<a
								class="nav-link {{ request()->is('admin/requests', 'admin/requests/completed', 'admin/requests/in_work', 'admin/requests/canceled', 'admin/requests/in_processing') ? 'active' : 'text-white' }} text-nowrap"
								href="/admin/requests">
								<i class="bi bi-archive-fill feather"></i>
								Все заявки<sup id="notification" class="d-none" style="margin-left: 5px; color:#96FBFE">new</sup>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link {{ request()->is('admin/users') ? 'active' : 'text-white' }} text-nowrap"
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
								Информация
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link {{ request()->is('admin/options') ? 'active' : 'text-white' }}  text-nowrap"
								href="/admin/options">
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

@if (Auth::check() && Auth::user()->admin->sound_notification)
	<script type="text/javascript">
	 $.ajaxSetup({
	  headers: {
	   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	 });
	 var requestsCount = {{ count(App\Models\RequestInfo::all()) }};
	 let notification = document.getElementById("notification");
	 audio = new Audio('/sound_notification.mp3');

	 function sound_notification() {
	  $.ajax({
	   url: "/admin/check_new_requests",
	   type: "POST",
	   success: function(response) {
	    if (response.newCount > requestsCount) {
	     audio.play();
	     notification.classList.remove('d-none');
	     document.title = "{{ config('app.name') }} (NEW)";
	    }
	    requestsCount = response.newCount;
	   },
	   error: function(error) {
	    console.log('error: failed to check new requests');
	   },
	  });
	 }
	 setInterval(sound_notification, {{ App\Models\Option::find(1)->value('check_interval') }});
	</script>
@endif

</html>
