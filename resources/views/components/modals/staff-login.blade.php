<div class="modal fade" id="staffLoginModal" tabindex="-1" aria-labelledby="staffLoginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('staff.checkin') }}">
            @csrf
            <input type="hidden" name="action" value="check_in">
            <div class="modal-content bg-white">
                <div class="modal-header">
                    {{--  <img src="{{ asset('build/assets/image/Office_logo.jpg') }}" alt="Logo" style="width: 50px; height: auto; margin-right: 10px;">  --}}
                    <h5 class="modal-title center" id="staffLoginModalLabel">STAFF Clock-in</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Staff ID -->
                    <div class="mb-3">
                        <label for="staff_id" class="form-label">Enter ID</label>
                        <input type="text" name="staff_id" class="form-control" id="staff_id" required>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="verifyBtn">Verify</button>
                        <div id="staff_error" class="text-danger mt-2" style="display: none;"></div>
                        <div id="staff_info" class="text-success mt-2" style="display: none;"></div>
                    </div>

                    <!-- PIN (Hidden Initially) -->
                    <div class="mb-3 d-none" id="pin_section">
                        <label for="clockin_pin" class="form-label">Clock-in PIN</label>
                        <input type="password" name="pin" class="form-control" id="clockin_pin" minlength="4" maxlength="6">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Login</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Custom CSS -->
<style>
    .bg-sky-blue {
        background-color: #87CEEB; /* Sky Blue */
    }
    .center {
        text-align: center;
    }
    .modal-header {
        padding: 1rem 2rem;
    }
</style>
