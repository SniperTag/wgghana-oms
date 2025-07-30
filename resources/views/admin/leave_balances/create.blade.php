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
        <a href="{{ route('admin.users_index') }}" class="btn btn-secondary mt-3">Back to Users</a>
    </form>
</div>

            <!-- END Page Content -->
        </main>
        {{-- Main section --}}

        <!-- END Main Container -->
        @include('layouts.footer')
    </div>
    <!-- END Page Container -->

    <script>
        $(document).ready(function() {
            $('#roles').select2({
                placeholder: "Select role(s)",
                width: '100%'
            });
        });

        $(document).ready(function() {
            $('#department').select2({
                placeholder: "Select department(s)",
                width: '100%'
            });
        });
    </script>
    <script>
        function updateRemainingDays() {
            const total = parseInt(document.getElementById('total_days').value) || 0;
            const used = parseInt(document.getElementById('used_days').value) || 0;
            document.getElementById('remaining_days').value = Math.max(total - used, 0);
        }

        document.getElementById('total_days').addEventListener('input', updateRemainingDays);
        document.getElementById('used_days').addEventListener('input', updateRemainingDays);
    </script>


    <!-- Select2 Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Bootstrap Bundle (Popper.js included) -->
    {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  --}}

</body>

</html>
