<div id="page-container"
    class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

    <!-- Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Header -->
    @include('layouts.header')

    <!-- Main Container -->
    <div>
        <main id="main-container" class="content-full">
            <div class="page-container d-flex flex-column min-vh-100">
                <div class="container mt-4">

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-blue-100 text-blue-800 p-4 rounded shadow">
        <h3 class="text-sm font-semibold">Approved Appointments</h3>
        <p class="text-3xl">{{ $approvedCount }}</p>
    </div>
    <div class="bg-red-100 text-red-800 p-4 rounded shadow">
        <h3 class="text-sm font-semibold">Declined Appointments</h3>
        <p class="text-3xl">{{ $declinedCount }}</p>
    </div>
    <div class="bg-yellow-100 text-yellow-800 p-4 rounded shadow">
        <h3 class="text-sm font-semibold">Rescheduled Appointments</h3>
        <p class="text-3xl">{{ $rescheduledCount }}</p>
    </div>
    <div class="bg-purple-100 text-purple-800 p-4 rounded shadow">
        <h3 class="text-sm font-semibold">Total Appointments</h3>
        <p class="text-3xl">{{ $totalCount }}</p>
    </div>
</div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="alert alert-success mb-4 shadow rounded text-sm bg-green-100 text-green-800 px-4 py-2">
            {{ session('message') }}
        </div>
    @endif

    {{-- Appointments Table --}}
    <h3 class="text-xl font-semibold mb-3">📋 My Appointments</h3>

    @if ($appointments->count())
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded shadow text-sm">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Visitor</th>
                        <th class="px-4 py-2 text-left">Type</th>
                        <th class="px-4 py-2 text-left">Date & Time</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Reason</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr class="border-b @if($appointment->status === 'rescheduled') bg-yellow-50 @elseif($appointment->status === 'pending') bg-yellow-100 @endif">
                            <td class="px-4 py-2 font-medium">{{ $appointment->visitor_name }}</td>
                            <td class="px-4 py-2">{{ ucfirst($appointment->meeting_type) }}</td>
                            <td class="px-4 py-2 text-blue-700">{{ $appointment->date->format('M d, Y h:i A') }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-semibold
                                    @switch($appointment->status)
                                        @case('approved') bg-green-100 text-green-700 @break
                                        @case('declined') bg-red-100 text-red-700 @break
                                        @case('rescheduled') bg-yellow-100 text-yellow-800 @break
                                        @default bg-gray-100 text-gray-600
                                    @endswitch">
                                    <i class="fas fa-circle text-[8px]"></i> {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 italic text-red-600">
                                @if ($appointment->status === 'declined' && $appointment->decline_reason)
                                    {{ $appointment->decline_reason }}
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 space-y-1">
                                @if ($appointment->status === 'pending')
                                    <div class="flex gap-2">
                                        <button wire:click="accept({{ $appointment->id }})"
                                            class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-xs">
                                            Accept
                                        </button>
                                        <button wire:click="confirmDecline({{ $appointment->id }})"
                                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-xs">
                                            Decline
                                        </button>
                                    </div>
                                @endif
                                <button wire:click="toggleExpand({{ $appointment->id }})"
                                    class="text-indigo-600 text-xs hover:underline">
                                    {{ $selectedExpandedId === $appointment->id ? 'Hide Details' : 'View Details' }}
                                </button>
                            </td>
                        </tr>

                        @if ($selectedExpandedId === $appointment->id)
                            <tr class="bg-gray-50">
                                <td colspan="6" class="px-4 py-3 text-gray-700 border-t text-sm">
                                    <strong>Description:</strong><br>
                                    {{ $appointment->description ?: 'No description provided.' }}
                                    <div class="mt-3">
                                        <button wire:click="showRescheduleModal({{ $appointment->id }})"
                                            class="text-sm text-blue-600 hover:underline">
                                            📅 Reschedule Appointment
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-4 text-center bg-gray-100 rounded text-gray-600">
            No appointments assigned to you.
        </div>
    @endif

    {{-- Decline Modal --}}
    @if ($declineModal)
        <x-modal title="Decline Appointment" wireClose="declineModal">
            <label class="block text-sm font-medium mb-1">Reason for Declining</label>
            <textarea wire:model.defer="declineReason" rows="3"
                class="w-full border rounded p-2 focus:ring focus:ring-red-300"></textarea>
            @error('declineReason') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror

            <div class="flex justify-end gap-2 mt-4">
                <button wire:click="$set('declineModal', false)"
                    class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Cancel</button>
                <button wire:click="declineConfirmed"
                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Submit</button>
            </div>
        </x-modal>
    @endif

    {{-- Reschedule Modal --}}
    @if ($rescheduleModal)
        <x-modal name="decline-appointment" maxWidth="md" :show="$declineModal" focusable>
    <h2 class="text-lg font-semibold mb-4">Decline Appointment</h2>

    <label class="block mb-2 text-sm font-medium">Reason for declining:</label>
    <textarea wire:model.defer="declineReason" rows="4"
        class="w-full border rounded p-2 focus:ring focus:ring-red-300"></textarea>
    @error('declineReason')
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror

    <div class="mt-4 flex justify-end space-x-2">
        <button wire:click="closeDeclineModal"
            class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
            Cancel
        </button>
        <button wire:click="declineConfirmed"
            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
            Submit Decline
        </button>
    </div>

    <div class="mt-2">
        <button wire:click="showRescheduleModal({{ $appointmentToDecline }})"
            class="mt-2 text-indigo-600 hover:underline text-sm">
            📅 Reschedule Appointment
        </button>
    </div>
</x-modal>

<x-modal name="reschedule-appointment" maxWidth="md" :show="$rescheduleModal" focusable>
    <h2 class="text-lg font-semibold mb-4">Reschedule Appointment</h2>

    <div class="mb-4">
        <label class="block mb-1 text-sm font-medium">New Date</label>
        <input type="date" wire:model="newDate"
            class="w-full border rounded p-2 focus:ring focus:ring-indigo-300">
        @error('newDate')
            <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block mb-1 text-sm font-medium">New Time</label>
        <input type="time" wire:model="newTime"
            class="w-full border rounded p-2 focus:ring focus:ring-indigo-300">
        @error('newTime')
            <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
    </div>

    <div class="mt-4 flex justify-end space-x-2">
        <button wire:click="closeRescheduleModal"
            class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
            Cancel
        </button>
        <button wire:click="rescheduleConfirmed"
            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            Confirm Reschedule
        </button>
    </div>
</x-modal>

    @endif

</div>

            </div>



        </main>

        <!-- END Main Container -->
    </div>


</div>
<script>
    Livewire.on('openModal', modalName => {
        window.dispatchEvent(new CustomEvent('open-modal', { detail: modalName }));
    });

    Livewire.on('closeModal', modalName => {
        window.dispatchEvent(new CustomEvent('close-modal', { detail: modalName }));
    });
</script>
