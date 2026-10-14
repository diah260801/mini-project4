@props(['stock' => 0, 'minimumStock' => 0])

@php
    $isOut = $stock <= 0;
    $isLow = $stock > 0 && $stock <= $minimumStock;
@endphp

@if ($isOut)
    <span class="badge bg-danger-subtle text-danger border border-danger-subtle badge-pill-soft d-inline-flex align-items-center gap-1">
        <i class="bi bi-x-circle-fill"></i> Habis (0)
    </span>
@elseif ($isLow)
    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle badge-pill-soft d-inline-flex align-items-center gap-1" title="Batas minimum: {{ $minimumStock }}">
        <i class="bi bi-exclamation-triangle-fill text-warning"></i> Stok Menipis ({{ $stock }})
    </span>
@else
    <span class="badge bg-success-subtle text-success border border-success-subtle badge-pill-soft d-inline-flex align-items-center gap-1">
        <i class="bi bi-check-circle-fill"></i> Tersedia ({{ $stock }})
    </span>
@endif
