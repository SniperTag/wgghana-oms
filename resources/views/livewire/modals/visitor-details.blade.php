<div>
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" wire:key="visitor-modal-{{ $visitor->id ?? 'loading' }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Visitor Details</h5>
                        <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        @if ($visitor)
                            <div class="d-flex mb-3">
                                <img src="{{ $visitor->photo ? asset('storage/'.$visitor->photo) : asset('images/default-avatar.png') }}" 
                                     class="rounded-circle border me-3" width="70" height="70" alt="Visitor photo">
                                <div>
                                    <h4 class="mb-1">{{ $visitor->full_name }}</h4>
                                    <p class="text-muted mb-0">{{ $visitor->email ?? 'No Email' }}</p>
                                    <p class="text-muted mb-0">Phone: {{ $visitor->phone ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6"><strong>Visitor UID:</strong> {{ $visitor->visitor_uid }}</div>
                                <div class="col-md-6"><strong>ID Number:</strong> {{ $visitor->id_number ?? 'N/A' }}</div>
                                <div class="col-md-6"><strong>Company:</strong> {{ $visitor->company ?? 'N/A' }}</div>
                                <div class="col-md-6">
                                    <strong>Status:</strong>
                                    <span class="badge bg-{{ $visitor->status === 'checked-in' ? 'success' : ($visitor->status === 'checked-out' ? 'danger' : 'secondary') }}">
                                        {{ ucfirst($visitor->status) }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Loading visitor details...</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('showModal', false)">Close</button>

                        @if ($visitor)
                            @if ($visitor->status !== 'checked-in')
                                <button wire:click="checkIn" wire:loading.attr="disabled" class="btn btn-success">
                                    <span wire:loading.remove><i class="bi bi-box-arrow-in-right"></i> Check In</span>
                                    <span wire:loading>Processing...</span>
                                </button>
                            @endif

                            @if ($visitor->status === 'checked-in')
                                <button wire:click="checkOut" wire:loading.attr="disabled" class="btn btn-danger">
                                    <span wire:loading.remove><i class="bi bi-box-arrow-right"></i> Check Out</span>
                                    <span wire:loading>Processing...</span>
                                </button>
                            @endif

                            <button wire:click="transfer" class="btn btn-primary">
                                <i class="bi bi-arrow-left-right"></i> Transfer
                            </button>

                            <button wire:click="editVisitor" class="btn btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-backdrop fade show" wire:click="$set('showModal', false)"></div>
    @endif
</div>
