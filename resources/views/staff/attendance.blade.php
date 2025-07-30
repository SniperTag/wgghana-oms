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




        {{-- Header Section --}}
        @include('layouts.header')

        <!-- Main Container -->
        <main id="main-container block-content-full">
            <!-- Page Content -->
            <div class="content mt-7">
                <div class="row">
                    <!-- Row #1 -->


                    <div class="container-fluid">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="fw-bold font-san-serif font-extrabold text-3xl">MY ATTENDANCE RECORDS</h1>

                        </div>

                        {{-- Filters Section --}}
                        <form method="GET" action="{{ route('attendance.index') }}"
                            class="row gy-2 gx-3 align-items-center mb-4">
                            <div class="col-auto">
                                <select class="form-select" name="filter">
                                    <option value="">-- Filter By --</option>
                                    <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Today
                                    </option>
                                    <option value="this_week" {{ request('filter') == 'this_week' ? 'selected' : '' }}>
                                        This Week</option>
                                    <option value="this_month"
                                        {{ request('filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                </select>
                            </div>

                            <div class="col-auto">
                                <input type="date" name="from" class="form-control" value="{{ request('from') }}"
                                    placeholder="From Date">
                            </div>

                            <div class="col-auto">
                                <input type="date" name="to" class="form-control" value="{{ request('to') }}"
                                    placeholder="To Date">
                            </div>

                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Apply Filter</button>
                            </div>

                            <div class="col-auto">
                                <a href="{{ route('attendance.index') }}" class="btn btn-outline-danger">Reset
                                    Filters</a>
                            </div>
                        </form>

                        <div class="mb-4 flex flex-wrap items-center justify-start gap-3 text-sm">

                            {{-- Step Out Livewire Component --}}
                            @livewire('step-out-manager')

                            {{-- Clock In/Out Dropdown --}}
                            <div class="dropdown">
                                <button
                                    class="btn btn-primary dropdown-toggle d-flex justify-content-between align-items-center"
                                    type="button" id="clockActionDropdown" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fas fa-clock me-2"></i> Clock Actions
                                </button>
                                <ul class="dropdown-menu shadow" aria-labelledby="clockActionDropdown">
                                    <li>
                                        <button class="dropdown-item text-success" data-bs-toggle="modal"
                                            data-bs-target="#clockInModal">
                                            <i class="fas fa-sign-in-alt me-2"></i> Clock In
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item text-danger"
                                            onclick="openClockOutModal({{ auth()->user()->id }})">
                                            <i class="fas fa-sign-out-alt me-2"></i> Clock Out
                                        </button>
                                    </li>

                                </ul>
                            </div>
                            {{--  @livewire('break-manager')  --}}
                        </div>


                        {{-- Attendance Table --}}
                        <div class="table-responsive shadow-sm border rounded">
                            <table class="table table-striped table-hover align-middle text-center mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        {{--  <th>Department</th>
                                        <th>Role</th>  --}}
                                        <th>Time-in</th>
                                        <th>Time-out</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        {{--  <th>IP</th>
                                        <th>Device</th>  --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($attendanceRecords as $record)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $record->user->name }}</td>
                                            {{--  <td>{{ $record->user->department->name ?? 'N/A' }}</td>
                                            <td>{{ $record->user->getRoleNames()->first() ?? 'N/A' }}</td>  --}}
                                            <td>
                                                @php
                                                    $status = $record->status;
                                                    $checkInClass = match ($status) {
                                                        'On Time' => 'badge bg-success',
                                                        'Late' => 'badge bg-warning text-dark',
                                                        'Very Late' => 'badge bg-danger',
                                                        default => 'badge bg-secondary',
                                                    };
                                                @endphp
                                                <span class="{{ $checkInClass }}">{{ $record->check_in_time }}</span>
                                            </td>
                                            <td>{{ $record->check_out_time ?? '—' }}</td>
                                            <td>{{ $record->status }}</td>
                                            <td>{{ $record->attendance_date }}</td>
                                            {{--  <td>{{ $record->ip_address }}</td>
                                            <td>{{ $record->device_info ?? 'Unknown' }}</td>  --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">No records found for the selected filters.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $attendanceRecords->appends(request()->query())->links() }}
                            </div>
                        </div>


                    </div>


                    @include('components.modals.staff-login')

                    @include('components.modals.clock-out')


                </div>

            </div>
            <!-- END Page Content -->
        </main>
        {{-- Main section --}}

        <!-- END Main Container -->
        @include('layouts.js')
    </div>


</body>

</html>
