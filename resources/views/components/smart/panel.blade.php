@props([
    'title' => null,
    'subtitle' => null,
    'padded' => true,
])

<section {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200']) }}>
    @if ($title || $subtitle || isset($actions))
        <header class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                @if ($title)<h2 class="font-semibold text-slate-950">{{ $title }}</h2>@endif
                @if ($subtitle)<p class="mt-0.5 text-sm text-slate-500">{{ $subtitle }}</p>@endif
            </div>
            @isset($actions)<div class="shrink-0">{{ $actions }}</div>@endisset
        </header>
    @endif

    <div @class(['p-5' => $padded])>{{ $slot }}</div>
</section>
