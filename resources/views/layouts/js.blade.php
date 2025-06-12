<div>

    <!-- Core JS: Codebase and Plugins -->
    <script src="{{ asset('build/assets/js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/codebase.app.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/plugins/chart.js/chart.umd.js') }}"></script>

    <!-- Page Specific JS -->
    <script src="{{ asset('build/assets/js/pages/op_auth_signin.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/pages/be_pages_dashboard.min.js') }}"></script>

    <!-- DataTables & Buttons -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Show clock-out section if just checked in -->


    <!-- Main Script -->
    <script>
        $(document).ready(function() {
                    // Initialize DataTable
                    const table = $('#dataTable').DataTable({
                        responsive: true,
                        autoWidth: false,
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'copyHtml5',
                                text: 'Copy',
                                className: 'btn btn-sm btn-secondary'
                            },
                            {
                                extend: 'excelHtml5',
                                text: 'Excel',
                                className: 'btn btn-sm btn-success'
                            },
                            {
                                extend: 'csvHtml5',
                                text: 'CSV',
                                className: 'btn btn-sm btn-info'
                            },
                            {
                                extend: 'pdfHtml5',
                                text: 'PDF',
                                className: 'btn btn-sm btn-danger',
                                orientation: 'landscape',
                                pageSize: 'A4'
                            },
                            {
                                extend: 'print',
                                text: 'Print',
                                className: 'btn btn-sm btn-primary'
                            }
                        ],
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Search users..."
                        },
                        initComplete: function() {
                            this.api().columns().every(function() {
                                const column = this;
                                // Add filtering only to specific columns (adjust indices)
                                if ([ /* indices */ ].includes(column.index())) {
                                    const select = $(
                                            '<select class="form-select form-select-sm"><option value="">Filter</option></select>'
                                        )
                                        .appendTo($(column.header()).empty())
                                        .on('change', function() {
                                            const val = $.fn.dataTable.util.escapeRegex($(this)
                                                .val());
                                            column.search(val ? '^' + val + '$' : '', true, false)
                                                .draw();
                                        });

                                    column.data().unique().sort().each(function(d) {
                                        if (d) select.append('<option value="' + d + '">' + d +
                                            '</option>');
                                    });
                                }
                            });
                        }
                    });

                    $('#dataTable_wrapper .dt-buttons').addClass('float-end');
                    $('#dataTable_wrapper .dataTables_filter').addClass('float-start');

                    // Toastr Configuration
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        positionClass: "toast-bottom-right",
                        timeOut: "5000",
                        showDuration: "300",
                        hideDuration: "1000",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut"
                    };

                    // Flash Session Notifications
                    @if (session('success'))
                        toastr.success(@json(session('success')));
                    @elseif (session('error'))
                        toastr.error(@json(session('error')));
                    @endif

                    console.log("✅ Staff verification script loaded");


                    {{--  document.addEventListener('DOMContentLoaded',function() {
                        console.log("✅ Staff verification + face match script loaded");

                        const verifyBtn = document.getElementById('verifyBtn');
                        const staffIdInput = document.getElementById('staff_id');
                        const pinSection = document.getElementById('pin_section');
                        const webcamSection = document.getElementById('webcam_section');
                        const webcam = document.getElementById('webcam');
                        const snapshotCanvas = document.getElementById('snapshot');
                        const faceInput = document.getElementById('face_snapshot');
                        const submitBtn = document.getElementById('submitBtn');
                        const staffError = document.getElementById('staff_error');
                        const staffInfo = document.getElementById('staff_info');
                        const form = document.querySelector('form');

                        function startWebcam() {
                            if (navigator.mediaDevices.getUserMedia) {
                                navigator.mediaDevices.getUserMedia({
                                        video: true
                                    })
                                    .then(stream => {
                                        webcam.srcObject = stream;
                                    })
                                    .catch(err => {
                                        console.error("❌ Webcam access error:", err);
                                        toastr.error(
                                            "Unable to access webcam. Please check your browser settings.");
                                    });
                            } else {
                                toastr.warning("Webcam not supported on this device.");
                            }
                        }

                        if (!verifyBtn || !staffIdInput || !pinSection || !webcamSection || !webcam || !
                            snapshotCanvas || !faceInput || !submitBtn || !staffError || !staffInfo || !form) {
                            console.error("❌ One or more required elements not found in DOM.");
                            return;
                        }

                        function stopWebcam() {
                            let stream = webcam.srcObject;
                            if (stream) {
                                stream.getTracks().forEach(track => track.stop());
                            }
                            webcam.srcObject = null;
                        }

                        verifyBtn.addEventListener('click', function() {
                            const staffId = staffIdInput.value.trim();

                            if (!staffId) {
                                toastr.warning("⚠️ Please enter a Staff ID.");
                                return;
                            }

                            console.log(`🔍 Verifying Staff ID: ${staffId}`);

                            fetch(`/admin/attendance/verify/${encodeURIComponent(staffId)}`)
                                .then(response => {
                                    console.log("Raw response:", response);
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        pinSection.classList.remove('d-none');
                                        webcamSection.classList.remove('d-none');
                                        staffError.style.display = 'none';
                                        staffInfo.innerHTML = data.message.replace(/\n/g, '<br>');
                                        staffInfo.style.display = 'block';
                                        submitBtn.disabled = false;
                                        startWebcam();
                                    } else {
                                        pinSection.classList.add('d-none');
                                        webcamSection.classList.add('d-none');
                                        staffError.textContent = data.message;
                                        staffError.style.display = 'block';
                                        staffInfo.style.display = 'none';
                                        submitBtn.disabled = true;
                                    }
                                })
                                .catch(error => {
                                    console.error('❌ Error during fetch:', error);
                                    pinSection.classList.add('d-none');
                                    webcamSection.classList.add('d-none');
                                    staffInfo.style.display = 'none';
                                    staffError.textContent = "Something went wrong. Please try again.";
                                    staffError.style.display = 'block';
                                    submitBtn.disabled = true;
                                });
                        });

                        form.addEventListener('submit', function(e) {
                            e.preventDefault();

                            // Capture snapshot
                            snapshotCanvas.width = webcam.videoWidth;
                            snapshotCanvas.height = webcam.videoHeight;

                            const ctx = snapshotCanvas.getContext('2d');
                            ctx.drawImage(webcam, 0, 0, webcam.videoWidth, webcam.videoHeight);

                            const imageData = snapshotCanvas.toDataURL('image/png');
                            faceInput.value = imageData;

                            const staffId = staffIdInput.value.trim();

                            if (!staffId) {
                                toastr.error("Staff ID missing during face verification.");
                                return;
                            }

                            // Start facial verification
                            toastr.info("🔍 Verifying face...");

                            fetch(`/admin/attendance/verify-face`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        staff_id: staffId,
                                        face_snapshot: imageData
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        toastr.success(data.message || "✅ Face verified!");
                                        console.log("🎉 Face match confidence:", data.confidence);
                                        form.submit(); // Proceed with form submit
                                    } else {
                                        toastr.error(data.message ||
                                            "❌ Face verification failed. Try again.");
                                    }
                                })
                                .catch(err => {
                                    console.error("❌ Error during face verification:", err);
                                    toastr.error("Error verifying face. Please try again.");
                                });
                        });
                    });
  --}}



    </script>



</div>
