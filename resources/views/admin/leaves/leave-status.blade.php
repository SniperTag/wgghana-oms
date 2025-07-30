<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('layouts.app')
</head>

<body>
    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

        @include('layouts.partials.sidebar')
        @include('layouts.header')

        <main id="main-container content-full">
            <div class="content mt-7">
                <div class="row">
                    <div class="container-fluid">

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="flex justify-between items-center mb-4">
                            <h1 class="text-2xl font-bold uppercase">
                                @if ($view === 'upcoming')
                                    🚀 Staffs Going On Leave
                                @else
                                    ✅ Staffs Currently On Leave
                                @endif
                            </h1>

                            <div class="space-x-2">
                                <a href="{{ route('leaves.status', ['view' => 'current']) }}"
                                    class="px-4 py-2 rounded {{ $view !== 'upcoming' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                                    Currently On Leave
                                </a>
                                <a href="{{ route('leaves.status', ['view' => 'upcoming']) }}"
                                    class="px-4 py-2 rounded {{ $view === 'upcoming' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                                    Going On Leave
                                </a>
                            </div>
                        </div>

                        <form method="GET" action="{{ route('leaves.status') }}" class="flex flex-wrap gap-2 mb-4">
                            <input type="hidden" name="view" value="{{ $view }}">
                            <select name="department_id" class="border p-2 rounded">
                                <option value="">All Departments</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="leave_type_id" class="border p-2 rounded">
                                <option value="">All Leave Types</option>
                                @foreach ($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="sort_days" class="border p-2 rounded">
                                <option value="">Sort by Days Left</option>
                                <option value="asc" {{ request('sort_days') == 'asc' ? 'selected' : '' }}>Fewest Days Left</option>
                                <option value="desc" {{ request('sort_days') == 'desc' ? 'selected' : '' }}>Most Days Left</option>
                            </select>

                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
                        </form>

                         <table id="leaveExportTable" class="table table-striped table-hover text-center">

                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2">Staff Name</th>
                                    <th class="border px-4 py-2">Staff ID</th>
                                    <th class="border px-4 py-2">Department</th>
                                    <th class="border px-4 py-2">Leave Type</th>
                                    @if ($view === 'upcoming')
                                        <th class="border px-4 py-2">Leave Start Date</th>
                                    @else
                                        <th class="border px-4 py-2">Expected Return</th>
                                        <th class="border px-4 py-2">Days Left</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leaves as $leave)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $leave->user->name }}</td>
                                        <td class="border px-4 py-2">{{ $leave->user->staff_id ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2">{{ $leave->user->department->name ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2">{{ $leave->leaveType->name ?? 'N/A' }}</td>

                                        @if ($view === 'upcoming')
                                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($leave->start_date)->format('Y-m-d') }}</td>
                                        @else
                                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($leave->end_date)->addDay()->format('Y-m-d') }}</td>
                                            <td class="border px-4 py-2">{{ $leave->user->leaveBalance->days_left ?? 0 }}</td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $view === 'upcoming' ? 5 : 6 }}" class="border px-4 py-2 text-center text-gray-500">No staff found</td>

                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $leaves->withQueryString()->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>
    

    @include('layouts.js')
</body>

</html>
