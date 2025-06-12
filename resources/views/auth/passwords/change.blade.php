<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Default Password | WG Office Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-8">

        {{-- Company Logo --}}
        <div class="flex justify-center mb-6">
            <img src="{{ asset('build/assets/image/Office_logo.jpg') }}" alt="Company Logo" class="h-16">
        </div>

        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Change Default Password</h2>

        {{-- Flash Success --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 text-sm rounded p-3 mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Flash Error --}}
        @if (session('error'))
            <div class="bg-red-100 border border-red-300 text-red-700 text-sm rounded p-3 mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-600 text-sm rounded p-3 mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')
            {{-- Hidden Fields --}}
            {{-- Current Password --}}
            <div class="mb-4">
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                <input type="password" name="current_password" id="current_password" required
                       class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            {{-- New Password --}}
            <div class="mb-4">
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" name="new_password" id="new_password" required
                       class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            {{-- Confirm Password --}}
            <div class="mb-6">
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                       class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300">
                Change Password
            </button>
        </form>
    </div>

</body>
</html>
