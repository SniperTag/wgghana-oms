<div class="container-fluid">
      <h1 class="mb-4 text-uppercase text-xl text-bold text-san-serif">All Roles</h1>
      {{--
                        <form method="GET" action="{{ route('attendance.index') }}" class="mb-4 d-flex gap-2">
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

          <table class="table table-bordered">
              <thead>
                  <tr>
                      <th>Role Name</th>
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
                              <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-primary">Edit</a>
                          </td>
                      </tr>
                  @endforeach
              </tbody>
          </table>

          <a href="{{ route('roles.create') }}" class="btn btn-success mt-3">Create Role</a>


      </div>

  </div>