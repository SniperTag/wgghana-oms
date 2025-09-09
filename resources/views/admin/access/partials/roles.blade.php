<div class="card p-3">
    <h4 class="mb-3">All Roles</h4>
    <a href="{{ route('roles.create') }}" class="btn btn-success mb-3">Create Role</a>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Department / Role</th>
                <th>Permissions Count</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @php $rolesByDept = $roles->groupBy('parent'); @endphp
            @foreach($rolesByDept as $dept => $subRoles)
                <tr class="table-primary" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target=".dept-{{ $loop->index }}">
                    <td colspan="3">
                        <strong>{{ ucfirst($dept ?? 'Unassigned') }}</strong>
                        <span class="float-end">Click to expand/collapse</span>
                    </td>
                </tr>
                @foreach($subRoles as $role)
                    <tr class="collapse dept-{{ $loop->parent->index }}">
                        <td>&nbsp;&nbsp;&nbsp;{{ $role->name }}</td>
                        <td>{{ $role->permissions->count() }}</td>
                        <td>
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this role?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>

<script>
document.querySelectorAll('tr.table-primary').forEach(row => {
    row.addEventListener('click', () => {
        const target = row.getAttribute('data-bs-target');
        document.querySelectorAll(target).forEach(r => r.classList.toggle('show'));
    });
});
</script>
