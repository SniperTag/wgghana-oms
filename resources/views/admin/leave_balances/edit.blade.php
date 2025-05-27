<a href="{{ route('leave_balances.create', $user->id) }}" class="btn btn-sm btn-info">
    Add Leave Balance
</a>

<div class="modal fade" id="editLeaveBalanceModal" tabindex="-1" aria-labelledby="editLeaveBalanceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('admin.leave_balances.update', $balance->id) }}">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Leave Balance for {{ $balance->user->name }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Total Days</label>
            <input type="number" name="total_days" class="form-control" value="{{ $balance->total_days }}" required>
          </div>
          <div class="mb-3">
            <label>Used Days</label>
            <input type="number" name="used_days" class="form-control" value="{{ $balance->used_days }}" required>
          </div>
          <div class="mb-3">
            <label>Remaining Days</label>
            <input type="number" name="remaining_days" class="form-control" value="{{ $balance->remaining_days }}" required>
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
