<div x-data="{ editUser: null }">
    <!-- Modal Overlay -->
    <div x-show="editUser" x-cloak x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">

        <!-- Modal Content -->
        <div @click.away="editUser = null"
            class="bg-white dark:bg-gray-800 max-w-6xl w-full p-6 rounded shadow-lg overflow-y-auto max-h-[90vh]">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">
                    Edit User - <span x-text="editUser ? editUser.name : ''"></span>
                </h2>
                <button @click="editUser = null" class="text-gray-500 hover:text-red-600 text-lg">&times;</button>
            </div>

            <!-- Body -->
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Update form --}}
                <form action="{{ route('admin.update.user', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- User Information --}}
                    <h4 class="mt-5 mb-3 fw-bold fs-3">User Information</h4>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Corporate Email</label>
                            <input type="email" name="corporate_email"
                                value="{{ old('corporate_email', $user->corporate_email) }}" class="form-control"
                                required>
                        </div>

                        {{-- Department --}}
                        <div class="col-md-3">
                            <label class="form-label">Department</label>
                            <select name="department_id" id="department" class="form-select" required>
                                <option value="">--Select Department--</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Sub Role --}}
                        <div class="col-md-3">
                            <label class="form-label">Sub Role</label>
                            <select name="sub_role_id" id="sub_role" class="form-select" required>
                                <option value="">--Select Sub Role--</option>
                            </select>
                        </div>

                        {{-- Access Role --}}
                        <div class="col-md-3">
                            <label class="form-label">Access Role</label>
                            <select name="role_id" id="main_role" class="form-select" required>
                                <option value="">--Select Access Role--</option>
                                @foreach ($roles as $roleName)
                                    <option value="{{ $roleName }}"
                                        {{ old('role_id', $user->roles->pluck('name')->first()) == $roleName ? 'selected' : '' }}>
                                        {{ ucfirst($roleName) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Gender --}}
                        <div class="col-md-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">--Select Gender--</option>
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>
                                    Male</option>
                                <option value="female"
                                    {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>
                                    Female</option>
                                <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>
                                    Other</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth"
                                value="{{ old('date_of_birth', $user->date_of_birth) }}" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Staff Type</label>
                            <select name="user_type" class="form-select">
                                <option value="">--Select User Type--</option>
                                <option value="employee"
                                    {{ old('user_type', $user->user_type) == 'employee' ? 'selected' : '' }}>Employee
                                </option>
                                <option value="national_service"
                                    {{ old('user_type', $user->user_type) == 'national_service' ? 'selected' : '' }}>
                                    National Service</option>
                                <option value="intern"
                                    {{ old('user_type', $user->user_type) == 'intern' ? 'selected' : '' }}>Intern
                                </option>
                                <option value="consultant"
                                    {{ old('user_type', $user->user_type) == 'consultant' ? 'selected' : '' }}>
                                    Consultant</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Nationality</label>
                            <input type="text" name="nationality"
                                value="{{ old('nationality', $user->nationality) }}" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Address / Location</label>
                            <input type="text" name="address" value="{{ old('address', $user->address) }}"
                                class="form-control" required>
                        </div>

                        {{-- ID Type --}}
                        <div class="col-md-3">
                            <label class="form-label">ID Type</label>
                            <select name="id_type" id="idType" class="form-select">
                                <option value="">Select ID Type</option>
                                <option value="passport"
                                    {{ old('id_type', $user->id_type) == 'passport' ? 'selected' : '' }}>Passport
                                </option>
                                <option value="national_id"
                                    {{ old('id_type', $user->id_type) == 'national_id' ? 'selected' : '' }}>National ID
                                </option>
                                <option value="voter_id"
                                    {{ old('id_type', $user->id_type) == 'voter_id' ? 'selected' : '' }}>Voter ID
                                </option>
                                <option value="other"
                                    {{ old('id_type', $user->id_type) == 'other' ? 'selected' : '' }}>
                                    Other</option>
                            </select>
                            <input type="text" name="id_type_other" id="idTypeOther" class="form-control mt-2"
                                placeholder="Enter other ID type"
                                style="{{ old('id_type', $user->id_type) == 'other' ? '' : 'display:none;' }}"
                                value="{{ old('id_type_other', $user->id_type_other) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">ID Number</label>
                            <input type="text" name="id_number" value="{{ old('id_number', $user->id_number) }}"
                                class="form-control">
                        </div>
                    </div>

                    {{-- Next Of Kin --}}
                    <h4 class="mt-5 mb-3 fw-bold fs-3">Next of Kin Details</h4>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="kin_name" value="{{ old('kin_name', $user->kin_name) }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Relationship</label>
                            <select name="kin_relationship" id="kin_relationship" class="form-select"
                                onchange="toggleOtherRelationship()">
                                <option value="">--Select Relationship--</option>
                                @foreach (['parent', 'child', 'spouse', 'sibling', 'friend', 'other'] as $rel)
                                    <option value="{{ $rel }}"
                                        {{ old('kin_relationship', $user->kin_relationship) == $rel ? 'selected' : '' }}>
                                        {{ ucfirst($rel) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3" id="other_relationship_div"
                            style="{{ old('kin_relationship', $user->kin_relationship) == 'other' ? '' : 'display:none;' }}">
                            <label class="form-label">Specify Relationship</label>
                            <input type="text" name="kin_relationship_other"
                                value="{{ old('kin_relationship_other', $user->kin_relationship_other) }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="kin_phone" value="{{ old('kin_phone', $user->kin_phone) }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="kin_email" value="{{ old('kin_email', $user->kin_email) }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="kin_address"
                                value="{{ old('kin_address', $user->kin_address) }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="kin_dob" value="{{ old('kin_dob', $user->kin_dob) }}"
                                class="form-control">
                        </div>
                    </div>

                    {{-- Emergency Contact --}}
                    <h4 class="mt-5 mb-3 fw-bold fs-3">Emergency Contact</h4>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="emergency_name"
                                value="{{ old('emergency_name', $user->emergency_name) }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Relationship</label>
                            <select name="emergency_relationship" id="emergency_relationship" class="form-select"
                                onchange="toggleOtherEmergencyRelationship()">
                                <option value="">--Select Relationship--</option>
                                @foreach (['parent', 'spouse', 'sibling', 'friend', 'other'] as $rel)
                                    <option value="{{ $rel }}"
                                        {{ old('emergency_relationship', $user->emergency_relationship) == $rel ? 'selected' : '' }}>
                                        {{ ucfirst($rel) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3" id="other_emergency_relationship_div"
                            style="{{ old('emergency_relationship', $user->emergency_relationship) == 'other' ? '' : 'display:none;' }}">
                            <label class="form-label">Specify Relationship</label>
                            <input type="text" name="emergency_relationship_other"
                                value="{{ old('emergency_relationship_other', $user->emergency_relationship_other) }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="emergency_phone"
                                value="{{ old('emergency_phone', $user->emergency_phone) }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="emergency_email"
                                value="{{ old('emergency_email', $user->emergency_email) }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="emergency_address"
                                value="{{ old('emergency_address', $user->emergency_address) }}"
                                class="form-control">
                        </div>
                    </div>

                    {{-- Employment Details --}}
                    <h4 class="mt-5 mb-3 fw-bold fs-3">Employment Details</h4>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Job Title</label>
                            <input type="text" name="job_title" value="{{ old('job_title', $user->job_title) }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Employment Type</label>
                            <select name="employment_type" id="employment_type" class="form-select" required>
                                <option value="">--Select Employment Type--</option>
                                <option value="fulltime"
                                    {{ old('employment_type', $user->employment_type) == 'fulltime' ? 'selected' : '' }}>
                                    Full Time</option>
                                <option value="parttime"
                                    {{ old('employment_type', $user->employment_type) == 'parttime' ? 'selected' : '' }}>
                                    Part Time</option>
                                <option value="contract"
                                    {{ old('employment_type', $user->employment_type) == 'contract' ? 'selected' : '' }}>
                                    Contract</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date of Joining</label>
                            <input type="date" name="date_of_joining"
                                value="{{ old('date_of_joining', $user->date_of_joining) }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Work Location</label>
                            <input type="text" name="work_location"
                                value="{{ old('work_location', $user->work_location) }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Supervisor</label>
                            <select name="supervisor_id" class="form-select">
                                <option value="">--Select Supervisor--</option>
                                @foreach ($users as $supervisor)
                                    <option value="{{ $supervisor->id }}"
                                        {{ old('supervisor_id', $user->supervisor_id) == $supervisor->id ? 'selected' : '' }}>
                                        {{ $supervisor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Salary</label>
                            <input type="number" step="0.01" name="salary"
                                value="{{ old('salary', $user->salary) }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Pay Grade</label>
                            <input type="text" name="pay_grade" value="{{ old('pay_grade', $user->pay_grade) }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Benefits</label>
                            <textarea name="benefits" class="form-control">{{ old('benefits', $user->benefits) }}</textarea>
                        </div>
                        <div class="col-md-3" id="contract_duration_div"
                            style="{{ old('employment_type', $user->employment_type) == 'contract' ? '' : 'display:none;' }}">
                            <label class="form-label">Contract Duration</label>
                            <input type="text" name="contract_duration"
                                value="{{ old('contract_duration', $user->contract_duration) }}"
                                class="form-control">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">Update User</button>
                        <a href="{{ route('all.staffs') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Trigger Button Example -->
    <button @click="editUser = {{ json_encode($user) }}" class="btn btn-primary">Edit User</button>
</div>
