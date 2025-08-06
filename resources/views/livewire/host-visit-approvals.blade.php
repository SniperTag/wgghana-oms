<div class="p-4 bg-white rounded shadow max-w-3xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Pending Visitor Check-in Requests</h2>

    @if(session()->has('message'))
        <div class="mb-4 p-2 bg-green-200 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mb-4 p-2 bg-red-200 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if($pendingVisits->isEmpty())
        <p class="text-gray-600 italic">No pending check-in requests.</p>
    @else
        <table class="w-full border border-gray-300 rounded">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-2 border-b">Visitor</th>
                    <th class="p-2 border-b">Requested At</th>
                    <th class="p-2 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingVisits as $visit)
                    <tr @if($selectedVisitLogId == $visit->id) class="bg-blue-50" @endif>
                        <td class="p-2 border-b">
                            {{ $visit->visitor->full_name }} ({{ $visit->visitor->visitor_uid }})<br>
                            <small class="text-gray-600">{{ $visit->visitor->email }}</small>
                        </td>
                        <td class="p-2 border-b">
                            {{ $visit->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="p-2 border-b">
                            @if($selectedVisitLogId !== $visit->id)
                                <button wire:click="selectVisit({{ $visit->id }})"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                    Review
                                </button>
                            @else
                                <div class="space-y-2">
                                    <button wire:click="approve"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                                        Approve
                                    </button>

                                    <div>
                                        <textarea wire:model.defer="declineReason" placeholder="Reason to decline"
                                            class="border rounded p-2 w-full" rows="2"></textarea>
                                        @error('declineReason') <span class="text-red-600">{{ $message }}</span> @enderror
                                    </div>

                                    <button wire:click="decline"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                        Decline
                                    </button>

                                    <button wire:click="$set('selectedVisitLogId', null); $set('declineReason', '');"
                                        class="text-gray-600 hover:underline mt-1">
                                        Cancel
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
