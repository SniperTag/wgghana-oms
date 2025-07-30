


<div id="page-container"
    class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

    <!-- Sidebar -->
    @include('layouts.partials.sidebar')

    @include('layouts.header')

    <!-- Main Container -->
    <main id="main-container" class="content-full">
        <div class="content py-5 px-4 sm:px-3 lg:px-10 bg-gray-100 min-h-screen">
            <div class="bg-white p-6 rounded-xl shadow-md max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Log Visitor Check-In</h2>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        {{-- Visitor Selection --}}
        <div>
            <label class="block font-medium text-gray-700">Visitor*</label>
            <select wire:model.defer="visitor_id" class="form-select w-full rounded-md">
                <option value="">Select Visitor</option>
                @foreach ($visitors as $visitor)
                    <option value="{{ $visitor->id }}">{{ $visitor->full_name }} ({{ $visitor->visitor_uid }})</option>
                @endforeach
            </select>
            @error('visitor_id') <small class="text-red-600">{{ $message }}</small> @enderror
        </div>

        {{-- Host --}}
        <div>
            <label class="block font-medium text-gray-700">Host / User*</label>
            <select wire:model.defer="host_id" class="form-select w-full rounded-md">
                <option value="">Select Host</option>
                @foreach ($hosts as $host)
                    <option value="{{ $host->id }}">{{ $host->name }}</option>
                @endforeach
            </select>
            @error('host_id') <small class="text-red-600">{{ $message }}</small> @enderror
        </div>

        {{-- Purpose and Reason --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-gray-700">Purpose*</label>
                <input type="text" wire:model.defer="purpose" class="form-input w-full rounded-md" />
                @error('purpose') <small class="text-red-600">{{ $message }}</small> @enderror
            </div>
            <div>
                <label class="block font-medium text-gray-700">Detailed Reason</label>
                <textarea wire:model.defer="visit_reason_detail" rows="2"
                          class="form-textarea w-full rounded-md"></textarea>
                @error('visit_reason_detail') <small class="text-red-600">{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- Visitor Type and Appointment --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-gray-700">Visitor Type</label>
                <select wire:model.defer="visitor_type_id" class="form-select w-full rounded-md">
                    <option value="">Select Type</option>
                    @foreach ($visitorTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('visitor_type_id') <small class="text-red-600">{{ $message }}</small> @enderror
            </div>
            <div>
                <label class="block font-medium text-gray-700">Linked Appointment (Optional)</label>
                <select wire:model.defer="appointment_id" class="form-select w-full rounded-md">
                    <option value="">-- None --</option>
                    @foreach ($appointments as $appointment)
                        <option value="{{ $appointment->id }}">#{{ $appointment->id }} ({{ $appointment->scheduled_at }})</option>
                    @endforeach
                </select>
                @error('appointment_id') <small class="text-red-600">{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- Badge, Location, Device --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block font-medium text-gray-700">Badge Number</label>
                <input type="text" wire:model.defer="badge_number" class="form-input w-full rounded-md" />
                @error('badge_number') <small class="text-red-600">{{ $message }}</small> @enderror
            </div>
            <div>
                <label class="block font-medium text-gray-700">Location</label>
                <input type="text" wire:model.defer="location" class="form-input w-full rounded-md" />
                @error('location') <small class="text-red-600">{{ $message }}</small> @enderror
            </div>
            <div>
                <label class="block font-medium text-gray-700">Device</label>
                <input type="text" wire:model.defer="device_name" class="form-input w-full rounded-md" />
                @error('device_name') <small class="text-red-600">{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- Approval Status --}}
        <div>
            <label class="block font-medium text-gray-700">Approval Status*</label>
            <select wire:model="approval_status" class="form-select w-full rounded-md">
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            @error('approval_status') <small class="text-red-600">{{ $message }}</small> @enderror
        </div>

        {{-- Show reason if rejected --}}
        @if ($approval_status === 'rejected')
            <div>
                <label class="block font-medium text-gray-700">Rejection Reason*</label>
                <textarea wire:model.defer="rejection_reason" rows="2" class="form-textarea w-full rounded-md"></textarea>
                @error('rejection_reason')
                    <small class="text-red-600">{{ $message}}</small>
                @enderror
            </div>
        @endif

        {{-- Remarks and Status --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-gray-700">Remarks</label>
                <textarea wire:model.defer="remarks" class="form-textarea w-full rounded-md"></textarea>
                @error('remarks') <small class="text-red-600">{{ $message }}</small> @enderror
            </div>
            <div>
                <label class="block font-medium text-gray-700">Status*</label>
                <select wire:model.defer="status" class="form-select w-full rounded-md">
                    <option value="pending">Pending</option>
                    <option value="checked_in">Checked In</option>
                    <option value="checked_out">Checked Out</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                @error('status') <small class="text-red-600">{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- Submit --}}
        <div class="text-end pt-4 relative">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-md"
                    wire:loading.attr="disabled">
                Submit Visit Log
            </button>

            <div wire:loading wire:target="submit"
                 class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 rounded-md">
                <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
            </div>
        </div>
    </form>
</div>
        </div>
    </main>

    <!-- END Main Container -->
</div>