{{--  <x-layouts.app :title="__('Dashboard')">


</x-layouts.app>  --}}




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

        {{-- Side bar dashboard End --}}

        {{-- Side bar dashboard start --}}

        {{-- Side bar dashboard End --}}



        {{-- Header Section --}}
        @include('layouts.header')

        <!-- Main Container -->
        <main id="main-container block-content-full">
            <!-- Page Content -->
            <div class="content mt-7">
                <div class="row">
                    <!-- Row #1 -->
                   

                    <div class="container-fluid">
                        <h1 class="mb-4">View Attendance Records</h1>
                        {{--
                        <form method="GET" action="{{ route('attendance.index') }}" class="mb-4 d-flex gap-2">
                            <input type="text" name="name" value="{{ request('name') }}" class="form-control w-25"
                                placeholder="Search by name">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">Reset</a>

                        </form>  --}}

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <div class="row mb-4">
                             <div class="col-md-2 d-flex align-items-center">
                            <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                                data-bs-target="#clockInModal">
                                Clock In
                            </button>
                        </div>
                            <div class="col-md-2 mb-3 flex items-end">
                                @if (session()->has('clocked_in_user_id'))
                                    <!-- Trigger Button -->
                                    <button type="button" class="btn btn-danger mt-2" data-bs-toggle="modal"
                                        data-bs-target="#clockOutModal">
                                         Clock Out
                                    </button>
                                @endif

                            </div>

                        </div>



                        <div class="table-responsive">
                            <table id="userTable"
                                class="table table-bordered table-hover align-middle text-center border-none">

                                <thead class="border-none">
                                    <tr>
                                        <th class="border-none">No</th>
                                        <th class="border-none">Name</th>
                                        <th class="border-none">Department</th>
                                        <th class="border-none">Role</th>
                                        <th class="border-none">Time-in</th>
                                        <th class="border-none">Time-out</th>
                                        <th class="border-none">Status</th>
                                        <th class="border-none">Date</th>
                                        <th class="border-none">IP Address</th>
                                        <th class="border-none">Device</th>
                                    </tr>
                                    <tr>
                                        <th class="border-none"></th>
                                        <th class="border-none">Name</th>
                                        <th class="border-none">Department</th>
                                        <th class="border-none"></th>
                                        <th class="border-none"></th>
                                        <th class="border-none">Time-out</th>
                                        <th class="border-none">Status</th>
                                        <th class="border-none"></th>
                                        <th class="border-none"></th>
                                        <th class="border-none"></th>
                                    </tr>
                                </thead>
                                <tbody class="border-none text-sm text-left">
                                    @forelse ($attendanceRecords as $record)
                                        <tr>
                                            <td class="border-none">{{ $loop->iteration }}</td>
                                            <td class="border-none">{{ $record->user->name }}</td>
                                            <td class="border-none">{{ $record->user->department->name ?? 'N/A' }}</td>
                                            <td class="border-none">
                                                {{ $record->user->getRoleNames()->first() ?? 'N/A' }}</td>
                                            <td class="border-none">
                                                @php
                                                    $status = $record->status;
                                                    $checkInClass = '';

                                                    if ($status === 'On Time') {
                                                        $checkInClass = 'bg-success text-white';
                                                    } elseif ($status === 'Late') {
                                                        $checkInClass = 'bg-warning text-dark';
                                                    } elseif ($status === 'Very Late') {
                                                        $checkInClass = 'bg-danger text-white';
                                                    }
                                                @endphp

                                                <span class="{{ $checkInClass }} px-2 py-1 rounded d-inline-block">
                                                    {{ $record->check_in_time }}
                                                </span>
                                            </td>

                                            <td class="border-none">{{ $record->check_out_time ?? '—' }}</td>
                                            <td class="border-none">{{ $record->status }}</td>
                                            <td class="border-none">{{ $record->attendance_date }}</td>
                                            <td class="border-none">{{ $record->ip_address }}</td>
                                            <td class="border-none">{{ $record->device_info ?? 'Unknown' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">No attendance records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $attendanceRecords->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                            @include('components.modals.staff-login')

                    @include('components.modals.clock-out')


                </div>

            </div>
            <!-- END Page Content -->
        </main>
        {{-- Main section --}}

        <!-- END Main Container -->
        @include('layouts.footer')
    </div>

     <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("✅ Staff verification script loaded");

            const verifyBtn = document.getElementById('verifyBtn');
            const staffIdInput = document.getElementById('staff_id');
            const pinSection = document.getElementById('pin_section');
            const submitBtn = document.getElementById('submitBtn');
            const staffError = document.getElementById('staff_error');
            const staffInfo = document.getElementById('staff_info');

            // Confirm all DOM elements are present
            if (!verifyBtn || !staffIdInput || !pinSection || !submitBtn || !staffError || !staffInfo) {
                console.error("❌ One or more required elements not found in DOM.");
                return;
            }

            verifyBtn.addEventListener('click', function() {
                const staffId = staffIdInput.value.trim();

                if (!staffId) {
                    alert("⚠️ Please enter a Staff ID.");
                    return;
                }

                console.log(`🔍 Verifying Staff ID: ${staffId}`);

                fetch(`/admin/attendance/verify/${staffId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            pinSection.classList.remove('d-none');
                            staffError.style.display = 'none';
                            staffInfo.innerHTML = data.message.replace(/\n/g, '<br>'); // <-- this line
                            staffInfo.style.display = 'block';
                            submitBtn.disabled = false;
                        } else {
                            pinSection.classList.add('d-none');
                            staffError.textContent = data.message;
                            staffError.style.display = 'block';
                            staffInfo.style.display = 'none';
                            submitBtn.disabled = true;
                        }
                    })
                    .catch(error => {
                        console.error('❌ Error during fetch:', error);
                        pinSection.classList.add('d-none');
                        staffInfo.style.display = 'none';
                        staffError.textContent = "Something went wrong. Please try again.";
                        staffError.style.display = 'block';
                        submitBtn.disabled = true;
                    });
            });
        });
    </script>

    {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>  --}}
</body>

</html>
