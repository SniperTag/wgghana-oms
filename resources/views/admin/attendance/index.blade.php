{{--  <x-layouts.app :title="__('Dashboard')">


</x-layouts.app>  --}}




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
        <main id="main-container content-full">
            <!-- Page Content -->
            <div class="content mt-7">
                <div class="row">





                    <div class="container-fluid">
                        <h1 class="mb-4 text-uppercase text-xl text-bold text-san-serif">View Attendance Records</h1>
                        {{--
                        <form method="GET" action="{{ route('attendance.index') }}" class="mb-4 d-flex gap-2">
                            <input type="text" name="name" value="{{ request('name') }}"
                                class="form-control w-25 rounded" placeholder="Search by name">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </form>  --}}

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif



                        <div class="table-responsive card">
                            <table id="dataTable"
                                class="table table-bordered table-hover align-middle text-center table-auto">

                                <thead class="border-none">
                                    <tr>
                                        <th class="border-none">No</th>
                                        <th class="border-none">Name</th>
                                        <th class="border-none">Department</th>
                                        <th class="border-none">Role</th>
                                        <th class="border-none">Time-in</th>
                                        <th class="border-none">Time-out</th>
                                        <th class="border-none">Status</th>
                                        <th class="border-none">Date</th>
                                        <th class="border-none">IP Address</th>
                                        <th class="border-none">Device</th>
                                    </tr>
                                </thead>
                                <tbody class="border-none text-sm text-left">
                                    @foreach ($attendanceRecords as $record)
                                        <tr>
                                            <td class="border-none">{{ $loop->iteration }}</td>
                                            <td class="border-none">{{ $record->user->name }}</td>
                                            <td class="border-none">{{ $record->user->department->name ?? 'N/A' }}</td>
                                            <td class="border-none">
                                                {{ $record->user->getRoleNames()->first() ?? 'N/A' }}</td>
                                            <td class="border-none">
                                                @php
                                                    $status = $record->status;
                                                    $checkInClass = '';

                                                    if ($status === 'On Time') {
                                                        $checkInClass = 'bg-success text-white';
                                                    } elseif ($status === 'Late') {
                                                        $checkInClass = 'bg-warning text-dark';
                                                    } elseif ($status === 'Very Late') {
                                                        $checkInClass = 'bg-danger text-white';
                                                    }

                                                    $checkOutTime = \Carbon\Carbon::parse($record->check_out_time);
                                                    $cutoffTime = \Carbon\Carbon::createFromTime(17, 0, 0); // 5:00 PM
                                                    $checkOutClass = '';

                                                    if ($checkOutTime->lessThan($cutoffTime)) {
                                                        $checkOutClass = 'text-danger fw-bold'; // red text for early checkout
                                                    } else {
                                                        $checkOutClass = 'text-success fw-bold'; // green text for valid checkout
                                                    }
                                                @endphp

                                                <span class="{{ $checkInClass }} px-2 py-1 rounded d-inline-block">
                                                    {{ $record->check_in_time }}
                                                </span>
                                            </td>

                                            <td class="{{ $checkOutClass }}">
                                                {{ $checkOutTime ? $checkOutTime->format('h:i A') : '—' }}
                                            </td>
                                            <td class="border-none">{{ $record->status }}</td>
                                            <td class="border-none">{{ $record->attendance_date }}</td>

                                            <td class="border-none">{{ $record->ip_address }}</td>
                                            <td class="border-none">{{ $record->device_info ?? 'Unknown' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $attendanceRecords->appends(request()->query())->links() }}
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

    {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>  --}}
    @include('layouts.footer')
</body>

</html>
