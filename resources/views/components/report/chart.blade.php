@props(['id', 'title', 'subtitle' => null])

<div class="chart-card">
    <div class="chart-card__header">
        <div class="title-with-subtitle">
            <h3>{{ $title }}</h3>
            @if($subtitle)
                <span class="subtitle">{{ $subtitle }}</span>
            @endif
        </div>
        <div class="chart-tabs">
            <button class="chart-tab active">شهري</button>
            <button class="chart-tab">أسبوعي</button>
        </div>
    </div>
    <div style="height: 300px; position: relative;" {{ $attributes }}>
        <canvas id="{{ $id }}"></canvas>
    </div>
</div>
