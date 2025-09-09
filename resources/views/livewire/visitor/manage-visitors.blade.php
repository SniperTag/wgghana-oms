<div id="page-container"
    class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

    <!-- Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Header -->
    @include('layouts.header')

    <!-- Main Container -->
    <div class="mt-3">
        <main id="main-container" class="content-full container-fluid">

            {{-- Statistics Row --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center shadow-sm" wire:poll.15s>
                        <div class="card-body">
                            <h6 class="text-muted">Transferred Visitors</h6>
                            <h3 class="fw-bold">{{ $transferCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Currently Checked In</h6>
                            <h3 class="fw-bold text-success">{{ $checkedInCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Checked Out Today</h6>
                            <h3 class="fw-bold text-danger">{{ $checkedOutCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Pending Approvals</h6>
                            <h3 class="fw-bold text-danger">{{ $waitingApprovalCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Search Card --}}
            {{-- Search Card - Simple Version --}}
            <div class="card mb-4">
                <div class="card-body">
                    {{-- Search Input --}}
                    <input type="text" class="form-control" placeholder="Phone or ID"
                        wire:model.live.debounce.300ms="searchTerm">

                    {{-- Loading Indicator --}}
                    <div wire:loading wire:target="searchTerm" class="mt-2">
                        <p class="text-muted">Searching<span class="loading-dots">...</span></p>
                    </div>

                    {{-- Search Results (hidden while loading) --}}
                    <div wire:loading.remove wire:target="searchTerm">
                        <ul class="list-group mt-2" @if (empty($searchResults)) style="display:none;" @endif>
                            @foreach ($searchResults as $visitor)
                                <li class="list-group-item list-group-item-action"
                                    wire:click="selectVisitor({{ $visitor->id }})">
                                    {{ $visitor->full_name }} - {{ $visitor->phone }} - {{ $visitor->visitor_uid }}
                                </li>
                            @endforeach
                        </ul>

                        {{-- No Results --}}
                        @if ($searchTerm && $searchResults->isEmpty())
                            <p class="text-muted mt-2">No visitors found.</p>
                        @endif
                    </div>
                </div>
            </div>

            <style>
                .loading-dots {
                    animation: loading 1.5s infinite;
                }

                @keyframes loading {

                    0%,
                    20% {
                        content: '.';
                    }

                    40% {
                        content: '..';
                    }

                    60%,
                    100% {
                        content: '...';
                    }
                }
            </style>

            {{-- Check-In Modal --}}
            <div wire:ignore.self class="modal fade" id="checkInModal" tabindex="-1" aria-hidden="true">

                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        @if ($selectedVisitor)
                            <div class="modal-header">
                                <h5 class="modal-title">Check In: {{ $selectedVisitor->full_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body bg-slate-400">
                                <form wire:submit.prevent="checkIn">
                                    {{-- Visitor Info --}}
                                    <div class="mb-3">
                                        <label>Visitor</label>
                                        <input type="text" class="form-control" readonly
                                            value="{{ $selectedVisitor->full_name }} — {{ $selectedVisitor->phone }}">
                                    </div>

                                    {{-- Host --}}
                                    <div class="mb-3">
                                        <label>Host</label>
                                        <select wire:model="host_id" class="form-control" required>
                                            <option value="">-- select host --</option>
                                            @foreach ($hostsList as $host)
                                                <option value="{{ $host->id }}">{{ $host->name }}
                                                    ({{ $host->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('host_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Visitor Type --}}
                                    <div class="mb-3">
                                        <label>Visitor Type</label>
                                        <select wire:model="visitor_type_id" class="form-control" required>
                                            <option value="">-- select type --</option>
                                            @foreach ($visitorTypes as $vt)
                                                <option value="{{ $vt->id }}">{{ $vt->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('visitor_type_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Purpose --}}
                                    <div class="mb-3">
                                        <label>Purpose / Reason</label>
                                        <input type="text" class="form-control" wire:model.defer="purpose" required>
                                        @error('purpose')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Footer --}}
                                    <div class="modal-footer border-top-0">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Check In Visitor</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Transfer Modal --}}
            @if ($showTransferModal)
                <div class="modal fade show" id="transferModal" style="display: block;" tabindex="-1"
                    aria-hidden="false">
                    <div class="modal-backdrop fade show"></div>
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            @if ($selectedVisitLog)
                                <div class="modal-header">
                                    <h5 class="modal-title">Transfer Visitor:
                                        {{ $selectedVisitLog->visitor->full_name ?? 'Unknown' }}</h5>
                                    <button type="button" class="btn-close" wire:click="closeTransferModal"></button>
                                </div>
                                <div class="modal-body">
                                    <form wire:submit.prevent="transferVisitor">
                                        <div class="mb-3">
                                            <label>Transfer To Host</label>
                                            <select wire:model="transferToHostId" class="form-control" required>
                                                <option value="">-- Select host --</option>
                                                @foreach ($hostsList as $host)
                                                    <option value="{{ $host->id }}">{{ $host->name }}
                                                        ({{ $host->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('transferToHostId')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label>Reason (optional)</label>
                                            <textarea wire:model.defer="transferReason" class="form-control" rows="3"></textarea>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                wire:click="closeTransferModal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Transfer Visitor</button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif


                {{-- Recent Visitors Table --}}
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">Recent Visit Logs</div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Purpose</th>
                                    <th>Host</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($visitLogs as $vl)
                                    <tr>
                                        <td>{{ $vl->visitor->full_name ?? 'Unknown' }}</td>
                                        <td>{{ $vl->purpose }}</td>
                                        <td>{{ $vl->host->name ?? '—' }}</td>
                                        <td>
                                            @switch($vl->status)
                                                @case('checked_in')
                                                    <span class="badge bg-success">Checked In</span>
                                                @break

                                                @case('checked_out')
                                                    <span class="badge bg-secondary">Checked Out</span>
                                                @break

                                                @case('pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @break

                                                @case('cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @break

                                                @default
                                                    <span class="badge bg-info">Unknown</span>
                                            @endswitch
                                        </td>

                                        <td>
                                            @if ($vl->status !== 'checked_out')
                                                <button wire:click="checkOut({{ $vl->id }})"
                                                    class="btn btn-sm btn-outline-danger">Check Out</button>
                                            @endif
                                            @if (in_array($vl->status, ['pending', 'checked_in']))
                                                <button wire:click="openTransferModal({{ $vl->id }})"
                                                    class="btn btn-sm btn-warning">
                                                    Transfer
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No records found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

            </main>
        </div>
    </div>
