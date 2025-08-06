@props([
    'label' => '',
    'type' => 'text',
])

<div class="space-y-1">
    @if($label)
        <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif
    <input type="{{ $type }}"
        {{ $attributes->merge(['class' => 'w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm']) }}>
</div>
