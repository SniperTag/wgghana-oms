<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('layouts.head')
</head>

<body>
    <!-- Page Container -->


    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

        <!-- Sidebar -->

        {{-- Side bar dashboard start --}}

        @include('layouts.partials.sidebar')

        @include('layouts.header')

        <!-- Main Container -->
        <main id="main-container content-full">
            <!-- Page Content -->
            <div class="content mt-7">
                <div class="row">
                    <!-- Row #1 -->
                    <div class="col-6 col-xl-3">
                        <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                            <div
                                class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                                <div class="d-none d-sm-block">
                                    <i class="si si-users fa-2x text-primary-light"></i>
                                </div>
                                <div class="text-end">
                                    <div class="fs-3 fw-semibold text-primary text-center">{{ $femaleCount }}</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Total Females</div>
                                </div>
                                <div class="text-start ">
                                    <div class="fs-3 fw-semibold text-primary text-center">{{ $maleCount }}</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Total Males</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-xl-3">
                        <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                            <div
                                class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                                <div class="d-none d-sm-block">
                                    <i class="si si-users fa-2x text-earth-light"></i>
                                </div>
                                <div class="text-end">
                                    <div class="fs-3 fw-semibold text-earth text-center">{{ $employeeCount }}</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Employees</div>
                                </div>
                                <div class="text-start">
                                    <div class="fs-3 fw-semibold text-earth text-center">{{ $nationalServiceCount }}
                                    </div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">National Service</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-xl-3">
                        <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                            <div
                                class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                                <div class="d-none d-sm-block">
                                    <i class="si si-envelope-open fa-2x text-elegance-light"></i>
                                </div>
                                <div class="text-end">
                                    <div class="fs-3 fw-semibold text-elegance">{{ $pendingCount }}</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Pending Leaves</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-xl-3">
                        <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                            <div
                                class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                                <div class="d-none d-sm-block">
                                    <i class="si si-users fa-2x text-pulse"></i>
                                </div>
                                <div class="text-end">
                                    <div class="fs-3 fw-semibold text-pulse">{{ $userCount }}</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Total Staffs</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- END Row #1 -->

                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">Create User</div>

                                    <div class="card-body">
                                        @if (session('success'))
                                            <div class="alert alert-success">{{ session('success') }}</div>
                                        @endif

                                        <form action="{{ route('admin.users.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf

                                            {{-- User Information --}}
                                            <h4 class="mt-5 mb-3 fw-bold fst-normal fs-3">User Information</h4>
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="form-label">Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="name" value="{{ old('name') }}"
                                                        class="form-control" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Email <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" name="email" value="{{ old('email') }}"
                                                        class="form-control" required>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Phone <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Corporate Email</label>
                                                    <input type="corporate_email" name="corporate_email"
                                                        value="{{ old('corporate_email') }}" class="form-control">
                                                </div>

                                                @php
                                                    use App\Models\Department;
                                                    use App\Models\SubRole;
                                                    $departments = Department::all();
                                                    $subRoles = SubRole::all()
                                                        ->groupBy('department_id')
                                                        ->map(fn($subs) => $subs->pluck('title', 'id')->toArray())
                                                        ->toArray();
                                                @endphp

                                                <div class="col-md-3">
                                                    <label class="form-label">Department <span
                                                            class="text-danger">*</span></label>
                                                    <select name="department_id" id="department" class="form-select"
                                                        required>
                                                        <option value="">--Select Department--</option>
                                                        @foreach ($departments as $department)
                                                            <option value="{{ $department->id }}"
                                                                {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                                {{ $department->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Sub Role <span
                                                            class="text-danger">*</span></label>
                                                    <select name="sub_role_id" id="sub_role" class="form-select"
                                                        required>
                                                        <option value="$subRole->id">--Select Sub Role--</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Access Role <span
                                                            class="text-danger">*</span></label>
                                                    <select name="role" class="form-select" required>
                                                        <option value="">-- Select Role --</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role }}"
                                                                {{ old('role') == $role ? 'selected' : '' }}>
                                                                {{ ucfirst($role) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>






                                                <div class="col-md-3">
                                                    <label class="form-label">Gender</label>
                                                    <select name="gender" class="form-select">
                                                        <option value="">--Select Gender--</option>
                                                        <option value="male"
                                                            {{ old('gender') == 'male' ? 'selected' : '' }}>Male
                                                        </option>
                                                        <option value="female"
                                                            {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                                        </option>
                                                        <option value="other"
                                                            {{ old('gender') == 'other' ? 'selected' : '' }}>Other
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Date of Birth <span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" name="date_of_birth"
                                                        value="{{ old('date_of_birth') }}" class="form-control">
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Staff Type</label>
                                                    <select name="user_type" class="form-select">
                                                        <option value="">--Select User Type--</option>
                                                        <option value="employee"
                                                            {{ old('user_type') == 'employee' ? 'selected' : '' }}>
                                                            Employee</option>
                                                        <option value="national_service"
                                                            {{ old('user_type') == 'national_service' ? 'selected' : '' }}>
                                                            National Service</option>
                                                        <option value="intern"
                                                            {{ old('user_type') == 'intern' ? 'selected' : '' }}>Intern
                                                        </option>
                                                        <option value="consultant"
                                                            {{ old('user_type') == 'consultant' ? 'selected' : '' }}>
                                                            Consultant
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Nationality <span
                                                            class="text-danger">*</span></label>
                                                    <input type="nationality" name="nationality"
                                                        value="{{ old('nationality') }}" class="form-control"
                                                        required>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Address / Location <span
                                                            class="text-danger">*</span></label>
                                                    <input type="address" name="address"
                                                        value="{{ old('address') }}" class="form-control" required>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">ID Type</label>
                                                    <select name="id_type" id="idType" class="form-select">
                                                        <option value="">Select ID Type</option>
                                                        <option value="passport"
                                                            {{ old('id_type') == 'passport' ? 'selected' : '' }}>
                                                            Passport</option>
                                                        <option value="national_id"
                                                            {{ old('id_type') == 'national_id' ? 'selected' : '' }}>
                                                            National ID</option>
                                                        <option value="voter_id"
                                                            {{ old('id_type') == 'voter_id' ? 'selected' : '' }}>Voter
                                                            ID</option>
                                                        <option value="other"
                                                            {{ old('id_type') == 'other' ? 'selected' : '' }}>Other
                                                        </option>
                                                    </select>

                                                    <!-- Hidden input that will show if "Other" is selected -->
                                                    <input type="text" name="id_type_other" id="idTypeOther"
                                                        class="form-control mt-2" placeholder="Enter other ID type"
                                                        style="display:none;" value="{{ old('id_type_other') }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">ID Number</label>
                                                    <input type="text" name="card_number"
                                                        value="{{ old('card_number') }}" class="form-control">
                                                </div>


                                                {{-- Next Of Kin Details --}}
                                                <h4 class="mt-5 mb-3 fw-bold fst-normal fs-3">Next of Kin Details</h4>
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Name</label>
                                                        <input type="text" name="kin_name"
                                                            value="{{ old('kin_name') }}" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Relationship</label>
                                                        <select name="kin_relationship" id="kin_relationship"
                                                            class="form-select" onchange="toggleOtherRelationship()">
                                                            <option value="">--Select Relationship--</option>
                                                            <option value="parent"
                                                                {{ old('kin_relationship') == 'parent' ? 'selected' : '' }}>
                                                                Parent</option>
                                                            <option value="child"
                                                                {{ old('kin_relationship') == 'child' ? 'selected' : '' }}>
                                                                Child</option>
                                                            <option value="spouse"
                                                                {{ old('kin_relationship') == 'spouse' ? 'selected' : '' }}>
                                                                Spouse</option>
                                                            <option value="sibling"
                                                                {{ old('kin_relationship') == 'sibling' ? 'selected' : '' }}>
                                                                Sibling</option>
                                                            <option value="friend"
                                                                {{ old('kin_relationship') == 'friend' ? 'selected' : '' }}>
                                                                Friend</option>
                                                            <option value="other"
                                                                {{ old('kin_relationship') == 'other' ? 'selected' : '' }}>
                                                                Other</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3" id="other_relationship_div"
                                                        style="display: none;">
                                                        <label class="form-label">Specify Relationship</label>
                                                        <input type="text" name="kin_relationship_other"
                                                            value="{{ old('kin_relationship_other') }}"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Phone</label>
                                                        <input type="text" name="kin_phone"
                                                            value="{{ old('kin_phone') }}" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" name="kin_email"
                                                            value="{{ old('kin_email') }}" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Address / Location</label>
                                                        <input type="address" name="kin_address"
                                                            value="{{ old('kin_address') }}" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label    ">Date of Birth</label>
                                                        <input type="date" name="date_of_birth"
                                                            value="{{ old('date_of_birth') }}" class="form-control">
                                                    </div>

                                                </div>



                                                {{-- Emergency Contact --}}
                                                <h4 class="mt-5 mb-3 fw-bold fst-normal fs-3">Emergency Contact</h4>
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Name <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="emergency_name"
                                                            value="{{ old('emergency_name') }}" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Relationship <span
                                                                class="text-danger">*</span></label>
                                                        <select name="emergency_relationship"
                                                            id="emergency_relationship" class="form-select"
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

                                                    <div class="col-md-3" id="other_emergency_relationship_div"
                                                        style="display: none;">
                                                        <label class="form-label">Specify Relationship</label>
                                                        <input type="text" name="emergency_relationship_other"
                                                            value="{{ old('emergency_relationship_other') }}"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Age</label>
                                                        <input type="number" name="emergency_age"
                                                            value="{{ old('emergency_age') }}" class="form-control">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Phone <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="emergency_phone"
                                                            value="{{ old('emergency_phone') }}"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" name="emergency_email"
                                                            value="{{ old('emergency_email') }}"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Address / Location <span
                                                                class="text-danger">*</span></label>
                                                        <input type="address" name="emergency_address"
                                                            value="{{ old('emergency_address') }}"
                                                            class="form-control">
                                                    </div>
                                                </div>

                                                {{-- Employment Details --}}
                                                <h4 class="mt-5 mb-3 fw-bold fst-normal fs-3">Employment Details
                                                </h4>
                                                <div class="row g-3">
                                                    {{-- <div class="col-md-3">
                                                        <label class="form-label">Job Title</label>
                                                        <input type="text" name="job_title"
                                                            value="{{ old('job_title') }}" class="form-control">
                                                    </div> --}}
                                                    <div class="col-md-3">
                                                        <label class="form-label">Employment Type <span
                                                                class="text-danger">*</span></label>
                                                        <select name="employment_type" id="employment_type"
                                                            class="form-select" required>
                                                            <option value="" disabled selected>-- Select
                                                                Employment
                                                                Type --</option>
                                                            <option value="fulltime"
                                                                {{ old('employment_type') == 'fulltime' ? 'selected' : '' }}>
                                                                Full Time
                                                            </option>
                                                            <option value="parttime"
                                                                {{ old('employment_type') == 'parttime' ? 'selected' : '' }}>
                                                                Part Time
                                                            </option>
                                                            <option value="contract"
                                                                {{ old('employment_type') == 'contract' ? 'selected' : '' }}>
                                                                Contract
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Date of Joining</label>
                                                        <input type="date" name="date_of_joining"
                                                            value="{{ old('date_of_joining') }}"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Work Location</label>
                                                        <input type="text" name="work_location"
                                                            value="{{ old('work_location') }}" class="form-control">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Supervisor</label>
                                                        <select name="supervisor_id" class="form-select">
                                                            <option value="">--Select Supervisor--</option>
                                                            @foreach ($supervisors as $supervisor)
                                                                <option value="{{ $supervisor->id ?? '' }}">
                                                                    {{ $supervisor?->name ?? '' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Salary</label>
                                                        <input type="number" step="0.01" name="salary"
                                                            value="{{ old('salary') }}" class="form-control">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Benefits</label>
                                                        <textarea name="benefits" class="form-control">{{ old('benefits') }}</textarea>
                                                    </div>


                                                    {{-- Contract Duration --}}
                                                    <div class="col-md-3" id="contract_duration_div"
                                                        style="display: none;">
                                                        <label class="form-label">Contract Duration</label>
                                                        <input type="text" name="contract_duration"
                                                            value="{{ old('contract_duration') }}"
                                                            class="form-control">
                                                    </div>
                                                </div>

                                                {{-- Upload Images --}}
                                                <<div class="col-md-3">
                                                    <label class="form-label">Upload Image</label>
                                                    <input type="file" id="avatarInput" class="form-control"
                                                        accept="image/*">
                                                    <img id="avatarPreview"
                                                        src="{{ old('avatar') ? asset('storage/' . old('avatar')) : '#' }}"
                                                        alt="Profile Preview"
                                                        style="display:none; margin-top:10px; width:100px; height:100px; object-fit:cover; border-radius:50%;">
                                            </div>
                                            <div class="mt-4">
                                                <button type="submit" class="btn btn-primary">Create
                                                    User</button>
                                            </div>
                                        </form>



                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

    </div>
    <!-- END Page Content -->
    </main>
    {{-- Main section --}}

    <!-- END Main Container -->
    @include('layouts.js')

    <!-- SubRole JS -->
    <script>
        (function() {
            // Helper: fill Sub Role options
            function fillSubRoles(selectEl, items, preselectId) {
                selectEl.innerHTML = '<option value="">--Select Sub Role--</option>';
                if (Array.isArray(items) && items.length) {
                    items.forEach(function(row) {
                        var opt = document.createElement('option');
                        opt.value = row.id;
                        opt.textContent = row.title;
                        selectEl.appendChild(opt);
                    });
                    selectEl.disabled = false;
                    // Preselect old subrole if provided and exists
                    if (preselectId) {
                        selectEl.value = String(preselectId);
                    }
                } else {
                    // No items: keep disabled & placeholder
                    selectEl.disabled = true;
                }
            }

            // Fetch subroles via GET (works with or without jQuery)
            function loadSubRoles(departmentId, onDone) {
                if (!departmentId) {
                    onDone([]);
                    return;
                }
                var url = "{{ route('subroles.byDepartment', ':id') }}".replace(':id', encodeURIComponent(
                    departmentId));

                // If jQuery exists, use it; else use fetch
                if (window.$ && $.getJSON) {
                    $.getJSON(url)
                        .done(function(data) {
                            onDone(data || []);
                        })
                        .fail(function() {
                            onDone([]);
                        });
                } else {
                    fetch(url, {
                            method: 'GET'
                        })
                        .then(function(r) {
                            return r.ok ? r.json() : [];
                        })
                        .then(function(data) {
                            onDone(data || []);
                        })
                        .catch(function() {
                            onDone([]);
                        });
                }
            }

            // Hook up events
            document.addEventListener('DOMContentLoaded', function() {
                var deptSelect = document.getElementById('department');
                var subRoleSelect = document.getElementById('sub_role');

                // Ensure disabled until we load
                subRoleSelect.disabled = true;

                function handleDeptChange(preselectSubRole) {
                    var deptId = deptSelect.value;
                    subRoleSelect.disabled = true;
                    subRoleSelect.innerHTML = '<option value="">--Select Sub Role--</option>';

                    if (!deptId) {
                        return;
                    }

                    // Optional: show loading state
                    var loadingOpt = document.createElement('option');
                    loadingOpt.value = '';
                    loadingOpt.textContent = 'Loading...';
                    subRoleSelect.appendChild(loadingOpt);

                    loadSubRoles(deptId, function(rows) {
                        fillSubRoles(subRoleSelect, rows, preselectSubRole);
                    });
                }

                // Change handler
                if (window.$) {
                    // jQuery path
                    $(deptSelect).on('change', function() {
                        handleDeptChange(null);
                    });
                } else {
                    // Vanilla path
                    deptSelect.addEventListener('change', function() {
                        handleDeptChange(null);
                    });
                }

                // Pre-populate if old values exist
                var oldDept = deptSelect.getAttribute('data-old-dept');
                var oldSub = deptSelect.getAttribute('data-old-subrole');

                if (oldDept) {
                    deptSelect.value = String(oldDept);
                    handleDeptChange(oldSub ? String(oldSub) : null);
                }
            });
        })();
    </script>

    <script>
        document.getElementById('avatarInput').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                const preview = document.getElementById('avatarPreview');
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        });
    </script>
    <div>

        <!--END Page Container-->



</body>

</html>
