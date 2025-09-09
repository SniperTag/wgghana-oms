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
                    <option value="{{ $host->id }}">{{ $host->name }} ({{ $host->email }})</option>
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
                    <option value="{{ $vt->id }}">{{ $vt->name }}</option>
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
            <input type="text" class="form-control" wire:model.defer="badge_number" readonly>
            @error('badge_number')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label>Location</label>
            <input type="text" class="form-control" wire:model.defer="location">
            @error('location')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div><!-- Check-In Modal -->
<div wire:ignore.self class="modal fade" id="checkInModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            @if ($selectedVisitor)
                <div class="modal-header">
                    <h5 class="modal-title">Check In: {{ $selectedVisitor->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="checkIn">
                        {{-- Visitor details (read-only) --}}
                        <div class="mb-3">
                            <label class="form-label">Visitor</label>
                            <input type="text" class="form-control" readonly
                                value="{{ $selectedVisitor->name }} — {{ $selectedVisitor->phone }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Host</label>
                                <select wire:model="host_id" class="form-control" required>
                                    <option value="">-- select host --</option>
                                    @foreach ($hostsList as $host)
                                        <option value="{{ $host->id }}">{{ $host->name }} ({{ $host->email }})</option>
                                    @endforeach
                                </select>
                                @error('host_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Visitor Type</label>
                                <select wire:model="visitor_type_id" class="form-control" required>
                                    <option value="">-- select type --</option>
                                    @foreach ($visitorTypes as $vt)
                                        <option value="{{ $vt->id }}">{{ $vt->name }}</option>
                                    @endforeach
                                </select>
                                @error('visitor_type_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Purpose / Reason</label>
                            <input type="text" class="form-control" wire:model.defer="purpose"
                                placeholder="e.g. Meeting, Delivery, Interview" required>
                            @error('purpose') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Details</label>
                            <textarea class="form-control" wire:model.defer="visit_reason_detail" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Badge Number</label>
                                <input type="text" class="form-control" wire:model.defer="badge_number" readonly>
                                @error('badge_number') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Location</label>
                                <input type="text" class="form-control" wire:model.defer="location">
                                @error('location') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Appointment (optional)</label>
                                <input type="text" class="form-control" wire:model.defer="appointment_id"
                                    placeholder="Appointment id or code">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Remarks (optional)</label>
                            <input type="text" class="form-control" wire:model.defer="remarks">
                        </div>

                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Check In Visitor</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Open check-in modal
    window.addEventListener('open-checkin-modal', () => {
        const modalEl = document.getElementById('checkInModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    });

    // Close check-in modal
    window.addEventListener('close-checkin-modal', () => {
        const modalEl = document.getElementById('checkInModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    });
});
</script>
@endpush


        <div class="col-md-4 mb-3">
            <label>Appointment (optional)</label>
            <input type="text" class="form-control" wire:model.defer="appointment_id"
                placeholder="Appointment id or code">
        </div>
    </div>

    <div class="mb-3">
        <label>Remarks (optional)</label>
        <input type="text" class="form-control" wire:model.defer="remarks">
    </div>

    <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Check In Visitor</button>
    </div>
</form>
