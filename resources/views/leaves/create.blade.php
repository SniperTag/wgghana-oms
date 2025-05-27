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
                                            <div class="fs-sm fw-semibold text-uppercase text-muted">Approved
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Approved --}}
                                    <div class="col-12 col-md-4">
                                        <div class=" ">
                                            <div class="fs-3 fw-semibold text-warning">{{ $pendingCount }}</div>
                                            <div class="fs-sm fw-semibold text-uppercase text-muted">Pendding
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Rejected --}}
                                    <div class="col-12 col-md-4">
                                        <div class=" ">
                                            <div class="fs-3 fw-semibold text-danger">{{ $rejectedCount }}</div>
                                            <div class="fs-sm fw-semibold text-uppercase text-muted">Rejected
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
                                    <div class="fs-3 fw-semibold text-pulse">{{ $totalannualLeaveCount }}</div>
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
                                    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Leave Request Form</h2>

                                    {{-- Show Toastr or Validation Errors --}}
                                    @if ($errors->any())
                                        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                                            <ul class="list-disc list-inside">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form action="{{ route('leaves.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            {{-- Leave Type --}}
                                            <div>
                                                <label for="leave_type_id"
                                                    class="block font-medium text-sm text-gray-700">Leave Type</label>
                                                <select name="leave_type_id" id="leave_type_id"
                                                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                    <option value="">-- Select Leave Type --</option>
                                                    @foreach ($leaveTypes as $type)
                                                        <option value="{{ $type->id }}"
                                                            {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                                            {{ $type->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Start Date --}}
                                            <div>
                                                <label for="start_date"
                                                    class="block font-medium text-sm text-gray-700">Start Date</label>
                                                <input type="date" name="start_date" id="start_date"
                                                    value="{{ old('start_date') }}"
                                                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-indigo-500 focus:border-indigo-500"
                                                    required>
                                            </div>

                                            {{-- End Date --}}
                                            <div>
                                                <label for="end_date"
                                                    class="block font-medium text-sm text-gray-700">End Date</label>
                                                <input type="date" name="end_date" id="end_date"
                                                    value="{{ old('end_date') }}"
                                                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-indigo-500 focus:border-indigo-500"
                                                    required>
                                            </div>

                                            {{-- Supporting Document --}}
                                            <div>
                                                <label for="attachment"
                                                    class="block font-medium text-sm text-gray-700">Attach File
                                                    (Optional)</label>
                                                <input type="file" name="attachment" id="attachment"
                                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                            </div>
                                        </div>

                                        {{-- Reason (Full Width) --}}
                                        <div class="mt-2">
                                            <label for="reason" class="block font-medium text-sm text-gray-700">Reason
                                                for Leave</label>
                                            <textarea name="reason" id="reason" rows="4"
                                                class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-indigo-500 focus:border-indigo-500">{{ old('reason') }}</textarea>
                                        </div>

                                        {{-- Submit Button --}}
                                        <div class="flex justify-end mt-2">
                                            <button type="submit"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-sm">
                                                Submit Request
                                            </button>
                                        </div>
                                    </form>
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
