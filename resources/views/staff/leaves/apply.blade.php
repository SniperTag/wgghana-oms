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
                    <!-- Row #1 -->
                    <div class="col-6 col-xl-3">
                        <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                            <div
                                class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                                <div class="d-none d-sm-block">
                                    <i class="si si-bag fa-2x text-primary-light"></i>
                                </div>
                                <div class="text-end">
                                    <div class="fs-3 fw-semibold text-primary">1500</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Total visitors</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-xl-3">
                        <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                            <div
                                class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                                <div class="d-none d-sm-block">
                                    <i class="si si-wallet fa-2x text-earth-light"></i>
                                </div>
                                <div class="text-end">
                                    <div class="fs-3 fw-semibold text-earth">$780</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Attance Record</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-xl-3">
                        <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                            <div
                                class="block-content block-content-full d-sm-flex justify-content-between align-items-center">

                                <div class="row">
                                    {{-- Pending --}}
                                    <div class="col-12 col-md-4">
                                        <div class="">
                                            <div class="fs-3 fw-semibold text-success">{{ $approvedCount }}</div>
                                            <div class="text-sm text-uppercase text-muted">Appr
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Approved --}}
                                    <div class="col-12 col-md-4">
                                        <div class=" ">
                                            <div class="fs-3 fw-semibold text-warning">{{ $pendingCount }}</div>
                                            <div class="text-sm text-uppercase text-muted">Pend
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Rejected --}}
                                    <div class="col-12 col-md-4">
                                        <div class=" ">
                                            <div class="fs-3 fw-semibold text-danger">{{ $rejectedCount }}</div>
                                            <div class="text-sm text-uppercase text-muted">Rej
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-xl-3">
                        <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                            <div
                                class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                                <div class="d-none d-sm-block">
                                    <i class="si si-users fa-2x text-pulse"></i>
                                </div>
                                <div class="text-end">
                                    <div class="fs-3 fw-semibold text-pulse">{{ $totalAnnualLeaveCount }}</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Leave Days Left</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- END Row #1 -->

                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="bg-white rounded-xl shadow-md p-6">


                                    <div class="container py-4">
                                        <h2 class="mb-4">Leave Application Form</h2>

                                        {{-- Show Toastr Notifications --}}
                                        @if (session('success'))
                                            <script>
                                                toastr.success("{{ session('success') }}");
                                            </script>
                                        @endif
                                        @if (session('error'))
                                            <script>
                                                toastr.error("{{ session('error') }}");
                                            </script>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-8">

                                                <form action="{{ route('leaves.store') }}" method="POST" novalidate>
                                                    @csrf

                                                    {{-- Leave Type Dropdown --}}
                                                    <div class="mb-3">
                                                        <label for="leave_type_id" class="form-label">Leave Type <span
                                                                class="text-danger">*</span></label>
                                                        <select name="leave_type_id" id="leave_type_id"
                                                            class="form-select @error('leave_type_id') is-invalid @enderror"
                                                            required>
                                                            <option value="">-- Select Leave Type --</option>
                                                            @foreach ($leaveTypes as $type)
                                                                <option value="{{ $type->id }}"
                                                                    {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                                                    {{ $type->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('leave_type_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    {{-- Start Date --}}
                                                    <div class="mb-3">
                                                        <label for="start_date" class="form-label">Start Date <span
                                                                class="text-danger">*</span></label>
                                                        <input type="date" name="start_date" id="start_date"
                                                            class="form-control @error('start_date') is-invalid @enderror"
                                                            value="{{ old('start_date') }}" required>
                                                        @error('start_date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    {{-- End Date --}}
                                                    <div class="mb-3">
                                                        <label for="end_date" class="form-label">End Date <span
                                                                class="text-danger">*</span></label>
                                                        <input type="date" name="end_date" id="end_date"
                                                            class="form-control @error('end_date') is-invalid @enderror"
                                                            value="{{ old('end_date') }}" required>
                                                        @error('end_date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    {{-- Reason Textarea --}}
                                                    <div class="mb-3">
                                                        <label for="reason" class="form-label">Reason for Leave <span
                                                                class="text-danger">*</span></label>
                                                        <textarea name="reason" id="reason" rows="4" class="form-control @error('reason') is-invalid @enderror"
                                                            placeholder="Provide your reason for leave..." required>{{ old('reason') }}</textarea>
                                                        @error('reason')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    {{-- Display User's Annual Leave Balance --}}
                                                    @if ($annualLeaveType && $leaveBalance)
                                                        <div class="alert alert-info">
                                                            <strong>Annual Leave Balance:</strong><br>
                                                            Total Days: {{ $leaveBalance->total_days }} <br>
                                                            Used Days: {{ $leaveBalance->used_days }} <br>
                                                            Remaining Days: {{ $leaveBalance->remaining_days }}
                                                        </div>
                                                    @else
                                                        <div class="alert alert-warning">
                                                            Your annual leave balance is not available. Please contact
                                                            HR.
                                                        </div>
                                                    @endif

                                                    {{-- Submit Button --}}
                                                    <button type="submit" class="btn btn-primary">Submit Leave
                                                        Request</button>
                                                    <a href="{{ route('leaves.index') }}"
                                                        class="btn btn-secondary ms-2">Cancel</a>
                                                </form>

                                            </div>

                                            {{-- Optional: Sidebar or Leave History --}}
                                            <div class="col-md-4">
                                                <h5>Your Recent Leave Requests</h5>
                                                @if ($leaves->isEmpty())
                                                    <p>No previous leave requests found.</p>
                                                @else
                                                    <ul class="list-group">
                                                        @foreach ($leaves->take(5) as $leave)
                                                            <li class="list-group-item">
                                                                <strong>{{ $leave->leaveType->name ?? 'N/A' }}</strong><br>
                                                                {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}
                                                                -
                                                                {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                                                <br>
                                                                Status: <span
                                                                    class="badge bg-{{ $leave->status == 'approved' ? 'success' : ($leave->status == 'pending' ? 'warning' : 'danger') }}">
                                                                    {{ ucfirst($leave->status) }}
                                                                </span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    <a href="{{ route('staff.leaves.index') }}"
                                                        class="btn btn-link mt-2">View All</a>
                                                @endif
                                            </div>
                                        </div>
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

    <!-- Bootstrap Bundle (Popper.js included) -->
    {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  --}}

</body>

</html>
