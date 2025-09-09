<div class="card p-3">
    <h4 class="mb-3">Assign Roles to Users</h4>

    @php
        $users = \App\Models\User::with('roles')->get();
        $roles = \Spatie\Permission\Models\Role::all();
    @endphp

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>User</th>
                <th>Current Roles</th>
                <th>Assign Roles</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>
                        @foreach($user->roles as $role)
                            <span class="badge bg-info">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        <form action="{{ route('users.assignRoles', $user->id) }}" method="POST">
                            @csrf
                            <select name="roles[]" class="form-select mb-2" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @if($user->roles->contains($role)) selected @endif>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-primary">Update Roles</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
