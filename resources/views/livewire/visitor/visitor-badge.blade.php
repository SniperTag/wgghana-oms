<div>
    <h2 class="mb-4 text-lg font-bold">Visitors List</h2>

    <table class="w-full border mb-8">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-2 py-1">ID</th>
                <th class="border px-2 py-1">Full Name</th>
                <th class="border px-2 py-1">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($visitors as $visitor)
                <tr>
                    <td class="border px-2 py-1">{{ $visitor->id }}</td>
                    <td class="border px-2 py-1">{{ $visitor->full_name }}</td>
                    <td class="border px-2 py-1">
                        <button
                            x-data
                            @click="$wire.emit('showBadge', {{ $visitor->id }})"
                            class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700"
                        >
                            <i class="fas fa-id-badge mx-1"></i> Show Badge
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    <div
        x-data="{ open: false }"
        x-show="open"
        x-cloak
        x-transition
        @show-badge-modal.window="open = true"
        @keydown.escape.window="open = false"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div
            @click.away="open = false"
            class="bg-white rounded shadow-lg p-6 max-w-sm w-full relative print-area"
        >
            <button @click="open = false"
                class="absolute top-2 right-2 text-gray-700 hover:text-gray-900 font-bold"
                aria-label="Close modal"
            >&times;</button>

            @if ($visitor)
                <div class="text-center mb-4">
                    <h2 class="text-2xl font-bold text-blue-700">Visitor Badge</h2>
                    <p class="text-sm text-gray-500">Waltergates Ghana Ltd.</p>
                </div>

                <div class="flex flex-col items-center space-y-3 mb-4">
                    @if ($visitor->face_image)
                        @if (Str::startsWith($visitor->face_image, 'data:image'))
                            <img src="{{ $visitor->face_image }}" alt="Visitor Photo"
                                class="w-24 h-24 rounded-full object-cover border" />
                        @else
                            <img src="{{ asset('storage/' . $visitor->face_image) }}" alt="Visitor Photo"
                                class="w-24 h-24 rounded-full object-cover border" />
                        @endif
                    @else
                        <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                            No Photo
                        </div>
                    @endif

                    <div class="text-center">
                        <p class="font-semibold">{{ $visitor->full_name }}</p>
                        <p class="text-sm text-gray-600">{{ $visitor->company }}</p>
                        <p class="text-sm font-mono">{{ $visitor->visitor_uid }}</p>
                    </div>
                </div>

                <div class="flex justify-center mb-4">
                    {!! \QrCode::size(120)->generate($visitor->visitor_uid) !!}
                </div>

                <div class="text-sm space-y-1 mb-4">
                    <p><strong>Visitor Type:</strong> {{ $visitor->visitorType->name ?? '-' }}</p>
                    <p><strong>ID Type:</strong> {{ $visitor->id_type ?? '-' }}</p>
                    <p><strong>Date:</strong> {{ $visitor->created_at->format('d M Y') }}</p>
                </div>

                <div class="text-center">
                    <button @click="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Print Badge
                    </button>
                </div>
            @else
                <p class="text-center text-gray-500">No visitor selected.</p>
            @endif
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    @media print {
        body * { visibility: hidden; }
        .print-area, .print-area * { visibility: visible; }
        .print-area {
            position: absolute; left: 0; top: 0; width: 100%; background: white; padding: 1rem;
        }
    }
</style>
