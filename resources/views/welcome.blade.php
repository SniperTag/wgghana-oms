<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'en') }}" class="dark">

<head>
    @include('layouts.app') {{-- Contains meta tags, title, stylesheets --}}
</head>

<body>
    <div id="page-container" class="main-content-boxed">
        @if (session('success'))
            <div class="container mt-3">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @php
            $errors = $errors ?? (session('errors') ?? new \Illuminate\Support\ViewErrorBag());
        @endphp

        @if ($errors->any())
            <div class="container mt-3">
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif


        <!--Main Container-->
        <main id="main-container">
            <!-- Page Content -->
            <div class="bg-image bg-primary">
                <!-- Bootstrap class for primary color -->

                <div class="row mx-0 bg-dark bg-opacity-50">

                    <!-- Left Panel - Branding -->
                    <div
                        class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-center justify-content-center">
                        <div class="text-center text-white p-10">
                            <h1 class="display-4 fw-bold">Waltergates Ghana Limited</h1>
                            <h3 class="fw-semibold">Office Management System</h3>




                        </div>
                    </div>

                    <!-- Right Panel - Sign In Form -->
                    <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-body-extra-light">
                        <div class="content content-full">

                            <!-- Header -->
                            <div class="px-4 py-2 mb-4 text-center">
                                <img src="{{ asset('image/Office_logo.jpg') }}"
                                    alt="Waltergates Office Logo" class="w-50 mb-4 mx-auto d-block">
                                <h1 class="h3 fw-bold mt-4 mb-2">Welcome to Your Dashboard</h1>
                                <h2 class="h5 fw-medium text-muted mb-0">Please sign in</h2>
                            </div>
                            <!-- END Header -->

                            <!-- Sign In Form -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <!-- Email Address -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                        :value="old('email')" required autofocus autocomplete="username" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- Password -->
                                <div class="mt-4">
                                    <x-input-label for="password" :value="__('Password')" />
                                    <x-text-input id="password" class="block mt-1 w-full" type="password"
                                        name="password" required autocomplete="current-password" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <!-- Remember Me -->
                                <div class="block mt-4">
                                    <label for="remember_me" class="inline-flex items-center">
                                        <input id="remember_me" type="checkbox"
                                            class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                            name="remember">
                                        <span
                                            class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                                    </label>
                                </div>


                                <div class="flex items-center justify-center mt-4">

                                    <x-primary-button class=" w-100 justify-center">
                                        {{ __('Log in') }}
                                    </x-primary-button>
                                </div>
                            </form>
                            <!-- END Sign In Form -->
                            <button type="button" class="btn btn-primary w-full mt-2" data-bs-toggle="modal"
                                data-bs-target="#clockInModal">
                                 Clock In
                            </button>


                        </div>


                    </div>
                </div>
            </div>
            <!-- END Page Content -->
        </main>
        <!-- END Main Container -->
    </div>
    <div class="items-center">
        @include('components.modals.staff-login')
        @include('components.modals.clock-out')
    </div>
    <!-- Footer Scripts -->
    @include('layouts.js')


</body>

</html>
