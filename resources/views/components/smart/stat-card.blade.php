@props([
    'label',
    'value',
    'caption' => null,
    'tone' => 'indigo',
    'icon' => null,
])

@php
    $tones = [
        'indigo' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'ring' => 'ring-indigo-100'],
        'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'ring' => 'ring-emerald-100'],
        'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'ring' => 'ring-amber-100'],
        'rose' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'ring' => 'ring-rose-100'],
        'slate' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'ring' => 'ring-slate-200'],
    ];
    $palette = $tones[$tone] ?? $tones['indigo'];
@endphp

<div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-2 truncate text-3xl font-bold tracking-tight text-slate-950">{{ $value }}</p>
            @if ($caption)
                <p class="mt-2 text-xs leading-5 text-slate-500">{{ $caption }}</p>
            @endif
        </div>

        @if ($icon)
            <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl {{ $palette['bg'] }} {{ $palette['text'] }} ring-1 {{ $palette['ring'] }}">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
