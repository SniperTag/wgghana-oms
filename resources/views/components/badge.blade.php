@props(['type' => 'default'])

@php
    $colors = [
        'default' => 'bg-gray-200 text-gray-800',
        'success' => 'bg-green-200 text-green-800',
        'danger' => 'bg-red-200 text-red-800',
        'info' => 'bg-blue-200 text-blue-800',
        'warning' => 'bg-yellow-200 text-yellow-800',
    ];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2 py-0.5 rounded text-xs font-medium " . ($colors[$type] ?? $colors['default'])]) }}>
    {{ $slot }}
</span>
