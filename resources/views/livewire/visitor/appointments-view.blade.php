<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Visitor Appointments</h2>
        <div>
            <button wire:click="switchTo('today')" class="btn btn-sm {{ $view === 'today' ? 'btn-primary' : 'btn-outline' }}">
                Today
            </button>
            <button wire:click="switchTo('upcoming')" class="btn btn-sm {{ $view === 'upcoming' ? 'btn-primary' : 'btn-outline' }}">
                Upcoming
            </button>
        </div>
    </div>

    @if ($view === 'today')
        <h4 class="text-lg font-medium mb-2">Today's Appointments</h4>
        <table class="table table-bordered w-full text-sm">
            <thead>
                <tr>
                    <th>Visitor</th>
                    <th>Phone</th>
                    <th>Host</th>
                    <th>Department</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($todayAppointments as $appointment)
                    <tr>
                        <td>{{ $appointment->visitor_name }}</td>
                        <td>{{ $appointment->visitor_phone }}</td>
                        <td>{{ $appointment->host->name ?? 'N/A' }}</td>
                        <td>{{ $appointment->department->name ?? 'N/A' }}</td>
                        <td>{{ $appointment->time->format('h:i A') }}</td>
                        <td>{{ ucfirst($appointment->status) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No appointments today.</td></tr>
                @endforelse
            </tbody>
        </table>
    @else
        <h4 class="text-lg font-medium mb-2">Upcoming Appointments</h4>
        <table class="table table-bordered w-full text-sm">
            <thead>
                <tr>
                    <th>Visitor</th>
                    <th>Phone</th>
                    <th>Host</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($upcomingAppointments as $appointment)
                    <tr>
                        <td>{{ $appointment->visitor_name }}</td>
                        <td>{{ $appointment->visitor_phone }}</td>
                        <td>{{ $appointment->host->name ?? 'N/A' }}</td>
                        <td>{{ $appointment->department->name ?? 'N/A' }}</td>
                        <td>{{ $appointment->date->format('d M, Y') }}</td>
                        <td>{{ $appointment->time->format('h:i A') }}</td>
                        <td>{{ ucfirst($appointment->status) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">No upcoming appointments.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif
</div>
