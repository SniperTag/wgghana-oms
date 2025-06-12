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

                <div class="container mx-auto p-4">
                    <h2 class="text-2xl font-bold mb-4">My Attendance Record</h2>
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
                        <!-- Quick Filters -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.attendance', ['filter' => 'today']) }}"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Today</a>

                            <a href="{{ route('admin.attendance', ['filter' => 'this_week']) }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">This Week</a>

                            <a href="{{ route('admin.attendance', ['filter' => 'this_month']) }}"
                                class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">This Month</a>

                            <a href="{{ route('admin.attendance') }}"
                                class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Clear Filter</a>
                        </div>

                        <!-- Date Range Filter -->
                        <form method="GET" class="flex flex-wrap gap-2 items-center">
                            <input type="date" name="from" value="{{ request('from') }}"
                                class="border p-2 rounded" {{ $hasFilter ? 'disabled' : '' }}>
                            <input type="date" name="to" value="{{ request('to') }}" class="border p-2 rounded"
                                {{ $hasFilter ? 'disabled' : '' }}>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded"
                                {{ $hasFilter ? 'disabled' : '' }}>Filter</button>
                        </form>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="row">
                        <!-- Clock In Button -->
                        <div class="col-md-2 d-flex align-items-center">
                            <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                                data-bs-target="#clockInModal">
                                Clock In
                            </button>
                        </div>

                        <!-- Clock Out Button -->
                        <div class="col-md-2 d-flex align-items-center">
                            <button type="button" class="btn btn-danger w-100"
                                onclick="openClockOutModal('{{ Auth::user()->staff_id }}', '{{ Auth::user()->schedule->clock_out_time ?? '17:30' }}')">

                                Clock Out
                            </button>
                        </div>
                    </div>



                    <div class="overflow-x-auto bg-white shadow-md rounded-lg mt-5">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gray-100 text-left">
                                <tr>
                                    <th class="p-2">Date</th>
                                    <th class="p-2">Check-In</th>
                                    <th class="p-2">Check-Out</th>
                                    <th class="p-2">Status</th>
                                    <th class="p-2">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $record)
                                    <tr class="border-b">
                                        <td class="p-2">{{ $record->attendance_date }}</td>
                                        <td class="p-2">{{ $record->check_in_time ?? '-' }}</td>
                                        <td class="p-2">{{ $record->check_out_time ?? '-' }}</td>
                                        <td class="p-2">
                                            @if ($record->status == 'On Time')
                                                <span class="text-green-600 font-semibold">{{ $record->status }}</span>
                                            @elseif ($record->status == 'Late')
                                                <span
                                                    class="text-yellow-600 font-semibold">{{ $record->status }}</span>
                                            @else
                                                <span class="text-red-600 font-semibold">{{ $record->status }}</span>
                                            @endif
                                        </td>
                                        <td class="p-2">{{ $record->notes ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center text-gray-500">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $records->links() }}
                    </div>
                    @include('components.modals.staff-login')

                    @include('components.modals.clock-out')
                </div>

            </div>
            <!-- END Page Content -->
        </main>
        {{-- Main section --}}

        <!-- END Main Container -->
    </div>

    {{--  <script>
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
    </script>  --}}

    {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>  --}}

    @include('layouts.js')

</body>

</html>
