<div class="container mt-6 mx-auto">
    <h2 class="text-xl font-bold mb-4 bg-white">Pending Visitor Approvals</h2>

    <table class="min-w-full bg-white border border-gray-200 shadow rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border-b text-left">Visitor</th>
                <th class="px-4 py-2 border-b text-left">Purpose</th>
                <th class="px-4 py-2 border-b text-left">More Detail</th>
                <th class="px-4 py-2 border-b text-left">Status</th>
                <th class="px-4 py-2 border-b text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($visitLogs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border-b">{{ $log->visitor->full_name }}</td>
                    <td class="px-4 py-2 border-b">{{ $log->purpose }}</td>
                    <td class="px-4 py-2 border-b">{{ $log->visit_reason_detail }}</td>
                    <td class="px-4 py-2 border-b">{{ $log->approval_status }}</td>
                    <td class="px-4 py-2 border-b text-center">
    @if($log->approval_status === 'pending')
        <!-- Approve Button -->
        <button wire:click="approve({{ $log->id }})"
            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
            Approve
        </button>

        <!-- Reject Button -->
        <button wire:click="$set('rejectReason', '')" x-data
            x-on:click="$refs['modal-{{ $log->id }}'].showModal()"
            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded ml-2">
            Reject
        </button>

        <!-- Modal -->
        <dialog x-ref="modal-{{ $log->id }}" class="rounded p-4 max-w-md w-full">
            <h3 class="text-lg font-semibold mb-2">Rejection Reason</h3>

            <label for="rejectReason-{{ $log->id }}" class="sr-only">Rejection Reason</label>

            <textarea id="rejectReason-{{ $log->id }}" name="rejectReason" wire:model.defer="rejectReason"
                class="w-full p-2 border rounded my-2" rows="3" placeholder="Enter reason for rejection">
            </textarea>

            <div class="mt-3 flex gap-2">
                <button wire:click="reject({{ $log->id }})"
                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                    Confirm Reject
                </button>
                <button x-on:click="$el.close()"
                    class="bg-gray-300 hover:bg-gray-400 px-3 py-1 rounded">
                    Cancel
                </button>
            </div>
        </dialog>
    @else
        <span class="text-gray-500 italic">No actions available</span>
    @endif
</td>

                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                        No pending visitor approvals.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
