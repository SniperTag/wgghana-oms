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
    <h2 class="mb-4">Leave Request Details</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5><strong>Leave Type:</strong> {{ $leave->leaveType->name ?? 'N/A' }}</h5>
            <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}</p>
            <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</p>
            <p><strong>Days Requested:</strong> {{ $leave->days_requested }}</p>
            <p><strong>Reason:</strong> {{ $leave->reason }}</p>

            <hr>

            <p><strong>Status:</strong>
                <span class="badge bg-{{ $leave->status === 'approved' ? 'success' : ($leave->status === 'rejected' ? 'danger' : 'warning') }}">
                    {{ ucfirst($leave->status) }}
                </span>
            </p>

            @if($leave->supervisor_required)
                <p><strong>Supervisor Status:</strong> {{ ucfirst($leave->supervisor_status) }}</p>
                <p><strong>Supervisor:</strong> {{ $leave->supervisor->name ?? 'N/A' }}</p>
                <p><strong>Supervisor Approved At:</strong> 
                    {{ $leave->supervisor_approved_at ? \Carbon\Carbon::parse($leave->supervisor_approved_at)->format('d M Y H:i') : 'Pending' }}
                </p>
            @endif

            @if($leave->approved_by)
                <p><strong>Approved By (HR/Admin):</strong> {{ $leave->approvedByUser->name ?? 'N/A' }}</p>
                <p><strong>Approved At:</strong> 
                    {{ $leave->approved_at ? \Carbon\Carbon::parse($leave->approved_at)->format('d M Y H:i') : 'Pending' }}
                </p>
            @endif

            <a href="{{ route('supervisor.leaves.index') }}" class="btn btn-secondary mt-3">Back to My Requests</a>
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
