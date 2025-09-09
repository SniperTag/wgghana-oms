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
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h5>All Permissions</h5>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createPermissionModal">
                        Create Permission
                    </button>
                </div>

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Permission Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editPermissionModal-{{ $permission->id }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete permission?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Edit Permission Modal --}}
                            <div class="modal fade" id="editPermissionModal-{{ $permission->id }}" tabindex="-1"
                                aria-labelledby="editPermissionModalLabel-{{ $permission->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content bg-light">
                                        <form action="{{ route('permissions.update', $permission->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title"
                                                    id="editPermissionModalLabel-{{ $permission->id }}">
                                                    Edit Permission: {{ $permission->name }}
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="permissionName-{{ $permission->id }}"
                                                        class="form-label">
                                                        Permission Name
                                                    </label>
                                                    <input type="text" class="form-control" name="name"
                                                        id="permissionName-{{ $permission->id }}"
                                                        value="{{ old('name', $permission->name) }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success">Update
                                                    Permission</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>

                {{-- Create Permission Modal --}}
                <div class="modal fade" id="createPermissionModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content bg-light">
                            <form action="{{ route('permissions.store') }}" method="POST">
                                @csrf
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Create New Permission</h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Permission Name</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
            <!-- END Page Content -->
        </main>
        <!-- END Main Container -->
        @include('layouts.js')
    </div>
    <!-- END Page Container -->

</body>

</html>
