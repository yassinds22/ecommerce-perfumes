@props(['title', 'value', 'trend' => null, 'icon', 'type' => 'revenue'])

@php
    $iconClasses = [
        'revenue' => 'revenue',
        'orders' => 'orders',
        'products' => 'products',
        'customers' => 'customers',
        'marketing' => 'marketing',
    ];
    $iconClass = $iconClasses[$type] ?? 'revenue';
@endphp

<div class="stat-card">
    <div class="stat-card__header">
        <div class="stat-card__icon {{ $iconClass }}"><i class="{{ $icon }}"></i></div>
        @if($trend)
            <span class="stat-card__trend {{ $trend >= 0 ? 'up' : 'down' }}">
                <i class="fas fa-caret-{{ $trend >= 0 ? 'up' : 'down' }}"></i> {{ abs($trend) }}%
            </span>
        @endif
    </div>
    <div class="stat-card__body">
        <div class="stat-card__value">{{ $value }}</div>
        <div class="stat-card__label">{{ $title }}</div>
    </div>
</div>
