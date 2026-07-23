@props([
    'title' => 'Nenhum registro encontrado',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'px-6 py-12 text-center']) }}>
    <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-500">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5v-9Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5" />
        </svg>
    </div>
    <h3 class="mt-4 font-semibold text-slate-900">{{ $title }}</h3>
    @if ($description)<p class="mx-auto mt-1 max-w-md text-sm text-slate-500">{{ $description }}</p>@endif
    @isset($actions)<div class="mt-5 flex justify-center">{{ $actions }}</div>@endisset
</div>
