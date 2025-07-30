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
                                    <i class="si si-users fa-2x text-primary-light"></i>
                                </div>
                                <div class="text-end">
                                    <div class="fs-3 fw-semibold text-primary text-center">{{ $femaleCount }}</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Total Females</div>
                                </div>
                                <div class="text-start ">
                                    <div class="fs-3 fw-semibold text-primary text-center">{{ $maleCount }}</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Total Males</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-xl-3">
                        <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                            <div
                                class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                                <div class="d-none d-sm-block">
                                    <i class="si si-users fa-2x text-earth-light"></i>
                                </div>
                                <div class="text-end">
                                    <div class="fs-3 fw-semibold text-earth text-center">{{ $staffCount }}</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Employees</div>
                                </div>
                                <div class="text-start">
                                    <div class="fs-3 fw-semibold text-earth text-center">{{ $nationalServiceCount }}
                                    </div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">National Service</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-xl-3">
                        <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                            <div
                                class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                                <div class="d-none d-sm-block">
                                    <i class="si si-envelope-open fa-2x text-elegance-light"></i>
                                </div>
                                <div class="text-end">
                                    <div class="fs-3 fw-semibold text-elegance">15</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Pending Leaves</div>
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
                                    <div class="fs-3 fw-semibold text-pulse">{{ $userCount }}</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Total Staffs</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- END Row #1 -->

                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">Create User</div>

                                    <div class="card-body">
                                        @if (session('success'))
                                            <div class="alert alert-success">{{ session('success') }}</div>
                                        @endif

                                        <form action="{{ route('admin.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <!-- Left Column: Basic Info -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="name">Full Name</label>
                                                        <input  type="text" name="name" id="name"
                                                            class="form-control rounded border border-gray-6000" value="{{ old('name') }}" required
                                                            placeholder="Enter full name">
                                                        @error('name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    

                                                    <div class="mb-3">
                                                        <label for="phone">Phone Number</label>
                                                        <input  type="text" name="phone" id="phone"
                                                            class="form-control rounded border border-gray-6000" value="{{ old('phone') }}"
                                                            placeholder="Enter phone number">
                                                        @error('phone')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                  



                                                    <div class="mb-3">
                                                        <label for="department_id">Department</label>
                                                        <select name="department_id" id="department_id"
                                                            class="form-control" required>
                                                            <option value="">-- Select Department --</option>
                                                            @foreach ($departments as $department)
                                                                <option value="{{ $department->id }}"
                                                                    {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                                    {{ $department->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('department_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                       <!-- ✅ Added User Type -->
                                                    <div class="mb-3">
                                                        <label for="user_type">User Type</label>
                                                        <select name="user_type" id="user_type" class="form-control"
                                                            required>
                                                            <option value="" disabled
                                                                {{ old('user_type') ? '' : 'selected' }}>Select User
                                                                Type</option>
                                                            <option value="employee"
                                                                {{ old('user_type') == 'employee' ? 'selected' : '' }}>
                                                                Employee</option>
                                                            <option value="national_service"
                                                                {{ old('user_type') == 'national_service' ? 'selected' : '' }}>
                                                                National Service</option>
                                                        </select>
                                                        @error('user_type')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Right Column: Roles & Leave Info -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="email">Corporate Email</label>
                                                        <input  type="email" name="email" id="email"
                                                            class="form-control rounded border border-gray-6000" value="{{ old('email') }}" required
                                                            placeholder="Enter email">
                                                        @error('email')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                      <div class="mb-3">
                                                        <label for="gender">Gender</label>
                                                        <select name="gender" id="gender" class="form-control">
                                                            <option value="" disabled
                                                                {{ old('gender') ? '' : 'selected' }}>Select Gender
                                                            </option>
                                                            <option value="male"
                                                                {{ old('gender') == 'male' ? 'selected' : '' }}>Male
                                                            </option>
                                                            <option value="female"
                                                                {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                                Female</option>
                                                            <option value="other"
                                                                {{ old('gender') == 'other' ? 'selected' : '' }}>Other
                                                            </option>
                                                        </select>
                                                        @error('gender')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>


                                                    <div class="mb-3">
                                                        <label for="roles">Assign Role(s)</label>
                                                        <select name="roles[]" id="roles"
                                                            class="form-control select2" multiple>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->name }}"
                                                                    {{ collect(old('roles', []))->contains($role->name) ? 'selected' : '' }}>
                                                                    {{ $role->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('roles')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                  

                                                    <hr>

                                                    <div class="mb-3">
                                                        <label for="leave_type_id">Leave Type</label>
                                                        <select name="leave_type_id" id="leave_type_id"
                                                            class="form-control" required>
                                                            <option value="">-- Select Leave Type --</option>
                                                            @foreach ($leaveTypes as $type)
                                                                <option value="{{ $type->id }}"
                                                                    {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                                                    {{ $type->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('leave_type_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="leave_days">Leave Days Allocated</label>
                                                        <input  type="number" name="leave_days" id="leave_days"
                                                            class="form-control rounded border border-gray-6000" value="{{ old('leave_days') }}"
                                                            min="0" placeholder="e.g. 15">
                                                        @error('leave_days')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Submit Button Full Width -->
                                            <div class="mt-4 text-center">
                                                <button type="submit" class="btn btn-primary px-5">Create
                                                    User</button>
                                            </div>
                                        </form>
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
