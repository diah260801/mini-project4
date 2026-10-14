@props([
    'title' => '',
    'subtitle' => null,
])

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-2 border-bottom">
    <div>
        <h2 class="mb-1 text-dark fw-bold">{{ $title }}</h2>
        @if ($subtitle)
            <p class="text-muted mb-0 small">{{ $subtitle }}</p>
        @endif
    </div>
    @if (isset($actions))
        <div class="d-flex align-items-center gap-2 flex-wrap">
            {{ $actions }}
        </div>
    @endif
</div>
