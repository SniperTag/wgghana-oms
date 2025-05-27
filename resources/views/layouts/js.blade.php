<div>
    <!-- Core JS: Codebase -->
    <script src="{{ asset('build/assets/js/codebase.app.min.js') }}"></script>

    <!-- jQuery (required before Bootstrap and plugins) -->
    <script src="{{ asset('build/assets/js/lib/jquery.min.js') }}"></script>

    <!-- jQuery Validation Plugin -->
    <script src="{{ asset('build/assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>


    <!-- Chart.js Plugin -->
    <script src="{{ asset('build/assets/js/plugins/chart.js/chart.umd.js') }}"></script>

    <!-- Custom Page JS (Signin Logic) -->
    <script src="{{ asset('build/assets/js/pages/op_auth_signin.min.js') }}"></script>

    <!-- Custom Page JS (Dashboard Logic) -->
    <script src="{{ asset('build/assets/js/pages/be_pages_dashboard.min.js') }}"></script>
    {{--  <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>  --}}

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Buttons extension -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>



   <script>
    $(document).ready(function () {
        let table = $('#dataTable').DataTable({
            responsive: true,
            autoWidth: false,
            dom: 'Bfrtip', // Buttons + Filtering input
            buttons: [
                {
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
            initComplete: function () {
                // Column filtering
                this.api().columns().every(function () {
                    var column = this;
                    if ([].includes(column.index())) { // Apply filter on selected columns
                        var select = $('<select class="form-select form-select-sm"><option value="">Filter</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column.data().unique().sort().each(function (d) {
                            if (d) {
                                select.append('<option value="' + d + '">' + d + '</option>')
                            }
                        });
                    }
                });
            }
        });

        // Move the search box and buttons to the correct places
        $('#dataTable_wrapper .dt-buttons').addClass('float-end');
        $('#dataTable_wrapper .dataTables_filter').addClass('float-start');
    });
</script>

</div>
