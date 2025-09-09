<div>
    @php
        $statusName = auth()->user()->status_name ?? 'Available';
        $statusColor = match ($statusName) {
            'Available' => 'green-600',
            'Stepped Out' => 'red-600',
            'On Break' => 'yellow-600',
            default => 'gray-600',
        };
    @endphp

    <div class="flex items-center gap-3">
        @if (!$currentlySteppedOut && !$currentlyOnBreak)
            <button type="button" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-yellow-700"
                data-bs-toggle="modal" data-bs-target="#stepOutModal">
                Step Out / Break
            </button>
            <span class="font-semibold text-{{ $statusColor }}">{{ $statusName }}</span>
        @elseif($currentlySteppedOut)
            <button wire:click="returnBack" wire:loading.attr="disabled"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Return
            </button>
            <span class="text-red-600 font-semibold">Stepped Out</span>
        @elseif($currentlyOnBreak)
            <button wire:click="endBreak" wire:loading.attr="disabled"
                class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                End Break
            </button>
            <span class="text-yellow-600 font-semibold">On Break</span>
        @endif
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="stepOutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header">
                    <h5 class="modal-title text-gray-900 dark:text-white">Step Out / Break</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" x-data="{ actionType: @entangle('actionType') }">

                    <!-- Action Selection -->
                    <div class="mb-3">
                        <label>Select Action</label>
                        <div class="flex gap-3 mt-2">
                            <label><input type="radio" wire:model="actionType" value="step_out" x-model="actionType"> Step Out</label>
                            <label><input type="radio" wire:model="actionType" value="break" x-model="actionType"> Break</label>
                        </div>
                    </div>

                    <!-- Step Out Reason -->
                    <template x-if="actionType === 'step_out'">
                        <div class="mb-3">
                            <label>Reason</label>
                            <input type="text" wire:model.defer="reason" class="w-full p-2 border rounded" placeholder="e.g. Meeting">
                        </div>
                    </template>

                    <!-- Break Type -->
                    <template x-if="actionType === 'break'">
                        <div class="mb-3">
                            <label>Break Type</label>
                            <select wire:model.defer="breakType" class="w-full p-2 border rounded">
                                <option value="">-- Choose --</option>
                                @foreach($breakTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </template>

                    <button wire:click="startAction" wire:loading.attr="disabled"
                        class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        Confirm
                    </button>
                    <span wire:loading wire:target="startAction" class="ml-2 text-gray-600">Processing...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:load', function () {
    Livewire.on('notify', e => { if(e?.type && e?.message) toastr[e.type](e.message) });
    Livewire.on('close-modal', e => {
        const modalEl = document.getElementById(e.id);
        if(modalEl) bootstrap.Modal.getInstance(modalEl)?.hide();
    });
});
</script>
