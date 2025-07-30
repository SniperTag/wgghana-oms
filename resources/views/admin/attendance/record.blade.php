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
                            <h1 class="fw-bold font-san-serif font-extrabold text-3xl text-uppercase">{{Str::title($user->name)}} ATTENDANCE RECORDS</h1>

                        </div>

                        {{-- Filters Section --}}
                        <form method="GET" action="{{ route('admin.attendance') }}"
                            class="row gy-2 gx-3 align-items-center mb-4">

                            {{-- Dropdown Filter (Today, This Week, This Month) --}}
                            <div class="col-auto">
                                <select class="form-select" name="filter" onchange="this.form.submit()">
                                    <option value="">-- Filter By --</option>
                                    <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Today
                                    </option>
                                    <option value="this_week" {{ request('filter') == 'this_week' ? 'selected' : '' }}>
                                        This Week</option>
                                    <option value="this_month"
                                        {{ request('filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                </select>
                            </div>

                            {{-- Custom Date Range --}}
                            <div class="col-auto">
                                <input type="date" name="from" class="form-control" value="{{ request('from') }}"
                                    {{ request('filter') ? 'disabled' : '' }} placeholder="From">


                            </div>

                            <div class="col-auto">
                                <input type="date" name="to" class="form-control" value="{{ request('to') }}"
                                    {{ request('filter') ? 'disabled' : '' }} placeholder="To">
                            </div>

                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary"
                                    {{ request('filter') ? 'disabled' : '' }}>
                                    Apply Filter
                                </button>
                            </div>

                            {{-- Reset Filters --}}
                            <div class="col-auto">
                                <a href="{{ route('admin.attendance') }}" class="btn btn-outline-danger">Reset
                                    Filters</a>
                            </div>

                        </form>

                        <div class="mb-4 flex flex-wrap items-center justify-start gap-3 text-sm">

                            {{-- Step Out Livewire Component --}}
                            @livewire('step-out-manager')

                            {{-- Clock In/Out Dropdown --}}
                            @php
                                $hasClockedIn = \App\Models\AttendanceRecord::where('user_id', Auth::id())
                                    ->whereDate('attendance_date', today())
                                    ->whereNotNull('check_in_time')
                                    ->exists();
                            @endphp

                            <div class="d-flex justify-content-start align-items-center gap-2">
                                @if (!$hasClockedIn)
                                    <button class="btn btn-success d-flex align-items-center gap-2"
                                        data-bs-toggle="modal" data-bs-target="#clockInModal">
                                        <i class="fas fa-sign-in-alt"></i> Clock In
                                    </button>
                                @else
                                    <button class="btn btn-danger d-flex align-items-center gap-2"
                                        onclick="openClockOutModal('{{ Auth::user()->scheduled_clock_out ?? '17:30' }}')">
                                        <i class="fas fa-sign-out-alt"></i> Clock Out
                                    </button>
                                @endif
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
                                    @forelse ($records as $record)
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
                                {{ $records->appends(request()->query())->links() }}
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
