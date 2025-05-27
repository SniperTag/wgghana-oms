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
                    <h2>Leave Request Details</h2>

                    <div class="card shadow mt-3">
                        <div class="card-header bg-primary text-white">
                            Request from {{ $leave->user->name }}
                        </div>
                        <div class="card-body">
                            <p><strong>Leave Type:</strong> {{ $leave->leaveType->name ?? 'N/A' }}</p>
                            <p><strong>Date Range:</strong> {{ $leave->start_date->format('d M Y') }} to
                                {{ $leave->end_date->format('d M Y') }}</p>
                            <p><strong>Days Requested:</strong> {{ $leave->days_requested }}</p>
                            <p><strong>Reason:</strong> {{ $leave->reason }}</p>

                            <hr>
                            <p><strong>Status:</strong>
                                <span class="badge badge-secondary">{{ ucfirst($leave->status) }}</span>
                            </p>
                            <p><strong>Supervisor Status:</strong>
                                @if ($leave->supervisor_status === 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif ($leave->supervisor_status === 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </p>
                            <p><strong>Submitted At:</strong> {{ $leave->created_at->format('d M Y, h:i A') }}</p>

                            <div class="mt-4">
                                @if ($leave->supervisor_status === 'pending')
                                    <form action="{{ route('supervisor.subordinates.approve', $leave->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-success"
                                            onclick="return confirm('Approve this request?')">
                                            ✅ Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('supervisor.subordinates.reject', $leave->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-danger" onclick="return confirm('Reject this request?')">
                                            ❌ Reject
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('supervisor.subordinates.index') }}" class="btn btn-secondary">
                                        ⬅ Back to List
                                    </a>
                                @endif
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

    <!-- Bootstrap Bundle (Popper.js included) -->
    {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  --}}

</body>

</html>
