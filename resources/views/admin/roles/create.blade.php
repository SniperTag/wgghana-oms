<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('layouts.head')
</head>

<body>
    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

        {{-- Sidebar --}}
        @include('layouts.partials.sidebar')

        {{-- Header --}}
        @include('layouts.header')

        <!-- Main Container -->
        <div class="container mt-7">

            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h5>All Roles</h5>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                    Create Role
                </button>
            </div>

            {{-- Roles Table --}}
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>
                                @foreach ($role->permissions as $perm)
                                    <span class="badge bg-info">{{ $perm->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editRoleModal-{{ $role->id }}">Edit</button>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete role?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        {{-- Create Role Modal --}}
        <div class="modal fade @if ($errors->any()) show @endif" id="createRoleModal" tabindex="-1"
            aria-labelledby="createRoleModalLabel" aria-hidden="true"
            @if ($errors->any()) style="display:block;" @endif>
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content bg-light">
                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="createRoleModalLabel">Create New Role</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            {{-- Display Validation Errors --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="roleName" class="form-label">Role Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="roleName" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="roleParent" class="form-label">Department (Optional)</label>
                                <input type="text" class="form-control @error('parent') is-invalid @enderror"
                                    id="roleParent" name="parent" value="{{ old('parent') }}"
                                    placeholder="Enter department name">
                                @error('parent')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Assign Permissions</label>
                                <div class="row">
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                                    value="{{ $permission->id }}"
                                                    {{ is_array(old('permissions')) && in_array($permission->id, old('permissions')) ? 'checked' : '' }}>
                                                <label class="form-check-label">{{ $permission->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Create Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        {{-- Auto show modal if validation errors exist --}}
        @if ($errors->any())
            <script>
                var createRoleModal = new bootstrap.Modal(document.getElementById('createRoleModal'));
                createRoleModal.show();
            </script>
        @endif

        {{-- Auto show modal if validation errors exist --}}
        @if ($errors->any())
            <script>
                var createRoleModal = new bootstrap.Modal(document.getElementById('createRoleModal'));
                createRoleModal.show();
            </script>
        @endif


        @include('layouts.js')

        {{-- Auto show modal if validation errors exist --}}
        @if ($errors->any())
            <script>
                var createRoleModal = new bootstrap.Modal(document.getElementById('createRoleModal'));
                createRoleModal.show();
            </script>
        @endif
    </div>

    @foreach ($roles as $role)
        <div class="modal fade" id="editRoleModal-{{ $role->id }}" tabindex="-1"
            aria-labelledby="editRoleModalLabel-{{ $role->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content bg-light">
                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="editRoleModalLabel-{{ $role->id }}">Edit Role:
                                {{ $role->name }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="editRoleName-{{ $role->id }}" class="form-label">Role Name</label>
                                <input type="text" class="form-control" id="editRoleName-{{ $role->id }}"
                                    name="name" value="{{ old('name', $role->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="editRoleParent-{{ $role->id }}" class="form-label">Department
                                    (Optional)</label>
                                <input type="text" class="form-control" id="editRoleParent-{{ $role->id }}"
                                    name="parent" value="{{ old('parent', $role->parent) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Assign Permissions</label>
                                <div class="row">
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                                    value="{{ $permission->id }}"
                                                    {{ in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                <label class="form-check-label">{{ $permission->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Update Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

</body>

</html>
