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
            <div class="content mt-7  ">
                <div class="max-w-full mx-auto p-4 bg-white shadow rounded table-responsive">
                    <h2 class="text-xl font-semibold mb-4">Pending Leave Approvals</h2>

                    <table id="attendanceTable" class="w-full border bg-gray-100 text-sm">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="p-2">Staff</th>
                                <th class="p-2">Leave Type</th>
                                <th class="p-2">Dates</th>
                                <th class="p-2">Reason</th>
                                <th class="p-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $leave)
                                <tr class="border-b">
                                    <td class="p-2">{{ $leave->user->name }}</td>
                                    <td class="p-2">{{ $leave->leaveType->name }}</td>
                                    <td class="p-2">{{ $leave->start_date }} to {{ $leave->end_date }}</td>
                                    <td class="p-2">
                                        @if ($leave->supervisor_required)
                                            {{ ucfirst($leave->supervisor_status) }}
                                        @else
                                            Not Required
                                        @endif
                                    </td>
                                    <td class="p-2 flex space-x-2">
                                        @if ($leave->user_id !== auth()->id())
                                            @can('approve leave')
                                                {{-- Optional Gate Check --}}
                                                <form method="POST" action="{{ route('leaves.approve', $leave->id) }}">
                                                    @csrf
                                                    <button
                                                        class="bg-green-600 text-white px-2 py-1 rounded">Approve</button>
                                                </form>

                                                <form method="POST" action="{{ route('leaves.reject', $leave->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="rejection_reason"
                                                        value="HR/Admin rejection">
                                                    <button class="bg-red-600 text-white px-2 py-1 rounded">Reject</button>
                                                </form>
                                            @endcan
                                        @else
                                            <span class="text-gray-500 italic">Your request</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-gray-500">No pending approvals.</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $leaves->links() }}
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

    <!-- Select2 Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Bootstrap Bundle (Popper.js included) -->
    {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  --}}

</body>

</html>
