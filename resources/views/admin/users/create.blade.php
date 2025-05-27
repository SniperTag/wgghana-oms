{{--  <x-layouts.app :title="__('Dashboard')">


</x-layouts.app>  --}}




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
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Users</div>
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
                                    {{--  <div><a href="{{ route('leave_balances.create', $user->id) }}"
                                            class="btn btn-sm btn-success flex justify-end">
                                            + Create Leave Balance
                                        </a></div>  --}}
                                    <div class="card-body">
                                        @if (session('success'))
                                            <div class="alert alert-success">{{ session('success') }}</div>
                                        @endif


                                        <form action="{{ route('admin.store') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <!-- Left Column -->
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label>Name</label>
                                                        <input type="text" name="name" class="form-control"
                                                            required placeholder="Enter your name">
                                                    </div>



                                                    <div class="mb-3">
                                                        <label>Password</label>
                                                        <input type="password" name="password" class="form-control"
                                                            required placeholder="Enter Password">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Confirm Password</label>
                                                        <input type="password" name="password_confirmation"
                                                            class="form-control" required
                                                            placeholder="Confirm Password">
                                                    </div>
                                                </div>

                                                <!-- Right Column -->
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control"
                                                            required placeholder="Enter Corporate Email">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="department_id">Department</label>
                                                        <select name="department_id" id="department"
                                                            class="form-control" required>
                                                            <option value="">-- Select Department --</option>
                                                            @foreach ($departments as $department)
                                                                <option value="{{ $department->id }}">
                                                                    {{ $department->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label>Phone</label>
                                                        <input type="text" name="phone" class="form-control"
                                                            placeholder="Enter Phone Number">
                                                    </div>
                                                </div>

                                                <!-- Full Width for Roles -->
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="roles">Assign Role(s)</label>
                                                        <select name="roles[]" id="roles"
                                                            class="form-control select2" multiple>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->name }}">{{ $role->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary">Create User</button>
                                                </div>
                                            </div>
                                        </form>




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
