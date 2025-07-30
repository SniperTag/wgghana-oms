<div class="p-4">
    <div class="mb-4 border-b border-gray-200">
    <nav class="flex space-x-2">
        <button wire:click="setTab('pending')"
            class="px-4 py-2 rounded border transition-all duration-150
                   {{ $activeTab === 'pending' ? 'bg-blue-300 text-white border-blue-300' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Pending Approvals
        </button>

        <button wire:click="setTab('approved')"
            class="px-4 py-2 rounded border transition-all duration-150
                   {{ $activeTab === 'approved' ? 'bg-blue-300 text-white border-blue-300' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Approved Leaves
        </button>
    </nav>
</div>


    @if (session()->has('success'))
        <div class="text-green-600 mb-3">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="text-red-600 mb-3">{{ session('error') }}</div>
    @endif
    @if (session()->has('warning'))
        <div class="text-yellow-600 mb-3">{{ session('warning') }}</div>
    @endif

    @if ($activeTab === 'pending')
        <div class="bg-white rounded shadow p-4">
            <h2 class="text-lg font-semibold mb-3">Pending Approvals</h2>
            <table class="w-full text-sm bg-gray-100">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2">Staff</th>
                        <th class="p-2">Leave Type</th>
                        <th class="p-2">From</th>
                        <th class="p-2">To</th>
                        <th class="p-2">Supervisor Status</th>
                        <th class="p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingLeaves as $leave)
                        <tr class="border-b">
                            <td class="p-2">{{ $leave->user->name }}</td>
                            <td class="p-2">{{ $leave->leaveType->name }}</td>
                            <td class="p-2">{{ $leave->start_date }}</td>
                            <td class="p-2">{{ $leave->end_date }}</td>
                            <td class="p-2">{{ ucfirst($leave->supervisor_status ?? 'N/A') }}</td>
                            <td class="p-2">
                                <button wire:click="approve({{ $leave->id }})" class="px-2 py-1 bg-green-600 text-white rounded">Approve</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-3 text-center text-gray-500">No pending leaves.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $pendingLeaves->links() }}</div>
        </div>
    @elseif ($activeTab === 'approved')
        <div class="bg-white rounded shadow p-4">
            <h2 class="text-lg font-semibold mb-3">Approved Leaves</h2>
            <table class="w-full text-sm bg-gray-100">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2">#</th>
                        <th class="p-2">Staff</th>
                        <th class="p-2">Leave Type</th>
                        <th class="p-2">Dates</th>
                        <th class="p-2">Approved By</th>
                        <th class="p-2">Approved At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvedLeaves as $index => $leave)
                        <tr class="border-b">
                            <td class="p-2">{{ $loop->iteration }}</td>
                            <td class="p-2">{{ $leave->user->name }}</td>
                            <td class="p-2">{{ $leave->leaveType->name }}</td>
                            <td class="p-2">{{ $leave->start_date }} - {{ $leave->end_date }}</td>
                            <td class="p-2">
                                {{ optional(\App\Models\User::find($leave->approved_by))->name ?? 'N/A' }}
                            </td>
                            <td class="p-2">{{ $leave->approved_at }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-3 text-center text-gray-500">No approved leaves.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $approvedLeaves->links() }}</div>
        </div>
    @endif
</div>
