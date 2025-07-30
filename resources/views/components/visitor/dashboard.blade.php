@props([
    'title',
    'value',
    'color' => 'primary',
    'icon' => 'people',
    'badge' => null,
    'Svg' => 'icons/visitor.svg',
    'badgeColor' => 'secondary',
    'href' => '#',
])

<a href="{{ $href }}" class="text-decoration-none">
    <div class="card shadow-sm border-0 h-100 hover-shadow transition">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center">
                    <i class="bi bi-{{ $icon }} fs-2 text-{{ $color }} me-2"></i>
                    <span class="text-muted small">{{ $title }}</span>
                </div>
                @if ($badge)
                    <span class="badge bg-{{ $color }}">{{ $badge }}</span>
                @endif
            </div>
            <h3 class="card-title mb-0 text-{{ $color }}">
                <span class="counter">{{ $value }}</span>
            </h3>
        </div>
    </div>
</a>
