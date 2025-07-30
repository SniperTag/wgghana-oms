<div id="page-container"
    class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

    <!-- Sidebar -->
    @include('layouts.partials.sidebar')
    @include('layouts.header')

    <!-- Main Container -->
    <main id="main-container" class="content-full">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow text-center">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Appointment Check-In</h2>

            <p class="text-gray-600 mb-4">Show this QR code at the reception:</p>

            @if ($appointment->qr_code)
                <img src="{{ asset('storage/' . $appointment->qr_code) }}" alt="QR Code" class="mx-auto mb-4 w-48 h-48">
            @endif

            <p class="text-lg font-semibold text-indigo-600">
                Visitor(s): {{ $appointment->visitors->pluck('full_name')->join(', ') }}
            </p>

            <p class="text-sm text-gray-500 mb-4">
                Scheduled: {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d M Y h:i A') }}
            </p>

            @if (session()->has('message'))
                <div class="p-2 mb-3 bg-green-100 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <div class="flex justify-center gap-4 mt-4">
                <button wire:click="checkIn" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Check In
                </button>
                <button wire:click="checkOut" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Check Out
                </button>
            </div>

            <a href="{{ route('appointment.booking') }}" class="mt-4 inline-block text-gray-600 hover:underline">
                Back to Appointments
            </a>
        </div>


    </main>
</div>
