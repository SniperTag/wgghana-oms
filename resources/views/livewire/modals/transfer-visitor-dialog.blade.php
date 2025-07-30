<div>
    @if ($show)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg relative">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8h2a2 2 0 0 1 2 2v1.5M7 16H5a2 2 0 0 1-2-2v-1.5m14.5-1.5l-3-3m3 3l-3 3m-7-3l3-3m-3 3l3 3">
                        </path>
                    </svg>
                    Transfer Visitor
                </h2>

                <div class="bg-gray-100 p-3 rounded mb-4">
                    <p class="text-sm font-medium">{{ $visitor->name }}</p>
                    <p class="text-sm text-gray-600">Currently with: {{ $visitor->host }}</p>
                </div>

                <form wire:submit.prevent="transfer" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">New Host *</label>
                        <input type="text" wire:model.defer="newHost" class="w-full border rounded px-3 py-2"
                            placeholder="Enter new host">
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach (['Sarah Johnson', 'Mike Wilson', 'Lisa Chen', 'David Brown'] as $host)
                                @if ($host !== $visitor->host)
                                    <button type="button" wire:click="$set('newHost', '{{ $host }}')"
                                        class="bg-gray-200 text-xs px-2 py-1 rounded hover:bg-gray-300">
                                        {{ $host }}
                                    </button>
                                @endif
                            @endforeach
                        </div>
                        @error('newHost')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Reason *</label>
                        <textarea wire:model.defer="reason" rows="3" class="w-full border rounded px-3 py-2"
                            placeholder="Enter reason for transfer"></textarea>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach (['Meeting room change', 'Host unavailable', 'Department transfer'] as $preset)
                                <button type="button" wire:click="$set('reason', '{{ $preset }}')"
                                    class="bg-gray-200 text-xs px-2 py-1 rounded hover:bg-gray-300">
                                    {{ $preset }}
                                </button>
                            @endforeach
                        </div>
                        @error('reason')
                            <span class="text-red-500 text-xs">{{ $messages }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" wire:click="$set('show', false)"
                            class="px-4 py-2 text-sm border rounded hover:bg-gray-100">Cancel</button>
                        <button wire:click="$emit('openTransferModal', {{ $visitor->id }})"
                            class="btn btn-outline-secondary btn-sm">
                            Transfer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
