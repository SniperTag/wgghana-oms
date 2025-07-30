<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('layouts.head')
</head>

<body>
    <!-- Page Container -->


    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

        <!-- Sidebar -->

        {{-- Side bar dashboard start --}}

        @include('layouts.partials.sidebar')

        {{-- Side bar dashboard End --}}

        {{-- Side bar dashboard start --}}

        {{-- Side bar dashboard End --}}



        {{-- Header Section --}}
        @include('layouts.header')

        <!-- Main Container -->
        <main id="main-container content-full">
            <!-- Page Content -->
            <div class="content mt-7">
                <div class="row">
                    <!-- Row #1 -->




                    <!-- END Row #1 -->

                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <h1 class="text-2xl font-extrabold mb-4 font-san-serif text-uppercase">Request Leave
                                </h1>
                                <div class="max-w-6xl mx-auto p-4 bg-white shadow rounded">


                                    <form method="POST" action="{{ route('leaves.store') }}">
                                        @csrf

                                        <div class="mb-4">
                                            <label>Leave Type</label>
                                            <select name="leave_type_id" class="form-select w-full">
                                                @foreach ($leaveTypes as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label>Start Date</label>
                                                <input type="date" name="start_date" class="form-input w-full"
                                                    required>
                                            </div>
                                            <div>
                                                <label>End Date</label>
                                                <input type="date" name="end_date" class="form-input w-full"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <label>Reason</label>
                                            <textarea name="reason" rows="4" class="form-textarea w-full"></textarea>
                                        </div>
                                        <div id="attachment-group" class="mt-4 hidden">
                                            <label for="attachment">Attach Medical Report</label>
                                            <input type="file" name="attachment" id="attachment"
                                                class="form-input w-full">
                                        </div>


                                        <div class="mt-4">
                                            <button class="bg-blue-600 text-white px-4 py-2 rounded">Submit
                                                Request</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="max-w-4xl mx-auto mt-6">
                                    <h3 class="text-lg font-bold mb-2">Your Leave History</h3>

                                    <table class="w-full border-collapse bg-white shadow rounded">
                                        <thead>
                                            <tr class="bg-gray-200 text-sm text-left">
                                                <th class="p-2">Type</th>
                                                <th class="p-2">Dates</th>
                                                <th class="p-2">Days</th>
                                                <th class="p-2">Status</th>
                                                <th class="p-2">Supervisor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($leaves as $leave)
                                                <tr class="border-b">
                                                    <td class="p-2">{{ $leave->leaveType->name }}</td>
                                                    <td class="p-2">{{ $leave->start_date }} to
                                                        {{ $leave->end_date }}</td>
                                                    <td class="p-2">{{ $leave->days_requested }}</td>
                                                    <td class="p-2">{{ ucfirst($leave->status) }}</td>
                                                    <td class="p-2">
                                                        @if ($leave->supervisor)
                                                            {{ $leave->supervisor->name }}
                                                            ({{ $leave->supervisor_status }})
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- END Page Content -->
        </main>
        {{-- Main section --}}

        <!-- END Main Container -->
        @include('layouts.js')
    </div>
    <!-- END Page Container -->

    <script>
        $(document).ready(function() {
            $('#roles').select2({
                placeholder: "Select role(s)",
                width: '100%'
            });
        });

        $(document).ready(function() {
            $('#department').select2({
                placeholder: "Select department(s)",
                width: '100%'
            });
        });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const leaveTypeSelect = document.querySelector('[name="leave_type_id"]');
        const attachmentGroup = document.getElementById('attachment-group');

        function toggleAttachment() {
            const selectedText = leaveTypeSelect.options[leaveTypeSelect.selectedIndex].text.toLowerCase();
            attachmentGroup.classList.toggle('hidden', !selectedText.includes('sick'));
        }

        leaveTypeSelect.addEventListener('change', toggleAttachment);
        toggleAttachment(); // initial check on load
    });
</script>


    <!-- Select2 Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Bootstrap Bundle (Popper.js included) -->
    {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  --}}

</body>

</html>
