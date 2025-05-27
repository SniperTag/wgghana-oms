<div class="row">
    <!-- Supervisor Approval -->
    <div class="col-md-3 mb-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                    Supervisor Approval
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    {{ $supervisorPending }}
                </div>
            </div>
        </div>
    </div>

    <!-- HR/Admin Approval -->
    <div class="col-md-3 mb-3">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    HR/Admin Approval
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    {{ $hrPending }}
                </div>
            </div>
        </div>
    </div>

    <!-- Approved Leaves -->
    <div class="col-md-3 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    Approved
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    {{ $approved }}
                </div>
            </div>
        </div>
    </div>

    <!-- Rejected Leaves -->
    <div class="col-md-3 mb-3">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                    Rejected
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    {{ $rejected }}
                </div>
            </div>
        </div>
    </div>
</div>
