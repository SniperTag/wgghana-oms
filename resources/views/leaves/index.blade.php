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
               <div class="row g-3">
    <!-- Remaining Days -->
    <div class="col-6 col-xl-2">
        <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
            <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                <div class="d-none d-sm-block">
                    <i class="fas fa-calendar fa-2x text-primary-light"></i>
                </div>
                <div class="text-end">
                    <div class="fs-3 fw-semibold text-primary">{{ $remainingAnnualLeave }}</div>
                    <div class="fs-sm fw-semibold text-uppercase text-muted">Remaining Days</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Attendance Record -->
    {{--  <div class="col-6 col-xl-2">
        <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
            <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                <div class="d-none d-sm-block">
                    <i class="si si-wallet fa-2x text-earth-light"></i>
                </div>
                <div class="text-end">
                    <div class="fs-3 fw-semibold text-earth">$780</div>
                    <div class="fs-sm fw-semibold text-uppercase text-muted">Attendance Record</div>
                </div>
            </div>
        </a>
    </div>  --}}

    <!-- Leave Status Counts -->
    <div class="col-6 col-xl-2">
        <a class="block block-rounded block-bordered block-link-shadow" href="#">
            <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                <div class="d-none d-sm-block">
                    <i class="bi bi-calendar-check fa-2x text-success"></i>
                </div>
                <div class="text-end">
                    <div class="fs-3 fw-semibold text-success">{{  $approvedCount }}</div>
                    <div class="fs-sm fw-semibold text-uppercase text-muted">Approved Leave</div>
                </div>
            </div>
        </a>
    </div>

<div class="col-6 col-xl-2">
        <a class="block block-rounded block-bordered block-link-shadow" href="#">
            <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                <div class="d-none d-sm-block">
                    <i class="bi bi-calendar-x fa-2x text-pulse"></i>
                </div>
                <div class="text-end">
                    <div class="fs-3 fw-semibold text-pulse">{{  $rejectedCount }}</div>
                    <div class="fs-sm fw-semibold text-uppercase text-muted">Rejected Leave</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Staffs on Leave -->
    <div class="col-6 col-xl-2">
        <a class="block block-rounded block-bordered block-link-shadow" href="#">
            <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                <div class="d-none d-sm-block">
                    <i class="bi bi-calendar-range fa-2x text-primary"></i>
                </div>
                <div class="text-end">
                    <div class="fs-3 fw-semibold text-primary">{{  $pendingCount }}</div>
                    <div class="fs-sm fw-semibold text-uppercase text-muted">Pending Leave</div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-xl-2">
        <a class="block block-rounded block-bordered block-link-shadow" href="#">
            <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                <div class="d-none d-sm-block">
                    <i class="si si-users fa-2x text-mute"></i>
                </div>
                <div class="text-end">
                    <div class="fs-3 fw-semibold text-pulse">{{ $onLeaveCount }}</div>
                    <div class="fs-sm fw-semibold text-uppercase text-muted">Staff on Leave</div>
                </div>
            </div>
        </a>
    </div>
</div>


                <!-- END Row #1 -->

                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-12">

                            <div class="bg-white p-6 rounded-xl shadow-md">
                                <div class="flex justify-between items-center mb-6">
                                    <h2 class="text-2xl font-semibold text-gray-800">Leave Requests</h2>
                                    <a href="{{ route('leaves.create') }}"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                        + Request Leave
                                    </a>
                                </div>

                                {{-- Toastr Notifications --}}

                                @if (session('success'))
                                    <script>
                                        toastr.success("{{ session('success') }}")
                                    </script>
                                @endif

                                @if (session('error'))
                                    <script>
                                        toastr.error("{{ session('error') }}")
                                    </script>
                                @endif

                                {{-- Table --}}
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200 text-sm">
                                        <thead>
                                            <tr class="bg-gray-100 text-gray-700 text-left">
                                                <th class="px-4 py-3 border">#</th>
                                                <th class="px-4 py-3 border">Staff</th>
                                                <th class="px-4 py-3 border">Leave Type</th>
                                                <th class="px-4 py-3 border">Date Range</th>
                                                <th class="px-4 py-3 border">Status</th>
                                                <th class="px-4 py-3 border">Approved By</th>
                                                <th class="px-4 py-3 border">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($leaves as $index => $leave)
                                                <tr class="border-t hover:bg-gray-50">
                                                    <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-2 border">{{ $leave->user->name }}</td>
                                                    <td class="px-4 py-2 border">{{ $leave->leaveType->name }}
                                                    </td>
                                                    <td class="px-4 py-2 border">
                                                        {{ $leave->start_date->format('d M Y') }} -
                                                        {{ $leave->end_date->format('d M Y') }}</td>
                                                    <td class="px-4 py-2 border">
                                                        @php
                                                            $status = $leave->status;
                                                        @endphp
                                                        <span
                                                            class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                                ">
                                                            {{ ucfirst($status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-2 border">
                                                        {{ $leave->approved_by_user ? $leave->approved_by_user->name : '-' }}
                                                    </td>
                                                    <td class="px-4 py-2 border space-x-2">
                                                        {{-- Optional Actions like view, cancel, or approve --}}
                                                        <a href="{{ route('leaves.index', $leave->id) }}"
                                                            class="text-blue-600 hover:underline text-sm">View</a>

                                                        @if (auth()->user()->can('approve leave') && $leave->status === 'pending')
                                                            <a href="{{ route('leaves.approve', $leave->id) }}"
                                                                class="text-green-600 hover:underline text-sm">Approve</a>
                                                            <a href="{{ route('leaves.reject', $leave->id) }}"
                                                                class="text-red-600 hover:underline text-sm">Reject</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-gray-500">No
                                                        leave requests found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Pagination --}}
                                <div class="mt-4">
                                    {{ $leaves->links() }}
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

            </div>

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

    <!-- Select2 Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


</body>

</html>
