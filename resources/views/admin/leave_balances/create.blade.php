<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('layouts.head')
</head>

<body>
    <!-- Page Container -->


    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

        <!-- Sidebar -->

        {{-- Side bar dashboard start --}}

        @include('layouts.partials.sidebar')

        {{-- Side bar dashboard End --}}

        {{-- Side bar dashboard start --}}

        {{-- Side bar dashboard End --}}



        {{-- Header Section --}}
        @include('layouts.header')

        <!-- Main Container -->
        <main id="main-container">
            <!-- Page Content -->
            <div class="content mt-7 bg-white">
    <h2>Create Leave Balance for Staff</h2>

    <form action="{{ route('leave_balances.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="user_id" class="block font-medium text-gray-700">Select User</label>
            <select name="user_id" id="user_id" class="w-full border rounded p-2" required>
                <option value="">-- Select User --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->staff_id ?? $user->email }})</option>
                @endforeach
            </select>
        </div>

        {{-- Leave Type --}}
        <div class="form-group">
            <label for="leave_type_id">Leave Type</label>
            <select name="leave_type_id" id="leave_type_id" class="form-control" required>
                <option value="">-- Select Leave Type --</option>
                @foreach ($leaveTypes as $type)
                    <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('leave_type_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Total Days --}}
        <div class="form-group">
            <label for="total_days">Total Days</label>
            <input type="number" name="total_days" id="total_days" class="form-control"
                value="{{ old('total_days') }}" min="0" required>
            @error('total_days')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Used Days --}}
        <div class="form-group">
            <label for="used_days">Used Days</label>
            <input type="number" name="used_days" id="used_days" class="form-control"
                value="{{ old('used_days', 0) }}" min="0" required>
            @error('used_days')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Remaining Days --}}
        <div class="form-group">
            <label for="remaining_days">Remaining Days</label>
            <input type="number" name="remaining_days" id="remaining_days" class="form-control"
                value="{{ old('remaining_days') }}" min="0" required>
            @error('remaining_days')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Year --}}
        <div class="form-group">
            <label for="year">Year</label>
            <input type="number" name="year" id="year" class="form-control"
                value="{{ old('year', date('Y')) }}" min="2000" max="2100" required>
            @error('year')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-3">Create Leave Balance</button>
        <a href="{{ route('all.staffs') }}" class="btn btn-secondary mt-3">Back to Users</a>
    </form>
</div>

            <!-- END Page Content -->
        </main>
        {{-- Main section --}}

        <!-- END Main Container -->
        @include('layouts.js')
    </div>
    <!-- END Page Container -->



</body>

</html>
