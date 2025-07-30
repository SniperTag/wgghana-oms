@extends('layouts.app') {{-- Or your main layout --}}


<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow-md">
    <h2 class="text-2xl font-semibold mb-6 text-center">Complete Your Registration</h2>

    {{-- Show any validation errors --}}
    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('invite.register.submit') }}">
        @csrf

        {{-- Hidden input to pass token --}}
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Display invited email readonly --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2" for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $user->email ?? '') }}"
                readonly
                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 cursor-not-allowed"
            >
        </div>

        {{-- Name --}}
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold mb-2">Full Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $user->name ?? '') }}"
                required
                class="w-full border border-gray-300 rounded px-3 py-2"
                placeholder="Enter your full name"
            >
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                class="w-full border border-gray-300 rounded px-3 py-2"
                placeholder="Create a password"
            >
        </div>

        {{-- Confirm Password --}}
        <div class="mb-6">
            <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">Confirm Password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                class="w-full border border-gray-300 rounded px-3 py-2"
                placeholder="Confirm your password"
            >
        </div>

        <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded"
        >
            Complete Registration
        </button>
    </form>
</div>

