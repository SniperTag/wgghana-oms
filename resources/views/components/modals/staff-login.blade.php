{{--  <!-- Trigger Button (example) -->
<!-- <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#clockInModal">Clock In</button> -->  --}}

<!-- Clock In Modal -->
<div class="modal fade" id="clockInModal" tabindex="-1" aria-labelledby="clockInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-white">
            <form id="clock-in-form" method="POST" action="{{ route('attendance.handle') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="clockInModalLabel">🕒 Clock In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

                        <div id="barcodeScannerContainer" style="display:none; width: 100%; height: 240px; border: 1px solid #ccc; border-radius: 5px;"></div>

                        <!-- Hidden face snapshot -->
                        <input type="hidden" id="face_snapshot" name="face_snapshot">
                        <input type="hidden" name="action" value="check_in">

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
                    <button type="submit" class="btn btn-success w-100">✅ Clock In</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include Webcam.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<!-- Include html5-qrcode for barcode scanning -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    // Webcam setup
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90
    });

    const clockInModal = document.getElementById('clockInModal');
    const barcodeScannerContainer = document.getElementById('barcodeScannerContainer');
    const staffIdInput = document.getElementById('staff_id');
    let html5QrcodeScanner;

    // Show modal: attach webcam and focus staff ID input
    clockInModal.addEventListener('shown.bs.modal', () => {
        Webcam.attach('#camera');
        staffIdInput.focus();
    });

    // Hide modal: reset webcam and barcode scanner
    clockInModal.addEventListener('hidden.bs.modal', () => {
        Webcam.reset();
        document.getElementById('snapshot-preview').classList.add('d-none');
        stopBarcodeScanner();
        barcodeScannerContainer.style.display = 'none';
    });

    // Take face snapshot
    function takeSnapshot() {
        Webcam.snap(function(data_uri) {
            document.getElementById('face_snapshot').value = data_uri;
            document.getElementById('snapshot-img').src = data_uri;
            document.getElementById('snapshot-preview').classList.remove('d-none');
        });
    }

    // Barcode Scan Button
    document.getElementById('scanBarcodeBtn').addEventListener('click', () => {
        if (barcodeScannerContainer.style.display === 'none') {
            barcodeScannerContainer.style.display = 'block';
            startBarcodeScanner();
        } else {
            stopBarcodeScanner();
            barcodeScannerContainer.style.display = 'none';
        }
    });

    // Start Barcode Scanner
    function startBarcodeScanner() {
        if (!html5QrcodeScanner) {
            html5QrcodeScanner = new Html5Qrcode("barcodeScannerContainer");
        }

        html5QrcodeScanner.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            console.error("Error starting barcode scanner:", err);
        });
    }

    // Stop Barcode Scanner
    function stopBarcodeScanner() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().then(() => {
                // Scanner stopped successfully
            }).catch(err => {
                console.error("Error stopping scanner:", err);
            });
        }
    }

    // Barcode scan success
    function onScanSuccess(decodedText, decodedResult) {
        staffIdInput.value = decodedText;
        stopBarcodeScanner();
        barcodeScannerContainer.style.display = 'none';
    }

    // Barcode scan failure (optional)
    function onScanFailure(error) {
        // Can ignore or log failures
        // console.warn(`Scan failed: ${error}`);
    }
</script>
