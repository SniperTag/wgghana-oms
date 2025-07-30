<div wire:poll.10s x-data="{ view: 'stepout' }" class="p-4  dark:bg-gray-900 rounded shadow max-w-6xl mx-auto mt-5">

    {{-- ✅ Summary --}}
    <div class="flex justify-between mb-4 items-center">
        <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200">Staff Status Board</h2>
        <div class="flex items-center gap-4">
            {{-- ✅ Toggle Buttons --}}
            <button @click="view = 'stepout'"
                :class="view === 'stepout' ? 'bg-blue-100 text-black' : 'bg-gray-500 text-gray-700'"
                class="px-4 py-2 rounded font-semibold transition">
                Stepped Out ({{ $totalSteppedOut }})
            </button>
            <button @click="view = 'break'"
                :class="view === 'break' ? 'bg-blue-100 text-black' : 'bg-gray-500 text-gray-700'"
                class="px-4 py-2 rounded font-semibold transition">
                On Break ({{ $totalOnBreak }})
            </button>
        </div>
    </div>

    {{-- ✅ Stepped Out Table --}}
    <div x-show="view === 'stepout'" x-transition>
        <h3 class="text-lg font-semibold mb-2">Currently Stepped Out</h3>
        @if ($steppedOutStaff->count() > 0)
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-3 py-2 text-left">Name</th>
                        <th class="px-3 py-2">Reason</th>
                        <th class="px-3 py-2">Elapsed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($steppedOutStaff as $staff)
                        <tr>
                            <td class="px-3 py-2">{{ $staff->user->name }}</td>
                            <td class="px-3 py-2">{{ $staff->reason }}</td>
                            <td class="px-3 py-2 text-gray-600" x-data x-init="
                                setInterval(() => {
                                    let start = new Date('{{ $staff->stepped_out_at }}').getTime();
                                    let diff = Date.now() - start;
                                    let hrs = Math.floor(diff / 3600000);
                                    let mins = Math.floor((diff % 3600000) / 60000);
                                    let secs = Math.floor((diff % 60000) / 1000);
                                    $el.textContent = `${hrs.toString().padStart(2,'0')}:${mins.toString().padStart(2,'0')}:${secs.toString().padStart(2,'0')}`;
                                }, 1000);
                            ">00:00:00</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">No staff stepped out currently.</p>
        @endif
    </div>

    {{-- ✅ Break Sessions Table --}}
    <div x-show="view === 'break'" x-transition>
        <h3 class="text-lg font-semibold mb-2">Currently On Break</h3>
        @if ($breakSessions->count() > 0)
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-3 py-2 text-left">Name</th>
                        <th class="px-3 py-2">Break Type</th>
                        <th class="px-3 py-2">Elapsed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($breakSessions as $break)
                        <tr>
                            <td class="px-3 py-2">{{ $break->user->name }}</td>
                            <td class="px-3 py-2">{{ $break->break_type }}</td>
                            <td class="px-3 py-2 text-gray-600" x-data x-init="
                                setInterval(() => {
                                    let start = new Date('{{ $break->started_at }}').getTime();
                                    let diff = Date.now() - start;
                                    let hrs = Math.floor(diff / 3600000);
                                    let mins = Math.floor((diff % 3600000) / 60000);
                                    let secs = Math.floor((diff % 60000) / 1000);
                                    $el.textContent = `${hrs.toString().padStart(2,'0')}:${mins.toString().padStart(2,'0')}:${secs.toString().padStart(2,'0')}`;
                                }, 1000);
                            ">00:00:00</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">No staff on break currently.</p>
        @endif
    </div>
</div>
