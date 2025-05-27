<div>
    <h5>Assign Permissions to Roles</h5>

    @foreach (\Spatie\Permission\Models\Role::all() as $role)
        <div class="card my-3">
            <div class="card-header">
                {{ $role->name }}
            </div>
            <div class="card-body">
                <form action="{{ route('roles.assignPermissions.store', $role) }}" method="POST">
                    @csrf
                    <div class="row">
                        @foreach (\Spatie\Permission\Models\Permission::all() as $permission)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                        value="{{ $permission->name }}"
                                        {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ $permission->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Update Permissions</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
