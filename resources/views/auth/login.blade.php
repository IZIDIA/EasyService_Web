<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="4 4 38 38" class="bi me-2" width="80"
                    height="80" role="img" aria-label="Bootstrap">
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
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Почта')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                    autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Пароль')" />

                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Запомнить меня') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-3">
                    {{ __('Войти') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
