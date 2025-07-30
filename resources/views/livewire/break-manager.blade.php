<div class="space-y-4">

    {{-- Break Button Section --}}
    <div>
        @if ($onBreak)
            <p class="text-sm text-gray-700 mb-2">
                On Break since: <strong>{{ \Carbon\Carbon::parse($currentBreak->started_at)->format('h:i A') }}</strong>
                <br>
                Break Type: <strong>{{ $currentBreak->break_type }}</strong>
            </p>
            <button wire:click="endBreak" class="bg-red-600 text-white px-4 py-2 rounded">
                End Break
            </button>

            {{-- Auto-refresh every minute to check duration --}}
            <script>
                setInterval(() => {
                    Livewire.emit('checkBreakDuration');
                }, 60000);
            </script>
        @else
            {{--  <label for="breakType" class="block mb-1 font-medium">Select Break Type</label>  --}}
            <select wire:model="selectedBreakType" id="breakType"
                class="border rounded px-3 py-2 mb-3 mt-4 w-auto inline-block">
                <option value="">-- Choose --</option>
                @foreach ($breakTypes as $type)
                    <option value="{{ $type }}">{{ $type }}</option>
                @endforeach
            </select>


            <button wire:click="startBreak" class="bg-green-600 text-white px-4 py-2 rounded"
                @if (!$selectedBreakType) disabled @endif>
                Start Break
            </button>

        @endif
    </div>

    {{-- Today's Break History --}}
    <div class="bg-white border rounded p-4 shadow max-w-md float-">
        <h3 class="font-semibold text-lg mb-2">Today’s Break Sessions</h3>

        @if (count($todayBreaks) > 0)
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-1">Start</th>
                        <th class="py-1">End</th>
                        <th class="py-1">Duration</th>
                        <th class="py-1">Type</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($todayBreaks as $break)
                        <tr class="border-b">
                            <td class="py-1">{{ \Carbon\Carbon::parse($break->started_at)->format('h:i A') }}</td>
                            <td class="py-1">
                                @if ($break->ended_at)
                                    {{ \Carbon\Carbon::parse($break->ended_at)->format('h:i A') }}
                                @else
                                    <span class="text-yellow-500">Ongoing</span>
                                @endif
                            </td>
                            <td class="py-1">
                                @if ($break->ended_at)
                                    {{ \Carbon\Carbon::parse($break->ended_at)->diffForHumans($break->started_at, true) }}
                                @else
                                    {{ \Carbon\Carbon::now()->diffForHumans($break->started_at, true) }}
                                @endif
                            </td>
                            <td class="py-1">{{ $break->break_type ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500 italic">No breaks taken today.</p>
        @endif
    </div>
</div>
