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


        @include('layouts.partials.sidebar')

        {{-- Header Section --}}
        @include('layouts.header')


        {{-- Main section --}}
        <main>
    <div class="content mt-5 space-y-5">

        <!-- Welcome -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Welcome, {{ $user->name }}</h1>
            <p class="text-sm text-gray-500">Here’s a snapshot of your team and activities.</p>
        </div>

        <!-- Notifications -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Recent Notifications</h2>
            <ul class="space-y-2 text-sm text-gray-700">
                @forelse($notifications as $note)
                    <li class="border-l-4 pl-3 border-blue-400 bg-blue-50 py-2 rounded">
                        {{ $note->data['message'] ?? $note->message }}
                        @if (isset($note->data['url']))
                            <a href="{{ url($note->data['url']) }}" class="text-blue-600 underline ml-2">View</a>
                        @endif
                        <span class="block text-xs text-gray-500">{{ $note->created_at->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="text-gray-500">No notifications available.</li>
                @endforelse
            </ul>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $cards = [
                    ['title' => 'Pending Approvals', 'count' => $pendingApprovalsCount, 'color' => 'yellow'],
                    ['title' => 'Subordinates On Leave', 'count' => $subordinatesOnLeaveCount, 'color' => 'blue'],
                    ['title' => 'Approved Leaves (Team)', 'count' => $approvedCount, 'color' => 'green'],
                    ['title' => 'Rejected Leaves (Team)', 'count' => $rejectedCount, 'color' => 'red'],
                    ['title' => 'Team Late Arrivals', 'count' => $teamLateCount, 'color' => 'pink'],
                    ['title' => 'Attendance Today', 'count' => $teamAttendanceToday, 'color' => 'indigo'],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="bg-{{ $card['color'] }}-50 border border-{{ $card['color'] }}-200 rounded-xl p-4 shadow hover:shadow-md transition">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-{{ $card['color'] }}-100 rounded-full">
                            <svg class="h-6 w-6 text-{{ $card['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xl font-semibold text-{{ $card['color'] }}-700">{{ $card['count'] }}</p>
                            <p class="text-xs font-medium uppercase text-{{ $card['color'] }}-600">{{ $card['title'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-2">Team Leave Trend</h2>
                <canvas id="leaveTrendChart" height="200"></canvas>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-2">Leave Type Breakdown</h2>
                <canvas id="leaveTypeChart" height="200"></canvas>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Team Recent Activities</h2>
            <ul class="space-y-3 text-sm text-gray-700">
                @forelse($activities as $activity)
                    <li class="border-b pb-2">
                        {{ $activity->description }}
                        <span class="block text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="text-gray-500">No recent activities logged.</li>
                @endforelse
            </ul>
        </div>

        <!-- Pending Approvals Table -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Pending Leave Approvals</h2>
            <table class="w-full table-auto text-sm text-gray-700">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-3 py-2 text-left">Employee</th>
                        <th class="px-3 py-2 text-left">Leave Type</th>
                        <th class="px-3 py-2 text-left">Dates</th>
                        <th class="px-3 py-2 text-left">Status</th>
                        <th class="px-3 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingApprovals as $leave)
                        <tr>
                            <td class="px-3 py-2">{{ $leave->user->name }}</td>
                            <td class="px-3 py-2">{{ $leave->leaveType->name }}</td>
                            <td class="px-3 py-2">{{ $leave->start_date }} - {{ $leave->end_date }}</td>
                            <td class="px-3 py-2 capitalize">{{ $leave->supervisor_status }}</td>
                            <td class="px-3 py-2 space-x-2">
                                <a href="{{ route('subordinates.show', $leave->id) }}" class="text-blue-600 underline">View</a>
                                <form action="{{ route('supervisor.approve', $leave->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 underline">Approve</button>
                                </form>
                                <form action="{{ route('supervisor.reject', $leave->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 underline">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-gray-500 text-center py-2">No pending approvals</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</main>

        <!-- END Main Container -->


    </div>
    <!-- END Page Container -->


    @include('layouts.js')
</body>

</html>
