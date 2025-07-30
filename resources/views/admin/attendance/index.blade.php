{{--  <x-layouts.app :title="__('Dashboard')">


</x-layouts.app>  --}}




<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('layouts.app')
</head>

<body>
    <!-- Page Container -->


    <div id="page-container"
        class="sidebar-o sidebar-light enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

        @include('layouts.partials.sidebar')


        {{-- Header Section --}}
        @include('layouts.header')

        <!-- Main Container -->
        <main id="main-container content-full">
            <!-- Page Content -->
            <div class="content mt-7">
                <div class="row">





                    <div class="container-fluid">
                        <h1 class="text-2xl font-bold mb-4 text-uppercase">Staffs Attendance Records</h1>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="card p-3">
                            @if (request('filter'))
                                <span class="text-sm text-gray-500">Filter:
                                    {{ ucwords(str_replace('_', ' ', request('filter'))) }}</span>
                            @elseif(request('from') && request('to'))
                                <span class="text-sm text-gray-500">Date Range: {{ request('from') }} →
                                    {{ request('to') }}</span>
                            @endif

                            {{-- Filters Section --}}
                            <form method="GET" action="{{ route('attendance.index') }}"
                                class="row gy-2 gx-3 align-items-center mb-4">
                                <div class="col-auto">
                                    <select class="form-select" name="filter">
                                        <option value="">-- Filter By --</option>
                                        <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>
                                            Today
                                        </option>
                                        <option value="this_week"
                                            {{ request('filter') == 'this_week' ? 'selected' : '' }}>
                                            This Week</option>
                                        <option value="this_month"
                                            {{ request('filter') == 'this_month' ? 'selected' : '' }}>This Month
                                        </option>
                                    </select>
                                </div>

                                <div class="col-auto">
                                    <input type="date" name="from" class="form-control"
                                        value="{{ request('from') }}" placeholder="From Date">
                                </div>

                                <div class="col-auto">
                                    <input type="date" name="to" class="form-control"
                                        value="{{ request('to') }}" placeholder="To Date">
                                </div>

                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                                </div>

                                <div class="col-auto">
                                    <a href="{{ route('attendance.index') }}" class="btn btn-outline-danger">Reset
                                        Filters</a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table id="attendanceTable"
                                    class="table table-striped table-hover text-center display nowrap"
                                    style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Department</th>
                                            <th>Role</th>
                                            <th>Time-in</th>
                                            <th>Time-out</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>IP Address</th>
                                            <th>Device</th>
                                            <th>Reason for Early clock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($attendanceRecords as $record)
                                            @php
                                                $status = $record->status;
                                                $checkInClass = match ($status) {
                                                    'On Time' => 'bg-success text-white',
                                                    'Late' => 'bg-warning text-dark',
                                                    'Very Late' => 'bg-danger text-white',
                                                    default => '',
                                                };

                                                $checkOutTime = $record->check_out_time
                                                    ? \Carbon\Carbon::parse($record->check_out_time)
                                                    : null;
                                                $cutoffTime = \Carbon\Carbon::createFromTime(17, 0, 0);
                                                $checkOutClass =
                                                    $checkOutTime && $checkOutTime->lt($cutoffTime)
                                                        ? 'text-danger fw-bold'
                                                        : 'text-success fw-bold';
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $record->user->name }}</td>
                                                <td>{{ $record->user->department->name ?? 'N/A' }}</td>
                                                <td>{{ $record->user->getRoleNames()->first() ?? 'N/A' }}</td>
                                                <td><span
                                                        class="{{ $checkInClass }} px-2 py-1 rounded d-inline-block">{{ $record->check_in_time }}</span>
                                                </td>
                                                <td class="{{ $checkOutClass }}">
                                                    {{ $checkOutTime ? $checkOutTime->format('h:i A') : '—' }}
                                                </td>
                                                <td>{{ $record->status }}</td>
                                                <td>{{ $record->attendance_date }}</td>
                                                <td>{{ $record->ip_address }}</td>
                                                <td>{{ $record->device_info ?? 'Unknown' }}</td>

                                                <td>
                                                    @php
                                                        $cutoff = \Carbon\Carbon::parse('17:00:00');
                                                        $clockOutTime = $record->check_out_time
                                                            ? \Carbon\Carbon::parse($record->check_out_time)
                                                            : null;
                                                    @endphp

                                                    @if ($clockOutTime && $clockOutTime->lt($cutoff) && $record->notes)
                                                        <button class="btn btn-sm btn-outline-info mt-1"
                                                            wire:click="$emit('showClockOutReasonModal', {{ $record->id }})">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>


                </div>

            </div>
            <!-- END Page Content -->
        </main>
        {{-- Main section --}}
        <!-- END Main Container -->

    </div>
    @livewire('view-clock-out-reason')

    {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>  --}}
    @include('layouts.js')


    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('showClockOutReasonModal', id => {
                console.log('Modal Event Triggered with ID:', id);
            });
        });
    </script>

</body>

</html>
