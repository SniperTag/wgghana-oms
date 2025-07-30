<div id="page-container"
    class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

    <!-- Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Header -->
    @include('layouts.header')

    <!-- Main Container -->
    <main id="main-container" class="content-full">
        <div class="page-container d-flex flex-column min-vh-100">

            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
                <div class="container">
                    <a class="navbar-brand text-dark fw-bold" href="index.html">
                        <i class="bi bi-people-fill me-2 text-dark"></i>
                        Visitors Manager
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link active text-dark fw-bold" href="#">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="{{ route('appointment.booking') }}">Appointments</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="add-visitor.html">
                                    <i class="bi bi-plus-circle me-1"></i> Walk-in
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="book-appointment.html">
                                    <i class="bi bi-calendar-plus me-1"></i> Book Appointment
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Content Container -->
            <div class="container mt-4">

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card text-gray-600">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Visitors</h6>
                                        <h2 class="mb-0" id="totalVisitors">{{ $visitorCount }}</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-people fs-1 text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-gray-600">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Checked In</h6>
                                        <h2 class="mb-0" id="checkedInVisitors">{{ $checkedInVisitors }}</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-check-circle fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-gray-600">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Pending</h6>
                                        <h2 class="mb-0">{{ $pendingVisitors }}</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-clock fs-1 text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-gray-600">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Checked Out</h6>
                                        <h2 class="mb-0" id="checkedOutVisitors">{{ $checkedOutVisitors }}</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-x-circle fs-1 text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Appointments -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Today's Appointments</h5>
                    </div>
                    <div class="card-body">
                        @if ($todayAppointments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Visitor Name</th>
                                            <th>Schedule Date & Time</th>
                                            <th>Host Name</th>
                                            <th>Purpose</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($todayAppointments as $index => $appt)
                                            @php
                                                $badgeColor = match ($appt->status) {
                                                    'pending' => 'primary',
                                                    'approved' => 'success',
                                                    'rejected' => 'danger',
                                                    'cancelled' => 'secondary',
                                                    'expired' => 'warning',
                                                    'completed' => 'dark',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $appt->visitors->pluck('full_name')->join(', ') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($appt->scheduled_at)->format('d M Y, g:i A') }}
                                                </td>
                                                <td>{{ $appt->user->name ?? 'N/A' }}</td>
                                                <td>{{ $appt->purpose ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $badgeColor }}">
                                                        {{ ucfirst($appt->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No appointments scheduled for today.</p>
                        @endif
                    </div>
                </div>


                <!-- Visitors List -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Visitors</h5>
                    </div>
                    <div class="card-body">
                        <!-- Search and Filter -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control" placeholder="Search visitors..."
                                        wire:model.debounce.500ms="searchTerm" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="statusFilter" id="filterAll"
                                        value="all" wire:model="statusFilter" checked>
                                    <label class="btn btn-outline-primary" for="filterAll">All</label>

                                    <input type="radio" class="btn-check" name="statusFilter" id="filterPending"
                                        value="pending" wire:model="statusFilter">
                                    <label class="btn btn-outline-primary" for="filterPending">Pending</label>

                                    <input type="radio" class="btn-check" name="statusFilter" id="filterCheckedIn"
                                        value="checked-in" wire:model="statusFilter">
                                    <label class="btn btn-outline-primary" for="filterCheckedIn">Checked In</label>

                                    <input type="radio" class="btn-check" name="statusFilter"
                                        id="filterCheckedOut" value="checked-out" wire:model="statusFilter">
                                    <label class="btn btn-outline-primary" for="filterCheckedOut">Checked Out</label>
                                </div>
                            </div>
                        </div>

                        <!-- Visitors Table -->
                        <div class="table-responsive bg-white rounded shadow p-3">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Group UID</th>
                                        <th>UID</th>
                                        <th>Email</th>
                                        <th>Company</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($visitors as $visitor)
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'checked-in' => 'success',
                                                'checked-out' => 'danger',
                                                'active' => 'primary',
                                            ];
                                            $badgeColor = $statusColors[$visitor->status] ?? 'secondary';
                                            $isGroup = $visitor->group_members->count() > 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <img src="{{ $visitor->photo ? asset('storage/' . $visitor->photo) : asset('images/default-avatar.png') }}"
                                                    class="rounded-circle border" width="40" height="40">
                                            </td>
                                            <td>
                                                <strong>{{ $visitor->full_name }}</strong>
                                                @if ($isGroup)
                                                    <span class="badge bg-info ms-1">Group
                                                        ({{ $visitor->group_members->count() }})</span>
                                                @endif
                                                <br>
                                                <small
                                                    class="text-muted">{{ ucfirst($visitor->gender ?? 'N/A') }}</small>
                                            </td>
                                            <td>
                                                @if ($visitor->group_uid)
                                                    <span class="badge bg-secondary">{{ $visitor->group_uid }}</span>
                                                @else
                                                    <span class="badge bg-secondary">N/A</span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-secondary">{{ $visitor->visitor_uid }}</span>
                                            </td>
                                            <td>{{ $visitor->email ?? 'N/A' }}</td>
                                            <td>{{ $visitor->company ?? 'N/A' }}</td>
                                            <td>{{ $visitor->phone ?? 'N/A' }}</td>
                                            <td><span
                                                    class="badge bg-{{ $badgeColor }}">{{ ucfirst($visitor->status) }}</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary"
                                                    wire:click="viewDetails({{ $visitor->id }})">
                                                    <i class="bi bi-eye"></i>
                                                </button>

                                                @if ($isGroup)
                                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                                        data-bs-target="#groupMembersModal{{ $visitor->id }}">
                                                        <i class="bi bi-people"></i>
                                                    </button>

                                                    <button class="btn btn-sm btn-outline-secondary"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#groupAccordion{{ $visitor->id }}">
                                                        <i class="bi bi-chevron-down"></i>
                                                    </button>
                                                @endif

                                                <button class="btn btn-sm btn-outline-danger"
                                                    wire:click="checkOut({{ $visitor->id }})">
                                                    <i class="bi bi-box-arrow-right"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        @if ($isGroup)
                                            <!-- Inline Accordion Row -->
                                            <tr class="collapse bg-light" id="groupAccordion{{ $visitor->id }}">
                                                <td colspan="8">
                                                    <h6 class="fw-bold mb-2">Group Members:</h6>
                                                    <ul class="list-group">
                                                        @foreach ($visitor->group_members as $member)
                                                            <li
                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                {{ $member->full_name }}
                                                                <span
                                                                    class="badge bg-secondary">{{ $member->visitor_uid }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            </tr>

                                            <!-- Modal -->
                                            <div class="modal fade" id="groupMembersModal{{ $visitor->id }}"
                                                tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Group Members for
                                                                {{ $visitor->full_name }}</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <ul class="list-group">
                                                                @foreach ($visitor->group_members as $member)
                                                                    <li
                                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                                        {{ $member->full_name }}
                                                                        <span
                                                                            class="text-muted">{{ $member->phone ?? 'N/A' }}</span>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No visitors found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-3">
                                {{ $visitors->links() }}
                            </div>
                        </div>

                    </div>
                </div>

            </div> <!-- container mt-4 -->
        </div> <!-- page-container -->

        <!-- Visitor Details Modal -->
        <div class="modal fade" id="visitorModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Visitor Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="visitorModalBody">
                        {{-- This content can be Livewire-rendered or filled by JS on event --}}
                        @if ($selectedVisitor)
                            <div>
                                <p><strong>Name:</strong> {{ $selectedVisitor->full_name }}</p>
                                <p><strong>Email:</strong> {{ $selectedVisitor->email ?? 'N/A' }}</p>
                                <p><strong>Company:</strong> {{ $selectedVisitor->company ?? 'N/A' }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($selectedVisitor->status) }}</p>
                                <!-- Add more visitor details as needed -->
                            </div>
                        @else
                            <p>Select a visitor to see details.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="transferBtn"
                            wire:click="showTransferModal">
                            <i class="bi bi-arrow-left-right"></i> Transfer
                        </button>
                        <button type="button" class="btn btn-success" id="checkInBtn" style="display: none;">
                            <i class="bi bi-box-arrow-in-right"></i> Check In
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="checkOutBtn"
                            style="display: none;">
                            <i class="bi bi-box-arrow-right"></i> Check Out
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transfer Modal -->
        {{--  <div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-arrow-left-right me-2"></i>Transfer Visitor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="transferVisitor">
                            <div class="mb-3">
                                <label for="newHost" class="form-label">Transfer to Host *</label>
                                <select class="form-select" id="newHost" wire:model.defer="transferHost" required>
                                    <option value="">Select new host</option>
                                    <option value="Sarah Johnson">Sarah Johnson</option>
                                    <option value="Mike Wilson">Mike Wilson</option>
                                    <option value="Lisa Chen">Lisa Chen</option>
                                    <option value="David Brown">David Brown</option>
                                    <option value="Emily Davis">Emily Davis</option>
                                </select>
                                @error('transferHost') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="transferReason" class="form-label">Reason for Transfer *</label>
                                <textarea class="form-control" id="transferReason" rows="3" wire:model.defer="transferReason" placeholder="Enter reason for transfer" required></textarea>
                                @error('transferReason') <small class="text-danger">{{ $message }}</small> @enderror
                                <div class="mt-2">
                                    <small class="text-muted">Quick reasons:</small>
                                    <div class="mt-1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary me-1"
                                            wire:click.prevent="$set('transferReason', 'Meeting room change')">Meeting room change</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary me-1"
                                            wire:click.prevent="$set('transferReason', 'Host unavailable')">Host unavailable</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            wire:click.prevent="$set('transferReason', 'Department transfer')">Department transfer</button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer px-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-arrow-left-right"></i> Transfer Visitor
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>  --}}

        @include('livewire.modals.visitor-details')


    </main>
    <!-- END Main Container -->
</div>
