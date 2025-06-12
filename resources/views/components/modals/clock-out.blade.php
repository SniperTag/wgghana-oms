<!-- Clock Out Modal -->
<div class="modal fade" id="clockOutModal" tabindex="-1" aria-labelledby="clockOutModalLabel" aria-hidden="true">
    <div class="modal-dialog bg-white">
        <form method="POST" action="{{ route('attendance.handle') }}">
            @csrf
            <input type="hidden" name="staff_id" id="clockOutStaffId" value="">
            <input type="hidden" name="action" value="check_out">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clockOutModalLabel">Clock Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="pin" class="form-label">Enter PIN</label>
                        <input type="password" class="form-control" id="pin" name="pin" required maxlength="6" autofocus>
                    </div>

                    <div class="mb-3 d-none" id="notesContainer">
                        <label for="notes" class="form-label">Reason for Early Clock-Out</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3" placeholder="Please provide a reason..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Clock Out</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    // Expected format: "HH:mm" or full timestamp "YYYY-MM-DD HH:mm:ss"
    function openClockOutModal(staffId, scheduledClockOutTime = null) {
    scheduledClockOutTime = scheduledClockOutTime || '17:30'; // fallback if null
        document.getElementById('clockOutStaffId').value = staffId;

        // Parse time using JS
        const now = new Date();
        const currentTime = now.getHours() + ":" + now.getMinutes().toString().padStart(2, '0');

        let isEarly = false;
        if (scheduledClockOutTime) {
            const [scheduledHour, scheduledMin] = scheduledClockOutTime.split(":");
            const currentTotalMinutes = now.getHours() * 60 + now.getMinutes();
            const scheduledTotalMinutes = parseInt(scheduledHour) * 60 + parseInt(scheduledMin);

            if (currentTotalMinutes < scheduledTotalMinutes) {
                isEarly = true;
            }
        }

        // Show or hide the notes field
        const notesField = document.getElementById('notesContainer');
        if (isEarly) {
            notesField.classList.remove('d-none');
            document.getElementById('notes').setAttribute('required', true);
        } else {
            notesField.classList.add('d-none');
            document.getElementById('notes').removeAttribute('required');
        }

        var myModal = new bootstrap.Modal(document.getElementById('clockOutModal'));
        myModal.show();
    }
</script>
