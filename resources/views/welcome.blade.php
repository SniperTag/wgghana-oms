<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'en') }}" class="dark">

<head>
    @include('partials.head') {{-- Contains meta tags, title, stylesheets --}}
</head>

<body>
    <div id="page-container" class="main-content-boxed">
        <script>
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @elseif (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        </script>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
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
                                <img src="{{ asset('build/assets/image/Office_logo.jpg') }}"
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
                            <button class="btn btn-primary mt-3 w-100" data-bs-toggle="modal"
                                data-bs-target="#staffLoginModal"> <!-- Added margin and width for consistency -->
                                Staff Clock In
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


    <script>
        // Toastr Notification Options
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            "timeOut": "5000",
            "showDuration": "300",
            "hideDuration": "1000",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Flash Session Messages
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @elseif (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        });
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("✅ Staff verification script loaded");

            const verifyBtn = document.getElementById('verifyBtn');
            const staffIdInput = document.getElementById('staff_id');
            const pinSection = document.getElementById('pin_section');
            const submitBtn = document.getElementById('submitBtn');
            const staffError = document.getElementById('staff_error');
            const staffInfo = document.getElementById('staff_info');

            // Confirm all DOM elements are present
            if (!verifyBtn || !staffIdInput || !pinSection || !submitBtn || !staffError || !staffInfo) {
                console.error("❌ One or more required elements not found in DOM.");
                return;
            }

            verifyBtn.addEventListener('click', function() {
                const staffId = staffIdInput.value.trim();

                if (!staffId) {
                    alert("⚠️ Please enter a Staff ID.");
                    return;
                }

                console.log(`🔍 Verifying Staff ID: ${staffId}`);

                fetch(`/admin/attendance/verify/${staffId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            pinSection.classList.remove('d-none');
                            staffError.style.display = 'none';
                            staffInfo.innerHTML = data.message.replace(/\n/g, '<br>'); // <-- this line
                            staffInfo.style.display = 'block';
                            submitBtn.disabled = false;
                        } else {
                            pinSection.classList.add('d-none');
                            staffError.textContent = data.message;
                            staffError.style.display = 'block';
                            staffInfo.style.display = 'none';
                            submitBtn.disabled = true;
                        }
                    })
                    .catch(error => {
                        console.error('❌ Error during fetch:', error);
                        pinSection.classList.add('d-none');
                        staffInfo.style.display = 'none';
                        staffError.textContent = "Something went wrong. Please try again.";
                        staffError.style.display = 'block';
                        submitBtn.disabled = true;
                    });
            });
        });

// Toastr Notification Options
         toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            "timeOut": "5000",
            "showDuration": "300",
            "hideDuration": "1000",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Flash Session Messages
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @elseif (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        });
    </script>
</body>

</html>
