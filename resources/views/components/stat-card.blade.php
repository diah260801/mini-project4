@props([
    'title' => '',
    'value' => '0',
    'icon' => 'bi-box',
    'color' => 'primary',
    'subtitle' => null,
    'link' => null,
    'linkText' => null,
])

<div class="card card-custom h-100 p-3">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="text-secondary small fw-semibold text-uppercase tracking-wider">{{ $title }}</span>
        <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-{{ $color }}-subtle text-{{ $color }} p-2" style="width: 42px; height: 42px;">
            <i class="bi {{ $icon }} fs-5"></i>
        </div>
    </div>
    <div class="d-flex align-items-baseline gap-2">
        <h3 class="mb-0 fw-bold">{{ $value }}</h3>
        @if ($subtitle)
            <span class="text-muted small">{{ $subtitle }}</span>
        @endif
    </div>
    @if ($link && $linkText)
        <div class="mt-3 pt-2 border-top">
            <a href="{{ $link }}" class="small text-decoration-none text-{{ $color }} fw-semibold d-inline-flex align-items-center gap-1">
                {{ $linkText }} <i class="bi bi-chevron-right" style="font-size: 11px;"></i>
            </a>
        </div>
    @endif
</div>
