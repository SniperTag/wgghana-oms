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
                <div class="container mt-4">
                    <h2 class="mb-4">Subordinate Leave Requests</h2>

                    @if ($leaves->count())
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Dates</th>
                                    <th>Status</th>
                                    <th>Supervisor Status</th>
                                    <th>Requested On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaves as $leave)
                                    <tr>
                                        <td>{{ $leave->user->name }}</td>
                                        <td>{{ $leave->leaveType->name ?? '-' }}</td>
                                        <td>{{ $leave->start_date->format('d M Y') }} -
                                            {{ $leave->end_date->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge badge-secondary">{{ ucfirst($leave->status) }}</span>
                                        </td>
                                        <td>
                                            @if ($leave->supervisor_status === 'pending')
                                                <span class="badge badge-warning fs-sm fw-semibold">Pending</span>
                                            @elseif ($leave->supervisor_status === 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif ($leave->supervisor_status === 'rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                            @else
                                                <span
                                                    class="badge badge-info">{{ ucfirst($leave->supervisor_status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $leave->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('supervisor.subordinates.show', $leave->id) }}"
                                                class="btn btn-sm btn-primary">
                                                View
                                            </a>
                                            @if ($leave->supervisor_status === 'pending')
                                                <form
                                                    action="{{ route('supervisor.subordinates.approve', $leave->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success"
                                                        onclick="return confirm('Approve this request?')">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form
                                                    action="{{ route('supervisor.subordinates.reject', $leave->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Reject this request?')">
                                                        Reject
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $leaves->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">No leave requests from your subordinates.</div>
                    @endif
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

    <!-- Bootstrap Bundle (Popper.js included) -->
    {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  --}}

</body>

</html>
