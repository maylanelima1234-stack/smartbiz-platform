@props(['label', 'value', 'trend' => null, 'trendType' => 'up', 'caption' => null])
<div {{ $attributes->merge(['class' => 'sb-card sb-metric']) }}>
    <div class="sb-metric-label">{{ $label }}</div>
    <div class="sb-metric-value">{{ $value }}</div>
    <div class="sb-metric-foot">
        @if($trend)<span class="{{ $trendType === 'warning' ? 'sb-trend-warning' : 'sb-trend-up' }}">{{ $trend }}</span>@endif
        @if($caption)<span>{{ $caption }}</span>@endif
    </div>
</div>
