@props([
    'value' => 0,
    'label' => null,
    'caption' => null,
])

@php($safeValue = max(0, min(100, (float) $value)))

<div {{ $attributes }}>
    @if ($label || $caption)
        <div class="mb-2 flex items-center justify-between gap-3 text-sm">
            <span class="font-medium text-slate-700">{{ $label }}</span>
            <span class="text-slate-500">{{ $caption ?? number_format($safeValue, 1, ',', '.') . '%' }}</span>
        </div>
    @endif

    <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
        <div class="h-full rounded-full bg-indigo-600 transition-all duration-500" style="width: {{ $safeValue }}%"></div>
    </div>
</div>
