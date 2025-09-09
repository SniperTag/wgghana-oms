<table class="table table-bordered">
    <thead>
        <tr>
            <th>User</th>
            <th>Email</th>
            <th>Role-Based Permissions</th>
            <th>Direct Permissions</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @foreach ($user->getPermissionsViaRoles() as $permission)
                        <span class="badge bg-secondary">{{ $permission->name }}</span>
                    @endforeach
                </td>
                <td>
                    @foreach ($user->getDirectPermissions() as $permission)
                        <span class="badge bg-info">{{ $permission->name }}</span>
                    @endforeach
                </td>
                <td>
                    {{-- Assign direct permissions --}}
                    <form method="POST" action="{{ route('access.givePermission', $user) }}" class="d-inline mb-1">
                        @csrf
                        <select name="permissions[]" class="form-select form-select-sm d-inline w-auto user-permission-select" multiple>
                            @foreach ($permissions as $permission)
                                <option value="{{ $permission->name }}"
                                    {{ $user->getDirectPermissions()->pluck('name')->contains($permission->name) ? 'selected' : '' }}>
                                    {{ $permission->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-success">Give</button>
                    </form>

                    {{-- Revoke direct permissions --}}
                    <form method="POST" action="{{ route('access.revokeMultiplePermissions', $user) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <select name="permissions[]" class="form-select form-select-sm d-inline w-auto user-permission-select" multiple>
                            @foreach ($user->getDirectPermissions() as $permission)
                                <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-danger">Revoke Selected</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- JS: Initialize Select2 --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.user-permission-select').select2({
            placeholder: "Select permission(s)",
            width: 'resolve'
        });
    });
</script>
