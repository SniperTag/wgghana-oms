<div>
    <div class="flex items-center gap-3">
        @if (!$currentlySteppedOut && !$currentlyOnBreak)
            <button type="button" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-yellow-700"
                data-bs-toggle="modal" data-bs-target="#stepOutModal">
                Step Out / Break
            </button>
            <span class="text-green-600 font-semibold">🟢 Available in the office</span>
        @elseif($currentlySteppedOut)
            <button wire:click="returnBack" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Return
            </button>
            <span class="text-red-600 font-semibold">🔴 Stepped Out of office</span>
        @elseif($currentlyOnBreak)
            <button wire:click="endBreak" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                End Break
            </button>
            <span class="text-blue-600 font-semibold">🟡 On Break</span>
        @endif
    </div>

    <!-- Modal -->
<div wire:ignore.self class="modal fade" id="stepOutModal" tabindex="-1" aria-labelledby="stepOutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header">
                    <h5 class="modal-title text-gray-900 dark:text-white" id="stepOutModalLabel">Step Out / Break</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 rounded bg-white dark:bg-gray-800"
     x-data="{ actionType: @entangle('actionType') }"
     x-init="$watch('actionType', () => { /* no-op, just trigger Alpine reactivity */ })">

    <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Select Action</label>
        <div class="flex gap-3 mt-2">
            <label><input type="radio" wire:model="actionType" value="step_out" x-model="actionType"> Step Out</label>
            <label><input type="radio" wire:model="actionType" value="break" x-model="actionType"> Break</label>
        </div>
    </div>

    <template x-if="actionType === 'step_out'">
        <div class="mb-3" x-key="step_out_form">
            <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Reason</label>
            <input type="text" wire:model.defer="reason" class="w-full p-2 border rounded" placeholder="e.g. Meeting with client">
        </div>
    </template>

    <template x-if="actionType === 'break'">
        <div class="mb-3" x-key="break_form">
            <label for="breakType" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Select Break Type</label>
            <select wire:model.defer="breakType" class="w-full p-2 border rounded">
                <option value="">-- Choose Break Type --</option>
                @foreach ($breakTypes as $type)
                    <option value="{{ $type }}">{{ $type }}</option>
                @endforeach
            </select>
        </div>
    </template>

    <button wire:click="startAction" wire:loading.attr="disabled" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
        Confirm
    </button>
    <span wire:loading wire:target="startAction" class="ml-2 text-gray-600">Processing...</span>
</div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('notify', (event) => {
                if (event?.type && event?.message) {
                    toastr[event.type](event.message);
                }
            });

            Livewire.on('close-modal', (event) => {
                const modalEl = document.getElementById(event.id);
                if (modalEl) {
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.hide();
                }
            });
        });
    </script>
</div>
