<div>
    @foreach ($users as $user)
        <div x-show="editUser === {{ $user->id }}" x-transition
            class="fixed inset-0 flex items-center justify-center z-50 bg-opacity-50">
            <div @click.away="editUser = null"
                class="bg-white dark:bg-gray-800 w-full max-w-3xl p-6 rounded shadow-lg overflow-y-auto max-h-[90vh]">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Edit User - {{ $user->name }}</h2>
                    <button @click="editUser = null" class="text-gray-500 hover:text-red-600 text-lg">&times;</button>
                </div>

                <!-- Edit Form -->
                <form action="{{ route('admin.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <!-- Form Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}"
                                required>
                        </div>
                        <div>
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                                required>
                        </div>
                        <div>
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                        </div>
                        <div>
                            <label>Department</label>
                            <select name="department_id" class="form-control" required>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ $user->department_id == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label>Assign Role(s)</label>
                            <select name="roles[]" id="roles" class="form-control select2 w-full" multiple>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ in_array($role->name, $user->getRoleNames()->toArray()) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                   
                    <div class="mt-4 flex justify-end">
                        + Create Leave Balance
                    </a>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</div>
