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
        <main id="main-container" class="p-3 bg-gray-50 min-h-screen mt-7">


            <!-- Leave Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-5 ">
                <!-- Approved Leaves -->
                <div class="bg-green-50 border border-green-300 rounded-lg p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center space-x-2">
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xl font-bold text-green-700">{{ $approvedCount }}</p>
                            <p class="uppercase text-sm font-semibold text-green-600 tracking-wide">Approved Leaves</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Leaves -->
                <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center space-x-2">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xl font-bold text-yellow-700">{{ $pendingCount }}</p>
                            <p class="uppercase text-sm font-semibold text-yellow-600 tracking-wide">Pending Leaves</p>
                        </div>
                    </div>
                </div>

                <!-- Rejected Leaves -->
                <div class="bg-red-50 border border-red-300 rounded-lg p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center space-x-2">
                        <div class="p-3 bg-red-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xl font-bold text-red-700">{{ $rejectedCount }}</p>
                            <p class="uppercase text-sm font-semibold text-red-600 tracking-wide">Rejected Leaves</p>
                        </div>
                    </div>
                </div>

                <!-- Currently On Leave -->
                <div class="bg-blue-50 border border-blue-300 rounded-lg p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center space-x-2">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 17l4-4 4 4m0-5l-4 4-4-4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xl font-bold text-blue-700">{{ $onLeaveCount }}</p>
                            <p class="uppercase text-sm font-semibold text-blue-600 tracking-wide">Currently On Leave
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Leave Days Left -->
                <div
                    class="bg-indigo-50 border border-indigo-300 rounded-lg p-3 shadow-sm hover:shadow-md transition col-span-1 sm:col-span-2 lg:col-span-2">
                    <div class="flex items-center space-x-2">
                        <div class="p-3 bg-indigo-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 1.343-3 3v6h6v-6c0-1.657-1.343-3-3-3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8V4m0 0L9 7m3-3l3 3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-4xl font-extrabold text-indigo-700">
                                {{ $leaveBalance?->remaining_days ?? 'N/A' }}</p>
                            <p class="uppercase text-lg font-semibold text-indigo-600 tracking-wide">Leave Days Left
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Annual Leave -->
                <div
                    class="bg-indigo-50 border border-indigo-300 rounded-lg p-3 shadow-sm hover:shadow-md transition col-span-1 sm:col-span-2 lg:col-span-2">
                    <div class="flex items-center space-x-2">
                        <div class="p-3 bg-indigo-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v3m0 12v3m7.071-7.071l-2.121 2.121M6.343 6.343l-2.122 2.122M21 12h-3M6 12H3m14.071 7.071l-2.122-2.121M6.343 17.657l-2.122-2.122" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-4xl font-extrabold text-indigo-700">{{ $totalAnnualLeaveCount }}</p>
                            <p class="uppercase text-lg font-semibold text-indigo-600 tracking-wide">Total Annual Leave
                                Days</p>
                        </div>
                    </div>
                </div>
            </div>

             <!-- Filters & Actions -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 space-y-4 md:space-y-0">

                <!-- Filter Form -->
                <form method="GET" action="{{ route('staff.leaves.index') }}"
                    class="flex flex-col sm:flex-row sm:space-x-4 items-center">

                    <!-- From Date -->
                    <input type="date" name="from" value="{{ request('from') }}" placeholder="From Date"
                        class="border border-gray-300 rounded-md p-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />

                    <!-- To Date -->
                    <input type="date" name="to" value="{{ request('to') }}" placeholder="To Date"
                        class="border border-gray-300 rounded-md p-2 text-sm mt-2 sm:mt-0 focus:outline-none focus:ring-2 focus:ring-indigo-500" />

                    <!-- Status Filter -->
                    <select name="status"
                        class="border border-gray-300 rounded-md p-2 text-sm mt-2 sm:mt-0 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Statuses</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                        </option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                        </option>
                    </select>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="bg-indigo-600 text-white rounded-md px-4 py-2 text-sm mt-2 sm:mt-0 hover:bg-indigo-700 transition">
                        Filter
                    </button>

                </form>

                <!-- Search + New Leave Button -->
                <div class="flex items-center space-x-2">

                    <!-- Search -->
                    <form method="GET" action="{{ route('staff.leaves.index') }}" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search Leave Type..."
                            class="border border-gray-300 rounded-md pl-8 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-2 top-2.5 h-5 w-5 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 1116.65 2.15a7.5 7.5 0 010 14.5z" />
                        </svg>
                    </form>

                    <!-- Apply Leave Button -->
                    <a href="{{ route('staff.leave.apply') }}"
                        class="bg-green-600 text-white px-4 py-2 rounded-md text-sm hover:bg-green-700 transition">
                        + Apply for Leave
                    </a>
                </div>
            </div>
            <!-- Leave Requests Table (same as before) -->
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 table-striped" id="dataTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Leave Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Start Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                End Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Approved By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Approval Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($leaves as $leave)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $leave->leaveType->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $leave->start_date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $leave->end_date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColor = match ($leave->status) {
                                            'approved' => 'text-green-600 bg-green-100',
                                            'pending' => 'text-yellow-600 bg-yellow-100',
                                            'rejected' => 'text-red-600 bg-red-100',
                                            default => 'text-gray-600 bg-gray-100',
                                        };
                                    @endphp
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                        {{ ucfirst($leave->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $leave->approvedByUser?->name ?? 'Pending' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $leave->approved_at?->format('Y-m-d H:i') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No leave requests
                                    found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </main>

        {{-- Main section --}}

        <!-- END Main Container -->

    </div>
    <!-- END Page Container -->

    <script>
        $(document).ready(function() {
            $('#roles').select2({
                placeholder: "Select role(s)",
                width: '100%'
            });
        });

        $(document).ready(function() {
            $('#department').select2({
                placeholder: "Select department(s)",
                width: '100%'
            });
        });
    </script>

    <!-- Select2 Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Bootstrap Bundle (Popper.js included) -->
    {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  --}}
    @include('layouts.footer')
</body>

</html>
