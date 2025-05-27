{{--  <x-layouts.app :title="__('Dashboard')">


</x-layouts.app>  --}}




<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('layouts.head')
</head>

<body  x-data="{ editUser: null }">
    <!-- Page Container -->


    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

        <!-- Sidebar -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif


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


                    <div class="container-fluid">
                        <h1 class="mb-4 tex-bold text-xl text-san-serif">User Management</h1>
                        <div class="d-flex justify-content-start mb-3">
                            @include('components.modals.create-user')
                        </div>

                        <div class="table-responsive">
                            <table id="userTable" class="table table-bordered table-hover align-middle text-center">

                                <thead class="table-light">
                                    <tr>

                                        <th class="border-none">No.</th>
                                        <th class="border-none">Name</th>
                                        <th class="border-none">Staff ID</th>
                                        <th class="border-none">Email</th>
                                        <th class="border-none">Phone</th>
                                        <th class="border-none">Department</th>
                                        <th class="border-none">Roles</th>
                                        <th class="border-none">Actions</th>
                                    </tr>
                                    <tr>

                                        <th class="border-none"></th>
                                        <th class="border-none">Name</th>
                                        <th class="border-none">Staff ID</th>
                                        <th class="border-none"></th>
                                        <th class="border-none"></th>
                                        <th class="border-none">Department</th>
                                        <th class="border-none">Roles</th>
                                        <th class="border-none"></th>
                                    </tr>
                                </thead>
                                <tbody class="border-none text-left text-sm">
                                    @foreach ($users as $user)
                                        <tr class="border-none">
                                            <td class="border-none">{{ $loop->iteration }}</td>
                                            <td class="border-none">{{ $user->name }}</td>
                                            <td class="border-none">{{ $user->staff_id }}</td>
                                            <td class="border-none">{{ $user->email }}</td>
                                            <td class="border-none">{{ $user->phone }}</td>
                                            <td class="border-none">{{ $user->department ? $user->department->name : 'N/A' }}</td>

                                            <td class="border-none">{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                                            <td class="border-none">
                                                <!-- Dropdown for Actions -->
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                        type="button" id="actionMenu{{ $user->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="actionMenu{{ $user->id }}">
                                                        <li>
                                                            <button class="dropdown-item" @click.prevent="editUser = {{ $user->id }}">Edit</button>


                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.destroy', $user->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="dropdown-item text-danger"
                                                                    type="submit"><i class="fas fa-trash"></i></button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    @include('components.modals.edit-user')

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
</body>

</html>
