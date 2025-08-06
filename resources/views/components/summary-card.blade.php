@props(['title' => null, 'wireClose' => null])

<div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-md p-6 rounded shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">{{ $title }}</h2>
            <button wire:click="$set('{{ $wireClose }}', false)" class="text-gray-600 hover:text-black text-xl">&times;</button>
        </div>
        {{ $slot }}
    </div>
</div>