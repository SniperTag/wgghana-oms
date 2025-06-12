<div>
    <div class="flex justify-between items-center mb-4">
        <input wire:model.debounce.300ms="search" type="text" placeholder="Search logs..."
               class="border p-2 rounded w-1/3" />

        <select wire:model="perPage" class="border p-2 rounded">
            <option value="5">5 per page</option>
            <option value="10">10 per page</option>
            <option value="25">25 per page</option>
        </select>
    </div>

    <div class="overflow-auto border rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Log Name</th>
                    <th class="px-4 py-2 text-left">Event</th>
                    <th class="px-4 py-2 text-left">Description</th>
                    <th class="px-4 py-2 text-left">Causer</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y
