  <div class="container-fluid">
                        <h1 class="mb-4 text-uppercase text-xl text-bold text-san-serif">All Roles</h1>
                        
                        {{--  <form method="GET" action="{{ route('attendance.index') }}" class="mb-4 d-flex gap-2">
                            <input type="text" name="name" value="{{ request('name') }}"
                                class="form-control w-25 rounded" placeholder="Search by name">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </form>  --}}

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive card">
                        
                            @foreach ($users as $user)
                        <div class="card my-3">
                            <div class="card-header">
                                <strong>{{ $user->name }}</strong> - {{ $user->email }}
                            </div>
                            <div class="card-body">
                               <form action="{{ route('access.givePermission', ['user' => $user->id]) }}" method="POST" class="d-flex align-items-center mb-3">
                                    @csrf
                                    <select name="permission" class="form-select me-2" style="width: 300px;">
                                        @foreach ($permissions as $permission)
                                            <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary">Give Permission</button>
                                </form>

                                <h6>Permissions:</h6>
                                <ul class="list-group">
                                    @foreach ($user->permissions as $perm)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $perm->name }}
                                            <form method="POST"
                                                action="{{ route('access.givePermission', $user) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Revoke</button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach

                            <a href="{{ route('roles.create') }}" class="btn btn-success mt-3">Create Role</a>
                            

                        </div>

                    </div>
