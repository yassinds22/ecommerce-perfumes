@props(['title', 'value', 'trend' => null, 'icon', 'type' => 'revenue', 'color' => null])

@php
    $iconClasses = [
        'revenue' => 'revenue',
        'orders' => 'orders',
        'products' => 'products',
        'customers' => 'customers',
        'marketing' => 'marketing',
        'profit' => 'profit',
    ];
    $iconClass = $iconClasses[$type] ?? 'revenue';
    
    // Function to abbreviate numbers
    $formatValue = function($val) {
        $cleanVal = str_replace([',', '$'], '', $val);
        if (!is_numeric($cleanVal)) return $val;
        
        $num = (float)$cleanVal;
        if ($num >= 1000000) return round($num / 1000000, 1) . 'M';
        if ($num >= 1000) return round($num / 1000, 1) . 'K';
        return number_format($num, strpos($cleanVal, '.') !== false ? 2 : 0);
    };

    $displayValue = str_starts_with($value, '$') ? '$' . $formatValue($value) : $formatValue($value);
@endphp

<div {{ $attributes->merge(['class' => 'stat-card']) }} style="{{ $color ? 'border-top: 3px solid ' . $color : '' }}">
    <div class="stat-card__header">
        <div class="stat-card__icon {{ $iconClass }}" style="{{ $color ? 'background: ' . $color . '20; color: ' . $color : '' }}">
            <i class="{{ $icon }}"></i>
        </div>
        @if($trend !== null)
            <span class="stat-card__trend {{ $trend >= 0 ? 'up' : 'down' }}">
                <i class="fas fa-caret-{{ $trend >= 0 ? 'up' : 'down' }}"></i> {{ abs(round($trend, 1)) }}%
            </span>
        @endif
    </div>
    <div class="stat-card__body">
        <div class="stat-card__value" title="{{ $value }}">{{ $displayValue }}</div>
        <div class="stat-card__label">{{ $title }}</div>
    </div>
</div>
