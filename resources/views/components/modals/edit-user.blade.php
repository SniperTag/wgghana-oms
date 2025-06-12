<div x-show="editUser" x-cloak x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
    <div @click.away="editUser = null"
        class="bg-white dark:bg-gray-800 w-full max-w-3xl p-6 rounded shadow-lg overflow-y-auto max-h-[90vh]">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Edit User - <span x-text="editUser.name"></span></h2>
            <button @click="editUser = null" class="text-gray-500 hover:text-red-600 text-lg">&times;</button>
        </div>

        <form action="{{ route('admin.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div x-data="{
                editUser: {
                    name: '{{ addslashes($user->name) }}',
                    email: '{{ $user->email }}',
                    phone: '{{ $user->phone ?? '' }}',
                    department_id: '{{ $user->department_id }}',
                    roles: @json($user->getRoleNames())
                }
            }" x-init="// Initialize Select2 and sync with Alpine model
            $nextTick(() => {
                const rolesSelect = $refs.rolesSelect;
                $(rolesSelect).select2();
            
                $(rolesSelect).on('change', function() {
                    editUser.roles = $(this).val();
                });
            
                // Set initial Select2 value from Alpine data
                $(rolesSelect).val(editUser.roles).trigger('change');
            });">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label>Name</label>
                       <input type="text" name="name" x-model="editUser.name" class="form-control">

                    </div>
                    <div>
                        <label>Email</label>
                        <input type="email" name="email" x-model="editUser.email" class="form-control" required>
                    </div>
                    <div>
                        <label>Phone</label>
                        <input type="text" name="phone" x-model="editUser.phone" class="form-control">
                    </div>
                    <div>
                        <label>Department</label>
                        <select name="department_id" class="form-control" x-model="editUser.department_id">
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    :selected="editUser.department_id == '{{ $department->id }}'">
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label>Assign Role(s)</label>
                        <select x-ref="rolesSelect" name="roles[]" multiple class="form-control select2 w-full">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>


            <div class="mt-4 flex justify-end">
                <button type="submit" class="btn btn-primary">Update User</button>
            </div>
        </form>
    </div>
</div>
