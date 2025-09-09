<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('layouts.head')
    <style>
        /* Transparent backdrop */
        .modal-backdrop.show {
            background-color: transparent !important;
        }

        /* Add subtle shadow and rounded corners to modal */
        .modal-content.custom-modal {
            background-color: rgba(255, 255, 255, 0.95);
            /* semi-transparent white */
            color: #000;
            /* black text */
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            /* soft shadow */
            border-radius: 0.75rem;
            /* rounded corners */
        }
    </style>
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
                                            <span
                                                class="badge bg-secondary text-dark">{{ ucfirst($leave->status) }}</span>
                                        </td>
                                        <td>
                                            @if ($leave->supervisor_status === 'pending')
                                                <span
                                                    class="badge bg-warning text-dark fs-sm fw-semibold">Pending</span>
                                            @elseif ($leave->supervisor_status === 'approved')
                                                <span class="badge bg-success text-dark">Approved</span>
                                            @elseif ($leave->supervisor_status === 'rejected')
                                                <span class="badge bg-danger text-dark">Rejected</span>
                                            @else
                                                <span
                                                    class="badge bg-info text-dark">{{ ucfirst($leave->supervisor_status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $leave->created_at->format('d M Y') }}</td>

                                        <td>
                                            <!-- View button triggers modal -->
                                            @if ($leave->supervisor_status === 'pending')
                                                <!-- Eye button triggers modal -->
                                                <button type="button" class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#leaveModal{{ $leave->id }}">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                            @else
                                                <!-- Disabled eye when supervisor has acted -->
                                                <button type="button" class="btn btn-sm btn-secondary" disabled>
                                                    <i class="bi bi-eye-slash"></i> Viewed
                                                </button>
                                            @endif


                                            <!-- Leave Details Modal -->
                                            <!-- Leave Details Modal -->
                                            <div class="modal fade" id="leaveModal{{ $leave->id }}" tabindex="-1"
                                                aria-labelledby="leaveModalLabel{{ $leave->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content custom-modal">
                                                        <div class="modal-header border-0">
                                                            <h5 class="modal-title"
                                                                id="leaveModalLabel{{ $leave->id }}">
                                                                Leave Request from {{ $leave->user->name }}
                                                                @if ($leave->supervisor_status === 'approved')
                                                                    <small class="text-success ms-2">(Approved at
                                                                        {{ $leave->supervisor_approved_at->format('d M Y, h:i A') }})</small>
                                                                @elseif ($leave->supervisor_status === 'rejected')
                                                                    <small class="text-danger ms-2">(Rejected at
                                                                        {{ $leave->supervisor_rejected_at->format('d M Y, h:i A') }})</small>
                                                                @endif
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <p><strong>Leave Type:</strong>
                                                                {{ $leave->leaveType->name ?? 'N/A' }}</p>
                                                            <p><strong>Date Range:</strong>
                                                                {{ $leave->start_date->format('d M Y') }} -
                                                                {{ $leave->end_date->format('d M Y') }}</p>
                                                            <p><strong>Days Requested:</strong>
                                                                {{ $leave->days_requested }}</p>
                                                            <p><strong>Reason:</strong> {{ $leave->reason }}</p>
                                                            <hr>
                                                            <p><strong>Status:</strong> <span
                                                                    class="badge bg-secondary text-dark">{{ ucfirst($leave->status) }}</span>
                                                            </p>
                                                            <p><strong>Supervisor Status:</strong>
                                                                @if ($leave->supervisor_status === 'approved')
                                                                    <span
                                                                        class="badge bg-success text-dark">Approved</span>
                                                                @elseif ($leave->supervisor_status === 'rejected')
                                                                    <span
                                                                        class="badge bg-danger text-dark">Rejected</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-warning text-dark">Pending</span>
                                                                @endif
                                                            </p>
                                                            <p><strong>Submitted At:</strong>
                                                                {{ $leave->created_at->format('d M Y, h:i A') }}</p>
                                                        </div>

                                                        <div class="modal-footer border-0">
                                                            @if ($leave->supervisor_status === 'pending')
                                                                <form
                                                                    action="{{ route('supervisor.approve', $leave->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button class="btn btn-success"
                                                                        onclick="return confirm('Approve this request?')">✅
                                                                        Approve</button>
                                                                </form>
                                                                <form
                                                                    action="{{ route('supervisor.reject', $leave->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button class="btn btn-danger"
                                                                        onclick="return confirm('Reject this request?')">❌
                                                                        Reject</button>
                                                                </form>
                                                            @endif
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

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
        @include('layouts.js')
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
