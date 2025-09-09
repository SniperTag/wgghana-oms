<div x-data="{ open: false }">
    <!-- Button to Open Modal -->
    <div class="d-flex justify-content-start mb-3">
        <button @click="open = true" class="btn btn-primary">+ Create User</button>
    </div>

    <!-- Modal -->
    <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-50">
        <div @click.away="open = false"
            class="bg-white dark:bg-gray-800 w-full max-w-3xl p-6 rounded shadow-lg overflow-y-auto max-h-[90vh]">

            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Create User</h2>
                <button @click="open = false" class="text-gray-500 hover:text-red-600 text-lg">&times;</button>
            </div>

            <!-- Modal Body (Create User Form) -->
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- User Information --}}
                <h4 class="mt-5 mb-3 fw-bold fst-normal fs-3">User Information</h4>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Corporate Email</label>
                        <input type="corporate_email" name="corporate_email" value="{{ old('corporate_email') }}"
                            class="form-control" required>
                    </div>

                    @php
                        use App\Models\Department;
                        use App\Models\SubRole;

                        $departments = Department::all();
                        // Group SubRoles by department_id and make sure keys are integers

                        $subRoles = SubRole::all()
                            ->groupBy('department_id')
                            ->map(function ($subs) {
                                return $subs->pluck('title', 'id')->mapWithKeys(function ($title, $id) {
                                    return [(int) $id => $title];
                                });
                            })
                            ->mapWithKeys(function ($subs, $deptId) {
                                return [(int) $deptId => $subs];
                            });
                    @endphp

                    <div class="col-md-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" id="department" class="form-select" required>
                            <option value="">--Select Department--</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Sub Role</label>
                        <select name="sub_role_id" id="sub_role" class="form-select" required>
                            <option value="">--Select Sub Role--</option>
                            {{-- Sub roles will be populated dynamically based on department selection --}}
                        </select>
                        @error('sub_role_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-3">
                        <label class="form-label">Access Role</label>
                        <select name="role_id" id="main_role" class="form-select" required>
                            <option value="">--Select Access Role--</option>
                            @foreach ($roles as $roleId => $roleName)
                                <option value="{{ $roleName }}">
                                    {{ ucfirst($roleName) }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">--Select Gender--</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male
                            </option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                            </option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                            class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Staff Type</label>
                        <select name="user_type" class="form-select">
                            <option value="">--Select User Type--</option>
                            <option value="employee" {{ old('user_type') == 'employee' ? 'selected' : '' }}>
                                Employee</option>
                            <option value="national_service"
                                {{ old('user_type') == 'national_service' ? 'selected' : '' }}>
                                National Service</option>
                            <option value="intern" {{ old('user_type') == 'intern' ? 'selected' : '' }}>Intern
                            </option>
                            <option value="consultant" {{ old('user_type') == 'consultant' ? 'selected' : '' }}>
                                Consultant
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Nationality</label>
                        <input type="nationality" name="nationality" value="{{ old('nationality') }}"
                            class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Address / Location</label>
                        <input type="address" name="address" value="{{ old('address') }}" class="form-control"
                            required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">ID Type</label>
                        <select name="id_type" id="idType" class="form-select">
                            <option value="">Select ID Type</option>
                            <option value="passport" {{ old('id_type') == 'passport' ? 'selected' : '' }}>
                                Passport</option>
                            <option value="national_id" {{ old('id_type') == 'national_id' ? 'selected' : '' }}>
                                National ID</option>
                            <option value="voter_id" {{ old('id_type') == 'voter_id' ? 'selected' : '' }}>Voter
                                ID</option>
                            <option value="other" {{ old('id_type') == 'other' ? 'selected' : '' }}>Other
                            </option>
                        </select>

                        <!-- Hidden input that will show if "Other" is selected -->
                        <input type="text" name="id_type_other" id="idTypeOther" class="form-control mt-2"
                            placeholder="Enter other ID type" style="display:none;"
                            value="{{ old('id_type_other') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">ID Number</label>
                        <input type="text" name="id_number" value="{{ old('id_number') }}" class="form-control">
                    </div>


                    {{-- Next Of Kin Details --}}
                    <h4 class="mt-5 mb-3 fw-bold fst-normal fs-3">Next of Kin Details</h4>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="kin_name" value="{{ old('kin_name') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Relationship</label>
                            <select name="kin_relationship" id="kin_relationship" class="form-select"
                                onchange="toggleOtherRelationship()">
                                <option value="">--Select Relationship--</option>
                                <option value="parent" {{ old('kin_relationship') == 'parent' ? 'selected' : '' }}>
                                    Parent</option>
                                <option value="child" {{ old('kin_relationship') == 'child' ? 'selected' : '' }}>
                                    Child</option>
                                <option value="spouse" {{ old('kin_relationship') == 'spouse' ? 'selected' : '' }}>
                                    Spouse</option>
                                <option value="sibling" {{ old('kin_relationship') == 'sibling' ? 'selected' : '' }}>
                                    Sibling</option>
                                <option value="friend" {{ old('kin_relationship') == 'friend' ? 'selected' : '' }}>
                                    Friend</option>
                                <option value="other" {{ old('kin_relationship') == 'other' ? 'selected' : '' }}>
                                    Other</option>
                            </select>
                        </div>

                        <div class="col-md-3" id="other_relationship_div" style="display: none;">
                            <label class="form-label">Specify Relationship</label>
                            <input type="text" name="kin_relationship_other"
                                value="{{ old('kin_relationship_other') }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="kin_phone" value="{{ old('kin_phone') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="kin_email" value="{{ old('kin_email') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Address / Location</label>
                            <input type="address" name="kin_address" value="{{ old('kin_address') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label    ">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                class="form-control">
                        </div>

                    </div>



                    {{-- Emergency Contact --}}
                    <h4 class="mt-5 mb-3 fw-bold fst-normal fs-3">Emergency Contact</h4>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="emergency_name" value="{{ old('emergency_name') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Relationship</label>
                            <select name="emergency_relationship" id="emergency_relationship" class="form-select"
                                onchange="toggleOtherEmergencyRelationship()">
                                <option value="">--Select Relationship--</option>
                                <option value="parent"
                                    {{ old('emergency_relationship') == 'parent' ? 'selected' : '' }}>
                                    Parent</option>
                                <option value="spouse"
                                    {{ old('emergency_relationship') == 'spouse' ? 'selected' : '' }}>
                                    Spouse</option>
                                <option value="sibling"
                                    {{ old('emergency_relationship') == 'sibling' ? 'selected' : '' }}>
                                    Sibling</option>
                                <option value="friend"
                                    {{ old('emergency_relationship') == 'friend' ? 'selected' : '' }}>
                                    Friend</option>
                                <option value="other"
                                    {{ old('emergency_relationship') == 'other' ? 'selected' : '' }}>
                                    Other</option>
                            </select>
                        </div>

                        <div class="col-md-3" id="other_emergency_relationship_div" style="display: none;">
                            <label class="form-label">Specify Relationship</label>
                            <input type="text" name="emergency_relationship_other"
                                value="{{ old('emergency_relationship_other') }}" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="emergency_phone" value="{{ old('emergency_phone') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="emergency_email" value="{{ old('emergency_email') }}"
                                class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Address / Location</label>
                            <input type="address" name="emergency_address" value="{{ old('emergency_address') }}"
                                class="form-control">
                        </div>
                    </div>

                    {{-- Employment Details --}}
                    <h4 class="mt-5 mb-3 fw-bold fst-normal fs-3">Employment Details</h4>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Job Title</label>
                            <input type="text" name="job_title" value="{{ old('job_title') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Employment Type</label>
                            <select name="employment_type" id="employment_type" class="form-select" required>
                                <option value="" disabled selected>-- Select
                                    Employment
                                    Type --</option>
                                <option value="fulltime" {{ old('employment_type') == 'fulltime' ? 'selected' : '' }}>
                                    Full Time
                                </option>
                                <option value="parttime" {{ old('employment_type') == 'parttime' ? 'selected' : '' }}>
                                    Part Time
                                </option>
                                <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>
                                    Contract
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Date of Joining</label>
                            <input type="date" name="date_of_joining" value="{{ old('date_of_joining') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Work Location</label>
                            <input type="text" name="work_location" value="{{ old('work_location') }}"
                                class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Supervisor</label>
                            <select name="supervisor_id" class="form-select">
                                <option value="">--Select Supervisor--</option>
                                @foreach ($users as $supervisor)
                                    <option value="{{ $supervisor?->id ?? '' }}">
                                        {{ $supervisor?->name ?? 'No Name' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Salary</label>
                            <input type="number" step="0.01" name="salary" value="{{ old('salary') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Pay Grade</label>
                            <input type="text" name="pay_grade" value="{{ old('pay_grade') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Benefits</label>
                            <textarea name="benefits" class="form-control">{{ old('benefits') }}</textarea>
                        </div>

                        {{-- Contract Duration --}}
                        <div class="col-md-3" id="contract_duration_div" style="display: none;">
                            <label class="form-label">Contract Duration</label>
                            <input type="text" name="contract_duration" value="{{ old('contract_duration') }}"
                                class="form-control">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Create
                            User</button>
                    </div>
            </form>

        </div>
    </div>
</div>
