<div id="page-container"
    class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

    <!-- Sidebar -->
    @include('layouts.partials.sidebar')
    @include('layouts.header')

    <!-- Main Container -->
    <main id="main-container" class="content-full">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-lg space-y-6">
    <h2 class="text-2xl font-bold text-gray-700 border-b pb-3">Create Appointment</h2>

    {{-- Search & Auto Suggest --}}
    <div class="relative">
        <label class="block text-gray-600 font-medium mb-2">Search Visitor (Name / ID / Phone)</label>
        <input type="text" wire:model.debounce.500ms="searchId"
            placeholder="Type name, ID, or phone..."
            class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none" />

        {{-- Loader --}}
        <div wire:loading wire:target="searchId" class="absolute right-3 top-2">
            <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
        </div>

        {{-- Dropdown --}}
        @if (!empty($searchId) && $searchResults->isNotEmpty())
            <div class="absolute z-50 w-full bg-white border border-gray-300 rounded mt-1 shadow-lg max-h-56 overflow-auto">
                @foreach ($searchResults as $result)
                    <div wire:click="selectVisitor({{ $result->id }})"
                        class="px-4 py-2 cursor-pointer hover:bg-indigo-100">
                        {!! highlightMatch($result->full_name, $searchId) !!}
                        <span class="text-gray-500 text-sm">({{ $result->phone ?? 'No Phone' }})</span>
                        <br>
                        <small class="text-gray-400">{{ $result->id_number ?? $result->visitor_uid }}</small>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Selected Visitors as Chips --}}
    @if (!empty($visitor_ids))
        <div class="bg-blue-50 border border-blue-200 rounded px-4 py-3">
            <p class="text-sm font-semibold text-blue-800 mb-2">Selected Visitor(s):</p>
            <div class="flex flex-wrap gap-2">
                @foreach ($visitor_ids as $id)
                    @php $visitor = $visitors->find($id); @endphp
                    @if ($visitor)
                        <div class="flex items-center bg-white border border-blue-300 text-blue-700 px-3 py-1 rounded-full shadow-sm">
                            <span class="mr-2 font-medium">{{ $visitor->full_name }}</span>
                            <button type="button" wire:click="removeVisitor({{ $id }})"
                                class="text-red-500 hover:text-red-700">&times;</button>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- Appointment Form --}}
    <form wire:submit.prevent="create" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block font-medium text-gray-600 mb-2">Host User</label>
            <select wire:model="user_id"
                class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400">
                <option value="">Select Host</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            @error('user_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium text-gray-600 mb-2">Department (Optional)</label>
            <select wire:model="department_id"
                class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400">
                <option value="">None</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium text-gray-600 mb-2">Scheduled Date & Time</label>
            <input type="datetime-local" wire:model="scheduled_at"
                class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400" />
            @error('scheduled_at') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium text-gray-600 mb-2">Purpose</label>
            <textarea wire:model="purpose"
                class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400"
                placeholder="Enter purpose of visit..."></textarea>
        </div>

        <div class="col-span-2 flex justify-end">
            <button type="submit"
                class="bg-indigo-600 text-white font-semibold px-6 py-2 rounded hover:bg-indigo-700 transition">
                Create Appointment
            </button>
        </div>
    </form>
</div>

{{-- Highlight Helper --}}
@php
function highlightMatch($text, $search) {
    if (!$search) return e($text);
    return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark>$1</mark>', e($text));
}
@endphp

    </main>
</div>
