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
                <div>

                    {{-- Search area --}}
                    <div class="d-flex justify-content-center align-items-center min-vh-100"
                        style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">

                        <div class="card shadow-lg border-0 p-4"
                            style="max-width: 500px; width: 100%; border-radius: 1rem;">

                            {{-- Title --}}
                            <h4 class="text-center mb-4 fw-bold text-primary">Search Visitor</h4>

                            {{-- Search Field --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Phone or ID Number</label>
                                <input type="text" class="form-control form-control-lg"
                                    placeholder="Enter phone or ID number" wire:model.defer="searchTerm"
                                    wire:keydown.enter.prevent="searchVisitor">
                            </div>

                            {{-- Search Button --}}
                            <div class="text-center">
                                <button wire:click="searchVisitor"
                                    class="btn btn-primary btn-lg w-100 position-relative" style="border-radius: .5rem;"
                                    wire:loading.attr="disabled">

                                    <span wire:loading.remove wire:target="searchVisitor">
                                        🔍 Search
                                    </span>

                                    <span wire:loading wire:target="searchVisitor">
                                        <div class="spinner-border spinner-border-sm text-light me-2" role="status">
                                        </div>
                                        Checking...
                                    </span>
                                </button>

                                {{-- Clear Button --}}
                                <button wire:click="clearSearch">Clear</button>

                            </div>

                            {{-- Search Message --}}
                            @if ($searchMessage)
                                <div class="alert alert-info text-center mt-3 mb-0" style="border-radius: .5rem;">
                                    {{ $searchMessage }}
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- Visitor quick preview --}}
                   @if($selectedVisitor)
    <div class="p-4 bg-green-100 rounded">
        <p><strong>Name:</strong> {{ $selectedVisitor->name }}</p>
        <p><strong>Phone:</strong> {{ $selectedVisitor->phone }}</p>
        <p><strong>ID Number:</strong> {{ $selectedVisitor->id_number }}</p>
        <button 
            wire:click="openCheckInModal" 
            class="px-4 py-2 mt-2 text-white bg-blue-600 rounded">
            Check In
        </button>
    </div>
@endif


                    {{-- Check-in Modal --}}
                    <div wire:ignore.self class="modal fade" id="checkInModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Visitor Check-in</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <form wire:submit.prevent="checkIn">

                                        {{-- Visitor details (read-only) --}}
                                        <div class="mb-3">
                                            <label class="form-label">Visitor</label>
                                            <input type="text" class="form-control" readonly
                                                value="{{ $selectedVisitor ? $selectedVisitor->name . ' — ' . $selectedVisitor->phone : '' }}">
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
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

                                            <div class="col-md-6 mb-3">
                                                <label>Visitor Type</label>
                                                <select wire:model="visitor_type_id" class="form-control" required>
                                                    <option value="">-- select type --</option>
                                                    @foreach ($visitorTypes as $vt)
                                                        <option value="{{ $vt->id }}">{{ $vt->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('visitor_type_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label>Purpose / Reason</label>
                                            <input type="text" class="form-control" wire:model.defer="purpose"
                                                placeholder="e.g. Meeting, Delivery, Interview" required>
                                            @error('purpose')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label>Details</label>
                                            <textarea class="form-control" wire:model.defer="visit_reason_detail" rows="3"></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label>Badge Number</label>
                                                <input type="text" class="form-control"
                                                    wire:model.defer="badge_number" required>
                                                @error('badge_number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>Location</label>
                                                <input type="text" class="form-control" wire:model.defer="location"
                                                    required>
                                                @error('location')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>Appointment (optional)</label>
                                                <input type="text" class="form-control"
                                                    wire:model.defer="appointment_id"
                                                    placeholder="Appointment id or code">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label>Remarks (optional)</label>
                                            <input type="text" class="form-control" wire:model.defer="remarks">
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Check In Visitor</button>
                                        </div>

                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Recent visit logs --}}
                    <div class="mt-4 container" style="max-width: 500px;">
                        <h6>Recent visit logs</h6>
                        <ul class="list-group">
                            @foreach ($visitLogs as $vl)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $vl->visitor->name ?? 'Unknown' }}</strong>
                                        <div><small>{{ $vl->purpose }} • Host: {{ $vl->host->name ?? '—' }}</small>
                                        </div>
                                    </div>
                                    <div>
                                        @if ($vl->status !== 'checked_out')
                                            <button wire:click="checkOut({{ $vl->id }})"
                                                class="btn btn-sm btn-outline-danger">
                                                Check Out
                                            </button>
                                        @else
                                            <span class="badge bg-secondary">Checked out</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>

            </div>
        </main>
    </div>
</div>

{{-- Bootstrap 5 Modal open/close listeners --}}
@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            window.addEventListener('open-checkin-modal', function() {
                let modalEl = document.getElementById('checkInModal');
                let modal = new bootstrap.Modal(modalEl);
                modal.show();
            });

            window.addEventListener('close-checkin-modal', function() {
                let modalEl = document.getElementById('checkInModal');
                let modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });
        });
    </script>
@endpush
