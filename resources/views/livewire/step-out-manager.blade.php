{{--  <div class="p-4 rounded shadow bg-white dark:bg-gray-800 mt-2 mb-4">

   @if (session()->has('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


    @if (!$currentlySteppedOut)
        <form wire:submit.prevent="stepOut">
            <label for="reason" class="block text-sm mb-2 font-medium text-gray-700">Step Out Reason</label>
            <input type="text" wire:model.defer="reason" class="w-full p-2 border rounded mb-3" placeholder="e.g. Meeting with client">
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                Step Out
            </button>
        </form>
    @else
        <div class="mb-3 text-red-600">You are currently stepped out.</div>
        <button wire:click="returnBack" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Return
        </button>
    @endif
</div>
<script>
    window.addEventListener('stepout-error', event => {
        toastr.error(event.detail.message);
    });
</script>  --}}



<div>
    <div class="flex items-center gap-3">
        @if (!$currentlySteppedOut)
            <button type="button"
                class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-yellow-700"
                data-bs-toggle="modal" data-bs-target="#stepOutModal">
                Step Out
            </button>
            <span class="text-green-600 font-semibold">🟢 Available in the office</span>
        @else
            <button wire:click="returnBack"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Return
            </button>
            <span class="text-red-600 font-semibold">🔴 Stepped Out of office</span>
        @endif
    </div>

    <div wire:ignore.self class="modal fade" id="stepOutModal" tabindex="-1" aria-labelledby="stepOutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header">
                    <h5 class="modal-title text-gray-900 dark:text-white" id="stepOutModalLabel">Step Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 rounded bg-white dark:bg-gray-800">
                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="stepOut">
                        <label for="reason" class="block text-sm mb-2 font-medium text-gray-700 dark:text-gray-200">Step Out Reason</label>
                        <input type="text" wire:model.defer="reason" class="w-full p-2 border rounded mb-3" placeholder="e.g. Meeting with client">
                        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                            Confirm Step Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                toastr.success("{{ session('success') }}");

                // Close modal after step-out success
                const modal = bootstrap.Modal.getInstance(document.getElementById('stepOutModal'));
                if (modal) modal.hide();
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        });
    </script>
</div>





{{--  <div>
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
    <div wire:ignore.self class="modal fade" id="stepOutModal" tabindex="-1" aria-labelledby="stepOutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header">
                    <h5 class="modal-title text-gray-900 dark:text-white" id="stepOutModalLabel">Step Out / Break</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 rounded bg-white dark:bg-gray-800">
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Select Action</label>
                        <div class="flex gap-3 mt-2">
                            <label><input type="radio" wire:model="actionType" value="step_out"> Step Out</label>
                            <label><input type="radio" wire:model="actionType" value="break"> Break</label>
                        </div>
                    </div>

                    @if ($actionType === 'step_out')
                        <div class="mb-3">
                            <label for="reason"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Reason</label>
                            <input type="text" wire:model.defer="reason" class="w-full p-2 border rounded"
                                placeholder="e.g. Meeting with client">
                        </div>
                    @elseif($actionType === 'break')
                        <div class="mb-3">
                            <label for="breakType"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Select Break
                                Type</label>
                            <select wire:model="breakType" class="w-full p-2 border rounded">
                                <option value="">-- Choose Break Type --</option>
                                @foreach ($breakTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <button wire:click="startAction"
                        class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
       document.addEventListener('livewire:load', function() {
    Livewire.on('notify', event => {
        toastr[event.type](event.message);
    });

    Livewire.on('close-modal', event => {
        const modal = bootstrap.Modal.getInstance(document.getElementById(event.id));
        if (modal) modal.hide();
    });
});

    </script>

</div>  --}}



