<div class="modal fade" id="editLeaveBalanceModal" tabindex="-1" aria-labelledby="editLeaveBalanceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('admin.leave-balances.update', $balance->id) }}">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editLeaveBalanceModalLabel">
            Edit Leave Balance for {{ $balance->user->name }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="total_days" class="form-label">Total Days</label>
            <input type="number" name="total_days" id="total_days" class="form-control"
              value="{{ $balance->total_days }}" min="0" required>
          </div>

          <div class="mb-3">
            <label for="used_days" class="form-label">Used Days</label>
            <input type="number" name="used_days" id="used_days" class="form-control"
              value="{{ $balance->used_days }}" min="0" required>
          </div>

          <div class="mb-3">
            <label for="remaining_days" class="form-label">Remaining Days</label>
            <input type="number" name="remaining_days" id="remaining_days" class="form-control"
              value="{{ $balance->remaining_days }}" min="0" required>
          </div>

          <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="number" name="year" id="year" class="form-control"
              value="{{ $balance->year }}" min="2000" max="2100" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
