{{-- <style>
    /* General input style */
    .dataTables_filter input {
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        width: 100%;
        max-width: 250px;
        font-size: 0.875rem;
        background-color: #ffffff;
        color: #000000;
        border: 1px solid #ccc;
    }

    @media (prefers-color-scheme: dark) {
        .dataTables_filter input {
            background-color: #1f2937 !important; /* dark:bg-gray-800 */
            color: #f9fafb !important;           /* text-white */
            border-color: #374151 !important;    /* dark:border-gray-700 */
        }

        .dataTables_wrapper .dataTables_filter label {
            color: #f3f4f6 !important;
        }

        .dt-buttons button {
            background-color: #374151 !important;
            color: #f9fafb !important;
            border-color: #4b5563 !important;
        }
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .dataTables_filter,
        .dt-buttons {
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100% !important;
            margin-bottom: 0.75rem;
        }

        .dataTables_filter input {
            width: 100% !important;
        }

        .dt-buttons button {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style> --}}

<div class="p-4 bg-white dark:bg-gray-900 rounded shadow mt-3 mb-3">
    {{-- Header --}}
    <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
            Step Out Summary Report
        </h2>

        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">
            <select wire:model="filter"
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2 text-lg">
                <option value="this_week">This Week</option>
                <option value="this_month">This Month</option>
            </select>

            <input type="text" id="stepOutSearch" placeholder="Search Step Out..."
                class="p-2 border rounded w-full sm:w-auto dark:bg-gray-800 dark:text-white dark:border-gray-700" />
        </div>
    </div>

    {{-- Step Out Table --}}
    <div class="overflow-x-auto">
        <table class="w-full table-auto text-sm text-left text-gray-800 dark:text-gray-100" id="StepOutReportTable">
            <thead class="bg-gray-100 dark:bg-gray-800 uppercase text-gray-700 dark:text-gray-200">
                <tr>
                    <th>Staff</th>
                    <th>Reason</th>
                    <th>Stepped Out At</th>
                    <th>Returned At</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stepOutRecords as $entry)
                    <tr class="border-b dark:border-gray-700">
                        <td>{{ $entry->user->name }}</td>
                        <td>{{ $entry->reason ?? '—' }}</td>
                        <td>{{ $entry->stepped_out_at->format('D h:i A') }}</td>
                        <td>{{ $entry->returned_at ? $entry->returned_at->format('D h:i A') : '—' }}</td>
                        <td>
                            @if ($entry->returned_at)
                                {{ $entry->stepped_out_at->diffForHumans($entry->returned_at, true) }}
                            @else
                                Ongoing
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-gray-500 dark:text-gray-400 text-center py-3">No data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $stepOutRecords->links('pagination::bootstrap-5', ['pageName' => 'stepoutPage']) }}
    </div>

    {{-- Break Section --}}
    <div class="mt-5">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">
            Break Sessions Report
        </h2>

        <input type="text" id="breakSearch" placeholder="Search Breaks..."
            class="mb-2 p-2 border float-right rounded w-full md:w-1/3 dark:bg-gray-800 dark:text-white dark:border-gray-700" />

        <div class="overflow-x-auto">
            <table class="w-full table-auto text-sm text-left text-gray-800 dark:text-gray-100" id="BreakReportTable">
                <thead class="bg-gray-100 dark:bg-gray-800 uppercase text-gray-700 dark:text-gray-200">
                    <tr>
                        <th>Staff</th>
                        <th>Break Type</th>
                        <th>Started At</th>
                        <th>Ended At</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($breakRecords as $break)
                        <tr class="border-b dark:border-gray-700">
                            <td>{{ $break->user->name }}</td>
                            <td>{{ $break->break_type }}</td>
                            <td>{{ $break->started_at->format('D h:i A') }}</td>
                            <td>{{ $break->ended_at ? $break->ended_at->format('D h:i A') : '—' }}</td>
                            <td>
                                @if ($break->ended_at)
                                    {{ $break->started_at->diffForHumans($break->ended_at, true) }}
                                @else
                                    Ongoing
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-gray-500 dark:text-gray-400 text-center py-3">No break data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $breakRecords->links('pagination::bootstrap-5', ['pageName' => 'breakPage']) }}
        </div>
    </div>
</div>

{{-- DataTables Scripts --}}
<script>
    $(document).ready(function () {
        let stepOutTable = $('#StepOutReportTable').DataTable({
            responsive: true,
            scrollX: true,
            dom:
                "<'d-flex justify-between items-center mb-3'<'dataTables_filter'f><'dt-buttons'B>>" +
                "rt" +
                "<'d-flex justify-between items-center mt-3'<'dataTables_info'i><'dataTables_paginate'p>>",
            buttons: ['csv', 'excel', 'pdf']
        });

        $('#stepOutSearch').on('keyup', function () {
            stepOutTable.search(this.value).draw();
        });

        let breakTable = $('#BreakReportTable').DataTable({
            responsive: true,
            scrollX: true,
            dom:
                "<'d-flex justify-between items-center mb-3'<'dataTables_filter'f><'dt-buttons'B>>" +
                "rt" +
                "<'d-flex justify-between items-center mt-3'<'dataTables_info'i><'dataTables_paginate'p>>",
            buttons: ['csv', 'excel', 'pdf']
        });

        $('#breakSearch').on('keyup', function () {
            breakTable.search(this.value).draw();
        });
    });
</script>
