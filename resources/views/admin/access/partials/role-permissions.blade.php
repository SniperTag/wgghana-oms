<div class="card p-3">
    <h4 class="mb-3">Assign Permissions to Roles</h4>

    @php
        $roles = \Spatie\Permission\Models\Role::with('permissions')->get();
        $permissions = \Spatie\Permission\Models\Permission::all();
    @endphp

    <div class="mb-3">
        <label class="form-label">Select Role</label>
        <select id="roleSelect" class="form-select">
            <option value="">-- Select Role --</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" data-permissions="{{ json_encode($role->permissions->pluck('id')) }}">
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
    </div>

    <form id="rolePermissionsForm" action="" method="POST" style="display:none;">
        @csrf
        <div class="row">
            @foreach($permissions as $perm)
                <div class="col-md-3">
                    <div class="form-check">
                        <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="form-check-input permission-checkbox" id="perm-{{ $perm->id }}">
                        <label class="form-check-label" for="perm-{{ $perm->id }}">{{ $perm->name }}</label>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update Permissions</button>
    </form>
</div>

<script>
const roleSelect = document.getElementById('roleSelect');
const rolePermissionsForm = document.getElementById('rolePermissionsForm');

roleSelect.addEventListener('change', function() {
    const selected = roleSelect.selectedOptions[0];
    if (!selected || !selected.value) {
        rolePermissionsForm.style.display = 'none';
        return;
    }

    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);

    const perms = JSON.parse(selected.dataset.permissions || '[]');
    perms.forEach(p => {
        const cb = document.querySelector(`.permission-checkbox[value="${p}"]`);
        if(cb) cb.checked = true;
    });

    rolePermissionsForm.action = `/roles/${selected.value}/assign-permissions`;
    rolePermissionsForm.style.display = 'block';
});
</script>
