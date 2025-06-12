<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | WG Office Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<script>
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if($errors->any())
        toastr.error("{{ $errors->first() }}");
    @endif
</script>

    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
        <!-- Company Logo -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('build/assets/image/Office_logo.jpg') }}" alt="Company Logo" class="h-16">
        </div>

        <h2 class="text-2xl font-bold mb-6 text-center text-gray-700">Reset Password</h2>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Token -->
           <input type="hidden" name="token" value="{{ $token ?? request()->route('token') }}">


            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                <input id="email" type="email" name="email"
                    value="{{ old('email', request()->email) }}"
                    required autofocus
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-600 mb-1">New Password</label>
                <input id="password" type="password" name="password"
                    required
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-600 mb-1">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                    required
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                @error('password_confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300">
                Reset Password
            </button>
        </form>

        <!-- Back to Login -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                Back to Login
            </a>
        </div>
    </div>

</body>
</html>
