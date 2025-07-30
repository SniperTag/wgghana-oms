<!-- Scripts loaded at the end -->
<!-- jQuery -->
{{--  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  --}}
<script src="{{ asset('js/lib/jquery.min.js') }}"></script>
<script src="{{ asset('js/codebase.app.min.js') }}"></script>
<script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/plugins/chart.js/chart.umd.js') }}"></script>

<script src="{{ asset('js/pages/op_auth_signin.min.js') }}"></script>
<script src="{{ asset('js/pages/be_pages_dashboard.min.js') }}"></script>




<!-- Bootstrap (if needed) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables + Buttons -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Moment + DateRangePicker -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- Pusher + Laravel Echo -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/8.2.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>


@livewireScripts
@stack('modals')

<!-- Inline Echo notification script (lightweight, waits for DOM load) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Echo !== 'undefined' && "{{ auth()->id() }}") {
            Echo.private('App.Models.User.{{ auth()->id() }}')
                .notification((notification) => {
                    toastr.info(notification.message, notification.title);
                });
        }
    });
</script>
<script>
    // ✅ Initialize Laravel Echo with Pusher
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: "{{ config('broadcasting.connections.pusher.key') }}",
        cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
        forceTLS: true // Only keep this if you have SSL enabled
    });

    // ✅ Listen to your channel and event
    window.Echo.channel('break-status')
        .listen('BreakStatusUpdated', (e) => {
            toastr.info(`${e.name} is ${e.on_break ? 'on break' : 'back from break'}`);
            updateStaffBreakStatus(e.staff_id, e.on_break);
        });

    function updateStaffBreakStatus(staffId, onBreak) {
        const el = document.querySelector(`#staff-status-${staffId}`);
        if (el) {
            el.textContent = onBreak ? '🟡 On Break' : '🟢 Active';
            el.className = onBreak ? 'text-yellow-600' : 'text-green-600';
        }
    }

    // ✅ Toastr Settings
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-bottom-right",
        timeOut: "5000"
    };

    // ✅ Flash Messages
    @if (session('success'))
        toastr.success(@json(session('success')));
    @elseif (session('error'))
        toastr.error(@json(session('error')));
    @endif

    //attendanceTable dataTable initialization
    $(document).ready(function() {
        $('#attendanceTable').DataTable({
            responsive: true,
            scrollX: true,
            dom: 'Bfrtip',
              dom:
            "<'d-flex justify-content-between align-items-center mb-3 text-white'<'dataTables_filter'f><'dt-buttons'B>>" +
            "rt" +
            "<'d-flex justify-content-between align-items-center mt-3'<'dataTables_info'i><'dataTables_paginate'p>>",
            buttons: [
                'csv', 'excel', 'pdf'
            ],
        });
    });
</script>
