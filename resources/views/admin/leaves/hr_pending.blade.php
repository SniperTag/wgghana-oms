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
        <main id="main-container content-full">
            <!-- Page Content -->
            <div class="content mt-7">

               <div class="p-4">
   <div class="p-4">

    {{-- Tab Navigation --}}
    <div class="mb-4 border-b border-gray-200">
        <nav class="flex space-x-2">
            <button id="tab-pending"
                    class="px-4 py-2 rounded border transition-all duration-150 bg-blue-500 text-white border-blue-500">
                Leave Pending History
            </button>
            <button id="tab-history"
                    class="px-4 py-2 rounded border transition-all duration-150 bg-white text-gray-700 border-gray-300 hover:bg-gray-100">
                Leave History
            </button>
        </nav>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="text-green-600 mb-3">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="text-red-600 mb-3">{{ session('error') }}</div>
    @endif

    {{-- Pending Leaves --}}
    <div id="pending-section" class="">
        <div class="bg-white rounded shadow p-4">
            <h2 class="text-lg font-semibold mb-3">Pending Approvals</h2>
            <table class="w-full text-sm bg-gray-100">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2">Staff</th>
                        <th class="p-2">Leave Type</th>
                        <th class="p-2">Start Date</th>
                        <th class="p-2">End Date</th>
                        <th class="p-2">Supervisor Status</th>
                        <th class="p-2">HR Status</th>
                        <th class="p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingLeaves as $leave)
                        <tr class="border-b">
                            <td class="p-2">{{ $leave->user->name }}</td>
                            <td class="p-2">{{ $leave->leaveType->name }}</td>
                            <td class="p-2">{{ $leave->start_date }}</td>
                            <td class="p-2">{{ $leave->end_date }}</td>

                            {{-- Supervisor Status --}}
                            <td class="p-2">
                                @php
                                    $supStatus = $leave->supervisor_status ?? 'pending';
                                    $supBadge = [
                                        'pending' => 'bg-yellow-300 text-black',
                                        'approved' => 'bg-green-500 text-white',
                                        'rejected' => 'bg-red-500 text-white'
                                    ][$supStatus] ?? 'bg-gray-300 text-black';
                                @endphp
                                <span class="px-2 py-1 rounded {{ $supBadge }}">{{ ucfirst($supStatus) }}</span>
                            </td>

                            {{-- HR Status --}}
                            <td class="p-2">
                                @php
                                    $status = $leave->hr_status ?? 'pending';
                                    $badge = [
                                        'pending' => 'bg-yellow-300 text-black',
                                        'approved' => 'bg-green-500 text-white',
                                        'rejected' => 'bg-red-500 text-white'
                                    ][$status] ?? 'bg-gray-300 text-black';
                                @endphp
                                <span class="px-2 py-1 rounded {{ $badge }}">{{ ucfirst($status) }}</span>
                            </td>

                            {{-- Actions --}}
                            <td class="p-2 flex space-x-2">
                                <form action="{{ route('leaves.approve', $leave->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        @if($leave->hr_status !== 'pending') disabled @endif
                                        class="px-2 py-1 rounded {{ $leave->hr_status === 'pending' ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-gray-300 text-gray-600 cursor-not-allowed' }}">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('leaves.reject', $leave->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        @if($leave->hr_status !== 'pending') disabled @endif
                                        class="px-2 py-1 rounded {{ $leave->hr_status === 'pending' ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-gray-300 text-gray-600 cursor-not-allowed' }}">
                                        Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="p-3 text-center text-gray-500">No pending leaves.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $pendingLeaves->links() }}</div>
        </div>
    </div>

    {{-- Approved/Rejected Leaves --}}
    <div id="history-section" class="hidden">
        <div class="bg-white rounded shadow p-4">
            <h2 class="text-lg font-semibold mb-3">Approved/Rejected Leaves</h2>
            <table class="w-full text-sm bg-gray-100">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2">#</th>
                        <th class="p-2">Staff</th>
                        <th class="p-2">Leave Type</th>
                        <th class="p-2">Dates</th>
                        <th class="p-2">Supervisor Status</th>
                        <th class="p-2">HR Status</th>
                        <th class="p-2">HR Action At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvedLeaves as $leave)
                        <tr class="border-b">
                            <td class="p-2">{{ $loop->iteration }}</td>
                            <td class="p-2">{{ $leave->user->name }}</td>
                            <td class="p-2">{{ $leave->leaveType->name }}</td>
                            <td class="p-2">{{ $leave->start_date }} - {{ $leave->end_date }}</td>

                            {{-- Supervisor Status --}}
                            <td class="p-2">
                                @php
                                    $supStatus = $leave->supervisor_status ?? 'pending';
                                    $supBadge = [
                                        'pending' => 'bg-yellow-300 text-black',
                                        'approved' => 'bg-green-500 text-white',
                                        'rejected' => 'bg-red-500 text-white'
                                    ][$supStatus] ?? 'bg-gray-300 text-black';
                                @endphp
                                <span class="px-2 py-1 rounded {{ $supBadge }}">{{ ucfirst($supStatus) }}</span>
                            </td>

                            {{-- HR Status --}}
                            <td class="p-2">
                                @php
                                    $status = $leave->hr_status ?? 'pending';
                                    $badge = [
                                        'approved' => 'bg-green-500 text-white',
                                        'rejected' => 'bg-red-500 text-white'
                                    ][$status] ?? 'bg-gray-300 text-black';
                                @endphp
                                <span class="px-2 py-1 rounded {{ $badge }}">{{ ucfirst($status) }}</span>
                            </td>

                            <td class="p-2">{{ $leave->hr_approved_at ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="p-3 text-center text-gray-500">No approved/rejected leaves.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $approvedLeaves->links() }}</div>
        </div>
    </div>

</div>

<script>
    const tabPending = document.getElementById('tab-pending');
    const tabHistory = document.getElementById('tab-history');
    const pendingSection = document.getElementById('pending-section');
    const historySection = document.getElementById('history-section');

    tabPending.addEventListener('click', () => {
        pendingSection.classList.remove('hidden');
        historySection.classList.add('hidden');

        tabPending.classList.add('bg-blue-500', 'text-white', 'border-blue-500');
        tabPending.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');

        tabHistory.classList.remove('bg-blue-500', 'text-white', 'border-blue-500');
        tabHistory.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
    });

    tabHistory.addEventListener('click', () => {
        historySection.classList.remove('hidden');
        pendingSection.classList.add('hidden');

        tabHistory.classList.add('bg-blue-500', 'text-white', 'border-blue-500');
        tabHistory.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');

        tabPending.classList.remove('bg-blue-500', 'text-white', 'border-blue-500');
        tabPending.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
    });
</script>


            </div>


            <!---View all approved Leaves-->



    </div>
    <!-- END Page Content -->
    </main>
    {{-- Main section --}}

    <!-- END Main Container -->
    @include('layouts.js')
    </div>
    <!-- END Page Container -->


</body>

</html>
