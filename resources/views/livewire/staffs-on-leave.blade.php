<div>
    <h2 class="text-xl font-bold mb-4">Staff Currently On Leave</h2>

    <div class="flex flex-wrap gap-2 mb-4">
        <select wire:model="department_id" class="border p-2 rounded">
            <option value="">All Departments</option>
            @foreach ($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
            @endforeach
        </select>

        <select wire:model="leave_type_id" class="border p-2 rounded">
            <option value="">All Leave Types</option>
            @foreach ($leaveTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>

        <select wire:model="sort_days" class="border p-2 rounded">
            <option value="asc">Sort: Fewest Days Left</option>
            <option value="desc">Sort: Most Days Left</option>
        </select>
    </div>

    <table class="table-auto w-full border border-gray-300">
        <thead class="bg-gray-200">
            <tr>
                <th class="border px-4 py-2">Staff Name</th>
                <th class="border px-4 py-2">Staff ID</th>
                <th class="border px-4 py-2">Department</th>
                <th class="border px-4 py-2">Leave Type</th>
                <th class="border px-4 py-2">Days Left</th>
                <th class="border px-4 py-2">Expected Return</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leaves as $leave)
                <tr>
                    <td class="border px-4 py-2">{{ $leave->user->name }}</td>
                    <td class="border px-4 py-2">{{ $leave->user->staff_id ?? 'N/A' }}</td>
                    <td class="border px-4 py-2">{{ $leave->user->department->name ?? 'N/A' }}</td>
                    <td class="border px-4 py-2">{{ $leave->leaveType->name ?? 'N/A' }}</td>
                    <td class="border px-4 py-2">{{ $leave->user->leaveBalance->days_left ?? 0 }}</td>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($leave->end_date)->addDay()->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border px-4 py-2 text-center text-gray-500">No staff on leave</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $leaves->links() }}
    </div>
</div>
