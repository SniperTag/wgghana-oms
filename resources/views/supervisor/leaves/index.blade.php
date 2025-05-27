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
                <div class="container">
                    <h2 class="mb-4">My Leave Requests</h2>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">Pending: {{ $pendingCount }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">Approved: {{ $approvedCount }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">Rejected: {{ $rejectedCount }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">Currently on Leave: {{ $onLeaveCount }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Balance Info -->
                    @if ($leaveBalance)
                        <div class="alert alert-secondary">
                            Annual Leave: {{ $leaveBalance->used_days ?? 0 }} used /
                            {{ $leaveBalance->remaining_days ?? 0 }} remaining
                            (Total: {{ $totalAnnualLeaveCount }})
                        </div>
                    @endif

                    <!-- Leave Table -->
                    <div class="card">
                        <div class="card-header">Leave History</div>
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Leave Type</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Days</th>
                                        <th>Status</th>
                                        <th>Supervisor Status</th>
                                        <th>Approved At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($leaves as $leave)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $leave->leaveType->name ?? 'N/A' }}</td>
                                            <td>{{ $leave->start_date->format('d M Y') }}</td>
                                            <td>{{ $leave->end_date->format('d M Y') }}</td>
                                            <td>{{ $leave->days_requested ?? '-' }}</td>
                                            <td><span
                                                    class="badge bg-{{ $leave->status === 'approved' ? 'success' : ($leave->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($leave->status) }}</span></td>
                                            <td>{{ $leave->supervisor_status }}</td>
                                            <td>{{ $leave->approved_at ? $leave->approved_at->format('d M Y') : '-' }}
                                            </td>
                                            <td>
                                                <a href="{{ route('supervisor.leaves.show', $leave->id) }}"
                                                    class="btn btn-sm btn-outline-primary">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No leave requests found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            {{ $leaves->links() }}
                        </div>
                    </div>
                </div>

            </div>
            <!-- END Page Content -->
        </main>
        {{-- Main section --}}

        <!-- END Main Container -->

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

    <!-- Bootstrap Bundle (Popper.js included) -->
    {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  --}}
    @include('layouts.footer')
</body>

</html>
