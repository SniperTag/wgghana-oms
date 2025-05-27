{{--  <!-- Clock-Out Modal -->
<div class="modal fade" id="clockOutModal" tabindex="-1" aria-labelledby="clockOutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('staff.checkout') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="clockOutModalLabel">Confirm Clock-Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="pin" class="form-label">Enter PIN</label>
                        <input type="password" name="pin" class="form-control" id="pin" required placeholder="****">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Clock-Out</button>
                </div>
            </form>
        </div>
    </div>
</div>  --}}



<!-- Clock-Out Modal -->
<div class="modal fade" id="clockOutModal" tabindex="-1" aria-labelledby="clockOutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered bg-white">
        <div class="modal-content">
            <form action="{{ route('staff.checkout') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="clockOutModalLabel">Confirm Clock-Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="pin" class="form-label">Enter PIN</label>
                        <input type="password" name="pin" class="form-control" id="pin" required placeholder="****">
                    </div>

                    @if(now()->lessThan(now()->setTime(17, 0)))
                        <div class="mb-3">
                            <label for="check_out_reason" class="form-label">Reason for early clock-out</label>
                            <textarea name="check_out_reason" id="check_out_reason" class="form-control" rows="3" required placeholder="Provide your reason here..."></textarea>
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Clock-Out</button>
                </div>
            </form>
        </div>
    </div>
</div>
