<!-- Clock In Modal -->
<div class="modal fade" id="clockInModal" tabindex="-1" aria-labelledby="clockInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-white">
            <form id="clock-in-form" method="POST" action="{{ route('attendance.handle') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="clockInModalLabel">🕒 Clock In / Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">
                    <!-- Left Column: Staff ID & Barcode Scan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="staff_id" class="form-label">Scan or Enter Staff ID</label>
                            <input type="text" id="staff_id" name="staff_id" class="form-control" required autofocus>
                        </div>

                        <button type="button" class="btn btn-primary mb-3" id="scanBarcodeBtn">
                            📡 Scan ID Barcode
                        </button>

                        <div id="barcodeScannerContainer"
                            style="display:none; width: 100%; height: 240px; border: 1px solid #ccc; border-radius: 5px;">
                        </div>

                        <input type="hidden" id="face_snapshot" name="face_snapshot">
                        <input type="hidden" name="action" id="attendance_action" value="check_in">

                        <button type="button" class="btn btn-secondary w-100 mt-3" onclick="takeSnapshot()">
                            📸 Capture Face
                        </button>
                    </div>

                    <!-- Right Column: Webcam -->
                    <div class="col-md-6">
                        <label class="form-label">📷 Face Camera</label>
                        <div id="camera" class="border rounded p-2"></div>
                        <div id="snapshot-preview" class="mt-2 d-none">
                            <p class="mb-1">✅ Face Captured:</p>
                            <img id="snapshot-img" src="" class="img-thumbnail">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-100" id="submitBtn">✅ Clock In</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include Dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    // ================== Setup ==================
    Webcam.set({ width: 320, height: 240, image_format: 'jpeg', jpeg_quality: 90 });

    const clockInModal = document.getElementById('clockInModal');
    const staffIdInput = document.getElementById('staff_id');
    const barcodeScannerContainer = document.getElementById('barcodeScannerContainer');
    const faceInput = document.getElementById('face_snapshot');
    const actionInput = document.getElementById('attendance_action');
    const submitBtn = document.getElementById('submitBtn');
    let html5QrcodeScanner;

    // Show modal: attach webcam
    clockInModal.addEventListener('shown.bs.modal', () => {
        Webcam.attach('#camera');
        staffIdInput.focus();
    });

    // Hide modal: reset webcam & scanner
    clockInModal.addEventListener('hidden.bs.modal', () => {
        Webcam.reset();
        document.getElementById('snapshot-preview').classList.add('d-none');
        stopBarcodeScanner();
        barcodeScannerContainer.style.display = 'none';
        document.getElementById('clock-in-form').reset();
        submitBtn.innerHTML = '✅ Clock In';
        submitBtn.disabled = false;
        actionInput.value = 'check_in';
    });

    // ================== Face Snapshot ==================
    function takeSnapshot() {
        Webcam.snap(data_uri => {
            faceInput.value = data_uri;
            document.getElementById('snapshot-img').src = data_uri;
            document.getElementById('snapshot-preview').classList.remove('d-none');
        });
    }

    // ================== Barcode Scanner ==================
    document.getElementById('scanBarcodeBtn').addEventListener('click', () => {
        if (barcodeScannerContainer.style.display === 'none') {
            barcodeScannerContainer.style.display = 'block';
            startBarcodeScanner();
        } else {
            stopBarcodeScanner();
            barcodeScannerContainer.style.display = 'none';
        }
    });

    function startBarcodeScanner() {
        if (!html5QrcodeScanner) html5QrcodeScanner = new Html5Qrcode("barcodeScannerContainer");

        html5QrcodeScanner.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            decodedText => {
                staffIdInput.value = decodedText.trim();
                stopBarcodeScanner();
                barcodeScannerContainer.style.display = 'none';
            },
            error => { console.warn(`Scan failed: ${error}`); }
        ).catch(err => console.error(err));
    }

    function stopBarcodeScanner() {
        if (html5QrcodeScanner) html5QrcodeScanner.stop().catch(err => console.error(err));
    }

    // ================== Form Submission ==================
    document.getElementById('clock-in-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const jsonData = {};
        formData.forEach((v, k) => jsonData[k] = v);

        submitBtn.disabled = true;
        submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Processing...`;

        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': jsonData._token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify(jsonData)
        })
        .then(async res => {
            let data;
            try { data = await res.json(); }
            catch { toastr.error("❌ Invalid server response."); throw new Error("Invalid JSON"); }
            if (!res.ok) { toastr.error(data.message || `❌ Server error: ${res.status}`); throw new Error("Server error"); }
            return data;
        })
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
                bootstrap.Modal.getInstance(clockInModal).hide();
                // Toggle action for next use
                actionInput.value = actionInput.value === 'check_in' ? 'check_out' : 'check_in';
            } else toastr.error(data.message);
        })
        .catch(err => { console.error(err); toastr.error('❌ Unexpected error'); })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = actionInput.value === 'check_in' ? '✅ Clock In' : '⏹️ Clock Out';
        });
    });
</script>
