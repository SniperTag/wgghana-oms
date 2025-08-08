<div>
    <h2 class="text-xl font-bold mb-4">Pending Visitor Approvals</h2>

    @foreach($logs as $log)
        <div class="border p-4 rounded mb-4 bg-white shadow">
            <p><strong>Visitor:</strong> {{ $log->visitor->full_name }}</p>
            <p><strong>Purpose:</strong> {{ $log->purpose }}</p>
            <p><strong>Reason:</strong> {{ $log->visit_reason_detail }}</p>

            <div class="mt-2">
                <button wire:click="approve({{ $log->id }})" class="bg-green-600 text-white px-3 py-1 rounded">Approve</button>

                <button wire:click="$set('rejectReason', '')" x-data x-on:click="$refs['modal-{{ $log->id }}'].showModal()" class="bg-red-600 text-white px-3 py-1 rounded">Reject</button>

                <!-- Modal -->
                <dialog x-ref="modal-{{ $log->id }}" class="rounded p-4">
                    <h3 class="text-lg font-semibold">Rejection Reason</h3>
                    <textarea wire:model.defer="rejectReason" class="w-full p-2 border rounded my-2" rows="3"></textarea>
                    <button wire:click="reject({{ $log->id }})" class="bg-red-600 text-white px-3 py-1 rounded">Confirm Reject</button>
                    <button x-on:click="$el.close()" class="ml-2">Cancel</button>
                </dialog>
            </div>
        </div>
    @endforeach
</div>
