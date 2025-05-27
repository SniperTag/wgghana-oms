  <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
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
                                        @foreach ($user->getDirectPermissions() as $permission)
                                            <span class="badge bg-info">{{ $permission->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('access.givePermission', $user) }}"
                                            class="d-inline">
                                            @csrf
                                            <select name="permission"
                                                class="form-select form-select-sm d-inline w-auto">
                                                @foreach ($permissions as $permission)
                                                    <option value="{{ $permission->name }}">{{ $permission->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-success">Give</button>
                                        </form>

                                        @foreach ($user->getDirectPermissions() as $permission)
                                            <form method="POST"
                                                action="{{ route('user.access.revokePermission', [$user->id, $permission->id]) }}"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Revoke
                                                    {{ $permission->name }}</button>
                                            </form>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
