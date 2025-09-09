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

        {{-- Main Content --}}
        <div class="container-fluid mt-6">
            <h2 class="mb-4">Access Management</h2>

            {{-- Tabs --}}
            <ul class="nav nav-tabs" id="accessTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles"
                        type="button">
                        Roles
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions"
                        type="button">
                        Permissions
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="user-access-tab" data-bs-toggle="tab" data-bs-target="#user-access"
                        type="button">
                        User Access
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-4" id="accessTabsContent">

                {{-- ---------------- Roles Tab ---------------- --}}
                <div class="tab-pane fade show active" id="roles" role="tabpanel">
                    <div class="mb-3">
                        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                            Create Role
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Role Name</th>
                                    <th>Department</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->parent ?? 'Unassigned' }}</td>
                                        <td class="d-flex flex-wrap gap-1">
                                            {{-- Edit Role --}}
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#editRoleModal-{{ $role->id }}">
                                                Edit
                                            </button>

                                            {{-- Assign Permissions via Modal --}}
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#permissionsRoleModal-{{ $role->id }}">
                                                Permissions
                                            </button>

                                            {{-- Assign Users via Modal --}}
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#assignUsersModal-{{ $role->id }}">
                                                Users
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- ---------------- Modals ---------------- --}}

                                    {{-- Edit Role Modal --}}
                                    <div class="modal fade" id="editRoleModal-{{ $role->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content bg-light">
                                                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">Edit Role: {{ $role->name }}</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Role Name</label>
                                                            <input type="text" name="name" class="form-control"
                                                                value="{{ old('name', $role->name) }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Department</label>
                                                            <input type="text" name="parent" class="form-control"
                                                                value="{{ old('parent', $role->parent) }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success">Update
                                                            Role</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Assign Permissions Modal --}}
                                    <div class="modal fade" id="permissionsRoleModal-{{ $role->id }}"
                                        tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content bg-light">
                                                <form action="{{ route('roles.assignPermissions', $role->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-warning text-dark">
                                                        <h5 class="modal-title">Assign Permissions to Role:
                                                            {{ $role->name }}</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-2">
                                                            @foreach ($permissions as $permission)
                                                                <div class="col-6 col-md-4 col-lg-3">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input"
                                                                            type="checkbox" name="permissions[]"
                                                                            value="{{ $permission->id }}"
                                                                            {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}>
                                                                        <label
                                                                            class="form-check-label">{{ $permission->name }}</label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success">Save
                                                            Permissions</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Assign Users Modal --}}
                                    <div class="modal fade" id="assignUsersModal-{{ $role->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content bg-light">
                                                <form action="{{ route('roles.assignUsers', $role->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title">Assign Users to Role:
                                                            {{ $role->name }}</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-2">
                                                            @foreach ($users as $user)
                                                                <div class="col-6 col-md-4 col-lg-3">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input"
                                                                            type="checkbox" name="users[]"
                                                                            value="{{ $user->id }}"
                                                                            {{ $role->users->contains('id', $user->id) ? 'checked' : '' }}>
                                                                        <label
                                                                            class="form-check-label">{{ $user->name }}</label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success">Save
                                                            Users</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Create Role Modal --}}
                <div class="modal fade" id="createRoleModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content bg-light">
                            <form action="{{ route('roles.store') }}" method="POST">
                                @csrf
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title">Create New Role</h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Role Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Department</label>
                                        <input type="text" name="parent" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Create Role</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                {{-- ---------------- Permissions Tab ---------------- --}}
                <div class="tab-pane fade" id="permissions" role="tabpanel">
                    <div class="mb-3">
                        <button class="btn btn-success mb-3" data-bs-toggle="modal"
                            data-bs-target="#createPermissionModal">
                            Create Permission
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
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
                                        <td class="d-flex gap-1">
                                            <!-- Edit Permission Modal Trigger -->
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#editPermissionModal-{{ $permission->id }}">
                                                Edit
                                            </button>
                                            <!-- Delete -->
                                            <form action="{{ route('permissions.destroy', $permission->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Delete this permission?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{-- Edit Permission Modal --}}
                                    <div class="modal fade" id="editPermissionModal-{{ $permission->id }}"
                                        tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content bg-light">
                                                <form action="{{ route('permissions.update', $permission->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">Edit Permission:
                                                            {{ $permission->name }}</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Permission Name</label>
                                                            <input type="text" name="name" class="form-control"
                                                                value="{{ old('name', $permission->name) }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

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
                                            <input type="text" name="name" class="form-control"
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

                {{-- ---------------- User Access Tab ---------------- --}}
                <div class="tab-pane fade" id="user-access" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Roles</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->roles->pluck('name')->join(', ') ?: '-' }}</td>
                                        <td>
                                            @forelse ($user->permissions as $perm)
                                                <span class="badge bg-info mb-1">{{ $perm->name }}</span>
                                            @empty
                                                <span class="text-muted">No permissions</span>
                                            @endforelse
                                        </td>
                                        <td class="d-flex flex-wrap gap-1">
                                            {{-- Assign Permissions Modal Trigger --}}
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#permissionsModal-{{ $user->id }}">
                                                Assign / Edit
                                            </button>

                                            {{-- Revoke All Permissions --}}
                                            <form action="{{ route('access.revokeMultiplePermissions', $user->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Revoke all permissions for this user?')">
                                                    Revoke All
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{-- Modal for Assign / Edit Permissions --}}
                                    <div class="modal fade" id="permissionsModal-{{ $user->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content bg-light">
                                                <form action="{{ route('access.givePermission', $user->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">Edit Permissions for
                                                            {{ $user->name }}</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-2">
                                                            @foreach ($permissions as $permission)
                                                                <div class="col-6 col-md-4 col-lg-3">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input"
                                                                            type="checkbox" name="permissions[]"
                                                                            value="{{ $permission->id }}"
                                                                            {{ $user->permissions->contains('id', $permission->id) ? 'checked' : '' }}>
                                                                        <label
                                                                            class="form-check-label">{{ $permission->name }}</label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success">Update
                                                            Permissions</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        @include('layouts.js')
    </div>
</body>

</html>
