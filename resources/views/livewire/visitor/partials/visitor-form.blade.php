<div class="border border-gray-300 rounded-xl p-4 shadow-sm relative bg-white">
    <h3 class="text-lg font-semibold text-indigo-700 mb-2">
        Visitor {{ $index + 1 }}
    </h3>

    @if ($isTeam && count($visitors) > 1)
        <button type="button" wire:click="removeVisitor({{ $index }})"
            class="absolute top-2 right-2 text-red-600 hover:text-red-800">
            &times; Remove
        </button>
    @endif

    <!-- Personal Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Full Name*</label>
            <input type="text" wire:model.defer="visitors.{{ $index }}.full_name"
                class="w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-sm font-medium">Gender</label>
            <select wire:model.defer="visitors.{{ $index }}.gender"
                class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Select</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium">Date of Birth</label>
            <input type="date" wire:model.defer="visitors.{{ $index }}.date_of_birth"
                class="w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-sm font-medium">Nationality</label>
            <input type="text" wire:model.defer="visitors.{{ $index }}.nationality"
                class="w-full border-gray-300 rounded-md shadow-sm" />
        </div>
    </div>

    <!-- Contact Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <div>
            <label class="block text-sm font-medium">Email*</label>
            <input type="email" wire:model.defer="visitors.{{ $index }}.email"
                class="w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-sm font-medium">Phone</label>
            <input type="text" wire:model.defer="visitors.{{ $index }}.phone"
                class="w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-sm font-medium">Company</label>
            <input type="text" wire:model.defer="visitors.{{ $index }}.company"
                class="w-full border-gray-300 rounded-md shadow-sm" />
        </div>
    </div>

    <!-- ID Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <div>
            <label class="block text-sm font-medium">ID Type*</label>
            <select wire:model.defer="visitors.{{ $index }}.id_type"
                class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Select</option>
                @foreach (['Ghana Card', 'Passport', 'Student ID', 'Work ID', 'Driver License'] as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium">ID Number*</label>
            <input type="text" wire:model.defer="visitors.{{ $index }}.id_number"
                class="w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-sm font-medium">City</label>
            <input type="text" wire:model.defer="visitors.{{ $index }}.city"
                class="w-full border-gray-300 rounded-md shadow-sm" />
        </div>
    </div>

    <!-- File Uploads -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
            <label class="block text-sm font-medium">Photo</label>
            <input type="file" wire:model="visitors.{{ $index }}.photo"
                class="w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-sm font-medium">Signature</label>
            <input type="file" wire:model="visitors.{{ $index }}.signature"
                class="w-full border-gray-300 rounded-md shadow-sm" />
        </div>
    </div>
</div>
