
<!-- Core Local Assets -->
<script src="{{ asset('build/assets/js/codebase.app.min.js') }}"></script>
{{-- <script src="{{ asset('build/assets/js/plugins/chart.js/chart.umd.js') }}"></script> --}}

<!-- Page Specific Scripts -->
<script src="{{ asset('build/assets/js/pages/op_auth_signin.min.js') }}"></script>
<script src="{{ asset('build/assets/js/pages/db_classic.min.js') }}"></script>
<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

   <!-- 2. Vite JS -->
    @vite('resources/js/app.js')

    <!-- Livewire Scripts -->
    @livewireScripts

<!-- Livewire Modals -->
@stack('modals')

   <!-- 1. Pass PHP variables to JS -->
    <script>
    window.SESSION_SUCCESS = @json(session('success'));
    window.SESSION_ERROR = @json(session('error'));

    document.addEventListener('livewire:load', () => {
        function initPlugins() {
            const attendanceTable = $('#attendanceTable');
            if(attendanceTable.length && !$.fn.DataTable.isDataTable(attendanceTable)){
                attendanceTable.DataTable({ /* ... */ });
            }


            if(window.SESSION_SUCCESS) toastr.success(window.SESSION_SUCCESS);
            if(window.SESSION_ERROR) toastr.error(window.SESSION_ERROR);
        }

        // Initial load
        initPlugins();

        // After Livewire updates
        Livewire.hook('message.processed', () => initPlugins());
    });


</script>
