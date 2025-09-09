<!-- Clock Out Modal -->
<div class="modal fade" id="clockOutModal" tabindex="-1" aria-labelledby="clockOutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="clockOutForm" onsubmit="event.preventDefault(); confirmClockOut();" role="form">
            @csrf
            <input type="hidden" name="action" value="check_out">
            <input type="hidden" id="staffIdHidden" value="{{ Auth::user()->staff_id ?? '' }}">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clockOutModalLabel">Clock Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- PIN Input -->
                    <div class="mb-3">
                        <label for="clockOutPin" class="form-label">Enter PIN</label>
                        <input type="password" id="clockOutPin" class="form-control" name="clockin_pin" required
                            maxlength="6" autocomplete="off" placeholder="Enter your 4-6 digit PIN">
                    </div>

                    <!-- Early Clock-Out Notes -->
                    <div class="mb-3 d-none" id="notesContainer">
                        <label for="notes" class="form-label">Reason for Early Clock-Out</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3" placeholder="Provide your reason here..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="clockOutSubmitBtn">⏹️ Clock Out</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openClockOutModal(scheduledClockOutTime = '17:30') {
        const pinInput = document.getElementById('clockOutPin');
        const notesInput = document.getElementById('notes');
        const notesContainer = document.getElementById('notesContainer');

        pinInput.value = '';
        notesInput.value = '';
        notesContainer.classList.add('d-none');
        notesInput.removeAttribute('required');

        const now = new Date();
        const [cutoffHour, cutoffMin] = scheduledClockOutTime.split(":").map(Number);
        const cutoffMinutes = cutoffHour * 60 + cutoffMin;
        const currentMinutes = now.getHours() * 60 + now.getMinutes();

        // Show notes if clocking out early
        if (currentMinutes < cutoffMinutes) {
            notesContainer.classList.remove('d-none');
            notesInput.setAttribute('required', 'required');
        }

        const modal = new bootstrap.Modal(document.getElementById('clockOutModal'));
        modal.show();
    }

    function confirmClockOut() {
        const pin = document.getElementById('clockOutPin').value.trim();
        const notes = document.getElementById('notes').value.trim() || '';
        const csrfToken = document.querySelector('input[name="_token"]').value;
        const staffIdValue = document.getElementById('staffIdHidden').value.trim();
        const submitBtn = document.getElementById('clockOutSubmitBtn');

        if (!staffIdValue) return toastr.error("❌ Staff ID not found.");
        if (!pin || pin.length < 4) return toastr.error("❌ Please enter a valid PIN.");
        if (submitBtn.disabled) return; // prevent double submit

        submitBtn.disabled = true;
        submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status"></span> Clocking Out...`;

        fetch("{{ route('attendance.handle') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    staff_id: staffIdValue,
                    action: "check_out",
                    clockin_pin: pin,
                    notes: notes
                })
            })
            .then(async res => {
                let data;
                try {
                    data = await res.json();
                } catch {
                    toastr.error("❌ Invalid server response.");
                    throw new Error("Invalid JSON");
                }
                if (!res.ok) {
                    toastr.error(data?.message || `❌ Server error: ${res.status}`);
                    throw new Error('Server error');
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    toastr.success(data.message || "✅ Clocked out successfully.");
                    bootstrap.Modal.getInstance(document.getElementById('clockOutModal')).hide();

                    // Update clock button dynamically
                    const clockButton = document.getElementById('clockButton');
                    if (clockButton) {
                        clockButton.innerText = '⏯️ Clock In';
                        clockButton.dataset.action = 'check_in';
                    }
                } else {
                    toastr.error(data.message || "❌ Clock-out failed.");
                }
            })
            .catch(err => {
                console.error("Clock-out error:", err);
                toastr.error("❌ Unexpected error occurred.");
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '⏹️ Clock Out';
            });
    }
</script>
