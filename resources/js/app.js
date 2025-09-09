// -----------------------------
// Import global bootstrap.js
// -----------------------------
import './bootstrap';

// -----------------------------
// jQuery + Plugins
// -----------------------------
import $ from 'jquery';
window.$ = window.jQuery = $;

// jQuery Validation (needed for Codebase)
import 'jquery-validation';

// -----------------------------
// Toastr
// -----------------------------
import 'toastr/build/toastr.min.css';
import toastr from 'toastr';
window.toastr = toastr; // make it global so your Blade scripts can use it

// -----------------------------
// Chart.js
// -----------------------------
import Chart from 'chart.js/auto';
window.Chart = Chart; // Codebase scripts expect global Chart

// -----------------------------
// Select2 & DataTables
// -----------------------------
import 'select2/dist/js/select2.full.min.js';
import 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-buttons/js/buttons.html5.js';
import 'datatables.net-buttons/js/buttons.print.js';

// CSS
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css';
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css';
import 'select2/dist/css/select2.min.css';

// -----------------------------
// DOM Ready Initializations
// -----------------------------
document.addEventListener('livewire:load', function () {

    function initPlugins() {
        // ✅ Example DataTable init if you want:
        // $('.datatable').DataTable();

        // ✅ Toastr from session
        if (window.SESSION_SUCCESS) toastr.success(window.SESSION_SUCCESS);
        if (window.SESSION_ERROR) toastr.error(window.SESSION_ERROR);
    }

    // Initial load
    initPlugins();

    // After Livewire updates
    Livewire.hook('message.processed', (message, component) => {
        initPlugins();
    });

});
