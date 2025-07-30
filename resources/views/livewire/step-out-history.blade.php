<div>
    <h4 class="text-lg font-bold mb-2">Step Out History</h4>
    <table class="table table-bordered mb-4">
        <thead>
            <tr>
                <th>Date</th>
                <th>Reason</th>
                <th>Stepped Out At</th>
                <th>Returned At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stepOutHistory as $record)
                <tr>
                    <td>{{ $record->stepped_out_at->format('d M Y') }}</td>
                    <td>{{ $record->reason }}</td>
                    <td>{{ $record->stepped_out_at->format('H:i') }}</td>
                    <td>{{ $record->returned_at ? $record->returned_at->format('H:i') : 'Not Returned' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No Step Out history found.</td>
                </tr>
            @endforelse

        </tbody>
         {{ $stepOutHistory->links() }}
    </table>

    <h4 class="text-lg font-bold mb-2">Break History</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Break Type</th>
                <th>Started At</th>
                <th>Ended At</th>
                <th>Duration (mins)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($breakHistory as $break)
                <tr>
                    <td>{{ $break->started_at->format('d M Y') }}</td>
                    <td>{{ $break->break_type }}</td>
                    <td>{{ $break->started_at->format('H:i') }}</td>
                    <td>{{ $break->ended_at ? $break->ended_at->format('H:i') : 'Ongoing' }}</td>
                    <td x-data="{ start: '{{ $break->started_at }}', end: '{{ $break->ended_at }}', duration: 0 }" x-init="if (!end) {
                        setInterval(() => {
                            const s = new Date(start).getTime();
                            const now = new Date().getTime();
                            duration = Math.floor((now - s) / 60000); // in minutes
                        }, 1000);
                    } else {
                        const s = new Date(start).getTime();
                        const e = new Date(end).getTime();
                        duration = Math.floor((e - s) / 60000);
                    }">
                        <span x-text="duration + ' mins'"></span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No Break history found.</td>
                </tr>
            @endforelse
        </tbody>
                    {{ $breakHistory->links() }}

    </table>
</div>
