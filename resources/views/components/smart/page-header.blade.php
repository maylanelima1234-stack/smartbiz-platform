@props([
    'title',
    'subtitle' => null,
    'eyebrow' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between']) }}>
    <div class="min-w-0">
        @if ($eyebrow)
            <p class="mb-1 text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600">{{ $eyebrow }}</p>
        @endif

        <h1 class="truncate text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">{{ $title }}</h1>

        @if ($subtitle)
            <p class="mt-1 max-w-3xl text-sm text-slate-500 sm:text-base">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="flex shrink-0 flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
