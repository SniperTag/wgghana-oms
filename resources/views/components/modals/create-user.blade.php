<div x-data="{ open: false }">
    <!-- Button to Open Modal -->
    <div class="d-flex justify-content-start mb-3">
        <button @click="open = true" class="btn btn-primary">+ Create User</button>
    </div>

    <!-- Modal -->
    <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-50">
        <div @click.away="open = false" class="bg-white dark:bg-gray-800 w-full max-w-3xl p-6 rounded shadow-lg overflow-y-auto max-h-[90vh]">

            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Create User</h2>
                <button @click="open = false" class="text-gray-500 hover:text-red-600 text-lg">&times;</button>
            </div>

            <!-- Modal Body (Create User Form) -->
            <form action="{{ route('admin.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="Enter name">
                    </div>
                    <div>
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="Enter email">
                    </div>
                    {{--  <div>
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div>
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>  --}}
                    <div>
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div>
                        <label>Department</label>
                        <select name="department_id" class="form-control" required>
                            <option value="">-- Select Department --</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label>Roles</label>
                        <select name="roles[]" id="roles" class="form-control" multiple>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
