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

                <div class="container mx-auto px-4 py-6" x-data="{ approveModal: false, rejectModal: false, selectedLeaveId: null }">
    <h2 class="text-2xl font-bold mb-4">Pending HR Leave Approvals</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($leaves->count())
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-xs font-semibold uppercase">
                    <tr>
                        <th class="py-3 px-4">Staff Name</th>
                        <th class="py-3 px-4">Leave Type</th>
                        <th class="py-3 px-4">From</th>
                        <th class="py-3 px-4">To</th>
                        <th class="py-3 px-4">Reason</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaves as $leave)
                        <tr class="border-b">
                            <td class="py-2 px-4">{{ $leave->user->name }}</td>
                            <td class="py-2 px-4">{{ $leave->leave_type->name ?? '-' }}</td>
                            <td class="py-2 px-4">{{ $leave->start_date }}</td>
                            <td class="py-2 px-4">{{ $leave->end_date }}</td>
                            <td class="py-2 px-4">{{ $leave->reason }}</td>
                            <td class="py-2 px-4 font-semibold">{{ $leave->status }}</td>
                            <td class="py-2 px-4">
                                <button @click="approveModal = true; selectedLeaveId = {{ $leave->id }}" 
                                        class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded">
                                    Approve
                                </button>
                                <button @click="rejectModal = true; selectedLeaveId = {{ $leave->id }}" 
                                        class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded ml-2">
                                    Reject
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $leaves->links() }}
        </div>
    @else
        <div class="text-gray-600 mt-4">
            No leave requests pending HR approval.
        </div>
    @endif

    <!-- Approve Modal -->
    <div x-show="approveModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-sm">
            <h3 class="text-lg font-semibold mb-4">Confirm Approval</h3>
            <p class="mb-4">Are you sure you want to approve this leave request?</p>
            <form :action="`/leave/hr-approve/${selectedLeaveId}`" method="POST">
                @csrf
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="approveModal = false" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Approve</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div x-show="rejectModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-sm">
            <h3 class="text-lg font-semibold mb-4">Confirm Rejection</h3>
            <p class="mb-4">Are you sure you want to reject this leave request?</p>
            <form :action="`/leave/hr-reject/${selectedLeaveId}`" method="POST">
                @csrf
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="rejectModal = false" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
            </div>

    </div>
    <!-- END Page Content -->
    </main>
    {{-- Main section --}}

    <!-- END Main Container -->
    @include('layouts.footer')
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
