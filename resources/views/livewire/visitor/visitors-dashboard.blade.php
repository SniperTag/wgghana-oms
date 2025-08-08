<div id="page-container"
    class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

    <!-- Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Header -->
    @include('layouts.header')

    <!-- Main Container -->
    <div>
        <main id="main-container" class="content-full">
            <div class="page-container d-flex flex-column min-vh-100">


                <div class="bg-white shadow rounded mb-6 px-4 py-3 flex justify-between items-center container mt-3">
                    <!-- Right: User Profile -->
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-700 hidden sm:inline">{{ Auth::user()->name }}</span>
                        <img src="{{ Auth::user()->profile_photo_url ?? asset('images/default-avatar.png') }}"
                            alt="User Profile" class="h-10 w-10 rounded-full object-cover border border-gray-300">
                        <h2 class="text-lg font-bold text-gray-700">Visitors Management Dashboard</h2>

                    </div>
                    <!-- Left: Navigation Buttons -->
                    <div class="flex flex-wrap gap-2 items-center">
                        <a href="{{ route('book.appointment') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                            Book Appointment
                        </a>
                        <a href="{{ route('visitor.registration') }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                            Register Visitor
                        </a>
                        <a href="{{ route('manage.visitors') }}"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm">
                            Visitor Manager
                        </a>
                    </div>


                </div>

                <div class="container mt-4">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-100 text-blue-800 p-4 rounded shadow">
                            <h3 class="text-sm font-semibold">Individual Visitors</h3>
                            <p class="text-3xl">{{ $summary['individual_count'] }}</p>
                        </div>
                        <div class="bg-green-100 text-green-800 p-4 rounded shadow">
                            <h3 class="text-sm font-semibold">Groups</h3>
                            <p class="text-3xl">{{ $summary['group_count'] }}</p>
                        </div>
                        <div class="bg-purple-100 text-purple-800 p-4 rounded shadow">
                            <h3 class="text-sm font-semibold">Total Visitors</h3>
                            <p class="text-3xl">{{ $summary['total_visitors'] }}</p>
                        </div>
                    </div>

                    <div class="container mx-auto py-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-semibold uppercase">Visitor Appointments</h2>
                            <div>
                                <button wire:click="switchTo('today')"
                                    class="btn btn-sm {{ $view === 'today' ? 'btn-primary' : 'btn-outline' }}">
                                    Today
                                </button>
                                <button wire:click="switchTo('upcoming')"
                                    class="btn btn-sm {{ $view === 'upcoming' ? 'btn-primary' : 'btn-outline' }}">
                                    Upcoming
                                </button>
                            </div>
                        </div>

                        @if ($view === 'today')
                            <h4 class="text-lg font-medium mb-2">Today's Appointments</h4>
                            <table class="table table-bordered w-full text-sm">
                                <thead>
                                    <tr>
                                        <th>Visitor</th>
                                        <th>Phone</th>
                                        <th>Host</th>
                                        <th>Department</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($todayAppointments as $appointment)
                                        <tr>
                                            <td>{{ $appointment->visitor_name }}</td>
                                            <td>{{ $appointment->visitor_phone }}</td>
                                            <td>{{ $appointment->host->name ?? 'N/A' }}</td>
                                            <td>{{ $appointment->department->name ?? 'N/A' }}</td>
                                            <td>{{ $appointment->time->format('h:i A') }}</td>
                                            <td>{{ ucfirst($appointment->status) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No appointments today.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @else
                            <h4 class="text-lg font-medium mb-2">Upcoming Appointments</h4>
                            <table class="table table-bordered w-full text-sm">
                                <thead>
                                    <tr>
                                        <th>Visitor</th>
                                        <th>Phone</th>
                                        <th>Host</th>
                                        <th>Department</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($upcomingAppointments as $appointment)
                                        <tr>
                                            <td>{{ $appointment->visitor_name }}</td>
                                            <td>{{ $appointment->visitor_phone }}</td>
                                            <td>{{ $appointment->host->name ?? 'N/A' }}</td>
                                            <td>{{ $appointment->department->name ?? 'N/A' }}</td>
                                            <td>{{ $appointment->date->format('d M, Y') }}</td>
                                            <td>{{ $appointment->time->format('h:i A') }}</td>
                                            <td>{{ ucfirst($appointment->status) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No upcoming appointments.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif
                    </div>


                    <div class="flex gap-4 mb-4">
                        <input type="text" wire:model.debounce.500ms="search"
                            placeholder="Search by name, email, company..." class="border rounded p-2 flex-grow" />
                        <select wire:model="status" class="border rounded p-2">
                            <option value="active">Active</option>
                            <option value="banned">Banned</option>
                        </select>
                        <div wire:loading wire:target="search, status" class="flex items-center gap-2 text-blue-600">
                            <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z" />
                            </svg>
                            <span>Loading...</span>
                        </div>


                    </div>

                    <h2 class="text-xl font-semibold mb-2">Individual Visitors</h2>
                    <div class="overflow-auto bg-white shadow rounded mb-5 table-responsive">
                        <table id="visitorsTable" class="w-full text-sm display nowrap">

                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-2 text-left">#</th>
                                    <th class="p-2 text-left">UID</th>
                                    <th class="p-2 text-left">Full Name</th>
                                    <th class="p-2 text-left">Email</th>
                                    <th class="p-2 text-left">Phone</th>
                                    <th class="p-2 text-left">Company</th>
                                    <th class="p-2 text-left">Status</th>
                                    <th class="p-2 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($individualVisitors as $visitor)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-2">{{ $loop->iteration }}</td>
                                        <td class="p-2">{{ $visitor->visitor_uid }}</td>
                                        <td class="p-2">{{ $visitor->full_name }}</td>
                                        <td class="p-2">{{ $visitor->email }}</td>
                                        <td class="p-2">{{ $visitor->phone }}</td>
                                        <td class="p-2">{{ $visitor->company }}</td>
                                        <td class="p-2 capitalize">{{ $visitor->status }}</td>
                                        <td class="p-2 space-x-2">
                                            <div class="relative inline-block text-left" x-data="{ open: false }">
                                                <button @click="open = !open" type="button"
                                                    class="inline-flex justify-center w-full px-2 py-1 text-sm font-medium text-gray-700 hover:text-blue-600 focus:outline-none">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>

                                                <div x-show="open" @click.away="open = false"
                                                    class="absolute z-10 right-0 mt-2 w-32 origin-top-right bg-white border border-gray-200 rounded shadow-lg"
                                                    x-cloak>
                                                    <button wire:click="editVisitor({{ $visitor->id }})"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fas fa-edit mr-1"></i> Edit
                                                    </button>
                                                    <button wire:click="confirmDelete({{ $visitor->id }})"
                                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                        <i class="fas fa-trash mr-1"></i> Delete
                                                    </button>
                                                </div>
                                            </div>


                                        </td>
                                        {{-- <td>
                                            <button x-data @click="$wire.emit('showBadge', {{ $visitor->id }})"
                                                class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">
                                                <i class="fas fa-id-badge mx-1"></i>
                                            </button>




                                        </td> --}}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="p-4 text-center text-gray-500">No visitors found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div wire:loading wire:target="showVisitorDetails"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="loader">Loading...</div>
                        </div>
                    </div>

                    <div>
                        @if ($showModal && $selectedVisitor)
                            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                wire:keydown.escape="closeModal" wire:click.self="closeModal" tabindex="0"
                                style="outline:none;">
                                <div class="bg-white rounded shadow-lg w-11/12 max-w-lg p-6 relative" wire:click.stop>
                                    <button wire:click="closeModal"
                                        class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-xl font-bold"
                                        aria-label="Close modal">&times;</button>

                                    <h3 class="text-xl font-bold mb-4">Visitor Details
                                        ({{ $selectedVisitor->visitor_uid }})</h3>
                                    <p><strong>Full Name:</strong> {{ $selectedVisitor->full_name }}</p>
                                    <p><strong>Email:</strong> {{ $selectedVisitor->email }}</p>
                                    <p><strong>Phone:</strong> {{ $selectedVisitor->phone }}</p>
                                    <p><strong>Gender:</strong> {{ $selectedVisitor->gender }}</p>
                                    <p><strong>Date Of Birth:</strong> {{ $selectedVisitor->date_of_birth }}</p>
                                    <p><strong>Company:</strong> {{ $selectedVisitor->company }}</p>
                                    <p><strong>Address:</strong> {{ $selectedVisitor->address }}</p>
                                    <p><strong>Nationality:</strong> {{ $selectedVisitor->nationality }}</p>

                                    <div class="mt-4 space-x-3">
                                        <button wire:click="requestCheckIn({{ $selectedVisitor->id }})"
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                                            Request Check-in
                                        </button>

                                        <!-- If you track active visitLog, you can show Check-out button here -->
                                        @php
                                            $activeVisitLog = \App\Models\VisitLog::where(
                                                'visitor_id',
                                                $selectedVisitor->id,
                                            )
                                                ->where('status', 'checked_in')
                                                ->latest()
                                                ->first();
                                        @endphp
                                        @if ($activeVisitLog)
                                            <button wire:click="checkOut({{ $activeVisitLog->id }})"
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                                                Check-out
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div>
                        <table id="visitorsTable" class="w-full text-sm display nowrap">

                        </table>
                        {{ $individualVisitors->links() }}
                        @if ($individualVisitors->isEmpty())
                            <p class="text-gray-400">No visitors found matching your search.</p>
                        @endif

                    </div>


                    <h2 class="text-xl font-semibold mb-2">Group Visitors</h2>
                    @php use Illuminate\Support\Str; @endphp

                    <div class="overflow-auto bg-white shadow rounded table-responsive">
                        <table id="groupsTable"
                            class="w-full text-sm mb-4 table table-striped table-hover display nowrap">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-2 text-left">#</th>
                                    <th class="p-2 text-left">Group UID</th>
                                    <th class="p-2 text-left">Group Leader</th>
                                    <th class="p-2 text-left">Company</th>
                                    <th class="p-2 text-left">Members</th>
                                    <th class="p-2 text-left">Status</th>
                                    <th class="p-2 text-left">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($groupVisitors as $index => $group)
                                    <!-- Leader Row -->
                                    <tr class="border-b hover:bg-gray-50" data-group="{{ $group['group_uid'] }}">
                                    <td class="p-2">{{ $loop->iteration }}</td>
                                    <td class="p-2">{{ $group['group_uid'] }}</td>
                                    <td class="p-2">{{ $group['leader']->full_name }}</td>
                                    <td class="p-2">{{ $group['leader']->company }}</td>
                                    <td class="p-2">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded">
                                            {{ $group['members_count'] }}
                                            {{ Str::plural('Member', $group['members_count']) }}
                                        </span>
                                    </td>
                                    <td class="p-2">
                                        <span
                                            class="inline-block px-2 py-1 rounded text-xs font-semibold
                            {{ $group['leader']->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $group['leader']->status }}
                                        </span>
                                    </td>
                                    <td class="p-2">
                                        <button class="text-blue-600 hover:underline"
                                            wire:click="toggleGroup({{ $index }})">
                                            {{ in_array($index, $expandedGroups) ? 'Hide Members' : 'View Members' }}
                                        </button>
                                    </td>
                                    </tr>

                                    <!-- Member Search Input -->
                                    @if (in_array($index, $expandedGroups))
                                        <tr>
                                            <td colspan="6" class="px-4 py-2 bg-gray-100">
                                                <input type="text" placeholder="Search group members..."
                                                    wire:model.debounce.500ms="groupSearchTerms.{{ $group['group_uid'] }}"
                                                    class="border rounded px-3 py-1 w-full text-sm" />
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Members (Except Leader) -->
                                    @if (in_array($index, $expandedGroups))
                                        @foreach ($group['members'] as $member)
                                            @continue($member->is_leader)
                                            <tr class="border-b bg-gray-50 text-sm"
                                                data-group="{{ $group['group_uid'] }}">
                                                <td class="p-2 pl-6">↳ {{ $member->group_uid }}</td>
                                                <td class="p-2">{{ $member->full_name }}</td>
                                                <td class="p-2">{{ $member->company }}</td>
                                                <td class="p-2">-</td>
                                                <td class="p-2">
                                                    <span
                                                        class="inline-block px-2 py-1 rounded text-xs font-semibold
                                    {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $member->status }}
                                                    </span>
                                                </td>
                                                <td class="p-2">
                                                    <button onclick="window.printGroup('{{ $group['group_uid'] }}')"
                                                        class="text-gray-600 hover:underline mr-2">
                                                        Print
                                                    </button>
                                                    <button wire:click="exportGroup('{{ $group['group_uid'] }}')"
                                                        class="text-green-600 hover:underline">
                                                        Export
                                                    </button>
                                                    <button onclick="exportGroup('{{ $group['group_uid'] }}')"
                                                        class="text-green-600 hover:underline">
                                                        Export Group
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-gray-400 italic">
                                            No group visitors found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>



        </main>

        <!-- END Main Container -->
    </div>


</div>



{{-- 
<script>
    function exportGroup(groupUid) {
        const table = $('#groupsTable').DataTable();
        table.search(groupUid).draw(); // filter by group UID

        setTimeout(() => {
            table.button('.buttons-excel').trigger(); // export filtered
            table.search('').draw(); // reset filter
        }, 500);
    }
</script> --}}
@push('scripts')
    <script>
        function initVisitorsTable() {
            // Destroy existing instance if it exists
            if ($.fn.dataTable.isDataTable('#visitorsTable')) {
                $('#visitorsTable').DataTable().destroy();
            }

            // Initialize DataTable
            $('#visitorsTable').DataTable({
                responsive: true,
                scrollX: true,
                dom: "<'d-flex justify-content-between align-items-center mb-3'<'dataTables_filter'f><'dt-buttons'B>>" +
                    "rt" +
                    "<'d-flex justify-content-between align-items-center mt-3'<'dataTables_info'i><'dataTables_paginate'p>>",
                buttons: [{
                        extend: 'csv',
                        className: 'btn btn-sm btn-primary',
                        exportOptions: {
                            rows: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-sm btn-success',
                        exportOptions: {
                            rows: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-sm btn-danger',
                        exportOptions: {
                            rows: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-secondary',
                        exportOptions: {
                            rows: ':visible'
                        }
                    }
                ]
            });
        }

        // Init on first load and after Livewire updates
        document.addEventListener('livewire:load', () => {
            initVisitorsTable();

            Livewire.hook('message.processed', () => {
                initVisitorsTable();
            });
        });
    </script>
@endpush
