<!DOCTYPE html>
<html lang="en">

<head>

    @extends('layouts.app')

</head>

<body>



<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-center mb-6">Reset Password</h2>

        @if (session('status'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-2 rounded mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            {{-- Hidden token field --}}
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email', request('email')) }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required autofocus>
            </div>

            {{-- New Password --}}
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium">New Password</label>
                <input type="password" name="password"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            {{-- Confirm Password --}}
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            {{-- Submit --}}
            <div>
                <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>


    @include('layouts.js')

</body>

</html>
